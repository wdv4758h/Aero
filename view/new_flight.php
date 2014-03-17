<?php

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


require_once 'twig.php';
$template = $twig->loadTemplate('new_flight.html');
echo $template->render(compact('datetime'));

?>