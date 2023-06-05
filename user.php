<?php
$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);

require_once('php/_lib.php');

if(!isset($_SESSION['loggedin'])){
    if(isset($q['page']) && $q['page'] === 'signup')
        echo '<li class="hnav">Signup</li>'."\n";
    else echo '<li class="hnav"><a href="'.abs_php_include($x).'index.php?page=signup">Signup</a></li>'."\n";

    if(isset($q['page']) && $q['page'] === 'login')
        echo '<li class="hnav">Login</li>'. "\n";
    else echo '<li class="hnav"><a id="user_login" href="'.abs_php_include($x).'index.php?page=login">Login</a></li>'. "\n";
}
else {
    //get count of non-read pms
    /*
    require_once('php/_conn.php');
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pms WHERE toid = :toid AND seen = false;");
    $stmt->bindValue(":toid", $_SESSION['userid']);
    $stmt->execute();
    $unseen = $stmt->fetchColumn();
    */

    if(isset($q['page']) && $q['page'] === 'inbox') echo '<li class="hnav">Inbox';
    else echo '<li class="hnav"><a href="'.abs_php_include($x).'index.php?page=inbox">Inbox';
    //if(isset($unseen) && $unseen > 0) echo '('.$unseen.')';

    if(isset($q['page']) && $q['page'] !== 'inbox') echo '</a>';
    echo '</li>';

    if(isset($q['page']) && $q['page'] === 'profile') echo '<li class="hnav">'.substr($_SESSION['username'], 0, 10).'</li>';
    else echo '<li class="hnav"><a href="'.abs_php_include($x).'index.php?page=profile">'.substr($_SESSION['username'], 0, 10).'</a></li>';
    
    echo '<li class="hnav"><a id="logout" href="'.abs_php_include($x).'logout.php">Logout</a></li>'. "\n";
}
$stmt = null;
$pdo = null;
?>