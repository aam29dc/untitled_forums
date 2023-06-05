<?php if(!isset($x)) $x = 0;require_once('php/_lib.php');?><!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="Cache-control" content="public"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php include_once(abs_php_include($x).'title.php');?></title>
	<meta name="description" content="userx's website"/>
	<meta name="keywords" content="programming, philosophy, math"/>
	<meta http-equiv="author" content="aam29dc"/>
	<link href="<?php abs_include($x);?>styles/styles.css?v=1" type="text/css" rel="stylesheet"/>
	<link rel="shortcut icon" href="<?php if(isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'light') echo abs_php_include($x)."img/Icon0.ico";else echo abs_php_include($x)."img/Icon1.ico";?>"/>
	<link type="text/plain" rel="author" href="humans.txt"/>
</head>
<body>
<script>const MYAPP = {};

MYAPP.html = document.querySelector('html');
MYAPP.theme = getCookie("theme");

if(MYAPP.theme === ""){
	setCookie("theme", "light", 30);
	MYAPP.theme = "light";
}

MYAPP.html.dataset.theme = "theme-" + MYAPP.theme;

function setCookie(cname, cvalue, exdays){
	let d = new Date();
	d.setTime(d.getTime() + exdays*24*60*60*1000);
	let expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname){
	const name = cname + "=";
	let decodedCookie = decodeURIComponent(document.cookie);
	let arr = decodedCookie.split(';');

	for(let i = 0;i < arr.length; i++){
		let ele = arr[i];

		while(ele.charAt(0) === ' '){
			ele = ele.substring(1);
		}
		if(ele.indexOf(name) === 0){
			return ele.substring(name.length, ele.length);
		}
	}
	return "";
}</script>
<div id="line"></div>
<div id="vline"></div>
<div id="background-text"><code><?php include_once(abs_php_include($x)."bgtext.php");?></code></div>
<div id="wrapper" <?php if(is_mobile()) echo 'style="width:97%;"';else echo 'style="width:940px;"';?>>
	<div id="header">
		<img src="<?php if(isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'light') echo abs_php_include($x)."img/logo16.png";else echo abs_php_include($x)."img/logo16inv.png"; ?>" alt="userx" id="checkerbox">
		<nav>
		<form method="get" action="index.php?">
			<ul>
				<?php include_once(abs_php_include($x)."user.php");?>
				<li class="hnav"><label for="search"><input type="search" name="search" id="searcht" <?php if(is_mobile()) echo 'style="width:60px;"';else echo 'style="width:120px;"';?> value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>"/></label></li>
				<li class="hnav"><label for="submit"><input type="submit" name="submit" id="searchb" value="x"/></label></li>
			</ul>
		</form>
		</nav>
	</div>
	<?php if(($_SERVER['QUERY_STRING'] !== 'page=login' && basename($_SERVER['PHP_SELF'], ".php") !== "login")){
		echo '<div id="login_popout">';
		include_once(abs_php_include($x)."login.php");
		echo '</div>';
	}?>
	<div id="heart" <?php if(is_mobile()) echo 'style="width:98%;"';else echo 'style="width:800px;"';?>>