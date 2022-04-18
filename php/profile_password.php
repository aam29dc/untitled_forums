<?php
session_start();

$x = 1;
include_once('../index_header.php');

if($_SERVER["REQUEST_METHOD"] === "POST"){
    require_once('conn.php');

    $error = false;

    //check if current pass is correct
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = ?;");
    $stmt->bindValue(1, $_SESSION['username']);
    $stmt->execute();

    if(!password_verify($_POST['profile_currentpass'], $stmt->fetchColumn())){
        echo "<p>Incorrect current password.</p>";
        $error = true;
    }

    //check if new password is equal to current password
    if($_POST['profile_newpass'] === $_POST['profile_currentpass']){
        echo "<p>New password can't be the same as the current password.</p>";
        $error = true;
    }

    //check if new pass is equal to cofirm pass
    if($_POST['profile_newpass'] !== $_POST['profile_confirmpass']){
        echo "<p>Confirm password failed, not the same as new password.</p>";
        $error = true;
    }

    if(!$error){
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username;");
        $stmt->bindValue(':password', password_hash($_POST['profile_newpass'], PASSWORD_DEFAULT));
        $stmt->bindValue(':username', $_SESSION['username']);

        if($stmt->execute()){
            echo "<p>Successfully changed password.</p>";
        }
        else echo "<p>Failed to change password.</p>";
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