<?php
require_once '../../include/base.php';
require_once('../../include/aero.php');
checkCredential();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $p = new TicketPlan();

    $value = array(
        ':user_id' => $_SESSION['id'],
	    ':flight1_id' => $request -> id1,
	    ':flight2_id' => $request -> id2,
	    ':flight3_id' => $request -> id3,
	    ':flight4_id' => $request -> id4,
	    ':flight5_id' => $request -> id5,
	    ':flight6_id' => $request -> id6
    );
    $p -> add($value);

} else if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $p = new TicketPlan();
    echo json_encode($p -> get($_SESSION['id']), JSON_NUMERIC_CHECK);
}

?>
