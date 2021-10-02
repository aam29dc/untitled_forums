<?php
session_start();
require_once('conn.php');
require_once('lib.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(preg_replace('/[^A-Za-z0-9\-]/', '', $_POST['username']) != $_POST['username']){
        echo "1";
        goto end;
    }
    if(strlen($_POST['pwd']) == 0){
        echo "2";
        goto end;
    }
  
    if(tableExists($pdo,'users')){
      $stmt = $pdo->prepare("SELECT COUNT(username) FROM users WHERE username = :user;");
      $stmt->bindValue(':user', $_POST['username']);
      $stmt->execute();
  
      if($stmt->fetchColumn() > 0){
  
        //...check if valid password
        $stmt = $pdo->prepare("SELECT userid, username, priviledge, password FROM users WHERE username = :user;");
        $stmt->bindValue(':user', $_POST['username']);
        $stmt->execute();
  
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
        if(password_verify($_POST['pwd'], $row['password'])){
            echo "0";
            $_SESSION['loggedin'] = true;
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['priviledge'] = $row['priviledge'];

            //check if ban is past date, then remove from bans
            $stmt = $pdo->prepare("SELECT lift from bans WHERE userid = ?;");
            $stmt->bindValue(1, $_SESSION['userid']);
            $stmt->execute();

            if(!((time() < strtotime($unban) + 14400) && !empty($unban))){
                $stmt = $pdo->prepare("UPDATE bans SET lift = NULL WHERE userid = ?;");
                $stmt->bindValue(1, $_SESSION['userid']);
                $stmt->execute();
            }
        } else echo "3";
      } else echo "4";
    } else echo "5";
} else echo "6";

end:
$pdo = null;
$stmt = null;
?>