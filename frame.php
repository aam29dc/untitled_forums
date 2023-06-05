<?php 
require_once('php/_lib.php');

function find_path($file, $depth = 2){
	for($i=0;$i<=2;$i++){
		if(@include_once(abs_php_include($i).$file)) break;
	}
}

$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);
if(!isset($q['page'])) $q['page'] = '404';

if($q['page'] === 'home')
	include_once('threads.php');
else if($q['page'] === 'submit')
	include_once('submit_thread.php');
else if($q['page'] === 'about')
	include_once('about.php');
else if($q['page'] === 'sub')
	include_once('subscribe.php');
else if($q['page'] === 'unsub')
	include_once('unsubscribe.php');
else if($q['page'] === 'signup')
	include_once('signup.php');
else if($q['page'] === 'login')
	include_once('login.php');
/*else if($q['page'] === 'logout')
	include_once('logout.php');*/
else if($q['page'] === 'profile')
	include_once('profile.php');
else if($q['page'] === 'inbox')
	include_once('inbox.php');
else if($q['page'] === 'cp_users')
	include_once('php/cp_users.php');
else if($q['page'] === 'member')
	include_once('member.php');
else if(contains("thread=", $_SERVER['QUERY_STRING']))
	include_once('thread.php');
else if(contains("&submit=x", $_SERVER['QUERY_STRING']))
	include_once('php/searched.php');
//else if(contains("&subscribe=Subscribe", $_SERVER['QUERY_STRING']))
//	include_once('php/subscribed.php');
//else if(contains("&unsub=Unsub", $_SERVER['QUERY_STRING']))
//	include_once('php/unsubscribed.php');
else if($q['page'] === 'pm')
	include_once('pm.php');
else if($q['page'] === 'convo')
	include_once('php/convo.php');
else if($q['page'] === 'block')
	include_once('php/block.php');
else if(contains("poll=", $_SERVER['QUERY_STRING']))
	include_once('poll.php');
else if($q['page'] === 'polls')
	include_once('polls.php');
else if($q['page'] === 'submit_poll')
	include_once('submit_poll.php');
else {
	if(@!include_once('threads.php')){
		@include_once('../threads.php');
	}
}
?>