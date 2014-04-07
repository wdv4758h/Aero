<?php
require_once('../include/aero.php');
require_once('../include/base.php');

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

if(!$_SESSION['is_admin']) {
    header('location: /flights/');
    exit();
}

$u = new User();
$users = $u -> get();

echo json_encode($users, JSON_NUMERIC_CHECK);

?>
