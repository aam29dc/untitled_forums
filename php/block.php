<?php
session_start();
require_once('conn.php');
require_once('lib.php');

$blockuser = str_replace("page=block&user=", "", $_SERVER['QUERY_STRING']);
    $blockuser = str_replace("&b=1", "", $blockuser);
    $blockuser = str_replace("&b=0", "", $blockuser);

$block = str_replace("page=block&user=".$blockuser."&b=", "", $_SERVER['QUERY_STRING']);

$error = false;

if(empty($blockuser) || !isset($blockuser) || is_numeric($blockuser)){
    echo "<p>Error: no user selected.</p>";
    $error = true;
}

if((empty($block) || !isset($block) || !is_numeric($block)) && $block !=0){
    echo "<p>Error: bool block variable not correctly set.</p>";
    $error = true;
}

//check if user exists & get id
$stmt = $pdo->prepare("SELECT userid FROM users WHERE username = ?;");
$stmt->bindValue(1, $blockuser);
$stmt->execute();
$blockid = $stmt->fetchColumn();

if($_SESSION['userid'] == $blockid){
    echo "<p>Error: can't block yourself.</p>";
    $error = true;
}

if(empty($blockid)){
    echo "<p>Error: that user doesn't exist.</p>";
    goto end;
}

if($error) goto end;

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET) && !empty($_GET)){
    //check if already blocked
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM blocks WHERE userid = :userid AND blockid = :blockid;");
    $stmt->bindValue(":userid", $_SESSION['userid']);
    $stmt->bindValue(":blockid", $blockid);
    $stmt->execute();
    $blocked = $stmt->fetchColumn();

    //if not blocked, do an insert
    if(empty($blocked) || $blocked == false){
        $stmt = $pdo->prepare("INSERT INTO blocks (userid, blockid) VALUES (:userid, :blockid);");
        $stmt->bindValue(":userid", $_SESSION['userid']);
        $stmt->bindValue(":blockid", $blockid);
        if($stmt->execute()){
            echo '<p>User: '.$blockuser.' blocked.</p>';
        }
        else {
            echo '<p>Error: failed to block user: '.$blockuser.'.</p>';
        }
    }
    //otherwise do an update
    else {
        $stmt = $pdo->prepare("UPDATE blocks SET blocked = :block WHERE userid = :userid AND blockid = :blockid;");
        $stmt->bindValue(":block", $block, PDO::PARAM_BOOL);
        $stmt->bindValue(":userid", $_SESSION['userid']);
        $stmt->bindValue(":blockid", $blockid);
        if($stmt->execute()){
            if(!$block) echo '<p>User: '.$blockuser.' unblocked.</p>';
            else echo '<p>User: '.$blockuser.' blocked.</p>';
        }
        else {
            echo '<p>Error: failed to block user: '.$blockuser.'.</p>';
        }
    }
} else {
    echo "<p>Error: no form submitted.</p>";
}
end:
$pdo = null;
$stmt = null;
?>