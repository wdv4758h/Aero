<?php
require_once('../include/aero.php');
require_once('../include/base.php');

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

// Initial and Get airports...
$u = new User();
$users = $u -> get();

// Render...
render('users_list.html', compact('users', '_SESSION'));

?>
