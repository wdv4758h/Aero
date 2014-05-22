<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(!isset($_POST['full_name']) || !isset($_POST['short_name'])) {
        echo "Can not be empty";
    }

    require_once('../include/aero.php');
    $a = new Country();
    $value = array(
	    ':full_name'    => $_POST['full_name'],
	    ':short_name'   => $_POST['short_name'],
    );
    $a -> add($value);

    header('location: /country/');

} else {
    render('country_add.html', array());
}

?>
