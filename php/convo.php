<?php
session_start();
$toid = str_replace("page=convo&id=", "", $_SERVER['QUERY_STRING']);

if(!is_numeric($toid) || empty($toid) || $toid < 0){
    echo "<p>Invalid conversation id.".$toid."</p>";
    goto end;
}

if(isset($_SESSION['loggedin'])){
    require_once('php/conn.php');

    //check if user exists and get username
    $stmt = $pdo->prepare("SELECT username FROM users WHERE userid = :toid;");
    $stmt->bindValue(":toid", $toid);
    $stmt->execute();
    if($stmt->rowCount() == 0){
        echo "<p>That user doesn't exist.</p>";
        goto end;
    }
    $toName = $stmt->fetchColumn();

    //check if user is blocked
    $stmt = $pdo->prepare("SELECT blocked FROM blocks WHERE userid = :userid AND blockid = :blockid;");
    $stmt->bindValue(":userid", $_SESSION['userid']);
    $stmt->bindValue(":blockid", $toid);
    $stmt->execute();
    if($stmt->fetchColumn()){
         echo "<p>Blocked messages.</p>";
         goto end;
    }
    //get pms
    $stmt = $pdo->prepare("SELECT * FROM pms WHERE (fromid = :x AND toid = :y) OR (fromid = :y AND toid = :x) ORDER BY timesent ASC;");
    $stmt->bindValue(":x", $_SESSION['userid']);
    $stmt->bindValue(":y", $toid);
    $stmt->execute();

    echo "\n".'To: '.$toName.'<div id="convo" style="border:1px inset #ccc;width:99%;min-height:40px;max-height:500px;overflow-y:scroll;">';

    if($stmt->rowCount() > 0){
        //set msgs sent to session user as seen
        $stmt2 = $pdo->prepare("UPDATE pms SET seen = 1 WHERE toid = :userid AND fromid = :toid;");
        $stmt2->bindValue(":userid", $_SESSION['userid']);
        $stmt2->bindValue(":toid", $toid);
        $stmt2->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo "\n".'<span>'."\n";
            //get username
            $stmt2 = $pdo->prepare("SELECT username FROM users WHERE userid = ?;");
            $stmt2->bindValue(1, $row['fromid']);
            $stmt2->execute();

            if($_SESSION['userid'] != $row['fromid']){
                echo "<mark>".$stmt2->fetchColumn();
            }
            else echo "<span>".$stmt2->fetchColumn();

            echo '<span class="f3"> ('.$row['timesent'].'): </span>';
            echo '<span>'.$row['msg'].'</span>';
            if($_SESSION['userid'] != $row['fromid']){
                echo "</mark>";
            } else echo "</span>";
            echo "\n".'</span>'."\n"."<br>";
        }
    }

    echo "\n".'</div>
    <form id="fpm" method="post" action="php/pmed.php">
        <input type="text" id="recipient" name="recipient" style="display:none;" value="'.$toid.'"/>
        <input type="text" id="pmsg" name="pmsg" class="textfield" style="width:99%;"/>
        <input type="submit" id="sendpm" name="sendpm" value="Send"/>
    </form>';

} else echo "<p>You are not currently signed in.</p>";

end:
$pdo = null;
$stmt = null;
$stmt2 = null;
?>