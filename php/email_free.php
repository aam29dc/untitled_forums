<?php
    require_once('conn.php');

    if(isset($_POST['email'])){
        $stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email;");
        $stmt->bindValue(":email", $_POST['email']);
        $stmt->execute();

        if($stmt->rowCount() > 0) echo '1';
    }
    $pdo = null;
    $stmt = null;
?>