<?php
    session_start();
    $x = 1;
    include_once('../index_header.php');
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $title = addslashes($_POST['submit_title']);
    $text = addslashes($_POST['submit_text']);

    //save variables if failed
    $_SESSION['submit_title'] = $title;
    $_SESSION['submit_text'] = $text;

    $error = false;

    if(empty($title)){ 
        echo "<p>Error: can't submit a thread without a title.</p>";
        $error = true;
    }
    if(empty($text)){
        echo "<p>Error: thread requires a message to be submitted.</p>";
        $error = true;
    }
    if($error == true) goto end;

    require_once('conn.php');
    require_once('lib.php');

    if(tableExists($pdo, 'threads')){
        $stmt = $pdo->prepare("INSERT INTO threads (authorid, title, msg, date) VALUES (:userid, :title, :text, NOW());");
        $stmt->bindValue(':userid', $_SESSION['userid']);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':text', $text);

        if($stmt->execute()){
            echo "<h1>Article submitted, thank you for your contribution.</h1><br>".
            "<h2>".htmlspecialchars($title)."</h2>".
            "<p>".htmlspecialchars($text)."</p>";

            unset($_SESSION['submit_text']);
            unset($_SESSION['submit_title']);
        }
        else{
            echo "<p>Sorry unable to submit thread at this time.</p>";
        }
    }
    else{
        echo "<p>Table threads does not exist.</p>";
    }
    
    end:
    $pdo = null;
    $stmt = null;
}else{
    echo "<p>Error: no form submitted.</p>";
}
echo "<h3>Redirecting to home page...</h3>";
echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php");</script>';
echo '<noscript><a href="'.abs_php_include($x).'index.php">Click to redirect to home page.</a></noscript>';
include_once('../index_footer.php');
?>