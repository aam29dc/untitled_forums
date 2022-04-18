<?php session_start();
$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);

require_once('php/conn.php');

//check if user already voted and get choiceid
$stmt = $pdo->prepare("SELECT choiceid FROM polls_votes WHERE pollid = :pollid AND userid = :userid;");
$stmt->bindValue(":pollid", $q['poll']);
$stmt->bindValue(":userid", $_SESSION['userid']);
$stmt->execute();

if($stmt->rowCount() === 0) $voted = false;
else {
     $voted = true;
     $choiceid = $stmt->fetchColumn();
}

//get authorid, and question from polls
$stmt = $pdo->prepare("SELECT pollid, authorid, question, date FROM polls WHERE pollid = :pollid;");
$stmt->bindValue(":pollid", $q['poll']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo '<div><h1>'.$row['question'].'</h1>';

//get username from authorid
$stmt = $pdo->prepare("SELECT username FROM users WHERE userid = :authorid;");
$stmt->bindValue(":authorid", $row['authorid']);
$stmt->execute();

echo'<h5>'.$stmt->fetchColumn().'<span style="float:right;">'.$row['date'].'</span></h5></div>';

//get total # of votes
$stmt = $pdo->prepare("SELECT COUNT(userid) FROM polls_votes WHERE pollid = :pollid;");
$stmt->bindValue(":pollid", $q['poll']);
$stmt->execute();
$total = $stmt->fetchColumn();

//get choices from polls_choices
$stmt = $pdo->prepare("SELECT choiceid, choice FROM polls_choices WHERE pollid = :pollid;");
$stmt->bindValue(":pollid", $q['poll']);
$stmt->execute();

echo '<div style="width:98%;margin:0 auto;"><form action="php/submit_vote.php?pollid='.$q['poll'].'" method="post">';

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo '<span>'.$row['choiceid'].') </span>';
    if(!$voted && isset($_SESSION['loggedin'])){
        echo '<input type="radio" name="choice" value="'.$row['choiceid'].'"/>';
    }

    echo '<span> "'.$row['choice'].'"</span>';
    if(!$voted) echo '<br/>';
    else {  //user already voted: draw percentages, and a bar graph
        $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM polls_votes WHERE pollid = :pollid AND choiceid = :choiceid;");
        $stmt2->bindValue(":pollid", $q['poll']);
        $stmt2->bindValue(":choiceid", $row['choiceid']);
        $stmt2->execute();
        $count = $stmt2->fetchColumn();
        if($total === 0) $percent = 0;
        else $percent = ($count / $total * 100);
        echo '<span style="margin-left:15px;"> '.(floor($percent*100)/100).'%</span><span style="margin-left:15px;">('.$count.' votes)</span><div style="background-color:rgb(155,155,155);height:16px;width:';
        if($percent <= 0.1) echo '0.1';
        else echo $percent;
        echo '%;"></div>';
    }
}

echo '<br/><span>'.$total.' total votes</span>';
if($voted){ 
    //get choice from choiceid
    $stmt = $pdo->prepare("SELECT choice FROM polls_choices WHERE pollid = :pollid AND choiceid = :choiceid;");
    $stmt->bindValue(":pollid", $q['poll']);
    $stmt->bindValue(":choiceid", $choiceid);
    $stmt->execute();
    echo '<br/><span>You voted #'.$choiceid.' "'.$stmt->fetchColumn().'".</span>';
}
if(!$voted && isset($_SESSION['loggedin'])) echo '<br/><button>Vote</button></form><br/>';
else echo '</form>';
if(!isset($_SESSION['loggedin'])) echo '<p style="float:right;padding:0;"><a href="index.php?page=login">Sign in to vote.</a></p>';
echo '</div>';

end:
$pdo = null;
$stmt = null;
?>