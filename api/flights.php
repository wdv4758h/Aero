<?php
require_once('../include/aero.php');
require_once('../include/base.php');

//// Redirect to Login Page for unauthorized connection...
//if(!isset($_SESSION['id'])) {
//    header('location: /login');
//    exit();
//}

$aero = new Flight();
$data = $aero -> get();

echo json_encode($data, JSON_NUMERIC_CHECK);
?>
