<?php
session_start();

if(!isset($_SESSION['loggedin'])){
  echo '<div class="fixindent"><h1>Sign up</h1><hr/><br/>
  <form method="post" action="php/signedup.php">
    <label for="user">Username:</label><br>
    <input type="text" id="username" name="username" class="textfield" value ="';

    if(isset($_COOKIE['username'])) echo htmlspecialchars($_COOKIE['username']);
    
    echo '"><br>
    <label for="pwd">Password:</label><br>
    <input type="password" id="pwd" name="pwd" class="textfield"><br>
    <label for="confirm_pwd">Confirm Password:</label><br>
    <input type="password" id="confirm_pwd" name="confirm_pwd" class="textfield"><br>
    <label for="email">Email:</label><br>
    <input type="text" id="email" name="email" class="textfield" value ="';

    if(isset($_COOKIE['email'])) echo htmlspecialchars($_COOKIE['email']);

    echo '"><br>
    <input type="submit" name="signup" value="Signup">
  </form>
  </div>';
}
else{
  echo "<p>You are already signed up and logged in.</p>";
}
?>