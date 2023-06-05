<?php session_start();
$x = 1;
include_once('../index_header.php');

if($_SERVER['REQUEST_METHOD'] === "POST"){
    require_once('_conn.php');
    require_once('_lib.php');
    $error = false;
    if(!isset($_POST['recipient'])){
        echo "<p>Error: message sent to no one.</p>";
        $error = true;
    }
    if(!isset($_POST['pmsg'])){
        echo "<p>Error: !isset message.</p>";
        $error = true;
    }
    if($_SESSION['userid'] === $_POST['recipient']){
        echo "<p>Error: can't message yourself.</p>";
        $error = true;
    }

    if($error) goto end;    //end before querying...

    //check if recipient exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE userid = ?;");
    $stmt->bindValue(1, $_POST['recipient']);
    $stmt->execute();
    if($stmt->fetchColumn() === 0){
        echo "<p>Error: That user doesn't exist.</p>";
        goto end;    //end before querying... ^
    }
    
    if(tableExists($pdo, 'pms')){
        $stmt = $pdo->prepare("INSERT INTO pms (fromid, toid, msg) VALUES (:fromid, :toid, :msg);");
        $stmt->bindValue(":fromid", $_SESSION['userid']);
        $stmt->bindValue(":toid", $_POST['recipient']);
        $stmt->bindValue(":msg", $_POST['pmsg']);
        if($stmt->execute()){
            echo "<p>Personal message sent.</p>";
        }
        else echo "<p>Failed to send pm.</p>";
    } else echo "<p>Sorry pms table doesn't exist yet.</p>";
}
end:
$pdo = null;
$stmt = null;

echo '<noscript><a href="'.abs_php_include($x).'index.php?page=convo&id='.$_POST['recipient'].'">Click to redirect back to conversation.</a></noscript>';

include_once(abs_php_include($x).'index_footer.php');
echo '<script src="'.abs_php_include($x).'js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php?page=convo&id='.$_POST['recipient'].'");</script>'
?>
</body></html>