<?php

require_once('include/base.php');

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: login');
    exit();
}

// Fetch from DB...
require_once('include/db.php');

$aero = new Aero();
$aero -> sql = 'SELECT * FROM flights';
$aero -> execute();
$flights = $aero -> query -> fetchAll();

// Render...
render('main.html', compact('flights', '_SESSION'));

?>
