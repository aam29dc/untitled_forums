<?php
session_start();

if($_SERVER["REQUEST_METHOD"] === "POST"){      
    $error = false;
    $report = "";

    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false){
        $report .= "<p>Error: enter a valid email address.</p>";
        $error = true;
    }
    if(preg_replace('/[^A-Za-z0-9\-]/', '', $_POST['s_username']) !== $_POST['s_username']){
        $report .= "<p>Error: enter a valid username with no special characters.</p>";
        $error = true;
    }
    if(strlen($_POST['s_pwd']) < 5){
        $report .= "<p>Error: enter a password with atleast 5 characters.</p>";
        $error = true;
    }
    if($_POST['s_pwd'] !== $_POST['confirm_pwd']){
        $report .= "<p>Error: confirm password failed.</p>";
        $error = true;
    }
    if($error) goto end;

    require_once('conn.php');
    require_once('lib.php');
    
    if(tableExists($pdo,'users')) {
        //check if user already exists
        $stmt = $pdo->prepare("SELECT COUNT(username) FROM users WHERE username = :user;");
        $stmt->bindValue(":user", $_POST['s_username']);
        $stmt->execute();

        $error = false;

        if($stmt->fetchColumn() !== 0){
            $report .= "<p>Sorry, a user with the name: ".htmlspecialchars($_POST['s_username'])." already exists.</p>";
            $error = true;
        }

        //check if email is being used
        $stmt = $pdo->prepare("SELECT COUNT(email) FROM users WHERE email = ?;");
        $stmt->bindValue(1, $_POST['email']);
        $stmt->execute();

        if($stmt->fetchColumn() !== 0){
            $report .= "<p>The email: ".htmlspecialchars($_POST['email'])." is already being used.</p>";
            $error = true;
        }

        if(!$error){
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:user, :pass, :email);");

            $stmt->bindValue(":user", $_POST['s_username']);
            $stmt->bindValue(":pass", password_hash($_POST['s_pwd'], PASSWORD_DEFAULT));
            $stmt->bindValue(":email", $_POST['email']);

            if($stmt->execute()){
                $report .= "<p>Successfully added user: ".$_POST['s_username']." Welcome to the forums, feel free to submit an article.</p>";
                $stmt = $pdo->prepare("SELECT @@IDENTITY;");    //return primary key (userid) generated from last insert
                $stmt->execute();

                $_SESSION['userid'] = $stmt->fetchColumn();
                $_SESSION['username'] = $_POST['s_username'];
                $_SESSION['loggedin'] = true;
                $_SESSION['priviledge'] = 1;
            } else $report .= "<p>Error adding user: ".htmlspecialchars($_POST['s_username'])."</p>";
        }
    } else $report .= "<p>Sorry users table doesn't exist yet.</p>";
} else $report .= "<p>Error: no form submitted.</p>";

end:
$pdo = null;
$stmt = null;

$x = 1;
include_once('../index_header.php');

echo $report;

if($error){
    echo "<h3>Redirecting back to sign-up...</h3>";
    echo '<noscript><a href="'.abs_php_include($x).'index.php?page=signup">Click to redirect to sign-up.</a></noscript>';
}
else {
    echo "<h3>Redirecting back to home page...</h3>";
    echo '<noscript><a href="'.abs_php_include($x).'index.php">Click to redirect to home page.</a></noscript>';
}

include_once('../index_footer.php');

if($error) echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php?page=signup");</script>';
else echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php");</script>';
?>
</body></html>