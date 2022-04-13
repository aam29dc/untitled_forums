<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == "GET"){
    require_once('conn.php');

    if(empty($_GET['postId'])){
        echo "1";
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
            echo "0-1";  //downvote
        } else {
            echo "2";
        }
    }
    //otherwise upvote post
    else {
        $stmt = $pdo->prepare("INSERT INTO posts_likes (postid, likeid, threadid) VALUES (:postid, :likeid, :threadid);");
        $stmt->bindValue(":postid", $_GET['postId']);
        $stmt->bindValue(":likeid", $_SESSION['userid']);
        $stmt->bindValue(":threadid", $_GET['threadId']);
        if($stmt->execute()){
            echo "0+1";  //upvote
        } else {
            echo "3";
        }
    }
}
end:
$pdo = null;
$stmt = null;

?>