      <h1>Unsubscribe from our news articles.</h1>
      <p>You will no longer receive our news articles in your email.</p>
      <form method="get"<?php if(basename($_SERVER['PHP_SELF'], ".php")=="unsubscribe") echo ' action="php/unsubscribed.php"';?>>
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" class="textfield" value="<?php if(isset($_SESSION['email'])) echo $_SESSION['email']; ?>"><br>
        <input type="submit" name="unsub" value="Unsub">
      </form>
      <br>