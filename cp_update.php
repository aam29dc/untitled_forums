<?php
require_once('conn.php');
//check if user is banned

$stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
$stmt->bindValue(1, $_SESSION['userid']);
$stmt->execute();
$unban = $stmt->fetchColumn();

if(time() > strtotime($unban.' + 4 hours')){
    if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET) && !empty($_GET)){
        if(!isset($_GET['userid']) || empty($_GET['userid'])){
            echo "<p>Error: Id required to update row.</p>";
            goto end;
        }

        //check if id is that of a admin
        $stmt = $pdo->prepare("SELECT priviledge FROM users WHERE userid = ?;");
        $stmt->bindValue(1, $_GET['userid']);
        $stmt->execute();

        if($stmt->fetchColumn() >= 3){
            echo "<p>You are not allowed to alter an admin row.</p>";
            goto end;
        }

        if(isset($_GET['priviledge']) && !empty($_GET['priviledge'])){
            $stmt = $pdo->prepare("UPDATE users SET priviledge = :priviledge WHERE userid = :userid;");
            $stmt->bindValue(":priviledge", (int)$_GET['priviledge']);
            $stmt->bindValue(":userid", $_GET['userid']);
            if($stmt->execute()){
                echo "<p>User: ".$_GET['userid']." priviledge updated to: ".$_GET['priviledge']."</p>";
            }
            else {
                echo "<p>Failed to update user priviledge.</p>";
            }
        }

        if(isset($_GET['ban']) && !empty($_GET['ban']) && (int)$_GET['ban'] != 0){    // "'its a perma'" - twitch.tv/payo
            $stmt = $pdo->prepare("INSERT INTO bans (userid, lift) VALUES (:userid, NOW() + INTERVAL :lift MINUTE);");
            $stmt->bindValue(":userid", $_GET['userid']);
            $stmt->bindValue(":lift", (int)$_GET['ban']);
            if($stmt->execute()) {
                echo "<p>Banned userid: ".$_GET['userid']." for ".$_GET['ban']." minute(s).</p>";
            }
            else {
                echo "<p>Failed to ban user.</p>";
            }
        }
    }else{
        echo "<p>Error: no form submitted.</p>";
    }
}else{
    echo '<p>Banned moderators cannot update tables. Unban@: '.$unban.'</p>';
}

end:
$pdo = null;
$stmt = null;
?>