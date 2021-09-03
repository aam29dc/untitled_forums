<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){      
    $error = false;
    $report = "";

    if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false){
        $report .= "<p>Error: enter a valid email address.</p>";
        $error = true;
    }
    if(preg_replace('/[^A-Za-z0-9\-]/', '', $_POST['username']) != $_POST['username']){
        $report .= "<p>Error: enter a valid username with no special characters.</p>";
        $error = true;
    }
    if(strlen($_POST['pwd']) < 5){
        $report .= "<p>Error: enter a password with atleast 5 characters.</p>";
        $error = true;
    }
    if($_POST['pwd'] != $_POST['confirm_pwd']){
        $report .= "<p>Error: confirm password failed.</p>";
        $error = true;
    }
    if($error == true) goto end;

    require_once('conn.php');
    require_once('lib.php');
    
    if(tableExists($pdo,'users')) {
        //check if user already exists
        $stmt = $pdo->prepare("SELECT COUNT(username) FROM users WHERE username = :user;");
        $stmt->bindValue(":user", $_POST['username']);
        $stmt->execute();

        $error = false;

        if($stmt->fetchColumn() != 0){
            $report .= "<p>Sorry, a user with the name: ".htmlspecialchars($_POST['username'])." already exists.</p>";
            $error = true;
        }

        //check if email is being used
        $stmt = $pdo->prepare("SELECT COUNT(email) FROM users WHERE email = ?;");
        $stmt->bindValue(1, $_POST['email']);
        $stmt->execute();

        if($stmt->fetchColumn() != 0){
            $report .= "<p>The email: ".htmlspecialchars($_POST['email'])." is already being used.</p>";
            $error = true;
        }

        if(!$error){
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:user, :pass, :email);");

            $stmt->bindValue(":user", $_POST['username']);
            $stmt->bindValue(":pass", password_hash($_POST['pwd'], PASSWORD_DEFAULT));
            $stmt->bindValue(":email", $_POST['email']);

            if($stmt->execute()){
                $report .= "<p>Successfully added user: ".$_POST['username']." Welcome to the forums, feel free to submit an article.</p>";
                $stmt = $pdo->prepare("SELECT @@IDENTITY;");    //return primary key (userid) generated from last insert
                $stmt->execute();

                $_SESSION['userid'] = $stmt->fetchColumn();
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['loggedin'] = true;
                $_SESSION['priviledge'] = 1;
            }
            else $report .= "<p>Error adding user: ".htmlspecialchars($_POST['username'])."</p>";
        }
    }
    else $report .= "<p>Sorry users table doesn't exist yet.</p>";
}
else $report .= "<p>Error: no form submitted.</p>";

end:
$pdo = null;
$stmt = null;

$x = 1;
include_once('../index_header.php');

echo $report;

echo "<h3>Redirecting to home page...</h3>";
echo '<script src="../js/waitdirect.js"></script><script>waitdirect(2000, "'.abs_php_include($x).'index.php");</script>';
echo '<noscript><a href="'.abs_php_include($x).'index.php">Click to redirect to home page.</a></noscript>';
include_once('../index_footer.php');
?>