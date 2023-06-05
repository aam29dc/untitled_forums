<?php
echo "<h1>Submit poll</h1><hr><br>";

if(isset($_SESSION['loggedin'])){
  require_once('php/_conn.php');
  require_once('php/_lib.php');

  if(!isset($_POST['choices']) || !is_numeric($_POST['choices']) || $_POST['choices'] < 2){
    $_POST['choices'] = 2;
  }
  else if($_POST['choices'] > 15){
    echo "<p>Error: Max number of choices is 15.</p>";
    $_POST['choices'] = 2;
  }

  //check if user is banned
  $stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
  $stmt->bindValue(1, $_SESSION['userid']);
  $stmt->execute();
  $unban = $stmt->fetchColumn();
  
  if(!((time() < strtotime($unban) + 14400) && isset($unban))){
    echo '<form action="" method="post"><label for="choices">Enter number of choices: </label><input class="textfield" type="number" id="choices" name="choices" min="2" max="15"/>
    <input type="submit" name="update" value="Update"/></form><br>
        <div style="margin-left:5px;"><form action="php/poll_submitted.php?choices='.$_POST['choices'].'" method="post">
        <label for="poll_question">The question: </label><br>
        <input type="text" class="textfield" name="poll_question" id="poll_question" size="64"/><br>
        <br><span>Choices</span><br>';

        for($i=1;$i<$_POST['choices']+1;$i++){
          echo '<label for="choice'.$i.'">'.$i.': </label>
          <input type="text" class="textfield" name="choice'.$i.'" id="choice'.$i.'" size="32"/><br>';
        }

        echo '<br><input type="submit" name="submit" value="Submit"/></form></div>';
  } else echo "<p>Banned users cannot submit a poll. Unban@: ".$unban."</p>";
} else echo "<p>You must login to submit an poll.</p>";

end:
$pdo = null;
$stmt = null;
?>