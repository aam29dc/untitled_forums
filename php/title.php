<?php
require_once('lib.php');

echo "Userx.edu | ";

if($_SERVER['QUERY_STRING'] === 'page=home')
	echo "Home";
else if($_SERVER['QUERY_STRING'] === 'page=submit')
	echo "Submit";
else if($_SERVER['QUERY_STRING'] === 'page=about')
	echo "About us";
else if($_SERVER['QUERY_STRING'] === 'page=sub' || contains("&subscribe=Subscribe", $_SERVER['QUERY_STRING']))
	echo "Subscribe";
else if(contains("&unsub=Unsub", $_SERVER['QUERY_STRING']))
	echo "Unsubscribe";
else if($_SERVER['QUERY_STRING'] === 'page=signup')
	echo "Signup";
else if($_SERVER['QUERY_STRING'] === 'page=login')
	echo "Login";
else if(basename($_SERVER['PHP_SELF'], ".php")=="logout")
	echo "Logout";
else if(contains("page=member", $_SERVER['QUERY_STRING'])){
	echo "Member | ".str_replace("&user=", "", str_replace("page=member", "", $_SERVER['QUERY_STRING']));
}
else if(basename($_SERVER['PHP_SELF'], ".php")=="edited_thread")
	echo "Edited thread";
else if(contains("thread=", $_SERVER['QUERY_STRING'])){
	echo "Thread | ";
	$thread = str_replace("thread=", "", $_SERVER['QUERY_STRING']);

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
		else{
			echo "404";
		}
	}
	else{
		echo "404";
	}
}
else if(contains("&submit=x", $_SERVER['QUERY_STRING'])){
	echo "Search | ";
	if(isset($_GET['search'])) echo $_GET['search'];
}
else if(basename($_SERVER['PHP_SELF'], ".php")=="submitted")
	echo "Submitting article";
else if(basename($_SERVER['PHP_SELF'], ".php")=="signedup")
	echo "Registering user";
else if(basename($_SERVER['PHP_SELF'], ".php")=="subscibed")
	echo "Registering subscription";
else if(basename($_SERVER['PHP_SELF'], ".php")=="unsubscribed")
	echo "Removing subscription";
else if(basename($_SERVER['PHP_SELF'], ".php")=="posted")
	echo "Submitting post";
else if(basename($_SERVER['PHP_SELF'], ".php")=="profile_msg")
	echo "Edit tag";
else if(basename($_SERVER['PHP_SELF'], ".php")=="profile_password")
	echo "Edit password";
else if(basename($_SERVER['PHP_SELF'], ".php")=="profile_username")
	echo "Edit username";
else if($_SERVER['QUERY_STRING'] === 'page=cp_users')
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
	echo "Index";
?>