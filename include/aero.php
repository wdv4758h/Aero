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

            $aero -> execute(array(':id'=>$id));
            $aero -> query -> setFetchMode(PDO::FETCH_CLASS, get_class($this));
            return $aero -> query -> fetch();

        } catch(PDOException $e) {
            echo 'Error[' . $e->getCode() . ']: ' . $e->getMessage();
        }
    }

    public function fetchAll() {
        try {
            $aero = new Aero();
            $aero -> sql = $this -> sql_select;
            $aero -> execute();
            return $aero -> query -> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, get_class($this));
        } catch(PDOException $e) {
            echo 'Error[' . $e->getCode() . ']: ' . $e->getMessage();
        }
    }

    public function add($value) {
        try {
            $aero = new Aero();
            $aero -> sql = $this -> sql_insert;
            $aero -> execute($value);
        } catch(PDOException $e) {
            echo 'Error[' . $e->getCode() . ']: ' . $e->getMessage();
        }
    }

    public function update($value) {
        try {
            $aero = new Aero();
            $aero -> sql = $this -> sql_update;
            $aero -> execute($value);
        } catch(PDOException $e) {
            echo 'Error[' . $e->getCode() . ']: ' . $e->getMessage();
        }
    }

    public function delete($id) {
        try {
            $aero = new Aero();
            $aero -> sql = $this -> sql_delete;
            if (is_array($id)) {
                $aero -> execute($id);
            } else {
                $aero -> execute(array(':id'=>$id));
            }
        } catch(PDOException $e) {
            echo 'Error[' . $e->getCode() . ']: ' . $e->getMessage();
        }
    }
}

class Airport extends AbstractAero {
    public $name;
    public $longitude;
    public $latitude;

    protected $sql_insert = 'INSERT INTO `airports` (`id`, `iata`, `name`, `longitude`, `latitude`, `timezone`, `country_id`) VALUES (NULL, :iata, :name, :longitude, :latitude, :timezone, :country_id)';
    protected $sql_select = 'SELECT `a`.*, `c`.`full_name`, `c`.`short_name` FROM `airports` `a` JOIN country `c` ON `a`.`country_id`=`c`.`id`';
    protected $sql_selectID = 'SELECT `a`.*, `c`.`full_name`, `c`.`short_name` FROM `airports` `a` JOIN country `c` ON `a`.`country_id`=`c`.`id` WHERE `a`.`id`=:id';
    protected $sql_update = 'UPDATE `airports` SET `iata`=:iata, `name`=:name, `longitude`=:longitude, `latitude`=:latitude, `timezone`=:timezone, `country_id`=:country_id WHERE `id`=:id';
    protected $sql_delete = 'DELETE FROM `airports` WHERE `id`=:id';

    public function get($id = null) {
        if ($id) {
            $result = $this -> fetch($id);

            $this -> id = $result -> id;
            $this -> iata = $result -> iata;
            $this -> name = $result -> name;
            $this -> longitude = $result -> longitude;
            $this -> latitude = $result -> latitude;
            $this -> timezone = $result -> timezone;
            $this -> country_id = $result -> country_id;
        } else {
            $result = $this -> fetchAll();
        }
        return $result;
    }
}

class Flight extends AbstractAero {
    public $code;
    public $departure;
    public $arrival;
    public $departure_date;
    public $arrival_date;
    public $fare;

