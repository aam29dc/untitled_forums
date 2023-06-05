<?php session_start();
if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(!isset($_POST['threadid']) || !is_numeric($_POST['threadid'])){
        echo "1";
        goto end;
    }

    require_once('_conn.php');

    //DELETE CLICKED
    if($_POST['deleted'] === "Delete"){
        //delete likes
        $stmt = $pdo->prepare("DELETE FROM posts_likes WHERE threadid = :threadid;");
        $stmt->bindValue(":threadid", $_POST['threadid']);
        $stmt->execute();

        //delete posts
        $stmt = $pdo->prepare("DELETE FROM posts WHERE threadid = :threadid;");
        $stmt->bindValue(":threadid", $_POST['threadid']);
        $stmt->execute();

        //delete thread
        $stmt = $pdo->prepare("DELETE FROM threads WHERE threadid = :threadid;");
        $stmt->bindValue(":threadid", $_POST['threadid']);

        if($stmt->execute()){
            echo "0";//"<p>Thread succesfully deleted.</p>";
        } else echo "2";//"<p>Error: unable to delete thread.</p>";
    }

    end:
    $pdo = null;
    $stmt = null;
}
?>