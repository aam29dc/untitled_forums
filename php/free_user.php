<?php
    require_once('_conn.php');

    if(isset($_POST['username'])){
        $stmt = $pdo->prepare("SELECT username FROM users WHERE username = :user;");
        $stmt->bindValue(":user", $_POST['username']);
        $stmt->execute();

        if($stmt->rowCount() > 0) echo '1';
        else echo '0';
    }
    $pdo = null;
    $stmt = null;
?>