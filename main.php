<?php

// Redirect to Login Page for unauthorized connection...
session_start();
if(!isset($_SESSION['id'])) {
    header('location: login');
    exit();
}

// Fetch from DB...
require_once('include/db.php');
try {
    $db = new PDO($dsn, $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM flights";
    $query = $db -> prepare($sql);
    $query -> execute();
    $flights = $query->fetchAll();
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
    exit();
}


// Render...
require_once 'twig.php';
$template = $twig->loadTemplate('main.html');
echo $template->render(compact('flights', '_SESSION'));

?>
