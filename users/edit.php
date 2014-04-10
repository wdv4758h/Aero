<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

require_once('../include/aero.php');
$u = new User();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(!isset($_POST['id'])) {
        echo "Oooops! It doesn't seems right";
        exit();
    }
    
    $username = trim($_POST['username']);
    $is_admin = $_POST['is_admin']?1:0;

    $u -> get($_POST['id']);
    if ($u -> is_admin) {
        echo "403 You can't modify admin accounts.";
        exit();
    }

    $value = array(
	         ':id' => $_POST['id'],
	   ':is_admin' => $is_admin,
    );
    $u -> update($value);

    header('location: /users/');

} else if($_SERVER['REQUEST_METHOD'] === 'GET') {

    if(!isset($_GET['id'])) {
        header('location: /users/');    
    }
    
    $u -> get($_GET['id']);
    if ($u -> is_admin) {
        echo "403 You can't modify admin accounts.";
        exit();
    }
    
    render('users_edit.html', array('user'=>$u));

} else {
    header('location: /users/');
}

?>
