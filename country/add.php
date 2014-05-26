<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(!isset($_POST['name']) || !isset($_POST['abbr'])) {
        echo "Can not be empty";
        exit();
    }

    require_once('../include/aero.php');
    $a = new Country();
    $value = array(
	    ':name'   => $_POST['name'],
	    ':abbr'   => $_POST['abbr'],
    );
    $a -> add($value);

    header('location: /country/');

} else {
    render('country_add.html', array());
}

?>
