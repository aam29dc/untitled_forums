<?php
session_start();
    
$x = 1;
include_once('../index_header.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(strlen($_POST['edit_message']) < 5){
        echo "<p>Error: enter a longer message.</p>";
        goto end;
    }

    require_once('conn.php');
    $postsid = str_replace("posts=", "", $_SERVER['QUERY_STRING']);

    if($_POST['delete'] == "Delete"){
            //get threadid
            $stmt = $pdo->prepare("SELECT threadid FROM posts WHERE postid = ?;");
            $stmt->bindValue(1, $postsid);
            if($stmt->execute()) $threadid = $stmt->fetchColumn();
            else echo "<p>Error: post does not exist.</p>";

        $stmt = $pdo->prepare("DELETE FROM posts WHERE threadid = :threadid AND postid = :postid;");
        $stmt->bindValue(":threadid", $threadid);
        $stmt->bindValue(":postid", $postsid);
        if($stmt->execute()){
            echo "<p>Post succesfully deleted.</p>";
        }
        else{
            echo "<p>Error: unable to delete post.</p>";
        }

        goto end;
    }

    $stmt = $pdo->prepare("UPDATE posts SET title = :title, msg = :msg, date = NOW() WHERE postid = :postid;");
    $stmt->bindValue(":title", $_POST['edit_title']);
    $stmt->bindValue(":msg", $_POST['edit_message']);
    $stmt->bindValue(":postid", $postsid);

    if($stmt->execute()){
        echo "<p>Post successfully updated.</p>";
    }
    else{
        echo "<p>Error: Update post failed.</p>";
    }

    end:
    $stmt = null;
    $pdo = null;
   
}else{
    echo "<p>Error: no form submitted.</p>";
}

if(isset($_SESSION['threadid']) && isset($_SESSION['pagesid'])){
    echo "<h3>Redirecting back to thread...</h3>";
    echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php?thread='.$_SESSION['threadid'].'&pages='.$_SESSION['pagesid'].'");</script>';
}
else{
    echo "<h3>Redirecting to home page...</h3>";
    echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php");</script>';
    echo '<noscript><a href="'.abs_php_include($x).'index.php">Click to redirect to home page.</a></noscript>';
}
include_once('../index_footer.php');
?>