<?php
session_start();

echo "<h1>Submit article</h1><hr/><br/>";

if(isset($_SESSION['loggedin'])){
  require_once('php/conn.php');
  require_once('php/lib.php');

  //check if user is banned
  $stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
  $stmt->bindValue(1, $_SESSION['userid']);
  $stmt->execute();
  $unban = $stmt->fetchColumn();
  
  if(!(time() > strtotime($unban.' + 4 hours'))){
    echo '<noscript><style>button.b1{display:none;}</style></noscript>';

    echo '<form action="php/submitted.php" method="post" name="submitform">
          <label for="submit_title">Enter a title:</label><br>
          <input type="text" id="submit_title" name="submit_title" style="width:98%;" size="64" class="textfield" value="';if(isset($_SESSION['submit_title'])) echo $_SESSION['submit_title']; echo '"><br>
          <label for="submit_text">Message:</label><br>';

    echo '<button type="button" onclick="input_tag(`submit_text`,`a`);" class="b1">link</button><button type="button" onclick="input_tag(`submit_text`,`b`);" class="b1">bold</button><button type="button" onclick="input_tag(`submit_text`,`i`);" class="b1">italic</button><button type="button" onclick="input_tag(`submit_text`,`s`);" class="b1">strike</button><button type="button" onclick="input_tag(`submit_text`,`u`);" class="b1">underline</button><button type="button" onclick="input_tag(`submit_text`,`sub`);" class="b1">sub</button><button type="button" onclick="input_tag(`submit_text`,`sup`);" class="b1">sup</button>';
    
    echo '<textarea id="submit_text" name="submit_text" rows="20" style="width:98%;" class="textfield">';if(isset($_SESSION['submit_text'])) echo $_SESSION['submit_text'];else 'Enter message here.'; echo '</textarea><br>
          <input type="submit" name="submit" value="Submit"></form><br>';
  }
  else{
    echo "<p>Banned users cannot submit a thread. Unban@: ".$unban."</p>";
  }
}
else{
  echo "<p>You must login to submit an article.</p>";
}
?>