<?php
session_start();
require_once 'include/base.php';

if(!isset($_SESSION['id']))
    render('homepage.html', array());
else
    header('location: /flights/');
?>
