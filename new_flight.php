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

if($_POST['code'] && $_POST['departure'] && $_POST['arrival']) {

    $code = $_POST['code'];
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $departure_date = $_POST['departure_date'];
    $arrival_date = $_POST['arrival_date'];

    require_once('include/db.php');
    $aero = new Aero();
    $aero -> sql = 'INSERT INTO `flights` (`id`, `code`, `departure`, `arrival`, `departure_date`, `arrival_date`) VALUES (NULL, :code, (SELECT id FROM airports WHERE name=:departure), (SELECT id FROM airports WHERE name=:arrival), :depart_date, :arrive_date)';
    $aero -> execute(array(
	':code' => $code,
	':departure'	=> $departure,
	':arrival'	=> $arrival,
	':depart_date'	=> $departure_date,
	':arrive_date'	=> $arrival_date
    ));

    header('location: main');


} else {
    render('new_flight.html', array());
}

?>
