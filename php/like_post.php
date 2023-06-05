<?php session_start();
$x = 1;
include_once('../index_header.php');
require_once('_lib.php');

if(!isset($_SESSION['loggedin'])){
    echo "<p>You must be logged in to like a post.</p>";
    goto end;
}

if($_SERVER['REQUEST_METHOD'] === "GET"){
    require_once('_conn.php');

    if(!isset($_GET['postId'])){
        echo "<p>Error: no postid to upvote.</p>";
        goto end;
    }
    //check if post already upvoted
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts_likes WHERE postid = :postid AND likeid = :likeid;");
    $stmt->bindValue(":postid", $_GET['postId']);
    $stmt->bindValue(":likeid", $_SESSION['userid']);
    $stmt->execute();

    //if already upvoted, then remove upvote
    if($stmt->fetchColumn() > 0){
        $stmt = $pdo->prepare("DELETE FROM posts_likes WHERE postid = :postid AND likeid = :likeid;");
        $stmt->bindValue(":postid", $_GET['postId']);
        $stmt->bindValue(":likeid", $_SESSION['userid']);
        if($stmt->execute()){
            echo "<p>Post downvoted.</p>";
        } else {
            echo "<p>Failed to down vote post.</p>";
        }
    }
    //otherwise upvote post
    else {
        $stmt = $pdo->prepare("INSERT INTO posts_likes (postid, likeid, threadid) VALUES (:postid, :likeid, :threadid);");
        $stmt->bindValue(":postid", $_GET['postId']);
        $stmt->bindValue(":likeid", $_SESSION['userid']);
        $stmt->bindValue(":threadid", $_GET['threadId']);
        if($stmt->execute()){
            echo "<p>Post upvoted.</p>";
        } else {
            echo "<p>Failed to up vote post.</p>";
        }
    }
}
end:
$pdo = null;
$stmt = null;

if(isset($_SESSION['threadid']) && isset($_SESSION['pagesid'])){
    echo "<h3>Redirecting back to thread...</h3>";
    echo '<noscript><a href="'.abs_php_include($x).'index.php?thread='.$_SESSION['threadid'].'&pages='.$_SESSION['pagesid'].'">Click to redirect to back to thread.</a></noscript>';
} else {
    echo "<h3>Redirecting to home page...</h3>";
    echo '<noscript><a href="'.abs_php_include($x).'index.php">Click to redirect to home page.</a></noscript>';
}

include_once(abs_php_include($x).'index_footer.php');

if(isset($_SESSION['threadid']) && isset($_SESSION['pagesid'])){
    echo '<script src="'.abs_php_include($x).'js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php?thread='.$_SESSION['threadid'].'&pages='.$_SESSION['pagesid'].'");</script>';
} else {
    echo '<script src="'.abs_php_include($x).'js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php");</script>';
}
?>