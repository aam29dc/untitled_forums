<?php session_start();
$x = 1;
include_once('../index_header.php');
require_once('_lib.php');

if(!isset($_GET['pollid'])){
    echo "<p>Error: no poll specified.</p>";
    goto end;
}

if(!isset($_POST['choice'])){
    echo "<p>Error: no choice specified.</p>";
    goto end;
}

if(!isset($_SESSION['loggedin'])){
    echo "<p>Error: need to be logged in to submit a vote.</p>";
    goto end;
}

require_once('_conn.php');

if($_SERVER['REQUEST_METHOD'] === "POST"){
    //insert vote into polls_votes
    $stmt = $pdo->prepare("INSERT INTO polls_votes (pollid, choiceid, userid) VALUES (:pollid, :choiceid, :userid);");
    $stmt->bindValue(":pollid", $_GET['pollid']);
    $stmt->bindValue(":choiceid", $_POST['choice']);
    $stmt->bindValue(":userid", $_SESSION['userid']);
    if($stmt->execute()){
        echo "<p>Vote submitted.</p>";
    } else {
        echo "<p>Error unable to submit vote.</p>";
    }
}

end:
$pdo = null;
$stmt = null;

echo "<h3>Redirecting to back to poll...</h3>";
echo '<noscript><a href="'.abs_php_include($x).'index.php?poll='.$_GET['pollid'].'">Click to redirect to home page.</a></noscript>';
include_once(abs_php_include($x).'index_footer.php');
echo '<script src="'.abs_php_include($x).'js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php?poll='.$_GET['pollid'].'");</script>';
?>
</body></html>