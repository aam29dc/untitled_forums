<?php
require_once('php/_lib.php');

if(isset($_SESSION['priviledge']) && $_SESSION['priviledge'] >= 2){
	echo '<nav>
		<ul>
			<li class="vnavh"><h1>Control Panel:</h1></li>
			<li class="vnav"><a href="'.abs_php_include($x).'index.php?page=cp_users">users</a></li>
			<li class="vnav"><a href="'.abs_php_include($x).'index.php?page=subs">subs</a></li>
		</ul>
	</nav>'."\n";
}
?>