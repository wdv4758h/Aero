<?php
require_once('../include/db.php');

session_start();

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$aero = new Aero();
$aero -> sql = 'SELECT f.id, f.code, a.name AS departure, b.name AS arrival, f.departure_date, f.arrival_date FROM flights f INNER JOIN airports a ON (f.departure=a.id) INNER JOIN airports b ON (f.arrival=b.id)';
$aero -> execute();

echo json_encode($aero -> query -> fetchAll());
?>
