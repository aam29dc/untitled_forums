<?php
session_start();

$x = 1;
include_once('../index_header.php');

if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(strlen($_POST['edit_message']) < MSG_MIN_LENGTH){
        echo "<p>Error: enter a longer message.</p>";
        goto end;
    }

    require_once('conn.php');
    $q = array();
    parse_str($_SERVER['QUERY_STRING'], $q);
    $threadid = $q['thread'];

    //DELETE CLICKED
    if($_POST['delete'] === "Delete"){
        //delete posts
        $stmt = $pdo->prepare("DELETE FROM posts WHERE threadid = :threadid;");
        $stmt->bindValue(":threadid", $threadid);
        $stmt->execute();

        //delete thread
        $stmt = $pdo->prepare("DELETE FROM threads WHERE threadid = :threadid;");
        $stmt->bindValue(":threadid", $threadid);

        if($stmt->execute()){
            echo "<p>Thread succesfully deleted.</p>";
        } else echo "<p>Error: unable to delete thread.</p>";

        goto end;
    }
    //EDIT CLICKED
    if($_POST['edit_title'] === $_POST['ori_title'] && $_POST['edit_message'] === $_POST['ori_message']){
        echo "<p>Nothing updated. No changes have been made to the thread title or message.</p>";
    } else {
        $stmt = $pdo->prepare("UPDATE threads SET title = :title, msg = :msg, date = NOW() WHERE threadid = :threadid;");
        $stmt->bindValue(":title", addslashes($_POST['edit_title']));
        $stmt->bindValue(":msg", addslashes($_POST['edit_message']));
        $stmt->bindValue(":threadid", $threadid);

        if($stmt->execute()){
            echo "<p>Thread successfully updated.</p>";
        } else echo "<p>Error: Update thread failed.</p>";
    }

    end:
    $stmt = null;
    $pdo = null;
} else echo "<p>Error: no form submitted.</p>";

$history = false;

if(isset($_SESSION['threadid']) && isset($_SESSION['pagesid']) && $_POST['delete'] !== "Delete"){
    echo "<h3>Redirecting back to thread...</h3>";
    echo '<noscript><a href="'.abs_php_include($x).'index.php?thread='.$_SESSION['threadid'].'&pages='.$_SESSION['pagesid'].'">Click to redirect to home page.</a></noscript>';
    $history = true;
} else { 
    echo "<h3>Redirecting to home page...</h3>"; 
    echo '<noscript><a href="'.abs_php_include($x).'index.php">Click to redirect to home page.</a></noscript>';
}

include_once('../index_footer.php');

if($history){
    echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php?thread='.$_SESSION['threadid'].'&pages='.$_SESSION['pagesid'].'");</script>';
} else {
    echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php");</script>';
}?>
</body></html>