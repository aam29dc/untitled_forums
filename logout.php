<?php
session_start();

header("Refresh:2; url='index.php'");

$result = "<div>"."\n";

if(isset($_SESSION['loggedin'])){
  $result .= "<p>Logging out...</p>";
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
}
else {
  $result .= "<p>You are already logged out.</p>";
}

$result .= "<h3>Redirecting to home page...</h3></div>"."\n";

include_once('index_header.php');
echo $result;
include_once('index_footer.php');

exit;
?>