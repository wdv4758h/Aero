<?php

require_once '../include/base.php';

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_POST['id'])) {
        echo 'Can not be empty';
        exit();
    }

    require_once('../include/aero.php');
    $p = new Plan();

    $value = array(
        ':users_id' => $_SESSION['id'],
	    ':flights_id' => $_POST['id'],
    );
    $p -> delete($value);
}

header('location: /flights/');

?>
