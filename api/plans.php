<?php

// The JSON standard MIME header.
header('Content-type: application/json');

// Make into JSON file if downloaded.
header('Content-Disposition: attachment; filename="plans.json"');

// JSON Vulnerability Protection 
echo ")]}',\n";

require_once '../include/base.php';

if(!isset($_SESSION['id'])) {
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once '../include/aero.php';
    $p = new Plan();
    echo json_encode($p -> get($_SESSION['id']));
}

?>
