<?php
session_start();

if(!isset($_SESSION['id']))
    header('location: /login');
else
    header('location: /flights/');
?>
