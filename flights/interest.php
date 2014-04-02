<?php

require_once '../include/base.php';

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_GET['id'])) {
        echo 'Can not be empty';
        exit();
    }

    require_once('../include/aero.php');
    $p = new Plan();

    $value = array(
        ':users_id' => $_SESSION['id'],
	    ':flights_id' => $_POST['id'],
    );
    $p -> add($value);

    header('location: /flights/');

} else {
    render('flights_add.html', array());
}

?>
