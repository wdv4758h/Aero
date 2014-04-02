<?php

require_once '../include/base.php';

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}
if(!$_SESSION['is_admin']) {
    header('location: /');
    exit();
}

if($_POST['name'] && $_POST['longitude'] && $_POST['latitude']) {

    require_once('../include/aero.php');
    $a = new Airport();
    $value = array(
	       ':name' => $_POST['name'],
	  ':longitude' => $_POST['longitude'],
	   ':latitude' => $_POST['latitude'],
    );
    $a -> add($value);

    header('location: /airports/');

} else {
    render('airports_add.html', array());
}

?>
