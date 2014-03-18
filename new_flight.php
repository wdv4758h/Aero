<?php

require_once 'include/base.php';

// Redirect to Login Page for unauthorized connection...
if(!isset($_SESSION['id'])) {
    header('location: login');
    exit();
}
if(!$_SESSION['is_admin']) {
    header('location: main');
    exit();
}

if($_POST['code'] && $_POST['departure'] && $_POST['arrival']) {

    $code = $_POST['code'];
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $departure_date = $_POST['depart_year'].'-'.$_POST['depart_month'].'-'.$_POST['depart_date'].' '.$_POST['depart_hour'].':'.$_POST['depart_minute'].':00';
    $arrival_date = $_POST['arrive_year'].'-'.$_POST['arrive_month'].'-'.$_POST['arrive_date'].' '.$_POST['arrive_hour'].':'.$_POST['arrive_minute'].':00';

    require_once('include/db.php');
    $aero = new Aero();
    $aero -> sql = 'INSERT INTO `flights` (`id`, `code`, `departure`, `arrival`, `departure_date`, `arrival_date`) VALUES (NULL, ?, ?, ?, ?, ?)';
    $aero -> execute(array($code,$departure, $arrival, $departure_date, $arrival_date));

    header('location: main');


} else {

    $prepend = array('01','02','03','04','05','06','07','08','09');
    $prepend_00 = array_merge(array('00'), $prepend);

    $datetime = array(
        'years' => range(2014,2015),
        'months' => array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ),
        'dates' => array_merge($prepend, range(10, 31)),
        'hours' => array_merge($prepend_00, range(10, 23)),
        'minutes' => array_merge($prepend_00, range(10, 59)),
    );

    render('new_flight.html', compact('datetime'));
}

?>
