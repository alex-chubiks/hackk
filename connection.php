<?php

$host = 'localhost';
$user = 'root';

$link = mysqli_connect($host, $user);

mysqli_select_db($link, 'test_project');

if(!$link){
    die('Error connect to DataBase');
}

?>