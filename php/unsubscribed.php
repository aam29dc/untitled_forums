<?php
session_start();
require_once('lib.php');
$x = 1;
include_once(abs_php_include($x).'index_header.php');
if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET) && !empty($_GET)){
    require_once('conn.php');

    //save variables for form
    $_SESSION['email'] = $_GET['email'];

    if(!filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
        echo "<p>That email address is invalid.</p>";
        goto end;
    }

    if(tableExists($pdo, 'subs')){
        $stmt = $pdo->prepare("SELECT COUNT(email) from subs WHERE email = :email;");
        $stmt->bindValue(':email', $_GET['email']);
        $stmt->execute();

        if($stmt->fetchColumn() === 1){
            $stmt = $pdo->prepare("DELETE FROM subs WHERE email = :email;");
            $stmt->bindValue(':email', $_GET['email']);
            
            if($stmt->execute()){
                echo "<h1>Successfully removed email from subs.</h1>";

                $stmt = $pdo->prepare("SELECT DISTINCT COUNT(email) FROM subs;");
                $stmt->execute();

                echo "<p>We currently have <b>" . $stmt->fetchColumn() . "</b> active subs to our articles.";
                unset($_SESSION['email']);
            } else echo "<h1>failed to remove you to our articles, try again later.</h1>";
        } else echo "<p>That email is not subscribed to our articles.</p>";
    } else echo "<p>Error table subs does not exist.</p>";
} else echo "<p>Error: no form submitted.</p>";

end:
$pdo = null;
$stmt = null;

if(basename($_SERVER['PHP_SELF'], ".php")==="unsubscribe"){
    require_once('lib.php');
    $x = 1;
}

echo "<h3>Redirecting to home page...</h3>";
echo '<noscript><a href="'.abs_php_include($x).'index.php">Click to redirect to home page.</a></noscript>';
include_once(abs_php_include($x).'index_footer.php');
echo '<script src="'.abs_php_include($x).'js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php");</script>';
?>
</body></html>