<?php
require_once('php/_lib.php');

$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);

if(!isset($q['page']) || (isset($q['page']) && $q['page'] === 'home') || (basename($_SERVER['PHP_SELF'], ".php") === "index" && !isset($_SERVER['QUERY_STRING'])))
	echo "Home";
else if(basename($_SERVER['PHP_SELF']) === "index.php" && isset($q['page']) && is_numeric($q['page']))
	echo "Threads | ".$q['page'];
else if(isset($q['page']) && $q['page'] === 'polls')
	echo "Polls";
else if(isset($q['page']) && $q['page'] === 'submit_poll')
	echo "Submit Poll";
else if(isset($q['page']) && $q['page'] === 'submit')
	echo "Submit Article";
else if(isset($q['page']) && $q['page'] === 'about')
	echo "About us";
else if(isset($q['page']) && $q['page'] === 'sub')
	echo "Subscribe";
else if(isset($q['page']) && $q['page'] === 'unsub')
	echo "Unsubscribe";
else if(isset($q['page']) && $q['page'] === 'signup')
	echo "Signup";
else if(isset($q['page']) && $q['page'] === 'login')
	echo "Login";
else if(basename($_SERVER['PHP_SELF'], ".php") === "logout")
	echo "Logout";
else if(basename($_SERVER['PHP_SELF'], ".php") === "logged")
	echo "Logging in";
else if(basename($_SERVER['PHP_SELF'], ".php") === "poll_vote")
	echo "Submitting vote";
else if(contains("page=member", $_SERVER['QUERY_STRING']))
	echo "Member | ".str_replace("&user=", "", str_replace("page=member", "", $_SERVER['QUERY_STRING']));
else if(basename($_SERVER['PHP_SELF'], ".php") === "edit")
	echo "Edit Post | ".str_replace("posts=", "", $_SERVER['QUERY_STRING']);
else if(basename($_SERVER['PHP_SELF'], ".php") === "thread_edited")
	echo "Edited thread";
else if(contains("thread=", $_SERVER['QUERY_STRING'])){
	if(basename($_SERVER['PHP_SELF'], ".php") === "thread_edit") echo "Edit ";
	echo "Thread | ";
	$thread = $q['thread'];

	require_once(abs_php_include($x).'php/_conn.php');

	if(!is_numeric($thread) || !isset($thread)){ // if query string is not numeric, then thread = newest thread
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

	require_once(abs_php_include($x).'php/_conn.php');

	if(!is_numeric($poll) || !isset($poll)){ // if query string is not numeric, then thread = newest thread
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
else echo "Untitled";
	
echo " | ArrottaTech.com";
?>
