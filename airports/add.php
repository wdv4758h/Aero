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

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(trim($_POST['name'])==='' || !isset($_POST['longitude']) || !isset($_POST['latitude'])) {
        echo "Can not be empty";
    }
    
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
