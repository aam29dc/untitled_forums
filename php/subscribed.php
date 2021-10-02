<?php
session_start();
require_once('lib.php');
$x = 1;
include_once(abs_php_include($x).'index_header.php');

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET) && !empty($_GET)){
    require_once('conn.php');

    //save variables for form
    $_SESSION['firstname'] = $_GET['firstname'];
    $_SESSION['lastname'] = $_GET['lastname'];
    $_SESSION['email'] = $_GET['email'];

    $error = false;

    if(!filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
        echo "<p>That email address is invalid.</p>";
        $error = true;
        goto end;
    }

    if(tableExists($pdo, 'subs')){
        $stmt = $pdo->prepare("SELECT COUNT(email) FROM subs WHERE email = :email;");
        $stmt->bindValue(':email', $_GET['email']);
        $stmt->execute();

        if($stmt->fetchColumn() == 0){
            $stmt = $pdo->prepare("INSERT INTO subs VALUES (:first,:last,:email);");
            $stmt->bindValue(':first', $_GET['firstname']);
            $stmt->bindValue(':last', $_GET['lastname']);
            $stmt->bindValue(':email', $_GET['email']);
            
            if($stmt->execute()){
                echo "<h1>Thank you for subscribing to our articles, ".$_SESSION['firstname']."</h1>";
                $stmt = $pdo->prepare("SELECT DISTINCT COUNT(email) FROM subs;");
                $stmt->execute();
                echo "<p>We currently have <b>" . $stmt->fetchColumn() . "</b> active subs to our articles.";

                unset($_SESSION['firstname']);
                unset($_SESSION['lastname']);
                unset($_SESSION['email']);
            } else echo "<h1>failed to add you to our articles, try again later.</h1>";
        }
        else {
            echo "<p>That email address is already subscribed to our articles.</p>";
            $error = true;
        }
    } else echo "<p>Error table subs does not exist.</p>";
} else echo "<p>Error: no form submitted.</p>";

end:
$pdo = null;
$stmt = null;

if(basename($_SERVER['PHP_SELF'], ".php") == "subscribed") {
    $x = 1;
}

if($error){
    echo "<h3>Redirecting back to subscribe...</h3>";
    echo '<noscript><a href="'.abs_php_include($x).'index.php?page=sub">Click to redirect back to subscribe.</a></noscript>';
}
else {
    echo "<h3>Redirecting back to home page...</h3>";
    echo '<noscript><a href="'.abs_php_include($x).'index.php">Click to redirect back to home page.</a></noscript>';
}

include_once(abs_php_include($x).'index_footer.php');

if($error){
    echo '<script src="'.abs_php_include($x).'js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php?page=sub");</script>';
} else echo '<script src="'.abs_php_include($x).'js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php");</script>';
?>
</body></html>