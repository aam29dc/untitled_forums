<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST"){
    require_once('conn.php');
    require_once('lib.php');
    $error = false;
    if(empty($_POST['recipient'])){
        echo "<p>Error: message sent to no one.</p>";
        $error = true;
    }
    if(empty($_POST['pmsg'])){
        echo "<p>Error: empty message.</p>";
        $error = true;
    }
    if($_SESSION['userid'] == $_POST['recipient']){
        echo "<p>Error: can't message yourself.</p>";
        $error = true;
    }

    if($error) goto end;    //end before querying...

    //check if recipient exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE userid = ?;");
    $stmt->bindValue(1, $_POST['recipient']);
    $stmt->execute();
    if($stmt->fetchColumn() == 0){
        echo "<p>Error: That user doesn't exist.</p>";
        goto end;    //end before querying... ^
    }
    
    if(tableExists($pdo, 'pms')){
        $stmt = $pdo->prepare("INSERT INTO pms (fromid, toid, msg) VALUES (:fromid, :toid, :msg);");
        $stmt->bindValue(":fromid", $_SESSION['userid']);
        $stmt->bindValue(":toid", $_POST['recipient']);
        $stmt->bindValue(":msg", $_POST['pmsg']);
        if($stmt->execute()){
            echo "<p>Personal message sent.</p>";
        }
        else {
            echo "<p>Failed to send pm.</p>";
        }
    }else{
        echo "<p>Sorry pms table doesn't exist yet.</p>";
    }
}
end:
$pdo = null;
$stmt = null;
?>