<?php
require_once('include/db.php');

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


$aero = new Aero();
printr($aero->get('Airport',1));


?>
