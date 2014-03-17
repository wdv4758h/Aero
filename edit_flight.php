<?php

// Redirect to Login Page for unauthorized connection...
session_start();
if(!isset($_SESSION['id'])) {
    header('location: login');
    exit();
}
if(!$_SESSION['is_admin']) {
    header('location: main');
    exit();
}

if($_POST['id'] && $_POST['code'] && $_POST['departure'] && $_POST['arrival'] && $_POST['departure_date'] && $_POST['arrival_date']) {

    $id = $_POST['id'];
    $code = $_POST['code'];
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $departure_date = $_POST['departure_date'];
    $arrival_date = $_POST['arrival_date'];
    //$departure_date = $_POST['depart_year'].'-'.$_POST['depart_month'].'-'.$_POST['depart_date'].' '.$_POST['depart_hour'].':'.$_POST['depart_minute'].':00';
    //$arrival_date = $_POST['arrive_year'].'-'.$_POST['arrive_month'].'-'.$_POST['arrive_date'].' '.$_POST['arrive_hour'].':'.$_POST['arrive_minute'].':00';

    require_once('include/db.php');
    try {
        $db = new PDO($dsn, $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'UPDATE `flights` SET `code`=?, `departure`=?, `arrival`=?, `departure_date`=?, `arrival_date`=? WHERE `id`=?';
        $query = $db -> prepare($sql);
        $query -> execute(array($code,$departure, $arrival, $departure_date, $arrival_date, $id));
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
        exit();
    }
    header('location: main');


} else if($_GET['id']) {

    require_once('include/db.php');
    try {
        $db = new PDO($dsn, $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM flights WHERE id=?";
        $query = $db -> prepare($sql);
        $query -> execute(array($_GET['id']));
        $flights = $query->fetchAll();
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
        exit();
    }

    require_once 'twig.php';
    $template = $twig->loadTemplate('edit_flight.html');
    echo $template->render(compact('flights'));
} else {

    //header('location: new_flight');
    echo "Can not be empty";

}

?>
