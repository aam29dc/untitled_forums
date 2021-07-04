      <h1>Subscribe to receive our news articles.</h1><hr/><br/>
      <p>You will receive our news articles in your email shortly after they have been posted.</p>
      <form method="get"<?php if(basename($_SERVER['PHP_SELF'], ".php")=="subscribe") echo ' action="php/subscribed.php"';?>>
        <label for="firstname">First name:</label><br>
        <input type="text" id="firstname" name="firstname" class="textfield" value="<?php if(isset($_SESSION['firstname'])) echo $_SESSION['firstname']; ?>"><br>
        <label for="lastname">Last name:</label><br>
        <input type="text" id="lastname" name="lastname" class="textfield" value="<?php if(isset($_SESSION['lastname'])) echo $_SESSION['lastname']; ?>"><br>
        <label for="email">Email (required):</label><br>
        <input type="text" id="email" name="email" class="textfield" value="<?php if(isset($_SESSION['email'])) echo $_SESSION['email']; ?>"><br>
        <input type="submit" name="subscribe" value="Subscribe">
      </form>
      <br>
      <a href="index.php?page=unsub">Unsubscribe</a>