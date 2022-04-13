<?php
session_start();

$x = 1;
include_once('../index_header.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once('conn.php');
    require_once('lib.php');
              
    $title = addslashes($_POST['post_title']);
    $text = addslashes($_POST['post_text']);
    $replyid = $_GET['replyid'];

    //save variables if failed
    $_SESSION['post_title'] = $title;
    $_SESSION['post_text'] = $text;

    $error = false;

    if(empty($text)){
        echo "<p>Error: enter a message to post a reply.</p>";
        $error = true;
    }
    if(strlen($_POST['post_text']) < MSG_MIN_LENGTH){
        echo "<p>Error: enter a longer message.</p>";
        $error = true;
    }
    if($error) goto end;

    if(isset($_SESSION['threadid'])){
        //get count of posts in thread to insert into postnumber
        $stmt = $pdo->prepare("SELECT posts FROM threads WHERE threadid = :threadid;");
        $stmt->bindValue(":threadid", $_SESSION['threadid']);
        $stmt->execute();
        $postNumber = $stmt->fetchColumn() + 1;

        //update posts count in threads table
        $stmt = $pdo->prepare("UPDATE threads SET posts = posts + 1 WHERE threadid = :threadid;");
        $stmt->bindValue(":threadid", $_SESSION['threadid']);
        $stmt->execute();

        $stmt = $pdo->prepare("INSERT INTO posts (threadid, authorid, replyid, postnum, title, msg) VALUES (:threadid, :authorid, :replyid, :postnum, :title, :msg);");
        $stmt->bindValue(":threadid", $_SESSION['threadid']);
        $stmt->bindValue(":authorid", $_SESSION['userid']);
        $stmt->bindValue(":replyid", $replyid);
        $stmt->bindValue(":postnum", $postNumber);
        $stmt->bindValue(":title", $title);
        $stmt->bindValue(":msg", $text);

        if($stmt->execute()){
            echo "<p>Reply posted.</p>";
            
            unset($_SESSION['post_title']);
            unset($_SESSION['post_text']);
        } else echo "<p>Error adding post.</p>";
    } else echo "<p>Error: Require threadid to submit a post</p>";

    end:
    $pdo = null;
    $stmt = null;
} else echo "<p>Error: no form submitted.</p>";

echo "<h3>Redirecting back to thread...</h3>";
echo '<noscript><a href="../index.php?thread='.$_SESSION['threadid'].'&pages='.$_SESSION['pagesid'].'">Click to redirect to back to thread.</a></noscript>';
include_once('../index_footer.php');
echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000,"'.abs_php_include($x).'index.php?thread='.$_SESSION['threadid'].'");</script>';
?>
</body></html>