<?php

require_once('../include/base.php');

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

// Render...
render('main.html', compact('flights', '_SESSION'));

?>
