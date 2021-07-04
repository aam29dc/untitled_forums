<?php
session_start();

$to = str_replace("page=pm&to=", "", $_SERVER['QUERY_STRING']);

if(empty($to)) $to = null;

require_once('php/conn.php');

if(isset($_SESSION['loggedin'])){
    //check if user is banned
    $stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
    $stmt->bindValue(1, $_SESSION['userid']);
    $stmt->execute();
    
    if(time() > strtotime($stmt->fetchColumn().' + 4 hours')){
        //get username
        $stmt = $pdo->prepare("SELECT username FROM users WHERE userid = ?;");
        $stmt->bindValue(1, $to);
        $stmt->execute();

        if($stmt->rowCount() == 0){
            echo "<p>That user doesn't exist.</p>";
        }
        else {
            echo '<form method="post" action="php/pmed.php"><label for="recipient">To:</label><br>';
            echo '<input type="text" id="recipient" name="recipient" style="display:none;" size="64" value="'.$to.'"/>';
            echo '<input type="text" id="dummy" name="dummy" style="width:98%;" size="64" class="textfield" value="'.$stmt->fetchColumn().'"/>';
            echo '<label for="pmsg">Message:</label><br><button type="button" onclick="input_tag(`pmsg`,`a`);" class="b1">link</button><button type="button" onclick="input_tag(`pmsg`,`b`);" class="b1">bold</button><button type="button" onclick="input_tag(`pmsg`,`i`);" class="b1">italic</button><button type="button" onclick="input_tag(`pmsg`,`s`);" class="b1">strike</button><button type="button" onclick="input_tag(`pmsg`,`u`);" class="b1">underline</button><button type="button" onclick="input_tag(`pmsg`,`sub`);" class="b1">sub</button><button type="button" onclick="input_tag(`pmsg`,`sup`);" class="b1">sup</button>';
            echo '<textarea id="pmsg" name="pmsg" rows="20" style="width:98%;" class="textfield"></textarea>';
            echo '<input type="submit" id="sendpm" name="sendpm" value="Send"/></form>';
        }
    }
    else {
        echo "<p>Banned users can't send personal messages.</p>";
    }
}
?>