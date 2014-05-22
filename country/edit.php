<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

require_once('../include/aero.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(!isset($_POST['id']) || trim($_POST['full_name'])=='' || trim($_POST['short_name'])=='' || trim($_POST['timezone'])=='') {
        echo "Can not be empty";
        exit();
    }

    $a = new Country();
    $value = array(
        ':id'           => $_POST['id'],
        ':full_name'    => $_POST['full_name'],
        ':short_name'   => $_POST['short_name'],
        ':timezone'     => $_POST['timezone'],
    );
    $a -> update($value);

    header('location: /country/');

} else if($_SERVER['REQUEST_METHOD'] === 'GET') {

    if(!isset($_GET['id'])) {
        header('location: /country/');
    }

    $a = new Country();
    $a -> get($_GET['id']);

    render('country_edit.html', array('country'=>$a));

} else {
    header('location: /country/');
}

?>
