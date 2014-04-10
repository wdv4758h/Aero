<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(trim($_POST['username'])==='' || trim($_POST['password'])==='' || trim($_POST['password2'])==='') {
        echo "Can not be empty";
    }
    
    require_once('../include/aero.php');

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);
    $is_admin = $_POST['is_admin']?1:0;
    
    $aero = new Aero();
    $aero -> sql = "SELECT username FROM `users` WHERE username = ?";
    $aero -> execute(array($_POST['username']));
    $result = $aero -> query -> fetchObject();
    if ($result) {
        echo 'Repeated';
        exit();
    }

    if ($password != $password2) {
        echo 'Different Password';
        exit();
    }

    $password = sha1('mightySalt'.$password); 
    
    $u = new User();
    $value = array(
	   ':username' => $username,
	   ':password' => $password,
	   ':is_admin' => $is_admin,
    );
    $u -> add($value);

    header('location: /users/');

} else {
    render('users_add.html', array());
}

?>
