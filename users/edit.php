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
$u = new User();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(!isset($_POST['id']) || trim($_POST['username'])=='' || trim($_POST['password'])=='' || trim($_POST['password2'])=='') {
        echo "Can not be empty";
        exit();
    }
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);
    $is_admin = $_POST['is_admin']?1:0;

    $u -> get($_POST['id']);
    if ($username != $u -> username) {
        echo "Ooops! It doesn't seems right";
        exit();
    }

    if ($password != $password2) {
        echo 'Different password';
        exit();
    }

    $password = sha1('mightySalt'.$password); 
    
    $value = array(
	         ':id' => $_POST['id'],
	   ':username' => $username,
	   ':password' => $password,
	   ':is_admin' => $is_admin,
    );
    $u -> update($value);

    header('location: /users/');

} else if($_SERVER['REQUEST_METHOD'] === 'GET') {

    if(!isset($_GET['id'])) {
        header('location: /users/');    
    }
    
    $u -> get($_GET['id']);

    render('users_edit.html', array('user'=>$u));

} else {
    header('location: /users/');
}

?>