    protected $sql_insert = 'INSERT INTO `flights` (`id`, `code`, `departure`, `arrival`, `departure_date`, `arrival_date`, `fare`) VALUES (NULL, :code, (SELECT id FROM airports WHERE name=:departure), (SELECT id FROM airports WHERE name=:arrival), :departure_date, :arrival_date, :fare)';
    protected $sql_select = 'SELECT f.id, f.code, a.name AS departure, a.timezone AS departure_timezone, a.iata AS departure_iata, b.name AS arrival, b.timezone AS arrival_timezone, b.iata AS arrival_iata, f.departure_date, f.arrival_date, f.fare FROM `flights` f INNER JOIN `airports` a ON (f.departure=a.id) INNER JOIN `airports` b ON (f.arrival=b.id)';
    protected $sql_selectID = 'SELECT f.id, f.code, a.name AS departure, b.name AS arrival, f.departure_date, f.arrival_date, f.fare FROM `flights` f INNER JOIN `airports` a ON (f.departure=a.id) INNER JOIN `airports` b ON (f.arrival=b.id) WHERE f.id=:id';
    protected $sql_update = 'UPDATE `flights` SET `code`=:code, `departure`=(SELECT id FROM `airports` WHERE name=:departure), `arrival`=(SELECT id FROM `airports` WHERE name=:arrival), `departure_date`=:departure_date, `arrival_date`=:arrival_date, `fare`=:fare WHERE `id`=:id';
    protected $sql_delete = 'DELETE FROM `flights` WHERE `id`=:id';

    public function get($id = null) {
        if ($id) {
            $result = $this -> fetch($id);

            $this -> id = $result -> id;
            $this -> code = $result -> code;
            $this -> departure = $result -> departure;
            $this -> departure_date = $result -> departure_date;
            $this -> departure_timezone = $result -> departure_timezone;
            $this -> arrival = $result -> arrival;
            $this -> arrival_date = $result -> arrival_date;
            $this -> arrival_timezone = $result -> arrival_timezone;
            $this -> fare = $result -> fare;
        } else {
            $result = $this -> fetchAll();
        }
        return $result;
    }
}

class Plan extends AbstractAero {
    public $users_id;
    public $flights_id;

    protected $sql_insert = 'INSERT INTO `plans` (`id`, `users_id`, `flights_id`) VALUES (NULL, :users_id, :flights_id)';
    protected $sql_select = 'SELECT f.id, f.code, a.name AS departure, a.timezone AS departure_timezone, b.name AS arrival, b.timezone AS arrival_timezone, f.departure_date, f.arrival_date, f.fare FROM `plans` p INNER JOIN `flights` f ON (p.flights_id=f.id) INNER JOIN `airports` a ON (f.departure=a.id) INNER JOIN `airports` b ON (f.arrival=b.id) WHERE `users_id`=:id';
    protected $sql_update = '';
    protected $sql_delete = 'DELETE FROM `plans` WHERE `users_id`=:users_id AND `flights_id`=:flights_id';

    public function get($id = null) {
        if ($id) {
            $aero = new Aero();
            $aero -> sql = $this -> sql_select;
            $aero -> execute(array(':id'=>$id));
            return $aero -> query -> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Flight');
        } else {
            //$result = $this -> fetchAll();
        }
    }

    public function add($value) {
        try {
            $aero = new Aero();
            $aero -> sql = 'SELECT * FROM `plans` WHERE `users_id`=:users_id AND `flights_id`=:flights_id';
            $aero -> execute($value);
            $result = $aero -> query -> fetchObject();

            if(!$result) {
                $aero -> sql = $this -> sql_insert;
                $aero -> execute($value);
            }
        } catch(PDOExeception $e) { // incomplete
            if ($e->getCode() == '2A000')
                echo 'Flight ID not found. (Need mockup)';
            else
                echo 'Error: ' . $e->errorCode();
                //echo 'Error: ' . $e->getMessage();
        }
    }

    public function update($value) {
        echo 'update() is not supported';
        exit();
    }

    public function delete($id) { // $id = array('users_id','flights_id')
        try {
            $aero = new Aero();
            $aero -> sql = 'SELECT * FROM `plans` WHERE `users_id`=:users_id AND `flights_id`=:flights_id';
            $aero -> execute($id);
            $result = $aero -> query -> fetchObject();

            if($result) {
                $aero -> sql = $this -> sql_delete;
                $aero -> execute($id);
            }
        } catch(PDOExeception $e) {
            echo 'Error[' . $e->getCode() . ']: ' . $e->getMessage();
        }
    }
}

