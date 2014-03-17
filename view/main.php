<?php
session_start();

if(!isset($_SESSION['id']) || trim($_SESSION['username'] == '')) {
    header('location: login.php');
    exit();
}


$flights = array(
    array(
        'code' => 'BR103',
        'from' => 'TPA',
        'to' => 'NRT',
        'depart' => '2014-03-10 13:53:00 UTC',
        'arrive' => '2014-03-10 13:53:00 UTC',
        'company' => 'EVA air',
    ),
    array(
        'code' => 'BR104',
        'from' => 'TPA',
        'to' => 'MOC',
        'depart' => '2014-03-10 13:53:00 UTC',
        'arrive' => '2014-03-11 01:00:00 UTC',
        'company' => 'EVA air',
    ),
);


require_once 'twig.php';
$template = $twig->loadTemplate('main.html');
echo $template->render();

?>