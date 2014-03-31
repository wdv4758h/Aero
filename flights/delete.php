<?php

require_once '../include/base.php';

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}
if(!$_SESSION['is_admin']) {
    header('location: /flights/');
    exit();
}

if($_POST['id'] && $_POST['code']) {

    require_once('../include/db.php');

    $aero = new Aero();
    $aero -> sql = 'DELETE FROM `flights` WHERE `id`=?';
    $aero -> execute(array($_POST['id']));

    header('location: /flights/');


} else if($_GET['id']) {

    require_once('../include/db.php');

    $aero = new Aero();
    $aero -> sql = 'SELECT f.id, f.code, a.name AS departure, b.name AS arrival, f.departure_date, f.arrival_date, f.fare FROM flights f INNER JOIN airports a ON (f.departure=a.id) INNER JOIN airports b ON (f.arrival=b.id) WHERE f.id = ?';
    $aero -> execute(array($_GET['id']));
    $flights = $aero -> query -> fetchAll();

    render('flights_delete.html', compact('flights'));

} else {

    header('location: /delete_flight/');

}

?>
