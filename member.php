<?php
require_once("php/conn.php");

$user = str_replace("&user=", "", str_replace("page=member", "", $_SERVER['QUERY_STRING']));

if(empty($user) || !isset($user)){
    echo "<p>Error: no user selected.</p>";
    goto end;
}

$stmt = $pdo->prepare("SELECT userid, username, priviledge, email, jdate, tag FROM users WHERE username = :user;");
$stmt->bindValue(":user", $user);
$stmt->execute();

if($stmt->rowCount() > 0){
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //get post count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE authorid = ?;");
    $stmt->bindValue(1, $row['userid']);
    $stmt->execute();
    $posts = $stmt->fetchColumn();

    //get thread count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM threads WHERE authorid = ?;");
    $stmt->bindValue(1, $row['userid']);
    $stmt->execute();
    $threads = $stmt->fetchColumn();

    echo '<h1>'.$row['username'].'<span class="f1">';

    //check if user is banned
    $stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
    $stmt->bindValue(1, $row['userid']);
    $stmt->execute();
    $unban = $stmt->fetchColumn();

    if((time() < strtotime($unban) + 14400) && !empty($unban)){
        $banned = true;
        echo ' [BANNED]';
    }

    echo '</span></h1><hr/>';

    if(!empty($row['tag'])) echo '<p>- &ldquo;'.htmlspecialchars($row['tag']).'&rdquo;</p>';

    if($_SESSION['loggedin']){
        //BUTTON: SEND MSG
        echo '<br/><a class="nsyn" href="index.php?page=convo&id='.$row['userid'].'"><button style="display:block;">Send Message</button></a><br/>';
        //BUTTON: BLOCK USER
        //get if blocked or not
        $stmt = $pdo->prepare("SELECT blocked FROM blocks WHERE userid = :userid AND blockid = :blockid;");
        $stmt->bindValue(":userid", $_SESSION['userid']);
        $stmt->bindValue(":blockid", $row['userid']);
        $stmt->execute();
        $blocked = $stmt->fetchColumn();

        if($blocked == false){
            echo '<a class="nsyn" href="index.php?page=block&user='.$user.'&b=1"><button style="display:block;">Block user</button></a><br/>';
        }
        else {
            echo '<a class="nsyn" href="index.php?page=block&user='.$user.'&b=0"><button style="display:block;">Unblock user</button></a><br/>';
        }
    }

    echo '<nav><ul><li>Join date: '.date_format(date_create($row['jdate']), 'F d Y').
    '</li><li>Threads started: '.$threads.'</li><li>Posts: '.$posts.
    '</li><li>Priviledge: '.$row['priviledge'];

    // 0: guest, 1: member, 2: mod, 3: admin
    if($row['priviledge'] == 0) echo " (guest)";
    else if($row['priviledge'] == 1) echo " (member)";
    else if($row['priviledge'] == 2) echo " (moderator)";
    else if($row['priviledge'] == 3) echo " (admin)";

    echo '</li></ul></nav>';
} else echo "<p>No member with the username: ".htmlspecialchars($user)." exists.</p>";

end:
$pdo = null;
$stmt = null;
?>