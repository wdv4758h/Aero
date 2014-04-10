<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

require_once('../include/db.php');

if($_POST['id'] && $_POST['code'] && $_POST['departure'] && $_POST['arrival'] && $_POST['departure_date'] && $_POST['arrival_date']) {

    $id = $_POST['id'];
    $code = $_POST['code'];
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $departure_date = $_POST['departure_date'];
    $arrival_date = $_POST['arrival_date'];
    $fare = $_POST['fare'];

    $aero = new Aero();
    $aero -> sql = 'UPDATE `flights` SET `code`=:code, `departure`=(SELECT id FROM airports WHERE name=:departure), `arrival`=(SELECT id FROM airports WHERE name=:arrival), `departure_date`=:depart_date, `arrival_date`=:arrive_date, `fare`=:fare WHERE `id`=:id';
    $aero -> execute(array(
	         ':id' => $id,
	       ':code' => $code,
	  ':departure' => $departure,
	    ':arrival' => $arrival,
	':depart_date' => $departure_date,
	':arrive_date' => $arrival_date,
	       ':fare' => $fare
    ));

    header('location: /flights/');


} else if($_GET['id']) {

    $aero = new Aero();
    $aero -> sql = 'SELECT f.id, f.code, a.name AS departure, b.name AS arrival, f.departure_date, f.arrival_date, f.fare FROM flights f INNER JOIN airports a ON (f.departure=a.id) INNER JOIN airports b ON (f.arrival=b.id) WHERE f.id=?';
    $aero -> execute(array($_GET['id']));
    $flights = $aero -> query -> fetchAll();

    $flights[0]['departure_date'] = str_replace(' ', 'T', $flights[0]['departure_date']);
    $flights[0]['arrival_date'] = str_replace(' ', 'T', $flights[0]['arrival_date']);

    render('flights_edit.html', compact('flights'));

} else {

    //header('location: new_flight');
    echo "[TODO: Need mockup!] Can not be empty";

}

?>
