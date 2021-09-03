<?php
session_start();

$x = 1;
include_once('../index_header.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once('conn.php');
              
    $title = addslashes($_POST['post_title']);
    $text = addslashes($_POST['post_text']);

    //save variables if failed
    $_SESSION['post_title'] = $title;
    $_SESSION['post_text'] = $text;

    if(empty($text)){
        echo "<p>Error: enter a message to post a reply.</p>";
        goto end;
    }

    if(isset($_SESSION['threadid'])){
        $stmt = $pdo->prepare("INSERT INTO posts (threadid, authorid, title, msg) VALUES (:threadid, :authorid, :title, :msg);");

        $stmt->bindValue(":threadid", $_SESSION['threadid']);
        $stmt->bindValue(":authorid", $_SESSION['userid']);
        $stmt->bindValue(":title", $title);
        $stmt->bindValue(":msg", $text);

        if($stmt->execute()){
            echo "<p>Reply posted.</p>";
            
            unset($_SESSION['post_title']);
            unset($_SESSION['post_text']);
        }
        else{
            echo "<p>Error adding post.</p>";
        }
    }
    else{
        echo "<p>Error: Require threadid to submit a post</p>";
    }

    end:
    $pdo = null;
    $stmt = null;
}else{
    echo "<p>Error: no form submitted.</p>";
}

echo "<h3>Redirecting back to thread...</h3>";
echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000,"'.abs_php_include($x).'index.php?thread='.$_SESSION['threadid'].'");</script>';
echo '<noscript><a href="index.php?thread='.$_SESSION['threadid'].'">Click to redirect to back to thread.</a></noscript>';
include_once('../index_footer.php');
?>