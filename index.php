<?php
session_start();

if(!isset($_SESSION['id']))
    header('location: login.php');
else
    header('location: main.php');


/*
if (array_key_exists('view', $_GET))
    include_once('view/'.$_GET['view'].'.php');
*/

?>