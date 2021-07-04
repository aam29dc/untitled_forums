<?php
define("DB_HOST","localhost");
define("DB_NAME","userxdb");
define("DB_USER","userx");

try{
    $pdo = new PDO("mysql:host=" . constant("DB_HOST") . ";dbname=" . constant("DB_NAME"), constant("DB_USER"), "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(Exception $e){
    echo $e->getMessage();
}
?>