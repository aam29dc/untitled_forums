<?php session_start();
require_once('php/_lib.php');
if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET) && isset($_GET)){
    require_once('_conn.php');
    //delete blocks
    $stmt = $pdo->prepare("DELETE FROM blocks WHERE userid = :userid OR blockid = :userid;");
    $stmt->bindValue(':userid', $_SESSION['userid']);
    $stmt->execute();

    //delete PMS
    $stmt = $pdo->prepare("DELETE FROM pms WHERE fromid = :userid;");
    $stmt->bindValue(':userid', $_SESSION['userid']);
    $stmt->execute();

    //delete poll - votes
    $stmt = $pdo->prepare("DELETE FROM polls_votes WHERE userid = :userid;");
    $stmt->bindValue(':userid', $_SESSION['userid']);
    $stmt->execute();
    //delete poll - choices
    //delete polls
    $stmt = $pdo->prepare("DELETE FROM polls WHERE polld = :userid;");
    $stmt->bindValue(':userid', $_SESSION['userid']);
    $stmt->execute();

    //delete likes
    $stmt = $pdo->prepare("DELETE FROM posts_likes WHERE likeid = :userid;");
    $stmt->bindValue(':userid', $_SESSION['userid']);
    $stmt->execute();

    //delete posts
    $stmt = $pdo->prepare("DELETE FROM posts WHERE authorid = :userid;");
    $stmt->bindValue(':userid', $_SESSION['userid']);
    $stmt->execute();

    //delete threads
    $stmt = $pdo->prepare("DELETE FROM threads WHERE authorid = :userid;");
    $stmt->bindValue(':userid', $_SESSION['userid']);
    $stmt->execute();

    $stmt = $pdo->prepare("DELETE FROM users WHERE username = :username;");
    $stmt->bindValue(':username', $_SESSION['username']);

    if($stmt->execute()){
        $result = "<p>Succesfully deleted profile: ".$_SESSION['username']."</p>";
    } else $result = "<p>Failed to delete profile.</p>";
} else $result = "<p>No profile selected.<p>";

$pdo = null;
$stmt = null;

/* logout */
$_SESSION = array();
unset($_SESSION['userid']);
unset($_SESSION['username']);
unset($_SESSION['loggedin']);
unset($_SESSION['priviledge']);
unset($_SESSION['threadid']);
unset($_SESSION['postsid']);
unset($_SESSION);
session_unset();
session_destroy();
/* logout */

$x = 1;
include_once(abs_php_include($x).'index_header.php');

echo $result;

echo "<h3>Redirecting to home page...</h3>";
echo '<noscript><a href="'.abs_php_include($x).'index.php">Click to redirect to home page.</a></noscript>';
include_once(abs_php_include($x).'index_footer.php');
echo '<script src="'.abs_php_include($x).'js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php");</script>';
?>
</body></html>