<?php
require_once('lib.php');

echo "Userx.edu | ";

$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);

if($q['page'] === 'home' || (basename($_SERVER['PHP_SELF'], ".php") === "index" && empty($_SERVER['QUERY_STRING'])))
	echo "Home";
else if($q['page'] === 'polls')
	echo "Polls";
else if($q['page'] === 'submit_poll')
	echo "Submit Poll";
else if($q['page'] === 'submit')
	echo "Submit Article";
else if($q['page'] === 'about')
	echo "About us";
else if($q['page'] === 'sub')
	echo "Subscribe";
else if($q['page'] === 'unsub')
	echo "Unsubscribe";
else if($q['page'] === 'signup')
	echo "Signup";
else if($q['page'] === 'login')
	echo "Login";
else if(basename($_SERVER['PHP_SELF'], ".php") === "logout")
	echo "Logout";
else if(basename($_SERVER['PHP_SELF'], ".php") === "logged")
	echo "Logging in";
else if(basename($_SERVER['PHP_SELF'], ".php") === "submit_vote")
	echo "Submitting vote";
else if(contains("page=member", $_SERVER['QUERY_STRING']))
	echo "Member | ".str_replace("&user=", "", str_replace("page=member", "", $_SERVER['QUERY_STRING']));
else if(basename($_SERVER['PHP_SELF'], ".php") === "edit")
	echo "Edit Post | ".str_replace("posts=", "", $_SERVER['QUERY_STRING']);
else if(basename($_SERVER['PHP_SELF'], ".php") === "edited_thread")
	echo "Edited thread";
else if(contains("thread=", $_SERVER['QUERY_STRING'])){
	if(basename($_SERVER['PHP_SELF'], ".php") === "edit_thread") echo "Edit ";
	echo "Thread | ";
	$thread = $q['thread'];

	require_once(abs_php_include($x).'conn.php');

	if(!is_numeric($thread) || empty($thread)){ // if query string is not numeric, then thread = newest thread
		$stmt = $pdo->prepare("SELECT MAX(threadid) FROM threads;");
		if($stmt->execute()){
			$thread = $stmt->fetchColumn();
		}
		else $thread = 1;
	}

	$stmt = $pdo->prepare("SELECT title FROM threads WHERE threadid = :thread;");
	$stmt->bindValue(":thread", $thread);
	if($stmt->execute()){
		if($stmt->rowCount() > 0){
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			echo $row['title'];
		}
		else echo "404";
	}
	else echo "404";
}
else if(contains("poll=", $_SERVER['QUERY_STRING'])){
	if(basename($_SERVER['PHP_SELF'], ".php") === "edit_poll") echo "Edit ";
	echo "Poll | ";
	$poll = $q['poll'];

	require_once(abs_php_include($x).'conn.php');

	if(!is_numeric($poll) || empty($poll)){ // if query string is not numeric, then thread = newest thread
		$stmt = $pdo->prepare("SELECT MAX(pollid) FROM polls;");
		if($stmt->execute()){
			$poll = $stmt->fetchColumn();
		}
		else $poll = 1;
	}

	$stmt = $pdo->prepare("SELECT question FROM polls WHERE pollid = :poll;");
	$stmt->bindValue(":poll", $poll);
	if($stmt->execute()){
		if($stmt->rowCount() > 0){
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			echo $row['question'];
		}
		else echo "404";
	}
	else echo "404";
}
else if(contains("&submit=x", $_SERVER['QUERY_STRING'])){
	echo "Search | ";
	if(isset($_GET['search'])) echo $_GET['search'];
}
else if(basename($_SERVER['PHP_SELF'], ".php") === "submitted")
	echo "Submitting article";
else if(basename($_SERVER['PHP_SELF'], ".php") === "signedup")
	echo "Registering user";
else if(basename($_SERVER['PHP_SELF'], ".php") === "subscribed")
	echo "Registering subscription";
else if(basename($_SERVER['PHP_SELF'], ".php") === "unsubscribed")
	echo "Removing subscription";
else if(basename($_SERVER['PHP_SELF'], ".php") === "posted")
	echo "Submitting post";
else if(contains("page=profile", $_SERVER['QUERY_STRING']))
	echo "My Profile";
else if(basename($_SERVER['PHP_SELF'], ".php") === "profile_msg")
	echo "Edit tag";
else if(basename($_SERVER['PHP_SELF'], ".php") === "profile_password")
	echo "Edit password";
else if(basename($_SERVER['PHP_SELF'], ".php") === "profile_username")
	echo "Edit username";
else if($q['page'] === 'cp_users')
	echo "Control Panel | Users";
else if(contains("page=inbox", $_SERVER['QUERY_STRING']))
	echo "Inbox";
else if(contains("page=pm", $_SERVER['QUERY_STRING']))
	echo "Send PM";
else if(contains("page=convo", $_SERVER['QUERY_STRING']))
	echo "Conversation";
else if(contains("page=block", $_SERVER['QUERY_STRING']))
	echo "Block user";
else
	echo "Untitled";	// Home
?>