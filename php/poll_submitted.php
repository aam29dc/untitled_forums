<?php session_start();
$x = 1;
include_once('../index_header.php');
require_once('_lib.php');

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $question = addslashes($_POST['poll_question']);

    $error = false;

    require_once('_conn.php');

    if(!isset($question)){ 
        echo "<p>Error: can't submit a poll without a question.</p>";
        $error = true;
    }

    if(!isset($_GET['choices']) || !is_numeric($_GET['choices']) || $_GET['choices'] < 2 || $_GET['choices'] > 15){ 
        echo "<p>Error: can't submit a poll without atleast two choices, or more than 15.</p>";
        $error = true;
    }

    for($i=1;$i<$_GET['choices']+1;$i++){
        if(!isset($_POST['choice'.strval($i)])){
            echo "<p>Error: you left choice ".$i." !isset.</p>";
            goto end;
        }
    }

    if($error) goto end;

    if(tableExists($pdo, 'polls')){
        //insert question into polls table
        $stmt = $pdo->prepare("INSERT INTO polls (question, authorid, date) VALUES (:question, :authorid, NOW());");
        $stmt->bindValue(":question", $question);
        $stmt->bindValue(":authorid", $_SESSION['userid']);
        $stmt->execute();

        //get pollid
        $stmt = $pdo->prepare("SELECT MAX(pollid) FROM polls;");
        $stmt->execute();

        $pollid = $stmt->fetchColumn();

        //insert choices into polls_choices
        for($i=1;$i<$_GET['choices']+1;$i++){
            $stmt = $pdo->prepare("INSERT INTO polls_choices (pollid, choiceid, choice) VALUES (:pollid, :choiceid, :choice);");
            $stmt->bindValue(":pollid", $pollid);
            $stmt->bindValue(":choiceid", $i);
            $stmt->bindValue(":choice", addslashes($_POST['choice'.strval($i)]));
            $stmt->execute();
        }

        echo "<h1>Poll submitted, thank you for your contribution.</h1><br>".
        "<h2>".htmlspecialchars($question)."</h2>";
    } else echo "<p>Table polls does not exist.</p>";
    
    end:
    $pdo = null;
    $stmt = null;
} else echo "<p>Error: no form submitted.</p>";

echo "<h3>Redirecting to Polls...</h3>";
echo '<noscript><a href="'.abs_php_include($x).'index.php?page=polls">Click to redirect to polls.</a></noscript>';
include_once(abs_php_include($x).'index_footer.php');
echo '<script src="'.abs_php_include($x).'js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php?page=polls");</script>';
?>
</body></html>