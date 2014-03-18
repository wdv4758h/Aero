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

if($_POST['id'] && $_POST['code']) {

    $id = $_POST['id'];
    $code = $_POST['code'];
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $departure_date = $_POST['depart_year'].'-'.$_POST['depart_month'].'-'.$_POST['depart_date'].' '.$_POST['depart_hour'].':'.$_POST['depart_minute'].':00';
    $arrival_date = $_POST['arrive_year'].'-'.$_POST['arrive_month'].'-'.$_POST['arrive_date'].' '.$_POST['arrive_hour'].':'.$_POST['arrive_minute'].':00';

    require_once('include/db.php');

    $aero = new Aero();
    $aero -> sql = 'DELETE FROM `flights` WHERE `id`=?';
    $aero -> execute(array($id));

    header('location: main');


} else if($_GET['id']) {

    require_once('include/db.php');

    $aero = new Aero();
    $aero -> sql = 'SELECT * FROM flights WHERE id=?';
    $aero -> execute(array($_GET['id']));
    $flights = $aero -> query -> fetchAll();

    render('delete_flight.html', compact('flights'));

} else {

    header('location: delete_flight');

}

?>
