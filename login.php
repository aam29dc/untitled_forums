<?php
session_start();

if(!isset($_SESSION['loggedin'])){
  echo '<div class="fixindent"><h1>Login</h1><hr><br>
  <form id="f_login" method="post" action="php/logged.php">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" class="textfield" value ="';

    if(isset($_COOKIE['username'])) echo htmlspecialchars($_COOKIE['username']);

    echo '"><br>
    <label for="pwd">Password:</label><br>
    <input type="password" id="pwd" name="pwd" class="textfield"><img src="'.abs_php_include($x).'img/show.png" alt="see" id="tpass" class="pass"><br>
    <input type="submit" id="login" name="login" value="Login">
  </form>
  <br>
  <a class="nsyn"><h5>lost username or password</h5></a>
  </div>'."\n";
} else echo "<p>You are currently logged in.</p>"."\n";
?>