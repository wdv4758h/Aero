<?php

require_once('../include/base.php');

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

// Render...
render('flight_list.html', compact('flights', '_SESSION'));

?>
