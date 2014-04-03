<?php
require_once('../include/aero.php');
require_once('../include/base.php');

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

if(!$_SESSION['is_admin']) {
    header('location: /flights/');
    exit();
}

// Render...
render('users_list.html', compact('_SESSION'));

?>
