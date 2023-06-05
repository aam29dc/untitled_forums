<?php
if(isset($_SESSION['loggedin'])){
    require_once("php/_conn.php");
    $stmt = $pdo->prepare("SELECT userid, username, email, jdate, priviledge, tag FROM users WHERE userid = :userid;");
    $stmt->bindValue(':userid', $_SESSION['userid']);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo '<h1>'.$row['username'].' <span class="f1">('.$_SESSION['userid'].')';

    //check if user is banned
    $stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
    $stmt->bindValue(1, $row['userid']);
    $stmt->execute();
    $unban = $stmt->fetchColumn();
    
    if((time() < strtotime($unban) + 14400) && isset($unban)){
        $banned = true;
        echo ' [BANNED]';
    }

    if(!isset($banned)) $banned = false;

    echo '</span></h1><hr>';

    //count threads started
    $stmt = $pdo->prepare("SELECT COUNT(authorid) FROM threads WHERE authorid = :userid;");
    $stmt->bindValue(":userid", $_SESSION['userid']);
    $stmt->execute();
    $threads = $stmt->fetchColumn();

    //count posts created
    $stmt = $pdo->prepare("SELECT COUNT(authorid) FROM posts WHERE authorid = :userid;");
    $stmt->bindValue(":userid", $_SESSION['userid']);
    $stmt->execute();

    if(isset($row['tag'])) echo '<p>- &ldquo;'.htmlspecialchars($row['tag']).'&rdquo;</p>';

    echo '<nav><ul><li><a class="collapsible">Edit Profile message</a>
    <div class="content">';
    if(!$banned) echo '<form method="get" action="php/profile_msg.php">
            <input type="text" id="profile_msg" name="profile_msg" size="98" class="textfield" value="'.$row['tag'].'"/>
            <br><button>Update</button>
        </form>';else echo "- <i>forbidden</i>";
    echo '</div></li>';

    echo '<li><a class="collapsible">Change Username</a>
    <div class="content">';
        if(!$banned) echo '<form method="get" action="php/profile_username.php">
            <input type="text" id="profile_username" name="profile_username" size="98" class="textfield" value="'.$_SESSION['username'].'"/>
            <br><button>Update</button>
        </form>';else echo "- <i>forbidden</i>";
    echo '</div></li>';

    echo '<li><a class="collapsible">Edit Password</a>
    <div class="content">
        <form method="post" action="php/profile_password.php">
            <label for="profile_currentpass">Current password:</label>
            <input type="password" id="profile_currentpass" name="profile_currentpass" size="98" class="textfield"/><br>
            <label for="profile_password">New password:</label>
            <input type="password" id="profile_newpass" name="profile_newpass" size="98" class="textfield"/><br>
            <label for="profile_confirmpass">Confirm password:</label>
            <input type="password" id="profile_confirmpass" name="profile_confirmpass" size="98" class="textfield"/>
            <br><button>Update</button>
        </form>
    </div></li>';

    echo '<br><li>Join date: '.date_format(date_create($row['jdate']), 'F d Y').
    '</li><li>Threads started: '.$threads.'</li><li>Posts: '.$stmt->fetchColumn().
    '</li><li>Priviledge: '.$row['priviledge'];

    // 0: guest, 1: member, 2: mod, 3: admin
    if($row['priviledge'] === 0) echo " (guest)";
    else if($row['priviledge'] === 1) echo " (member)";
    else if($row['priviledge'] === 2) echo " (moderator)";
    else if($row['priviledge'] === 3) echo " (admin)";

    echo '</li><li><br><hr><a class="collapsible">Delete account</a>
    <div class="content">
        <form method="get" action="php/profile_delete.php">
            <input type="submit" id="profile_delete" name="profile_delete" value="Delete" style="float:right;"/>
        </form>
    </div></li></ul></nav>';
} else echo "<p>You are not currently logged in.</p>";

$pdo = null;
$stmt = null;
?>