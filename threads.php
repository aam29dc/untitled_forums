<?php
$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);

if(!isset($q['page']) || !is_numeric($q['page']) || $q['page'] <= 1) $q['page'] = 1;   // if query string is not numeric, then page=1;

//save page for when user hits, back, inside a thread
$_SESSION['mainid'] = $q['page'];

require_once('php/_lib.php');
require_once('php/_conn.php');

if(tableExists($pdo,'threads')){
    $stmt = $pdo->prepare("SELECT threadid, title, msg, authorid, date FROM threads ORDER BY threadid DESC LIMIT ?, ".TMAX.";");  // ? = ($q['page']-1)*10
    $stmt->bindValue(1, (int)(($q['page']-1)*TMAX), PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo '<h3><a class="nsyn" style="display:block;" href="?thread='.$row['threadid'].'">'.$row['threadid'].") ";
            
            //check if user is blocked
            if(isset($_SESSION['loggedin'])){
                $stmt2 = $pdo->prepare("SELECT blocked FROM blocks WHERE userid = :userid AND blockid = :blockid;");
                $stmt2->bindValue(":userid", $_SESSION['userid']);
                $stmt2->bindValue(":blockid", $row['authorid']);
                $stmt2->execute();
                $blocked = $stmt2->fetchColumn();
            }

            if(isset($_SESSION['loggedin']) && $blocked) echo "Blocked title.";
            else echo htmlspecialchars(stripslashes($row['title']));
            //get count of replies
            $stmt2 = $pdo->prepare("SELECT COUNT(threadid) FROM posts WHERE threadid = ?;");
            $stmt2->bindValue(1, $row['threadid']);
            $stmt2->execute();
            echo '<span style="float:right;">'.$stmt2->fetchColumn().' replies</span></a></h3><p style="text-indent:5px;">';
            
            if(isset($_SESSION['loggedin']) && $blocked) echo "Blocked message.";
            else {
                echo substr(htmlchars_minus(stripslashes($row['msg']), ...$htmltags).'</p>', 0, 500);
                if(strlen(htmlchars_minus(stripslashes($row['msg']), ...$htmltags).'</p>') > 500){
                    echo "<span>...[continued]...</span>";
                }
            }
            //get username
            $stmt2 = $pdo->prepare("SELECT username FROM users WHERE userid = ?;");
            $stmt2->bindValue(1, $row['authorid']);
            $stmt2->execute();
            $author = $stmt2->fetchColumn();
            echo '<h5><a href="index.php?page=member&user='.$author.'">- '.$author.'</a><span style="float:right;">'.$row['date'].'</span></h5>';
            echo "<hr>"."\n";
        }
        echo "<br>";
        //BUTTON: PREV
        if($q['page']>1) echo '<a class="nsyn" href="?page='.($q['page']-1).'"><button>&laquo; Previous</button></a> ';
        //BUTTON: NEXT
            //get count of threads
            $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM threads;");
            $stmt2->execute();
        if($stmt2->fetchColumn() > TMAX && $stmt->rowCount() === TMAX) echo '<a class="nsyn" href="?page='.($q['page']+1).'"><button>Next &raquo;</button></a>';
    }
    else {
        echo "<p>No threads on this page.</p>";
        if($q['page'] > 1) echo '<a class="nsyn" href="?page='.($q['page']-1).'"><button>&laquo; Previous</button></a>';
    }
} else echo "<p>Sorry threads table doesn't exist yet.</p>";

$pdo = null;
$stmt = null;
$stmt2 = null;
?>