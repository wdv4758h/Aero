<?php
require_once('../include/db.php');

session_start();

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: ../login');
    exit();
}

$aero = new Aero();
$aero -> sql = 'SELECT * FROM flights';
$aero -> execute();

echo json_encode($aero -> query -> fetchAll());
?>
