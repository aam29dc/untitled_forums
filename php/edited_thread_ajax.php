<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(strlen($_POST['edit_message']) < 5){
        echo "1";//"<p>Error: enter a longer message.</p>";
        goto end;
    }

    require_once('conn.php');
    
    if(empty($_POST['threadid']) || !isset($_POST['threadid']) || !is_numeric($_POST['threadid'])){
        echo "2";
        goto end;
    }
    //EDIT CLICKED
    if($_POST['edit_title'] == $_POST['ori_title'] && $_POST['edit_message'] == $_POST['ori_message']){
        echo "4";//"<p>Nothing updated. No changes have been made to the thread title or message.</p>";
    } else {
        $stmt = $pdo->prepare("UPDATE threads SET title = :title, msg = :msg, date = NOW() WHERE threadid = :threadid;");
        $stmt->bindValue(":title", addslashes($_POST['edit_title']));
        $stmt->bindValue(":msg", addslashes($_POST['edit_message']));
        $stmt->bindValue(":threadid", $_POST['threadid']);

        if($stmt->execute()){
            echo "0";//"<p>Thread successfully updated.</p>";
        } else echo "5";//"<p>Error: Update thread failed.</p>";
    }

    end:
    $stmt = null;
    $pdo = null;
} else echo "6";//"<p>Error: no form submitted.</p>";
?>