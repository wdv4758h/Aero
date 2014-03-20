<?php
require_once('../include/db.php');

$aero = new Aero();
$aero -> sql = 'SELECT * FROM flights';
$aero -> execute();

echo json_encode($aero -> query -> fetchAll());
?>
