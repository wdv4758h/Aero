<?php
require_once 'db.php';

abstract class AbstractAero {
    public $id;
    protected $sql_insert;
    protected $sql_select;
    protected $sql_update;
    protected $sql_delete;
    
    abstract public function get($id);
    
    public function fetch($id) {
        try {
            $aero = new Aero();
            
            if (isset($this -> sql_selectID)) 
                $aero -> sql = $this -> sql_selectID;
            else
                $aero -> sql = $this -> sql_select . ' WHERE id=:id';

            $aero -> query -> setFetchMode(PDO::FETCH_CLASS, get_class($this));
            return $aero -> query -> fetch();

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
    public function update($id, $value) {
        try {
            $aero = new Aero();
            $aero -> sql = $this -> sql_update;
            $aero -> execute($value);
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
    public function delete($id) {
        try {
            $aero = new Aero();
            $aero -> sql = $this -> sql_delete;
            $aero -> execute(array($id));
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}

class Airport extends AbstractAero {
    public $name;
    public $longitude;
    public $latitude;
    
    protected $sql_select = 'SELECT * FROM `airports`';
    protected $sql_update = 'UPDATE `airports` SET `name`=:name, `longitude`=:longitude, `latitude`=:latitude WHERE `id`=:id';
    protected $sql_delete = 'DELETE FROM `airports` WHERE `id`=:id';
    
    public function get($id) {
        $result = $this -> fetch($id);
        
        $this -> $id = $result -> $id;
        $this -> $name = $result -> $anme;
        $this -> $longitude = $result -> $longitude;
        $this -> $latitude = $result -> $latitude;
    }
}

class Flights extends AbstractAero {
    public $code;
    public $departure;
    public $arrival;
    public $departure_date;
    public $arrival_date;
    public $fare;
    
    protected $sql_select = 'SELECT f.id, f.code, a.name AS departure, b.name AS arrival, f.departure_date, f.arrival_date, f.fare FROM `flights` f INNER JOIN `airports` a ON (f.departure=a.id) INNER JOIN `airports` b ON (f.arrival=b.id)';
    protected $sql_selectID = 'SELECT f.id, f.code, a.name AS departure, b.name AS arrival, f.departure_date, f.arrival_date, f.fare FROM `flights` f INNER JOIN `airports` a ON (f.departure=a.id) INNER JOIN `airports` b ON (f.arrival=b.id) WHERE f.id=:id';
    protected $sql_update = 'UPDATE `flights` SET `code`=:code, `departure`=(SELECT id FROM `airports` WHERE name=:departure), `arrival`=(SELECT id FROM `airports` WHERE name=:arrival), `departure_date`=:depart_date, `arrival_date`=:arrive_date, `fare`=:fare WHERE `id`=:id';
    protected $sql_delete = 'DELETE FROM `flights` WHERE `id`=:id';
    
    public function get($id) {
        $result = $this -> fetch($id);
        
        $this -> $id = $result -> $id;
        $this -> $code = $result -> $code;
        $this -> $departure = $result -> $departure;
        $this -> $arrival = $result -> $arrival;
        $this -> $departure_date = $result -> $departure_date;
        $this -> $arrival_date = $result -> $arrival_date;
        $this -> $fare = $result -> $fare;
    }
}

class Plan extends AbstractAero {
    public $users_id;
    public $flights_id;
    
    protected $sql_select = 'SELECT * FROM `plans`';
    protected $sql_update = 'UPDATE `plans` SET `users_id`=:users_id, `flights_id`=:flights_id WHERE `id`=:id';
    protected $sql_delete = 'DELETE FROM `plans` WHERE `id`=:id';
    
    public function get($id) {
        $result = $this -> fetch($id);
        
        $this -> $id = $result -> $id;
        $this -> $users_id = $result -> $users_id;
        $this -> $flights_id = $result -> $flights_id;
    }
}

class User {
    public $username;
    public $password;
    public $is_admin;
    
    protected $sql_select = 'SELECT * FROM `users`';
    protected $sql_update = 'UPDATE `users` SET `username`=:username, `password`=:password, `is_admin`=:is_admin WHERE `id`=:id';
    protected $sql_delete = 'DELETE FROM `users` WHERE `id`=:id';
    
    public function get($id) {
        $result = $this -> fetch($id);
        
        $this -> $id = $result -> $id;
        $this -> $username = $result -> $username;
        $this -> $password = $result -> $password;
        $this -> $is_admin = $result -> $is_admin;
    }
}

?>
