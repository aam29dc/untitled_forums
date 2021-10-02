<?php
function createpages($numpages, $currentpage, $link){
    echo " [ ";

    for($i=1;$i<=$numpages-1;$i++){
        if($currentpage == $i) echo '<a>'.$i.'</a>, ';
        else echo '<a href="'.$link.$i.'">'.$i.'</a>, ';
    }

    if($currentpage == $i) echo '<a>'.$i.'</a>';
    else echo '<a href="'.$link.$i.'">'.$i.'</a>';

    echo " ] ";
}
?>
<?php
session_start();
require_once('php/conn.php');
require_once('php/lib.php');

//get thread and pages numbers alone
$pages = str_replace("&pages=", "", preg_replace('/^[0-9]+/', '', str_replace("thread=", "", $_SERVER['QUERY_STRING'])));
$thread = preg_replace("/[^0-9]/", "", substr(str_replace("thread=", "", $_SERVER['QUERY_STRING']), 0, 5));

if(!is_numeric($thread) || empty($thread)){     // if query string is not numeric, then set equal to newest thread
    $stmt = $pdo->prepare("SELECT MAX(threadid) FROM threads;");
    if($stmt->execute()){
        $thread = $stmt->fetchColumn();
    }
    else $thread = 1;
}

if(!is_numeric($pages) || empty($pages) || $pages < 1) $pages = 1;

$banned = false;

//check if user is banned
$stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
$stmt->bindValue(1, $_SESSION['userid']);
$stmt->execute();
$unban = $stmt->fetchColumn();
if((time() < strtotime($unban) + 14400) && !empty($unban)) $banned = true;

