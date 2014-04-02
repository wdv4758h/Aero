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

require_once('../include/aero.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(!isset($_POST['id']) || trim($_POST['name'])=='' || $_POST['longitude']==0 || $_POST['latitude']==0) {
        echo "Can not be empty";
        exit();
    }
    
    $a = new Airport();
    $value = array(
	         ':id' => $_POST['id'],
	       ':name' => $_POST['name'],
	  ':longitude' => $_POST['longitude'],
	   ':latitude' => $_POST['latitude'],
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

}

?>
