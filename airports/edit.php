<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

require_once('../include/aero.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(trim($_POST['name'])==='' || !isset($_POST['longitude']) || !isset($_POST['latitude']) || trim($_POST['timezone'])==='' || !isset($_POST['country_id'])) {
        echo "Can not be empty";
        exit();
    }

    $a = new Airport();
    $value = array(
        ':id'           => $_POST['id'],
        ':name'         => $_POST['name'],
        ':longitude'    => $_POST['longitude'],
        ':latitude'     => $_POST['latitude'],
        ':timezone'     => $_POST['timezone'],
        ':country_id'   => $_POST['country_id'],
    );
    $a -> update($value);

    header('location: /airports/');

} else if($_SERVER['REQUEST_METHOD'] === 'GET') {

    if(!isset($_GET['id'])) {
        header('location: /airports/');
    }

    $a = new Airport();
    $a -> get($_GET['id']);

    render('airports_edit.html', array('airports'=>$a));

} else {
    header('location: /airports/');
}

?>
