<?php
require_once('include/aero.php');

function printr($data, $exit = TRUE) {
      if ($data) {
              print '<pre>';
                  print_r($data);
                      print '</pre>';
                        }
        if ($exit) {
                exit;
                  }
}


$a = new Airport();

printr($a->get(1));


?>
