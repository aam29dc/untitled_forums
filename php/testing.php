<?php
//check if user is banned
include_once('conn.php');
$stmt = $pdo->prepare("SELECT lift FROM bans WHERE userid = 3");
$stmt->execute();
$unban = $stmt->fetchColumn();

//echo "x: ".empty($unban)." ".$unban."\n";

echo "y: ".time()." ".(strtotime($unban) + 14400)."\n"." z: ".(time() - (strtotime($unban) + 14400 ));

if((time() < strtotime($unban) + 14400) && !empty($unban)){
    echo "banned";
}
else {
    echo "unbanned";
}
?>