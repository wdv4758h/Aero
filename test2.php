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

class Airport {
    public $id;
    public $name;
    public $longitude;
    public $latitude;
       
    public function get($id) {
        try {
            $aero = new Aero();
            $aero -> sql = 'select * from airports where id=:id';
            $aero -> execute(array(':id'=>$id));

            $aero -> query -> setFetchMode(PDO::FETCH_CLASS, 'Airport'); 
            $a = $aero -> query -> fetch();           
            
            $this -> id = $a -> id;
            $this -> name = $a -> name;
            $this -> longitude = $a -> longitude;
            $this -> latitude = $a -> latitude;
            
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}

$airport = new Airport;
$airport -> get(2);
printr($airport);

?>
