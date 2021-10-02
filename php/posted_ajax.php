<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once('conn.php');
    require_once('lib.php');
              
    $title = addslashes($_POST['post_title']);
    $text = addslashes($_POST['post_text']);

    //save variables if failed
    $_SESSION['post_title'] = $title;
    $_SESSION['post_text'] = $text;

    $error = false;

    if(empty($text)){
        echo "1";
        $error = true;
    }
    if(strlen($_POST['post_text']) < MSG_MIN_LENGTH){
        echo "2";
        $error = true;
    }
    if($error) goto end;

    if(isset($_SESSION['threadid'])){
        $stmt = $pdo->prepare("INSERT INTO posts (threadid, authorid, title, msg) VALUES (:threadid, :authorid, :title, :msg);");

        $stmt->bindValue(":threadid", $_SESSION['threadid']);
        $stmt->bindValue(":authorid", $_SESSION['userid']);
        $stmt->bindValue(":title", $title);
        $stmt->bindValue(":msg", $text);

        if($stmt->execute()){
            echo "0";
            
            unset($_SESSION['post_title']);
            unset($_SESSION['post_text']);
        } else echo "3";
    } else echo "4";

    end:
    $pdo = null;
    $stmt = null;
} else echo "5";
?>