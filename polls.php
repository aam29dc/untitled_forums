<?php
$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);

if(!isset($q['pages']) || !is_numeric($q['pages']) || $q['pages'] < 1) $q['pages'] = 1;   // if query string is not numeric, then page=1;

//save page for when user hits, back, inside a page
//$_SESSION['pollpage'] = $q['pages'];

require_once('php/_conn.php');
require_once('php/_lib.php');

if(tableExists($pdo,'polls')){
    $stmt = $pdo->prepare("SELECT pollid, question, authorid, date FROM polls ORDER BY pollid DESC LIMIT ?, ".TMAX.";");  // ? = ($q['pages']-1)*10
    $stmt->bindValue(1, (int)(($q['pages']-1)*TMAX), PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo '<h3><a class="nsyn" style="display:block;" href="?poll='.$row['pollid'].'">'.$row['pollid'].") ";
            
            //check if user is blocked
            if(isset($_SESSION['loggedin'])){
                $stmt2 = $pdo->prepare("SELECT blocked FROM blocks WHERE userid = :userid AND blockid = :blockid;");
                $stmt2->bindValue(":userid", $_SESSION['userid']);
                $stmt2->bindValue(":blockid", $row['authorid']);
                $stmt2->execute();
                $blocked = $stmt2->fetchColumn();
            }

            if(isset($_SESSION['loggedin']) && $blocked) echo "Blocked question.";
            else echo htmlspecialchars(stripslashes($row['question']));
            //get count of replies
            $stmt2 = $pdo->prepare("SELECT COUNT(userid) FROM polls_votes WHERE pollid = :pollid;");
            $stmt2->bindValue(":pollid", $row['pollid']);
            $stmt2->execute();
            echo '<span style="float:right;">'.$stmt2->fetchColumn().' votes</span></a></h3>';
            
            //get username
            $stmt2 = $pdo->prepare("SELECT username FROM users WHERE userid = ?;");
            $stmt2->bindValue(1, $row['authorid']);
            $stmt2->execute();
            $author = $stmt2->fetchColumn();
            echo '<h5><a href="index.php?page=member&user='.$author.'">- '.$author.'</a><span style="float:right;">'.$row['date'].'</span></h5>';
            echo "<hr><br>"."\n";
        }
        //BUTTON: PREV
        if($q['pages']!==1) echo '<a class="nsyn" href="?page=polls&pages='.($q['pages']-1).'"><button>&laquo; Previous</button></a><br/>';
        //BUTTON: NEXT
            //get count of polls
            $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM polls;");
            $stmt2->execute();
        if($stmt2->fetchColumn() > TMAX && $stmt->rowCount() === TMAX) echo '<a class="nsyn" href="?page=polls&pages='.($q['pages']+1).'"><button>Next &raquo;</button></a><br/>';
    }
    else {  //BUTTON: Prev (for main)
        echo "<p>No polls on this page.</p>";
        if($q['pages']!==1) echo '<a class="nsyn" href="?page=polls&pages='.($q['pages']-1).'"><button>&laquo; Previous</button></a>';
    }
    //BUTTON: CREATE POLL
    if(isset($_SESSION['loggedin'])) echo '<a class="nsyn" href="?page=submit_poll"><button>Create poll</button></a>';
} else echo "<p>Sorry polls table doesn't exist yet.</p>";

$pdo = null;
$stmt = null;
$stmt2 = null;
?>