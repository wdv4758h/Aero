<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(
            trim($_POST['code']) == '' ||
            trim($_POST['departure']) == '' ||
            trim($_POST['arrival']) == '' ||
            trim($_POST['departure_date']) == '' ||
            trim($_POST['arrival_date']) == '' ||
            trim($_POST['fare']) == ''
      ) {
        echo "Can't be empty";
        exit();
    }

    require_once('../include/aero.php');
    $f = new Flight();
    $value = array(
	       ':code' => $_POST['code'],
	  ':departure' => $_POST['departure'],
	    ':arrival' => $_POST['arrival'],
	':departure_date' => $_POST['departure_date'],
	':arrival_date' => $_POST['arrival_date'],
	       ':fare' => $_POST['fare']
    );
    $f -> add($value);

    header('location: /flights/');


} else {
    render('flights_add.html', array());
}

?>