if(tableExists($pdo,'threads')){
    $_SESSION['threadid'] = $thread;

    $stmt = $pdo->prepare("SELECT threadid, title, msg, authorid, date FROM threads WHERE threadid = :thread;");
    $stmt->bindValue(':thread', $thread);
    $stmt->execute();

    //print THREAD title, msg, 
    if($stmt->rowCount() > 0){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo '<div id="threadtitle" style="background-color:var(--heartsectionh1c);"><h1 id="thread_title" style="display:inline;">';
        
        //check if user is blocked
        $stmt = $pdo->prepare("SELECT blocked FROM blocks WHERE userid = :userid AND blockid = :blockid;");
        $stmt->bindValue(":userid", $_SESSION['userid']);
        $stmt->bindValue(":blockid", $row['authorid']);
        $stmt->execute();
        $blocked = $stmt->fetchColumn();
        
        if($blocked) echo 'Blocked title.</h1>';
        else echo htmlspecialchars(stripslashes($row['title']))."</h1>";
        // BUTTONS (thread) EDIT
        if(isset($_SESSION['loggedin']) && (($_SESSION['userid'] == $row['authorid']) || $_SESSION['priviledge'] >= 2) && !$banned){
            echo '<a id="edit_thread" class="nsyn" href="edit_thread.php?thread='.$thread.'"><img src="img/edit16.png" style="float:right;"></a>';
        }
        echo '</div><p id="thread_msg" style="text-indent:5px;">';

        if($blocked) echo "Blocked message.";
        else echo htmlchars_minus(stripslashes($row['msg']), ...$htmltags)."</p>";

        // get username
        $stmt = $pdo->prepare("SELECT username FROM users WHERE userid = :userid;");
        $stmt->bindValue(":userid", $row['authorid']);
        $stmt->execute();
        $author = $stmt->fetchColumn();
        echo '<h5><a href="index.php?page=member&user='.$author.'">- '.$author.'</a><span style="float:right;">'.$row['date']."</span></h5>";
        echo "<hr>"."\n";

        //print POSTS from posts table
        $stmt = $pdo->prepare("SELECT postid, authorid, title, msg, date FROM posts WHERE threadid = :thread LIMIT :pages, ".TMAX.";");
        $stmt->bindValue(":thread", $thread);
        $stmt->bindValue(":pages", (int)(($pages-1)*TMAX), PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            echo "<table>";
            $i = 1;
            $edit_posts = 0;
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // get users username
                $stmt2 = $pdo->prepare("SELECT username FROM users WHERE userid = :userid;");
                $stmt2->bindValue(":userid", $row['authorid']);
                $stmt2->execute();
                $author = $stmt2->fetchColumn();
                echo '<tr><td class="tduser"><mark><a href="index.php?page=member&user='.$author.'">'.$author.'</a></mark><br/><img src="img/user24.png"/><br/><small>';
                    // get users # of posts
                    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE authorid = :userid;");
                    $stmt2->bindValue(":userid", $row['authorid']);
                    $stmt2->execute();
                    echo $stmt2->fetchColumn()."</small></td><td><h4>#";

                echo $i + ($pages-1)*TMAX." ";  // number of post in thread

                //check if user is blocked
                $stmt2 = $pdo->prepare("SELECT blocked FROM blocks WHERE userid = :userid AND blockid = :blockid;");
                $stmt2->bindValue(":userid", $_SESSION['userid']);
                $stmt2->bindValue(":blockid", $row['authorid']);
                $stmt2->execute();
                $blocked = $stmt2->fetchColumn();

                echo '<span class="post_title">';

                if($blocked) echo "Blocked title.";
                else echo htmlspecialchars($row['title']);

                echo '</span>';

                // BUTTONS (POSTS): EDIT
                if(isset($_SESSION['loggedin']) && (($_SESSION['userid'] == $row['authorid']) || $_SESSION['priviledge'] >= 2) && !$banned){
                    $_SESSION['pagesid'] = $pages;
                    echo '<a data-postid="'.$row['postid'].'" class="edit_post nsyn" href="edit.php?posts='.$row['postid'].'"><img style="float:right;" src="img/edit16.png" alt="edit"/></a>';
                    $edit_posts++;
                }

                echo "</h4>";
                echo '<p class="post_msg">';

                if($blocked) echo "Blocked message.";
                else echo htmlchars_minus(stripslashes($row['msg']), ...$htmltags);

                echo '</p><h5 style="text-align:right;">'.$row['date'].'</h5></td></tr>';

                $i++;
            }
        } else if($stmt->rowCount() == 0 && $pages > 1) echo "<table><tr><td><p>This page does not exist yet.</p><td></tr>";
        else echo "<table><tr><td><p>No one has posted a reply yet.</p><td></tr>";
        
        // BUTTON: BACK
        echo '</table><div>';
        /*
        echo '<br><div style="float:left;"><a class="nsyn" href="?thread='.$thread.'&pages=';
            if(isset($_SESSION['pagesid'])) echo $_SESSION['pagesid']-1;
            else echo '1';
        echo '"><button>&laquo; Back</button></a><br/>';
        */

        // BUTTON: PREV
        if($pages > 1){
            echo '<br/><a class="nsyn" href="?thread='.$thread.'&pages='.($pages-1).'"><button>Prev</button></a>';
        }

        // BUTTON: NEXT
            // get thread number of posts
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE threadid = :threadid;");
            $stmt->bindValue(":threadid", $thread);
            $stmt->execute();
            $numOfPosts = $stmt->fetchColumn();
            // get count of number of pages for thread
            if($numOfPosts > TMAX) $numOfPages = ceil($numOfPosts/TMAX);
            else $numOfPages = 0;

        if($numOfPages > 0){      
            createpages($numOfPages, $pages, "?thread=".$thread."&pages=");
            if($pages < $numOfPages) echo '<a class="nsyn" href="?thread='.$thread.'&pages='.($pages+1).'"><button>Next</button></a>';
        }

        echo '</div>';

        // REPLY FORM OR SIGN IN
        if(isset($_SESSION['loggedin'])){
            if(!$banned){
            echo '<button id="post_coll" type="button" class="collapsible" style="float:right;">Post a reply</button>
            <div id="post_content" class="content" style="float:left;clear:left;">
                <form id="post_f" method="post" action="php/posted.php">
                    <input type="text" id="post_title" name="post_title" size="98" class="textfield" style="width:98%;margin-bottom:5px;"/><br/>';
                    include_once('php/msg_buttons.php');
                    drawMsgButtons('post_text');
                    echo '<textarea id="post_text" name="post_text" class="textfield" rows="10" style="width:98%;"></textarea>
                    <br><button id="post_b">Post</button>
                </form>
            </div>';
            } else echo '<span style="float:right;">Banned users can\'t post. Unban@: '.$unban.'</span>';
        } else echo '<p style="float:right;padding:0;"><a href="index.php?page=login">Sign in to post a reply.</a></p>';
    } else echo "<p>Error: that thread number doesn't exist.</p>";
} else echo "<p>Sorry threads table doesn't exist yet.</p>";

$pdo = null;
$stmt = null;
$stmt2 = null;
?>