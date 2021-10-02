<?php
session_start();

if(!isset($_SESSION['loggedin'])){
  echo '<div class="fixindent"><h1>Sign up</h1><hr/><br/>
  <form id="f_signup" method="post" action="php/signedup.php">
    <label for="user">Username:</label><br>
    <input type="text" id="username" name="username" class="textfield" value ="';

    if(isset($_COOKIE['username'])) echo htmlspecialchars($_COOKIE['username']);
    
    echo '"><span id="user_free" style="margin-left:5px;"></span><br>
    <label for="pwd">Password:</label><br>
    <input type="password" id="pwd" name="pwd" class="textfield"><img src="img/show.png" alt="see" id="tpass" style="height:16px;width:16px;cursor:pointer;" onClick="swapsrc(`tpass`,`img/show.png`,`img/hide.png`);togglepass(`pwd`);"><br>
    <label for="confirm_pwd">Confirm Password:</label><br>
    <input type="password" id="confirm_pwd" name="confirm_pwd" class="textfield"><img src="img/show.png" alt="see" id="tcpass" style="height:16px;width:16px;cursor:pointer;" onClick="swapsrc(`tcpass`,`img/show.png`,`img/hide.png`);togglepass(`confirm_pwd`);"><span id="span_pwd" style="margin-left:5px;"></span><br>
    <label for="email">Email:</label><br>
    <input type="text" id="email" name="email" class="textfield" value ="';

    if(isset($_COOKIE['email'])) echo htmlspecialchars($_COOKIE['email']);

    echo '"><span id="email_free" style="margin-left:5px;"></span><br>
    <input type="submit" id="signup" name="signup" value="Signup">
  </form>
  </div>';
} else echo '<p>You are already signed up and logged in.</p>';
?>