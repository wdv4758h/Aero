<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

if($_POST['code'] && $_POST['departure'] && $_POST['arrival']) {

    $code = $_POST['code'];
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $departure_date = $_POST['departure_date'];
    $arrival_date = $_POST['arrival_date'];
    $fare = $_POST['fare'];

    require_once('../include/db.php');
    $aero = new Aero();
    $aero -> sql = 'INSERT INTO `flights` (`id`, `code`, `departure`, `arrival`, `departure_date`, `arrival_date`, `fare`) VALUES (NULL, :code, (SELECT id FROM airports WHERE name=:departure), (SELECT id FROM airports WHERE name=:arrival), :depart_date, :arrive_date, :fare)';
    $aero -> execute(array(
	       ':code' => $code,
	  ':departure' => $departure,
	    ':arrival' => $arrival,
	':depart_date' => $departure_date,
	':arrive_date' => $arrival_date,
	       ':fare' => $fare
    ));

    header('location: /flights/');


} else {
    render('flights_add.html', array());
}

?>
