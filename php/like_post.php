<?php
session_start();
$x = 1;
include_once('../index_header.php');

if($_SERVER['REQUEST_METHOD'] === "GET"){
    require_once('conn.php');

    if(empty($_GET['postId'])){
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

include_once('../index_footer.php');
?>