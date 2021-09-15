<?php 
require_once('lib.php');

function find_path($file, $depth = 2){
	for($i=0;$i<=2;$i++){
		if(@include_once(abs_php_include($i).$file)) break;
	}
}

if($_SERVER['QUERY_STRING'] === 'page=home')
	include_once('main.php');
else if($_SERVER['QUERY_STRING'] === 'page=submit')
	include_once('submit.php');
else if($_SERVER['QUERY_STRING'] === 'page=about')
	include_once('about.php');
else if($_SERVER['QUERY_STRING'] === 'page=sub')
	include_once('subscribe.php');
else if($_SERVER['QUERY_STRING'] === 'page=unsub')
	include_once('unsubscribe.php');
else if($_SERVER['QUERY_STRING'] === 'page=signup')
	include_once('signup.php');
else if($_SERVER['QUERY_STRING'] === 'page=login')
	include_once('login.php');
/*else if($_SERVER['QUERY_STRING'] === 'page=logout')
	include_once('php/logout.php');*/
else if($_SERVER['QUERY_STRING'] === 'page=profile')
	include_once('profile.php');
else if(contains("page=inbox", $_SERVER['QUERY_STRING']))
	include_once('inbox.php');
else if(contains("page=cp_users", $_SERVER['QUERY_STRING']))
	include_once('cp_users.php');
else if(contains("page=member", $_SERVER['QUERY_STRING']))
	include_once('member.php');
else if(contains("thread=", $_SERVER['QUERY_STRING']))
	include_once('thread.php');
else if(contains("&submit=x", $_SERVER['QUERY_STRING']))
	include_once('php/searched.php');
else if(contains("&subscribe=Subscribe", $_SERVER['QUERY_STRING']))
	include_once('php/subscribed.php');
else if(contains("&unsub=Unsub", $_SERVER['QUERY_STRING']))
	include_once('php/unsubscribed.php');
else if(contains("page=pm", $_SERVER['QUERY_STRING']))
	include_once('pm.php');
else if(contains("page=convo", $_SERVER['QUERY_STRING']))
	include_once('php/convo.php');
else if(contains("page=block", $_SERVER['QUERY_STRING']))
	include_once('php/block.php');
else{
	if(@!include_once('main.php')){
		@include_once('../main.php');
	}
}
?>