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
//$aero -> sql = 'SELECT * FROM flights';
$aero -> sql = 'SELECT f.id, f.code, a.name AS departure, b.name AS arrival, f.departure_date, f.arrival_date FROM flights f INNER JOIN airports a ON (f.departure=a.id) INNER JOIN airports b ON (f.arrival=b.id)';
$aero -> execute();
$flights = $aero -> query -> fetchAll();

// Render...
render('main.html', compact('flights', '_SESSION'));

?>
