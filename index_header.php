<?php require_once('php/lib.php');?><!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="Cache-control" content="public"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php include_once(abs_php_include($x).'php/title.php'); ?></title>
	<meta name="description" content="userx's website"/>
	<meta name="keywords" content="programming, philosophy, math"/>
	<meta http-equiv="author" content="aam29dc"/>
	<link href="<?php abs_include($x);?>styles/styles.css" type="text/css" rel="stylesheet"/>
	<link rel="shortcut icon" href="<?php if($_COOKIE['theme'] == 'light') echo abs_include($x)."img/Icon0.ico";else echo abs_include($x)."img/Icon1.ico";?>"/>
</head>
<body>
<div class="background-text"><code><?php include_once(abs_php_include($x)."php/bgtext.php");?></code></div>
<div class="wrapper" <?php if(is_mobile()) echo 'style="width:97%;"';else echo 'style="width:940px;"';?>>
	<div class="header">
		<img src="<?php if($_COOKIE['theme'] == 'light') echo abs_include($x)."img/logo16.png";else echo abs_include($x)."img/logo16inv.png"; ?>" alt="userx" id="checkerbox" onClick="swapsrc('checkerbox','img/logo16.png','img/logo16inv.png');swaptheme();">
		<nav>
		<form method="get" action="index.php?">
			<ul>
				<?php include_once(abs_php_include($x)."php/user.php");?>
				<li class="hnav"><label for="search"><input type="search" id="search" name="search" class="searcht" <?php if(is_mobile()) echo 'style="width:60px;"';else echo 'style="width:120px;"';?> value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>"/></label></li>
				<li class="hnav"><label for="submit"><input type="submit" id="submit" name="submit" class="searchb" value="x"/></label></li>
			</ul>
		</form>
		</nav>
	</div>
	<div class="heart" <?php if(is_mobile()) echo 'style="width:98%;"';else echo 'style="width:800px;"';?>>