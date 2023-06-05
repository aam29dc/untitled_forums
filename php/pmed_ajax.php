<?php
if($_SERVER['REQUEST_METHOD'] === "POST"){
    require_once('_conn.php');
    require_once('_lib.php');

    $error = false;
    
    if(!isset($_POST['recipient'])){
        echo '1';
        $error = true;
    }
    if(!isset($_POST['pmsg'])){
        echo '2';
        $error = true;
    }
    if($_SESSION['userid'] === $_POST['recipient']){
        echo '3';
        $error = true;
    }

    if($error) goto end;    //end before querying...

    //check if recipient exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE userid = ?;");
    $stmt->bindValue(1, $_POST['recipient']);
    $stmt->execute();
    if($stmt->fetchColumn() === 0){
        echo '4';
        goto end;    //end before querying... ^
    }
    
    if(tableExists($pdo, 'pms')){
        $stmt = $pdo->prepare("INSERT INTO pms (fromid, toid, msg) VALUES (:fromid, :toid, :msg);");
        $stmt->bindValue(":fromid", $_SESSION['userid']);
        $stmt->bindValue(":toid", $_POST['recipient']);
        $stmt->bindValue(":msg", $_POST['pmsg']);
        if($stmt->execute()){
            echo '0';
        }
        else echo '5';
    } else echo '6';
}
end:
$pdo = null;
$stmt = null;
?>