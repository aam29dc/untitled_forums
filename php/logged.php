<?php session_start();
$x = 1;
require_once('_conn.php');
require_once('_lib.php');

if(isset($_SESSION['threadid'])){
  header("Refresh:2; url='../index.php?thread=".$_SESSION['threadid']."'");
  $report = "<h3>Redirecting back to thread...</h3>";
}
else {
  header("Refresh:2; url='../index.php?page=login'");
  $report = "<h3>Redirecting back to login...</h3>";
}

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $error = false;
  
    if(preg_replace('/[^A-Za-z0-9\-]/', '', $_POST['username']) !== $_POST['username']){
      $report .= "<p>Error: enter a valid username with no special characters.</p>";
      $error = true;
    }
    if(strlen($_POST['pwd']) === 0){
      $report .= "<p>Error: enter a password.</p>";
      $error = true;
    }
    if($error) goto end;
  
    if(tableExists($pdo,'users')){
      $stmt = $pdo->prepare("SELECT COUNT(username) FROM users WHERE username = :user;");
      $stmt->bindValue(':user', $_POST['username']);
      $stmt->execute();
  
      if($stmt->fetchColumn() > 0){
  
        //...check if valid password
        $stmt = $pdo->prepare("SELECT userid, username, priviledge, password FROM users WHERE username = :user;");
        $stmt->bindValue(':user', $_POST['username']);
        $stmt->execute();
  
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
        if(password_verify($_POST['pwd'], $row['password'])){
          $report .= "<p>Successfully logged in.</p>";
          $_SESSION['loggedin'] = true;
          $_SESSION['userid'] = $row['userid'];
          $_SESSION['username'] = $_POST['username'];
          $_SESSION['priviledge'] = $row['priviledge'];

          //check if ban is past date, then remove from bans
          /*$stmt = $pdo->prepare("SELECT lift from bans WHERE userid = ?;");
          $stmt->bindValue(1, $_SESSION['userid']);
          $stmt->execute();
          $unban = $stmt->fetchColumn();*/

          /*if(!((time() < strtotime($unban) + 14400) && isset($unban))){
              $stmt = $pdo->prepare("UPDATE bans SET lift = NULL WHERE userid = ?;");
              $stmt->bindValue(1, $_SESSION['userid']);
              $stmt->execute();
              $report .= "<p>You have been unbanned.</p>";
          }*/
        } else $report .= "<p>Incorrect password.</p>";
      } else $report .= "<p>No user with the name: ".htmlspecialchars($_POST['username'])." exists.</p>";
    } else $report .= "<p>Query failed. No user table exists.</p>";
} else $report .= "<p>Error: no form submitted.</p>";

end:
$pdo = null;
$stmt = null;

include_once(abs_php_include($x).'index_header.php');

echo $report;

include_once(abs_php_include($x).'index_footer.php');?>
</body></html>