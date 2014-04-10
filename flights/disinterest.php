<?php

require_once '../include/base.php';
checkCredential();

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
