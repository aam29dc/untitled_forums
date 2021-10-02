<?php
session_start();

if(isset($_SESSION['loggedin'])){
  $_SESSION = array();
  unset($_SESSION['userid']);
  unset($_SESSION['username']);
  unset($_SESSION['loggedin']);
  unset($_SESSION['priviledge']);
  unset($_SESSION['threadid']);
  unset($_SESSION['postsid']);
  unset($_SESSION['pagesid']);
  unset($_SESSION['mainid']);
  unset($_SESSION['firstname']);
  unset($_SESSION['lastname']);
  unset($_SESSION['email']);
  unset($_SESSION['submit_title']);
  unset($_SESSION['submit_text']);
  unset($_SESSION);
  session_unset();
  session_destroy();
  echo '0';
} else echo '1';
exit;
?>