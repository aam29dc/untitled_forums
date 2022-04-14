<?php
$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);

if($q['page'] == 'home' || (basename($_SERVER['PHP_SELF'], ".php") == 'index' && empty($_SERVER['QUERY_STRING']))){
    echo '<li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px">home</li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=polls">polls</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=submit">submit</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=about">about</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=sub">sub</a></li>';
}
else if($q['page'] == 'polls'){
    echo '<li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=home">home</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px">polls</li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=submit">submit</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=about">about</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=sub">sub</a></li>';
}
else if($q['page'] == 'submit'){
    echo '<li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=home">home</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=polls">polls</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px">submit</li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=about">about</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=sub">sub</a></li>';
}
else if($q['page'] == 'about'){
    echo '<li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=home">home</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=polls">polls</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=submit">submit</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px">about</li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=sub">sub</a></li>';
}
else if($q['page'] == 'sub' || $q['page'] == 'unsub'){
    echo '<li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=home">home</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=polls">polls</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=submit">submit</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'?page=about">about</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px">sub</li>';
}
else {
    if(!isset($x)) require_once('lib.php');
    echo '<li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'index.php?page=home">home</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'index.php?page=polls">polls</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'index.php?page=submit">submit</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'index.php?page=about">about</a></li>
            <li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="'.abs_php_include($x).'index.php?page=sub">sub</a></li>';
}
echo "\n".'<li class="vnav"><img src="';abs_include($x); echo 'img/vlist3.png" alt="." height="3px" width="3px"><a href="http://github.com/aam29dc">github</a></li>'."\n";
?>