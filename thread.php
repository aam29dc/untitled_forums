<?php @session_start(); //when posting w/ ajax the refresh should keep session data
@include('php/_conn.php');   /* require_once: causes an error, include fixes it but causes a warning for already defined constants ... */
require_once('php/_lib.php');

function createpages($numpages, $currentpage, $link){
    echo " [ ";

    for($i=1;$i<=$numpages-1;$i++){
        if($currentpage === $i) echo '<a>'.$i.'</a>, ';
        else echo '<a href="'.$link.$i.'">'.$i.'</a>, ';
    }

    if($currentpage === $i) echo '<a>'.$i.'</a>';
    else echo '<a href="'.$link.$i.'">'.$i.'</a>';

    echo " ] ";
}

//get thread and pages numbers alone
$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);

if(!isset($q['thread']) || !is_numeric($q['thread'])){     // if query string is not numeric, then set equal to newest thread
    $stmt = $pdo->prepare("SELECT MAX(threadid) FROM threads;");
    if($stmt->execute()){
        $q['thread'] = $stmt->fetchColumn();
    }
    else $q['thread'] = 1;
}
if(!isset($q['pages']) || !is_numeric($q['pages']) || $q['pages'] < 1) $q['pages'] = 1;
if(!isset($q['sort']) || !is_numeric($q['sort'])) $q['sort'] = 0;

$banned = false;

//check if user is banned
if(isset($_SESSION['loggedin'])){
    $stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = ?;");
    $stmt->bindValue(1, $_SESSION['userid']);
    $stmt->execute();
    $unban = $stmt->fetchColumn();
}
if((isset($_SESSION['loggedin']) && time() < strtotime($unban) + 14400) && isset($unban)) $banned = true;

