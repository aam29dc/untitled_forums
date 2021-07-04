<?php
session_start();
$toid = str_replace("page=convo?id=", "", $_SERVER['QUERY_STRING']);

if(!is_numeric($toid) || empty($toid) || $toid < 0){
    echo "<p>Invalid conversation id.</p>";
    goto end;
}

if(isset($_SESSION['loggedin'])){
    require_once('php/conn.php');

    //check if user is blocked
    $stmt = $pdo->prepare("SELECT blocked FROM blocks WHERE userid = :userid AND blockid = :blockid;");
    $stmt->bindValue(":userid", $_SESSION['userid']);
    $stmt->bindValue(":blockid", $toid);
    $stmt->execute();
    if($stmt->fetchColumn()){
         echo "<p>Blocked messages.</p>";
         goto end;
    }

    $stmt = $pdo->prepare("SELECT * FROM pms WHERE (fromid = :x AND toid = :y) OR (fromid = :y AND toid = :x) ORDER BY timesent ASC;");
    $stmt->bindValue(":x", $_SESSION['userid']);
    $stmt->bindValue(":y", $toid);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        //set msgs sent to session user as seen
        $stmt2 = $pdo->prepare("UPDATE pms SET seen = 1 WHERE toid = :userid AND fromid = :toid;");
        $stmt2->bindValue(":userid", $_SESSION['userid']);
        $stmt2->bindValue(":toid", $toid);
        $stmt2->execute();

        echo '<div style="border:1px inset #ccc;width:99%;">';

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo '<span>';
            //get username
            $stmt2 = $pdo->prepare("SELECT username FROM users WHERE userid = ?;");
            $stmt2->bindValue(1, $row['fromid']);
            $stmt2->execute();

            if($_SESSION['userid'] != $row['fromid']){
                echo "<mark>".$stmt2->fetchColumn();
            }
            else echo $stmt2->fetchColumn();

            echo '<span class="f3"> ('.$row['timesent'].'): </span>';
            echo '<span>'.$row['msg'].'</span>';
            if($_SESSION['userid'] != $row['fromid']){
                echo "</mark>";
            }
            echo '<span><br/>';
        }
        echo '<br/></div><form method="post" action="php/pmed.php"><input type="text" id="recipient" name="recipient" style="display:none;" value="'.$toid.'"/>';
        echo '<input type="text" id="pmsg" name="pmsg" class="textfield" style="width:99%;"/><input type="submit" id="sendpm" name="sendpm" value="Reply"/></form>';
    }
    else {
        echo "<p>Conversation doesn't exist.</p>";
    }
}

end:
$pdo = null;
$stmt = null;
$stmt2 = null;
?>