<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(trim($_POST['name'])==='' || !isset($_POST['longitude']) || !isset($_POST['latitude']) || trim($_POST['timezone'])==='') {
        echo "Can not be empty";
    }

    require_once('../include/aero.php');
    $a = new Airport();
    $value = array(
	       ':name' => $_POST['name'],
	  ':longitude' => $_POST['longitude'],
	   ':latitude' => $_POST['latitude'],
	   ':timezone' => $_POST['timezone'],
    );
    $a -> add($value);

    header('location: /airports/');

} else {
    render('airports_add.html', array());
}

?>