class User extends AbstractAero {
    public $username;
    public $password;
    public $is_admin;

    protected $sql_insert = 'INSERT INTO `users` (`id`, `username`, `password`, `is_admin`) VALUES (NULL, :username, :password, :is_admin)';
    protected $sql_select = 'SELECT `id`, `username`, `is_admin` FROM `users`';
    protected $sql_update = 'UPDATE `users` SET `is_admin`=:is_admin WHERE `id`=:id';
    protected $sql_delete = 'DELETE FROM `users` WHERE `id`=:id';

    public function get($id = null) {
        if ($id) {
            $result = $this -> fetch($id);

            $this -> id = $result -> id;
            $this -> username = $result -> username;
            $this -> password = $result -> password;
            $this -> is_admin = $result -> is_admin;
        } else {
            $result = $this -> fetchAll();
        }
        return $result;
    }
}

class Country extends AbstractAero {
    public $full_name;
    public $short_name;
    public $timezone;

    protected $sql_insert = 'INSERT INTO `country` (`id`, `full_name`, `short_name`) VALUES (NULL, :full_name, :short_name)';
    protected $sql_select = 'SELECT `id`, `full_name`, `short_name` FROM `country`';
    protected $sql_update = 'UPDATE `country` SET `full_name`=:full_name, `short_name`=:short_name WHERE `id`=:id';
    protected $sql_delete = 'DELETE FROM `country` WHERE `id`=:id';

    public function get($id = null) {
        if ($id) {
            $result = $this -> fetch($id);

            $this -> id         = $result -> id;
            $this -> full_name  = $result -> full_name;
            $this -> short_name = $result -> short_name;
        } else {
            $result = $this -> fetchAll();
        }
        return $result;
    }
}

class Ticket extends AbstractAero {
    public $departure_id;
    public $arrival_id;
    public $trans_time;

