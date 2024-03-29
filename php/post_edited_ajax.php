<?php session_start();
if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST['edit_message']) && strlen($_POST['edit_message']) < 5  && isset($_POST['delete']) && $_POST['delete'] !== "Delete"){
        echo "1";
        goto end;
    }

    require_once('_conn.php');

    if(!isset($_POST['postid']) || !is_numeric($_POST['postid'])){
        echo "2";
        goto end;
    }

    //DELETE CLICKED
    if(isset($_POST['delete']) && $_POST['delete'] === "Delete"){
            //get threadid
            $stmt = $pdo->prepare("SELECT threadid FROM posts WHERE postid = ?;");
            $stmt->bindValue(1, $_POST['postid']);
            if($stmt->execute()) $threadid = $stmt->fetchColumn();
            else echo "2";
        
            //delete likes first
            $stmt = $pdo->prepare("DELETE FROM posts_likes WHERE threadid = :threadid AND postid = :postid;");
            $stmt->bindValue(":threadid", $threadid);
            $stmt->bindValue(":postid", $_POST['postid']);
            $stmt->execute();

        $stmt = $pdo->prepare("DELETE FROM posts WHERE threadid = :threadid AND postid = :postid;");
        $stmt->bindValue(":threadid", $threadid);
        $stmt->bindValue(":postid", $_POST['postid']);
        if($stmt->execute()){
            echo "0";
        } else echo "3";

        goto end;
    }

    //EDIT CLICKED
    if($_POST['edit_title'] === $_POST['ori_title'] && $_POST['edit_message'] === $_POST['ori_message']){
        echo "4";
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET title = :title, msg = :msg, date = NOW() WHERE postid = :postid;");
        $stmt->bindValue(":title", addslashes($_POST['edit_title']));
        $stmt->bindValue(":msg", addslashes($_POST['edit_message']));
        $stmt->bindValue(":postid", $_POST['postid']);

        if($stmt->execute()){
            echo "0";
        } else echo "5";
    }

    end:
    $stmt = null;
    $pdo = null;
   
} else echo "6";
?>