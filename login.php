<?php
require_once 'include/base.php';

// If is not POST connection... Render Login Page
if(!isset($_POST['username']) || trim($_POST['password'])=='') {
    render('login.html', array(array('error'->'error')));
    exit();
}

// Processing POST connection...
$username = $_POST['username'];
$password = $_POST['password'];

require_once('include/db.php');

$aero = new Aero();
$aero -> sql = 'SELECT * FROM users WHERE username = ?';
$aero -> execute(array($username));
$result = $aero -> query -> fetchObject();

// Check
if (!$result) {
    render('login.html', array(array('error'->'error')));
    exit();
}

// Create password hash...
$password = sha1('mightySalt'.$password);


if ($password != $result->password) {
    session_destroy();

    // Render Login Page
    render('login.html', array(array('error'->'error')));

} else {

    // Authorized User... Redirecting...
    session_regenerate_id();
    $_SESSION['id'] = $result->id;
    $_SESSION['username'] = $result->username;
    $_SESSION['is_admin'] = $result->is_admin;
    session_write_close();
    header('location: main');
}

?>
