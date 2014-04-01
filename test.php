<?php
require_once('include/aero.php');


$a = new Airport();

$b = $a->get(1);

var_dump($a);
echo '<br>';
echo '<br>';
var_dump($b);

$value = array(
        ':name' => 'test',
        ':longitude' => '1.01',
        ':latitude' => '2.02',
        );
$a -> add($value);

?>
