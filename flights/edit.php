<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

require_once('../include/aero.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(
            trim($_POST['id']) == '' ||
            trim($_POST['code']) == '' ||
            trim($_POST['departure']) == '' ||
            trim($_POST['arrival']) == '' ||
            trim($_POST['departure_date']) == '' ||
            trim($_POST['arrival_date']) == ''
     ) {
        echo "Can't be empty";
        exit();
    }

    $f = new Flight();
    $value = array(
	         ':id' => $_POST['id'],
	       ':code' => $_POST['code'],
	  ':departure' => $_POST['departure'],
	    ':arrival' => $_POST['arrival'],
	':departure_date' => $_POST['departure_date'],
	':arrival_date' => $_POST['arrival_date'],
	       ':fare' => $_POST['fare']
    );
    $f -> update($value);

    header('location: /flights/');

} else { // HTTP GET REQUEST

    if (!isset($_GET['id'])) {
        header('location: /flights/');
    }

    $f = new Flight();
    $f -> get($_GET['id']);

    $f -> departure_date = str_replace(' ', 'T', $f -> departure_date);
    $f -> arrival_date = str_replace(' ', 'T', $f -> arrival_date);

    render('flights_edit.html', array('flight'=>$f));

}

?>
