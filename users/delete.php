<?php
require_once '../include/base.php';
checkCredential(Credential::isAdmin);

require_once('../include/aero.php');
$user = new User();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(!isset($_POST['id'])) {
        echo "Can not be empty";
    }
    
    $user -> delete($_POST['id']);

    header('location: /users/');

} else if($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    if(!isset($_GET['id'])) {
        header('location: /users/');    
    }
    
    $user -> get($_GET['id']);
    
    render('users_delete.html', array('user'=>$user));

} else {

    header('location: /users/');

}

?>
