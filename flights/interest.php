<?php

require_once '../include/base.php';
checkCredential();

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(!isset($_GET['id'])) {
        echo 'Can not be empty';
        exit();
    }

    require_once('../include/aero.php');
    $p = new Plan();

    $value = array(
        ':user_id' => $_SESSION['id'],
	    ':flight_id' => $_GET['id'],
    );
    
    $p -> add($value);

    header('location: /flights/');

} else {
    render('flights_add.html', array());
}

?>
