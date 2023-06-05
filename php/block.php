<?php
require_once('_conn.php');
require_once('_lib.php');

$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);
//$blockuser = $q['user'];
//$block = $q['b'];

$error = false;

if(!isset($q['user']) || is_numeric($q['user'])){
    echo "<p>Error: no user selected.</p>";
    $error = true;
}

if((!isset($q['b']) || !is_numeric($q['b'])) && $q['b'] !==0){
    echo "<p>Error: bool block variable not correctly set.</p>";
    $error = true;
}

//check if user exists & get id
$stmt = $pdo->prepare("SELECT userid FROM users WHERE username = ?;");
$stmt->bindValue(1, $q['user']);
$stmt->execute();
$blockid = $stmt->fetchColumn();

if($_SESSION['userid'] === $blockid){
    echo "<p>Error: can't block yourself.</p>";
    $error = true;
}

if(!isset($blockid)){
    echo "<p>Error: that user doesn't exist.</p>";
    $error = true;
}

if($error) goto end;

if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET) && isset($_GET)){
    //check if already blocked
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM blocks WHERE userid = :userid AND blockid = :blockid;");
    $stmt->bindValue(":userid", $_SESSION['userid']);
    $stmt->bindValue(":blockid", $blockid);
    $stmt->execute();
    $blocked = $stmt->fetchColumn();

    //if not blocked, do an insert
    if(!isset($blocked) || $blocked === false){
        $stmt = $pdo->prepare("INSERT INTO blocks (userid, blockid) VALUES (:userid, :blockid);");
        $stmt->bindValue(":userid", $_SESSION['userid']);
        $stmt->bindValue(":blockid", $blockid);
        if($stmt->execute()){
            echo '<p>User: '.$q['user'].' blocked.</p>';
        }
        else {
            echo '<p>Error: failed to block user: '.$q['user'].'.</p>';
        }
    }
    //otherwise do an update
    else {
        $stmt = $pdo->prepare("UPDATE blocks SET blocked = :block WHERE userid = :userid AND blockid = :blockid;");
        $stmt->bindValue(":block", $q['b'], PDO::PARAM_BOOL);
        $stmt->bindValue(":userid", $_SESSION['userid']);
        $stmt->bindValue(":blockid", $blockid);
        if($stmt->execute()){
            if(!$q['b']) echo '<p>User: '.$q['user'].' unblocked.</p>';
            else echo '<p>User: '.$q['user'].' blocked.</p>';
        }
        else {
            echo '<p>Error: failed to block user: '.$q['user'].'.</p>';
        }
    }
} else {
    echo "<p>Error: no form submitted.</p>";
}
end:
$pdo = null;
$stmt = null;
?>