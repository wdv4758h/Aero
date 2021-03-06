<?php
require_once 'include/base.php';

if($_POST['username'] && $_POST['password'] && $_POST['password2']) {

    require_once('include/db.php');

    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $is_admin = $_POST['is_admin']?1:0;

    $aero = new Aero();
    $aero -> sql = "SELECT username FROM `users` WHERE username = ?";
    $aero -> execute(array($username));
    $result = $aero -> query -> fetchObject();
    if ($result) {
        $status = 'repeated';
        render('signup.html', compact('status'));
        exit();
    }

    if ($password != $password2) {
        $status = 'different';
        render('signup.html', compact('status', 'username'));
        exit();
    }

    $password = sha1('mightySalt'.$password);

    $aero = new Aero();
    $aero -> sql = 'INSERT INTO `users` (`id`, `username`, `password`, `is_admin`) VALUES (NULL, ?, ?, ?)';
    $aero -> execute(array($username,$password, $is_admin));

    header('location: /');


} else {

    render('signup.html', array());

}
?>
