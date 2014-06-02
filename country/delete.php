<?php
require_once '../include/base.php';
checkCredential(Credential::isAdmin);

require_once('../include/aero.php');
$country = new Country();

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(!isset($_POST['abbr'])) {
        echo "Can not be empty";
        exit();
    }

    $country -> delete($_POST['abbr'], 'abbr');

    header('location: /country/');

} else if($_SERVER['REQUEST_METHOD'] === 'GET') {

    if(!isset($_GET['id'])) {
        header('location: /country/');
    }

    $country -> get($_GET['id']);

    render('country_delete.html', array('country'=>$country));

} else {

    header('location: /country/');

}

?>
