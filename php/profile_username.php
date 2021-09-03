<?php
session_start();

$x = 1;
include_once('../index_header.php');

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET) && !empty($_GET)){
    require_once('conn.php');

    //check if user is banned
    $stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
    $stmt->bindValue(1, $_SESSION['userid']);
    $stmt->execute();
    $unban = $stmt->fetchColumn();

    if(time() > strtotime($unban.' + 4 hours')){
        echo "<p>Banned users can't edit their profile. Unban@: ".$unban."</p>";
    }
    else {
        $stmt = $pdo->prepare("UPDATE users SET username = :newname WHERE username = :username;");
        $stmt->bindValue(':newname', $_GET['profile_username']);
        $stmt->bindValue(':username', $_SESSION['username']);

        if($stmt->execute()){
            $_SESSION['username'] = $_GET['profile_username'];
            echo "<p>Changed username: ".$_SESSION['username']."</p>";
        }
        else{
            echo "<p>Failed to change username.</p>";
        }
    }
}else{
    echo "<p>No profile selected.<p>";
}
$pdo = null;
$stmt = null;

echo "<h3>Redirecting back to profile...</h3>";
echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php?page=profile");</script>';
echo '<noscript><a href="'.abs_php_include($x).'index.php">Click to redirect to home page.</a></noscript>';

include_once('../index_footer.php');
?>