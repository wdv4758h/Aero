<?php
session_start();
require_once 'include/base.php';

if ( isset($_SESSION['id']) && $_SESSION['is_admin'])
    header('location: /tickets/');
else
    render('homepage.html', compact('_SESSION'));
?>
