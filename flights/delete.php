<?php

require_once '../include/base.php';
require_once('../include/aero.php');
checkCredential(Credential::isAdmin);

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(!isset($_POST['id'])) {
        echo "Can not be empty";
        exit();
    }

    $flight = new Flight();
    $flight -> delete($_POST['id']);

    header('location: /flights/');


} else if($_SERVER['REQUEST_METHOD'] === 'GET') {

    if(!isset($_GET['id'])) {
        header('location: /flights/');
    }

    $flight = new Flight();
    $flight -> get($_GET['id']);

    render('flights_delete.html', array('flight' => $flight));

} else {

    header('location: /flight/');

}

?>
