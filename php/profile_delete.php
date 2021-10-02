<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET) && !empty($_GET)){
    require_once('conn.php');

    //delete posts
    $stmt = $pdo->prepare("DELETE FROM posts WHERE authorid = :username;");
    $stmt->bindValue(':username', $_SESSION['userid']);
    $stmt->execute();

    //delete threads
    $stmt = $pdo->prepare("DELETE FROM threads WHERE authorid = :username;");
    $stmt->bindValue(':username', $_SESSION['userid']);
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
include_once('../index_header.php');

echo $result;

echo "<h3>Redirecting to home page...</h3>";
echo '<noscript><a href="../index.php">Click to redirect to home page.</a></noscript>';
include_once('../index_footer.php');
echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000, "../index.php");</script>';
?>
</body></html>