<?php
session_start();
require_once 'include/base.php';

if ( isset($_SESSION['id']))
    header('location: /tickets/');
else
    render('homepage.html', compact('_SESSION'));
?>
