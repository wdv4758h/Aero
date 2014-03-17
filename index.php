<?php
session_start();

if(!isset($_SESSION['id']))
    header('location: login');
else
    header('location: main');


/*
if (array_key_exists('view', $_GET))
    include_once('view/'.$_GET['view'].'.php');
*/

?>
