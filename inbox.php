<?php
require_once('php/_conn.php');
require_once('php/_lib.php');

define("IMAX", 10);     //inbox max messages per page
$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);
if(!isset($q['pages']) || !is_numeric($q['pages']) || $q['pages'] < 1) $q['pages'] = 1;

if(isset($_SESSION['loggedin'])){
    //get unique sent/received msgs (conversations)
    $stmt = $pdo->prepare("SELECT * FROM (SELECT *, row_number() OVER (PARTITION BY (CASE WHEN fromid = :userid THEN toid ELSE fromid END) ORDER BY timesent DESC) AS seqnum FROM pms WHERE :userid IN (fromid, toid)) pms WHERE seqnum = :userid LIMIT :page, ".IMAX.";");
    $stmt->bindValue(":userid", $_SESSION['userid']);
    $stmt->bindValue(":page", (int)(($q['pages']-1)*IMAX), PDO::PARAM_INT);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        //get unread messages
        $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM pms WHERE fromid = ? AND seen = false;");
        $stmt2->bindValue(1, $_SESSION['userid']);
        $stmt2->execute();
        echo '<table style="text-align:center;"><caption>Sent messages (unread): '.$stmt2->fetchColumn().'</caption>';

        echo '<tr><th style="width:5%;">?</th><th style="width:10%;">from</th><th style="width:10%;">time</th><th style="width:75%;">last msg</th></tr>';
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo '<tr><td>';
            
            //seen or unseen icon
            if($row['seen']) echo '<img src="img/seen16.png">';
            else echo '<img src="img/unseen16.png">';     

            //get the other users username
            $stmt2 = $pdo->prepare("SELECT username FROM users WHERE userid = ?;");
            if($row['fromid'] === $_SESSION['userid'])
                $stmt2->bindValue(1, $row['toid']);
            else
                $stmt2->bindValue(1, $row['fromid']);
            $stmt2->execute();
            $toname = $stmt2->fetchColumn();

            echo '</td><td><a href="index.php?page=member&user='.$toname.'">'.limitstr($toname, 7);
            echo '</a></td><td>'.date_format(date_create($row['timesent']), 'h:m:s').'</td><td class="link" onclick="window.location.href=`index.php?page=convo&id=';
            //get convo id
            if($row['fromid'] === $_SESSION['userid']) $toid = $row['toid'];
            else $toid = $row['fromid'];
            echo $toid.'`" >';

            echo '<noscript><a href="index.php?page=convo&id='.$toid.'">';

            $stmt2 = $pdo->prepare("SELECT blocked FROM blocks WHERE userid = :userid AND blockid = :blockid;");
            $stmt2->bindValue(":userid", $_SESSION['userid']);
            $stmt2->bindValue(":blockid", $toid);
            $stmt2->execute();
            if($stmt2->fetchColumn()){
                echo 'Blocked message.'.'</a></noscript><span style="display:none;">'.'Blocked message'.'</span></td></tr>';
            } else echo limitstr($row['msg'], 20).'</a></noscript><span style="display:none;">'.limitstr($row['msg'], 20).'</spa></td></tr>';
        }
        echo "</table>";
        //BUTTON: PREV
        if($q['pages'] > 1){
            echo '<a class="nsyn" href="index.php?page=inbox&pages='.($q['pages']-1).'"><button>Prev</button></a>';
        }
        //BUTTON: NEXT
        // one off ?
        $stmt2 = $pdo->prepare("SELECT SUM(num) FROM (SELECT COUNT(DISTINCT toid) AS num FROM pms WHERE fromid = :userid UNION SELECT COUNT(DISTINCT fromid) AS num FROM pms WHERE toid = :userid) AS x;");
        $stmt2->bindValue(":userid", $_SESSION['userid']);
        $stmt2->execute();
        if($q['pages'] < ceil($stmt2->fetchColumn()/IMAX)){
            echo '<a class="nsyn" href="index.php?page=inbox&pages='.($q['pages']+1).'"> <button>Next &raquo;</button></a>';
        }

    } else echo "<p>Inbox is !isset.</p>";
    $pdo = null;
    $stmt = null;
    $stmt2 = null;
} else echo "<p>Sign in to view inbox.</p>";
?>