<?php
session_start();

$threadid = str_replace("thread=", "", $_SERVER['QUERY_STRING']);
$error = false;

include_once('index_header.php');

if(!is_numeric($threadid) || empty($threadid)){
    echo "<p>Error: Invalid posts string.</p>";
    $error = true;
}
if(!isset($_SESSION['loggedin'])){
    echo "<p>You must be logged in to edit a thread.</p>";
    $error = true;
}

require_once('php/conn.php');

//check if user is banned
$stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
$stmt->bindValue(1, $_SESSION['userid']);
$stmt->execute();
$unban = $stmt->fetchColumn();

if(time() > strtotime($unban.' + 4 hours')){
    echo "<p>Banned users cannot edit a thread. Unban@: ".$unban."</p>";
    $error = true;
}
if($error == true) goto end;

//get contents of users post
$stmt = $pdo->prepare("SELECT threadid, authorid, title, msg FROM threads WHERE threadid = :threadid;");
$stmt->bindValue(":threadid", $threadid);

if($stmt->execute()){
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(($_SESSION['userid'] != $row['authorid']) && $_SESSION['priviledge'] <= 1){
        echo "<p>This user cannot edit this thread.</p>";
        goto end;
    }

    echo '<noscript><style>button.b1{display:none;}</style></noscript>';

    echo '<form action="php/edited_thread.php?thread='.$threadid.'" method="post">
    <label for="edit_title">Edit title:</label><br/>
    <input type="text" id="edit_title" name="edit_title" size="64" style="width:98%;" class="textfield" value="'.$row['title'].'"><br/>
    <label for="edit_text">Edit Message:</label><br/>';

    echo '<button type="button" onclick="input_tag(`edit_message`,`a`);" class="b1">link</button><button type="button" onclick="input_tag(`edit_message`,`b`);" class="b1">bold</button><button type="button" onclick="input_tag(`edit_message`,`i`);" class="b1">italic</button><button type="button" onclick="input_tag(`edit_message`,`s`);" class="b1">strike</button><button type="button" onclick="input_tag(`edit_message`,`u`);" class="b1">underline</button><button type="button" onclick="input_tag(`edit_message`,`sub`);" class="b1">sub</button><button type="button" onclick="input_tag(`edit_message`,`sup`);" class="b1">sup</button>';

    echo '<textarea id="edit_message" name="edit_message" rows="20" style="width:98%;" class="textfield">'.$row['msg'].'</textarea><br/>
    <input type="submit" name="edit" value="Edit">
    <input style="float:right;" type="submit" name="delete" value="Delete">
    </form>';
}
else{
    echo "<p>Error: thread with that threadid doesn't exist.</p>";
}

end:
$stmt = null;
$pdo = null;

include_once('index_footer.php');
?>