if(tableExists($pdo,'threads')){
    $_SESSION['threadid'] = $q['thread'];
    $_SESSION['pagesid'] = $q['pages'];

    $stmt = $pdo->prepare("SELECT threadid, title, msg, authorid, date FROM threads WHERE threadid = :thread;");
    $stmt->bindValue(':thread', $q['thread']);
    $stmt->execute();

    //print THREAD title, msg, 
    if($stmt->rowCount() > 0){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo '<div id="threadtitle" style="background-color:var(--heartsectionh1c);"><h1 id="thread_title" style="display:inline;">';
        
        //check if user is blocked
        if(isset($_SESSION['loggedin'])){
            $stmt = $pdo->prepare("SELECT blocked FROM blocks WHERE userid = :userid AND blockid = :blockid;");
            $stmt->bindValue(":userid", $_SESSION['userid']);
            $stmt->bindValue(":blockid", $row['authorid']);
            $stmt->execute();
            $blocked = $stmt->fetchColumn();
        }
        
        if(isset($_SESSION['loggedin']) && $blocked) echo 'Blocked title.</h1>';
        else echo htmlspecialchars(stripslashes($row['title']))."</h1>";
        // BUTTON: (thread) EDIT
        if(isset($_SESSION['loggedin']) && (($_SESSION['userid'] === $row['authorid']) || $_SESSION['priviledge'] >= 2) && !$banned){
            echo '<a id="edit_thread" class="nsyn" href="php/thread_edit.php?thread='.$q['thread'].'"><img src="img/edit16.png" alt="edit" style="float:right;"></a>';
        }
        echo '</div><p id="thread_msg" style="text-indent:5px;">';

        if(isset($_SESSION['loggedin']) && $blocked) echo "Blocked message.";
        else echo htmlchars_minus(stripslashes($row['msg']), ...$htmltags)."</p>";

        // get username
        $stmt = $pdo->prepare("SELECT username FROM users WHERE userid = :userid;");
        $stmt->bindValue(":userid", $row['authorid']);
        $stmt->execute();
        $author = $stmt->fetchColumn();
        echo '<h5><a href="index.php?page=member&user='.$author.'">- '.$author.'</a><span style="float:right;">'.$row['date']."</span></h5>";
        echo "<hr>"."\n";

        if(!isset($q['sort']) || $q['sort'] == 0) $order = "date ASC";
        else $order = "date DESC";
        //print POSTS from posts table
        $stmt = $pdo->prepare("SELECT postid, authorid, replyid, postnum, title, msg, date FROM posts WHERE threadid = :thread ORDER BY ".$order." LIMIT :pages, ".TMAX.";");
        $stmt->bindValue(":thread", $q['thread']);
        $stmt->bindValue(":pages", (int)(($q['pages']-1)*TMAX), PDO::PARAM_INT);
        $stmt->execute();

        if($stmt->rowCount() > 0){
        //sort thread by date links
            echo '<div>Sort by: ';
            if($q['sort'] == 1) echo '<a class="nsyn" href="?thread='.$q['thread'].'&pages='.$q['pages'].'&sort=0">Oldest</a>';
            else echo 'Oldest';
            echo ' | '; 
            if(!isset($q['sort']) || $q['sort'] == 0) echo '<a class="nsyn" href="?thread='.$q['thread'].'&pages='.$q['pages'].'&sort=1">Newest</a>';
            else echo 'Newest';
            echo '</div>';

            echo '<table style="table-layout:fixed;">';
            $i = 1;
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // get users username
                $stmt2 = $pdo->prepare("SELECT username FROM users WHERE userid = :userid;");
                $stmt2->bindValue(":userid", $row['authorid']);
                $stmt2->execute();
                $author = $stmt2->fetchColumn();
                echo '<tr><td class="tduser"><mark><a href="index.php?page=member&user='.$author.'">'.$author.'</a></mark><br><img src="img/user24.png" alt="user"><br><small>';
                    // get users # of posts
                    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE authorid = :userid;");
                    $stmt2->bindValue(":userid", $row['authorid']);
                    $stmt2->execute();
                    echo $stmt2->fetchColumn()."</small></td><td><h4>#";

                echo $row['postnum'].' ';           //$i + ($q['pages']-1)*TMAX." ";  // number of post in thread

                //check if user is blocked
                if(isset($_SESSION['loggedin'])){
                    $stmt2 = $pdo->prepare("SELECT blocked FROM blocks WHERE userid = :userid AND blockid = :blockid;");
                    $stmt2->bindValue(":userid", $_SESSION['userid']);
                    $stmt2->bindValue(":blockid", $row['authorid']);
                    $stmt2->execute();
                    $blocked = $stmt2->fetchColumn();
                }

                echo '<span class="post_title">';

                if(isset($_SESSION['loggedin']) && $blocked) echo "Blocked title.";
                else{
                    if(isset($row['replyid'])){
                        //get postnum from replyid
                        $stmt2 = $pdo->prepare("SELECT postnum FROM posts WHERE postid = :postid;");
                        $stmt2->bindValue(":postid", $row['replyid']);
                        $stmt2->execute();

                        echo " [Reply to #".$stmt2->fetchColumn()."] ";
                    }
                    echo htmlspecialchars($row['title']);
                }

                echo '</span>';

                if(isset($_SESSION['loggedin'])){
                    // BUTTON: REPLY
                    echo '<a data-postnum="'.$row['postnum'].'" data-replyid="'.$row['postid'].'" class="reply_post nsyn" href="?thread='.$q['thread'].'&pages='.$q['pages'].'&sort=';
                        if(!isset($q['sort'])) echo '0';
                        else echo $q['sort'];
                    echo '&replyid='.$row['postid'].'#post_content"><img style="float:right;width:16px;height:16px;" src="img/reply.png" alt="reply"></a>';
                }

                if(isset($_SESSION['loggedin']) && (($_SESSION['userid'] === $row['authorid']) || $_SESSION['priviledge'] >= 2) && !$banned){
                    $_SESSION['pagesid'] = $q['pages'];
                // BUTTON: EDIT
                    echo '<a data-num="'.($i-1).'" data-postid="'.$row['postid'].'" class="edit_post nsyn" href="php/post_edit.php?posts='.$row['postid'].'"><img style="float:right;" src="img/edit16.png" alt="edit"></a>';
                }

                echo "</h4>";
                echo '<p class="post_msg">';

                if(isset($_SESSION['loggedin']) && $blocked) echo "Blocked message.";
                else echo htmlchars_minus(stripslashes($row['msg']), ...$htmltags);

                //get # of likes for post
                $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM posts_likes WHERE postid = :postid;");
                $stmt2->bindValue(":postid", $row['postid']);
                $stmt2->execute();

                // BUTTON: UPVOTE post (likes)
                echo '</p><div><a data-postid="'.$row['postid'].'" class="upvote" href="php/like_post.php?threadId='.$q['thread'].'&postId='.$row['postid'].'"><img style="width:16px;height:16px;" src="img/like.png" alt="likes" /></a><span class="likes">'.$stmt2->fetchColumn().'</span>
                <div class="likeids">';
                // print names of who liked this post
                $stmt2 = $pdo->prepare("SELECT username FROM users WHERE userid IN (SELECT likeid FROM posts_likes WHERE postid = :postid);");
                $stmt2->bindValue(":postid", $row['postid']);
                $stmt2->execute();
                while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
                    echo $row2['username'].'<br>';
                }
                echo '</div><span style="float:right;text-align:right;">'.$row['date'].'</span></div></td></tr>';

                $i++;
            }
        } else if($stmt->rowCount() === 0 && $q['pages'] > 1) echo "<table><tr><td><p>This page does not exist yet.</p><td></tr>";
        else echo "<table><tr><td><p>No one has posted a reply yet.</p><td></tr>";
        
        // BUTTON: BACK
        echo '</table>';
        echo '<br><div style="float:left;"><a class="nsyn" href="index.php?page=';
            if(isset($_SESSION['mainid']) && $_SESSION['mainid'] != 0) echo $_SESSION['mainid'];
            else echo '1';
        echo '"><button>&laquo; Back</button></a><br>';

        // BUTTON: PREV
        if($q['pages'] > 1){
            echo '<br><a class="nsyn" href="?thread='.$q['thread'].'&pages='.($q['pages']-1).'"><button>&laquo; Prev</button></a>';
        }

        // BUTTON: NEXT
            // get thread number of posts
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE threadid = :threadid;");
            $stmt->bindValue(":threadid", $q['thread']);
            $stmt->execute();
            $numOfPosts = $stmt->fetchColumn();
            // get count of number of pages for thread
            if($numOfPosts > TMAX) $numOfPages = ceil($numOfPosts/TMAX);
            else $numOfPages = 0;

        if($numOfPages > 0){      
            createpages($numOfPages, $q['pages'], "?thread=".$q['thread']."&pages=");
            if($q['pages'] < $numOfPages) echo '<a class="nsyn" href="?thread='.$q['thread'].'&pages='.($q['pages']+1).'"><button>Next &raquo;</button></a>';
        }

        echo '</div>';

        // REPLY FORM OR SIGN IN
        if(isset($_SESSION['loggedin'])){
            if(!$banned){
            echo '<button id="post_coll" type="button" class="collapsible" style="float:right;">Post a reply</button>
            <div id="post_content" class="content" style="float:left;clear:left;">
                <form id="post_f" method="post" action="php/posted.php';
                if(isset($_GET['replyid'])){ echo '?replyid='.$_GET['replyid'].'';}    //add reply id to string
                echo '"><span id="reply">';
                    if(isset($_GET['replyid'])){ 
                        echo '[Reply to #';
                        //get postnum from replyid
                        $stmt = $pdo->prepare("SELECT postnum FROM posts WHERE postid = :postid;");
                        $stmt->bindValue(":postid", $_GET['replyid']);
                        $stmt->execute();
                        echo $stmt->fetchColumn()."]";
                    }
                    echo '</span>';
                    echo '<label for="post_title"><span style="display:none;">title</span></label><input type="text" id="post_title" name="post_title" size="98" class="textfield" style="width:98%;margin-bottom:5px;"/><br>';
                    include_once('php/_msg_buttons.php');
                    drawMsgButtons('post_text');
                    echo '<label for="post_text"><span style="display:none;">message</span></label><textarea id="post_text" name="post_text" class="textfield" rows="10" style="width:98%;"></textarea>
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