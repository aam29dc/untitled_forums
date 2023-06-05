<?php
require_once('_conn.php');

//users
$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS users (userid int NOT NULL PRIMARY KEY AUTO_INCREMENT, username VARCHAR(32) NOT NULL UNIQUE, priviledge int NOT NULL DEFAULT '0', password VARCHAR(255) NOT NULL, email VARCHAR(32) NOT NULL UNIQUE, jdate datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, tag VARCHAR(64));");
if($stmt->execute()){}
else { echo 'failed to create users';}

//reset tokens (for password reset)
/* $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS tokens (userid int NOT NULL UNIQUE, expdate datetime NOT NULL DEFAULT CURRENT_TIMESTAMP + INTERVAL 1 HOURS, FOREIGN KEY (userid) REFERENCES users(userid));");
if($stmt->execute()){}
else { echo 'failed to create tokens';} */

//threads
$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS threads (threadid int NOT NULL PRIMARY KEY AUTO_INCREMENT, authorid int NOT NULL, title VARCHAR(128) NOT NULL, msg TEXT NOT NULL, date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, posts int NOT NULL DEFAULT '0', FOREIGN KEY (authorid) REFERENCES users(userid));");
if($stmt->execute()){}
else { echo 'failed to create threads';}

//posts
$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS posts (postid int NOT NULL PRIMARY KEY AUTO_INCREMENT, threadid int NOT NULL, authorid int NOT NULL, title VARCHAR(128), msg TEXT NOT NULL, date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, postnum int NOT NULL, replyid int DEFAULT NULL, FOREIGN KEY (threadid) REFERENCES threads(threadid), FOREIGN KEY (authorid) REFERENCES users(userid), FOREIGN KEY (replyid) REFERENCES posts(postid));");
if($stmt->execute()){}
else { echo 'failed to create posts';}

//likes (posts)
$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS posts_likes (postid int NOT NULL, likeid int NOT NULL, threadid int NOT NULL, FOREIGN KEY (postid) REFERENCES posts(postid), FOREIGN KEY (likeid) REFERENCES users(userid), FOREIGN KEY (threadid) REFERENCES threads(threadid));");
if($stmt->execute()){}
else { echo 'failed to create likes for posts';}

//polls
$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS polls (pollid int NOT NULL PRIMARY KEY AUTO_INCREMENT, question VARCHAR(32) NOT NULL, authorid int NOT NULL, date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (authorid) REFERENCES users(userid));");
if($stmt->execute()){}
else { echo 'failed to create polls';}

//polls - choices
$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS polls_choices (choiceid int NOT NULL, pollid int NOT NULL, choice VARCHAR(32) NOT NULL, FOREIGN KEY (pollid) REFERENCES polls(pollid), UNIQUE KEY (choiceid, pollid));");
if($stmt->execute()){}
else { echo 'failed to create polls choice';}

//polls - votes
$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS polls_votes (pollid int NOT NULL, choiceid int NOT NULL, userid int NOT NULL, FOREIGN KEY (pollid) REFERENCES polls(pollid), FOREIGN KEY (choiceid) REFERENCES polls_choices(choiceid), FOREIGN KEY (userid) REFERENCES users(userid));");
if($stmt->execute()){}
else { echo 'failed to create polls votes';}

//subs
$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS subs (firstname VARCHAR(32), lastname VARCHAR(32), email VARCHAR(32) NOT NULL UNIQUE);");
if($stmt->execute()){}
else { echo 'failed to create subs';}

//bans
$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS bans (userid int NOT NULL PRIMARY KEY, email VARCHAR(32), lift datetime, FOREIGN KEY (userid) REFERENCES users(userid));");
if($stmt->execute()){}
else { echo 'failed to create bans';}

//personal messages //pmid int NOT NULL PRIMARY KEY AUTO_INCREMENT // timesent used instead
$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS pms (fromid int NOT NULL, toid int NOT NULL, timesent datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, msg TEXT NOT NULL, seen BIT NOT NULL DEFAULT FALSE, FOREIGN KEY (fromid) REFERENCES users(userid), FOREIGN KEY (toid) REFERENCES users(userid));");
if($stmt->execute()){}
else { echo 'failed to create pms';}

//blocks
$stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS blocks (userid int NOT NULL, blockid int NOT NULL, blocked BIT NOT NULL DEFAULT TRUE, FOREIGN KEY (userid) REFERENCES users(userid), FOREIGN KEY (blockid) REFERENCES users(userid));");
if($stmt->execute()){}
else { echo 'failed to create blocks';}

$pdo = null;
$stmt = null;
?>