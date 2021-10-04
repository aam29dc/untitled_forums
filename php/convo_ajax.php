<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require_once('conn.php');

    $error = false;

    if(empty($_SESSION['userid'])){
        echo '1';
        $error = true;
    }
    if(empty($_POST['to'])){
        echo '2';
        $error = true;
    }
    if($error){
        goto end;
    }

    $stmt = $pdo->prepare("SELECT fromid, toid, timesent, msg, seen FROM pms WHERE (fromid = :fromid AND toid = :toid) OR (fromid = :toid AND toid = :fromid) ORDER BY timesent ASC;");
    $stmt->bindValue(":fromid", $_SESSION['userid']);
    $stmt->bindValue(":toid", $_POST['to']);
    $stmt->execute();

    //  get other users name
    $stmt2 = $pdo->prepare("SELECT username FROM users WHERE userid = :otherid;");
    $stmt2->bindValue(":otherid", $_POST['to']);
    $stmt2->execute();

    $toName = $stmt2->fetchColumn();

    //rearrange styled for js
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<span>";
        if($_SESSION['userid'] == $row['fromid']){
            echo "<span>".$_SESSION['username'];
        } else echo "<mark>".$toName;

        echo '<span class="f3"> ('.$row['timesent'].'): </span><span>'.$row['msg'].'</span>';

        if($_SESSION['userid'] == $row['fromid']){
            echo "</span>";
        } else echo "</mark>";

        echo "</span><br>";
    }

    end:
    $pdo = null;
    $stmt = null;
    $stmt2 = null;
}
?>