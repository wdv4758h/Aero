<?php
require_once('../include/aero.php');
require_once('../include/base.php');

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

// Initial and Get airports...
$a = new Country();
$countrys = $a -> get();

echo json_encode($countrys, JSON_NUMERIC_CHECK);

?>
