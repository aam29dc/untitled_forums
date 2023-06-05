<?php
if(!isset($_SESSION['loggedin'])){
  echo '<div class="fixindent"><h1>Sign up</h1><hr><br>
  <form id="f_signup" method="post" action="php/signedup.php">
    <label for="s_username">Username:</label><br>
    <input type="text" id="s_username" name="s_username" class="textfield" value ="';

    if(isset($_COOKIE['username'])) echo htmlspecialchars($_COOKIE['username']);
    
    echo '"><span id="user_free" style="margin-left:5px;"></span><br>
    <label for="s_pwd">Password:</label><br>
    <input type="password" id="s_pwd" name="s_pwd" class="textfield"><img src="img/hide.png" alt="see" id="s_tpass" class="pass"><br>
    <label for="confirm_pwd">Confirm Password:</label><br>
    <input type="password" id="confirm_pwd" name="confirm_pwd" class="textfield"><img src="img/hide.png" alt="see" id="tcpass" class="pass"><span id="span_pwd" style="margin-left:5px;"></span><br>
    <label for="email">Email:</label><br>
    <input type="text" id="email" name="email" class="textfield" value ="';

    if(isset($_COOKIE['email'])) echo htmlspecialchars($_COOKIE['email']);

    echo '"><span id="email_free" style="margin-left:5px;"></span><br>
    <input type="submit" id="signup" name="signup" value="Signup">
  </form>
  </div>';
} else echo '<p>You are already signed up and logged in.</p>';
?>