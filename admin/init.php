<?php
include 'connect.php';
//routes
$temp = 'include/templates/';
$func = 'include/functions/';
$css = 'layout/css/';
$js = 'layout/js/';
$lang = 'include/languages/';
//include important files
include $func . 'functions.php';
include $lang . 'en.php';
include $temp . '/header.php';
if (!isset($nonavbar)) {
    include $temp . 'navbar.php';
}
