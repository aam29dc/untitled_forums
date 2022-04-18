<?php
if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(empty($_POST['threadid']) || !isset($_POST['threadid']) || !is_numeric($_POST['threadid'])){
        echo "1";
        goto end;
    }

    require_once('conn.php');

    //DELETE CLICKED
    if($_POST['deleted'] === "Delete"){
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