    public $no_stop = '
        SELECT
            f1.code AS code1,
            f1.departure_date AS departure1_date,
            f1.arrival_date AS arrival1_date,
            f1.fare AS fare1,
            a1.timezone AS departure1_timezone, a1.iata AS departure1_iata,
            b1.timezone AS arrival1_timezone, b1.iata AS arrival1_iata,
            TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date) AS flight1_time,

            null AS code2,
            null AS departure2_date,
            null AS arrival2_date,
            null AS fare2,
            null AS departure2_timezone, null AS departure2_iata,
            null AS arrival2_timezone, null AS arrival2_iata,
            null AS flight2_time,

            null AS code3,
            null AS departure3_date,
            null AS arrival3_date,
            null AS fare3,
            null AS departure3_timezone, null AS departure3_iata,
            null AS arrival3_timezone, null AS arrival3_iata,
            null AS flight3_time,

            TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date) AS transfer
        FROM `flights` `f1`
        JOIN `airports` `a1` ON `f1`.`departure`=`a1`.`id`
        JOIN `airports` `b1` ON `f1`.`arrival`=`b1`.`id`
        WHERE `f1`.`departure`=:departure_id AND `f1`.`arrival`=:arrival_id';

    public $one_stop = '
        SELECT
            f1.code AS code1,
            f1.departure_date AS departure1_date,
            f1.arrival_date AS arrival1_date,
            f1.fare AS fare1,
            a1.timezone AS departure1_timezone, a1.iata AS departure1_iata,
            b1.timezone AS arrival1_timezone, b1.iata AS arrival1_iata,
            TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date) AS flight1_time,

            f2.code AS code2,
            f2.departure_date AS departure2_date,
            f2.arrival_date AS arrival2_date,
            f2.fare AS fare2,
            a2.timezone AS departure2_timezone, a2.iata AS departure2_iata,
            b2.timezone AS arrival2_timezone, b2.iata AS arrival2_iata,
            TIMEDIFF(CONVERT_TZ(f2.arrival_date, b2.timezone, a2.timezone), f2.departure_date) AS flight2_time,

            null AS code3,
            null AS departure3_date,
            null AS arrival3_date,
            null AS fare3,
            null AS departure3_timezone, null AS departure3_iata,
            null AS arrival3_timezone, null AS arrival3_iata,
            null AS flight3_time,

            TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date) AS transfer
        FROM `flights` `f1`
        JOIN `flights` `f2` ON `f1`.`arrival`=`f2`.`departure`
        JOIN `airports` `a1` ON `f1`.`departure`=`a1`.`id`
        JOIN `airports` `b1` ON `f1`.`arrival`=`b1`.`id`
        JOIN `airports` `a2` ON `f2`.`departure`=`a2`.`id`
        JOIN `airports` `b2` ON `f2`.`arrival`=`b2`.`id`
        WHERE `f1`.`departure`=:departure_id AND `f2`.`arrival`=:arrival_id';

    public $two_stop = '
        SELECT
            f1.code AS code1,
            f1.departure_date AS departure1_date,
            f1.arrival_date AS arrival1_date,
            f1.fare AS fare1,
            a1.timezone AS departure1_timezone, a1.iata AS departure1_iata,
            b1.timezone AS arrival1_timezone, b1.iata AS arrival1_iata,
            TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date) AS flight1_time,

            f2.code AS code2,
            f2.departure_date AS departure2_date,
            f2.arrival_date AS arrival2_date,
            f2.fare AS fare2,
            a2.timezone AS departure2_timezone, a2.iata AS departure2_iata,
            b2.timezone AS arrival2_timezone, b2.iata AS arrival2_iata,
            TIMEDIFF(CONVERT_TZ(f2.arrival_date, b2.timezone, a2.timezone), f2.departure_date) AS flight2_time,

            f3.code AS code3,
            f3.departure_date AS departure3_date,
            f3.arrival_date AS arrival3_date,
            f3.fare AS fare3,
            a3.timezone AS departure3_timezone, a3.iata AS departure3_iata,
            b3.timezone AS arrival3_timezone, b3.iata AS arrival3_iata,
            TIMEDIFF(CONVERT_TZ(f3.arrival_date, b3.timezone, a3.timezone), f3.departure_date) AS flight3_time,

            TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date) AS transfer
        FROM `flights` `f1`
        JOIN `flights` `f2` ON `f1`.`arrival`=`f2`.`departure`
        JOIN `flights` `f3` ON `f2`.`arrival`=`f3`.`departure`
        JOIN `airports` `a1` ON `f1`.`departure`=`a1`.`id`
        JOIN `airports` `b1` ON `f1`.`arrival`=`b1`.`id`
        JOIN `airports` `a2` ON `f2`.`departure`=`a2`.`id`
        JOIN `airports` `b2` ON `f2`.`arrival`=`b2`.`id`
        JOIN `airports` `a3` ON `f3`.`departure`=`a3`.`id`
        JOIN `airports` `b3` ON `f3`.`arrival`=`b3`.`id`
        WHERE `f1`.`departure`=:departure_id AND `f3`.`arrival`=:arrival_id';

    public function get($id = null) {
        return null;
    }

    public function search($value, $trans_time) {
        try {
            $aero = new Aero();

            switch ($trans_time){
                case 0:
                    $aero -> sql = $this -> no_stop;
                    break;
                case 1:
                    $aero -> sql = $this -> no_stop . " UNION " . $this -> one_stop;
                    break;
                case 2:
                    $aero -> sql = $this -> no_stop . " UNION " . $this -> one_stop . " UNION " . $this -> two_stop;
                    break;
                default:
                    $aero -> sql = $this -> no_stop;
            }

            $aero -> execute($value);
            return $aero -> query -> fetchAll();
        } catch(PDOException $e) {
            echo 'Error[' . $e->getCode() . ']: ' . $e->getMessage();
        }
    }
}

?>
