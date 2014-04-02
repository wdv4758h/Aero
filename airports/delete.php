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
$airport = new Airport();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(!isset($_POST['id'])) {
        echo "Can not be empty";
    }
    
    $airport -> delete($_POST['id']);

    header('location: /airports/');

} else if($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    if(!isset($_GET['id'])) {
        header('location: /airports/');    
    }
    
    $airport -> get($_GET['id']);
    
    render('airports_delete.html', array('airports'=>$airport));

} else {

    header('location: /airports/');

}

?>
