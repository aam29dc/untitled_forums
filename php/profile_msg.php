<?php
session_start();

$x = 1;
include_once('../index_header.php');

if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET) && !empty($_GET)){
    require_once('conn.php');

    //check if user is banned
    $stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
    $stmt->bindValue(1, $_SESSION['userid']);
    $stmt->execute();
    $unban = $stmt->fetchColumn();
    
    if((time() < strtotime($unban) + 14400) && !empty($unban)){
        echo "<p>Banned users can't edit their profile. Unban@: ".$unban."</p>";
    }
    else {
        $stmt = $pdo->prepare("UPDATE users SET tag = :msg WHERE username = :username;");
        $stmt->bindValue(':msg', $_GET['profile_msg']);
        $stmt->bindValue(':username', $_SESSION['username']);

        if($stmt->execute()){
            echo "<p>Updated profile message: ".$_GET['profile_msg']."</p>";
        } else echo "<p>Failed to update profile message.</p>";
}
} else echo "<p>No profile selected.<p>";
$pdo = null;
$stmt = null;

echo "<h3>Redirecting back to profile...</h3>";
echo '<noscript><a href="'.abs_php_include($x).'index.php">Click to redirect to home page.</a></noscript>';
include_once('../index_footer.php');
echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php?page=profile");</script>';
?>
</body></html>