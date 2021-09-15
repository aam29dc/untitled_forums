<?php
$file = 'img/xdraw.c';

if(!is_dir('php')){
    $file = '../img/xdraw.c';
}

echo file_get_contents($file);
?>