<?php

require_once '../include/base.php';
checkCredential(Credential::isAdmin);

require_once('../include/aero.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(trim($_POST['name'])=='' || trim($_POST['abbr'])=='' || trim($_POST['old_abbr'])=='') {
        echo "Can not be empty";
        exit();
    }

    $a = new Country();
    $value = array(
        ':name'     => $_POST['name'],
        ':abbr'     => $_POST['abbr'],
        ':old_abbr' => $_POST['old_abbr'],
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
