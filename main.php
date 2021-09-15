<?php
session_start();

$page = str_replace("page=", "", $_SERVER['QUERY_STRING']);

if(!is_numeric($page) || empty($page) || $page < 1) $page = 1;   // if query string is not numeric, then page=1;

echo "<div>";

//save page for when user hits, back, inside a thread
$_SESSION['pagesid'] = $page;

require_once('php/lib.php');
require_once('php/conn.php');

if(tableExists($pdo,'threads')){
    $stmt = $pdo->prepare("SELECT threadid, title, msg, authorid, date FROM threads ORDER BY threadid DESC LIMIT ?, ".TMAX.";");  // ? = ($page-1)*10
    $stmt->bindValue(1, (int)(($page-1)*TMAX), PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo '<h3><a style="display:block;" href="?thread='.$row['threadid'].'">'.$row['threadid']." ";
            
            //check if user is blocked
            $stmt2 = $pdo->prepare("SELECT blocked FROM blocks WHERE userid = :userid AND blockid = :blockid;");
            $stmt2->bindValue(":userid", $_SESSION['userid']);
            $stmt2->bindValue(":blockid", $row['authorid']);
            $stmt2->execute();
            $blocked = $stmt2->fetchColumn();

            if($blocked) echo "Blocked title.";
            else echo htmlspecialchars($row['title']);
            //get count of replies
            $stmt2 = $pdo->prepare("SELECT COUNT(threadid) FROM posts WHERE threadid = ?;");
            $stmt2->bindValue(1, $row['threadid']);
            $stmt2->execute();
            echo '<span style="float:right;">'.$stmt2->fetchColumn().' replies</span></a></h3><p style="text-indent:5px;">';
            
            if($blocked) echo "Blocked message.";
            else echo htmlchars_minus($row['msg'], "a", "b", "i", "u", "s", "sub", "sup").'</p>';
            //get username
            $stmt2 = $pdo->prepare("SELECT username FROM users WHERE userid = ?;");
            $stmt2->bindValue(1, $row['authorid']);
            $stmt2->execute();
            $author = $stmt2->fetchColumn();
            echo '<h5><a href="index.php?page=member&user='.$author.'">- '.$author.'</a><span style="float:right;">'.$row['date'].'</span></h5>';
            echo "<hr><br/>"."\n";
        }
        echo "<br>";
        //BUTTON: PREV
        if($page!=1) echo '<a class="nsyn" href="?page='.($page-1).'"><button>Previous</button></a> ';
        //BUTTON: NEXT
        if($stmt->rowCount() >= TMAX) echo '<a class="nsyn" href="?page='.($page+1).'"><button>Next</button></a>';
    }
    else{
        echo "<p>No threads on this page.</p>";
        if($page!=1) echo '<a class="nsyn" href="?page='.($page-1).'"><button>Previous</button></a>';
    }
}
else{
    echo "<p>Sorry threads table doesn't exist yet.</p>";
}
$pdo = null;
$stmt = null;
$stmt2 = null;

echo "</div>";
?>