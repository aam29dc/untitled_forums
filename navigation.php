<?php
require_once('php/_lib.php');
$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);
if(!isset($q['page'])) $q['page'] = '404';

if($q['page'] === 'home' || (basename($_SERVER['PHP_SELF'], ".php") === 'index' && !isset($_SERVER['QUERY_STRING']))){
    echo '<li class="vnav"><img src="'.abs_php_include($x).'img/vlist3.png" alt="." height="3px" width="3px">home</li>';
} else {echo '<li class="vnav"><img src="'.abs_php_include($x).'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=home">home</a></li>';}

if($q['page'] === 'polls'){
    echo '<li class="vnav"><img src="'.abs_php_include($x).'img/vlist3.png" alt="." height="3px" width="3px">polls</li>';
} else {echo '<li class="vnav"><img src="'.abs_php_include($x).'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=polls">polls</a></li>';}

if($q['page'] === 'submit'){
    echo '<li class="vnav"><img src="'.abs_php_include($x).'img/vlist3.png" alt="." height="3px" width="3px">submit</li>';
} else {echo '<li class="vnav"><img src="'.abs_php_include($x).'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=submit">submit</a></li>';}

if($q['page'] === 'about'){
    echo '<li class="vnav"><img src="'.abs_php_include($x).'img/vlist3.png" alt="." height="3px" width="3px">about</li>';
} else {echo '<li class="vnav"><img src="'.abs_php_include($x).'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=about">about</a></li>';}

if($q['page'] === 'sub' || $q['page'] === 'unsub'){
    echo '<li class="vnav"><img src="'.abs_php_include($x).'img/vlist3.png" alt="." height="3px" width="3px">sub</li>';
} else {echo '<li class="vnav"><img src="'.abs_php_include($x).'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'index.php?page=sub">sub</a></li>';}

echo "\n".'<li class="vnav"><img src="'.abs_php_include($x).'img/vlist3.png" alt="." height="3px" width="3px"><a href="http://github.com/aam29dc">github</a></li>'."\n";
?>