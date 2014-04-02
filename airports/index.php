<?php
require_once('../include/aero.php');
require_once('../include/base.php');

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

// Initial and Get airports...
$a = new Airport();
$airports = $a -> get();

// Render...
render('airports_list.html', compact('airports', '_SESSION'));

?>
