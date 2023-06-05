<?php session_start();
$x = 1;
include_once('../index_header.php');
require_once('_lib.php');

$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);
$postsid = $q['posts'];
$error = false;

if(!isset($postsid) || !is_numeric($postsid)){
    echo "<p>Error: Invalid posts string.</p>";
    $error = true;
}
if(!isset($_SESSION['loggedin'])){
    echo "<p>You must be logged in to edit a post.</p>";
    $error = true;
}

require_once('_conn.php');

//check if user is banned
$stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
$stmt->bindValue(1, $_SESSION['userid']);
$stmt->execute();
$unban = $stmt->fetchColumn();

if((time() < strtotime($unban) + 14400) && isset($unban)){
    echo "<p>Banned users cannot edit a post. Unban@: ".$unban."</p>";
    $error = true;
}
if($error) goto end;

//get contents of users post
$stmt = $pdo->prepare("SELECT postid, authorid, title, msg FROM posts WHERE postid = :postsid;");
$stmt->bindValue(":postsid", $postsid);

if($stmt->execute()){
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(($_SESSION['userid'] !== $row['authorid']) && $_SESSION['priviledge'] <= 1){
        echo "<p>This user cannot edit this post.</p>";
        goto end;
    }
    echo '<noscript><style>button.b1{display:none;}</style></noscript>';

    echo '<form action="post_edited.php?posts='.$postsid.'" method="post">
    <label for="edit_title">Edit title:</label><br>
    <input type="text" id="edit_title" name="edit_title" size="64" style="width:98%;" class="textfield" value="'.stripslashes($row['title']).'"><br>
    <input type="text" id="ori_title" name="ori_title" style="display:none;" value="'.stripslashes($row['title']).'">
    <label for="edit_text">Edit Message:</label><br>';

    include_once('_msg_buttons.php');
    drawMsgButtons('edit_message');

    echo '<textarea id="edit_message" name="edit_message" rows="20" style="width:98%;" class="textfield">'.stripslashes($row['msg']).'</textarea><br>
    <textarea id="ori_message" name="ori_message" style="display:none;">'.stripslashes($row['msg']).'</textarea>
    <input type="submit" name="edit" value="Edit">
    <input style="float:right;" type="submit" name="delete" value="Delete">
    </form>';
} else echo "<p>Error: post with that postsid doesn't exist.</p>";

end:
$stmt = null;
$pdo = null;

include_once(abs_php_include($x).'index_footer.php');?>
</body></html>