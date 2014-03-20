<?php

require_once 'include/base.php';

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: login');
    exit();
}
if(!$_SESSION['is_admin']) {
    header('location: main');
    exit();
}

require_once('include/db.php');

if($_POST['id'] && $_POST['code'] && $_POST['departure'] && $_POST['arrival'] && $_POST['departure_date'] && $_POST['arrival_date']) {

    $id = $_POST['id'];
    $code = $_POST['code'];
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $departure_date = $_POST['departure_date'];
    $arrival_date = $_POST['arrival_date'];

    $aero = new Aero();
    $aero -> sql = 'UPDATE `flights` SET `code`=?, `departure`=?, `arrival`=?, `departure_date`=?, `arrival_date`=? WHERE `id`=?';
    $aero -> execute(array($code,$departure, $arrival, $departure_date, $arrival_date, $id));

    header('location: main');


} else if($_GET['id']) {

    $aero = new Aero();
    $aero -> sql = 'SELECT * FROM flights WHERE id=?';
    $aero -> execute(array($_GET['id']));
    $flights = $aero -> query -> fetchAll();

    $flights[0]['departure_date'] = str_replace(' ', 'T', $flights[0]['departure_date']);
    $flights[0]['arrival_date'] = str_replace(' ', 'T', $flights[0]['arrival_date']);

    render('edit_flight.html', compact('flights'));

} else {

    //header('location: new_flight');
    echo "Can not be empty";

}

?>
