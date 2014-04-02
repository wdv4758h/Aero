<?php

/*
   Testing AERO class
*/

// Include AERO class.
require_once('include/aero.php');

// Initialize object.
$a = new Airport();

// Testing get().
//$a->get(1);
//var_dump($a);
var_dump($a->get());

// Tesing add().
//$value = array(
//        ':name' => 'test',
//        ':longitude' => '1.01',
//        ':latitude' => '2.02',
//        );
//$a -> add($value);

// Testing update().
//$value = array(
//        ':id' => '6',
//        ':name' => 'test2',
//        ':longitude' => '4.01',
//        ':latitude' => '6.02',
//        );
//$a -> update($value);

// Testing delete().
//$a -> delete(6);

?>
