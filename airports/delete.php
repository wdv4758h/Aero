<?php
require_once '../include/base.php';
checkCredential(Credential::isAdmin);

require_once('../include/aero.php');
$airport = new Airport();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(!isset($_POST['id'])) {
        echo "Can not be empty";
        exit();
    }
    
    $airport -> delete($_POST['iata'], 'iata');

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
