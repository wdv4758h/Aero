<?php
require_once('../../include/aero.php');
require_once('../../include/base.php');

//// Redirect to Login Page for unauthorized connection...
//if(!isset($_SESSION['id'])) {
//    header('location: /login');
//    exit();
//}

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    // Initial and Get tickets...
    $a = new Ticket();
    $value = array(
        ':departure'     => $request -> departure,
        ':arrival'       => $request -> arrival,
    );
    $tickets = $a -> search($value, $request -> trans_time, $request -> round_trip, $request -> order, $request -> asc_desc);

    echo json_encode($tickets, JSON_NUMERIC_CHECK);

} else {
}

?>
