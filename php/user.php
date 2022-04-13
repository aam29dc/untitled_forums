<?php session_start();

$q = array();
parse_str($_SERVER['QUERY_STRING'], $q);
$page = $q['page'];

if(!isset($x)) require_once('lib.php');

if(!isset($_SESSION['loggedin'])){
    if($_SERVER['QUERY_STRING'] == 'page=signup')
        echo '<li class="hnav">Signup</li>'."\n";
    else echo '<li class="hnav"><a href="'.abs_php_include($x).'index.php?page=signup">Signup</a></li>'."\n";

    if($_SERVER['QUERY_STRING'] == 'page=login')
        echo '<li class="hnav">Login</li>'. "\n";
    else echo '<li class="hnav"><a id="user_login" href="'.abs_php_include($x).'index.php?page=login">Login</a></li>'. "\n";
}
else {
    //get count of non-read pms
    if($pdo == null){   /* unfortuante way to fix pdo null error */
        @include_once('conn.php');
        @$pdo = new PDO("mysql:host=" . constant("DB_HOST") . ";dbname=" . constant("DB_NAME"), constant("DB_USER"), "");
        @$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pms WHERE toid = ? AND seen = false;");
    $stmt->bindValue(1, $_SESSION['userid']);
    $stmt->execute();
    $unseen = $stmt->fetchColumn();

    if($page == 'inbox') echo '<li class="hnav">Inbox';
    else echo '<li class="hnav"><a href="'.abs_php_include($x).'index.php?page=inbox">Inbox';
    if($unseen > 0) echo '('.$unseen.')';

    if($page != 'inbox') echo '</a>';
    echo '</li>';

    if($page == 'profile') echo '<li class="hnav">'.substr($_SESSION['username'], 0, 10).'</li>';
    else echo '<li class="hnav"><a href="'.abs_php_include($x).'index.php?page=profile">'.substr($_SESSION['username'], 0, 10).'</a></li>';
    
    echo '<li class="hnav"><a id="logout" href="'.abs_php_include($x).'logout.php">Logout</a></li>'. "\n";
}
?>