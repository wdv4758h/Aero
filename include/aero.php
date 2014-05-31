<?php
require_once 'db.php';

abstract class AbstractAero {
    public $id;
    protected $sql_insert;
    protected $sql_select;
    protected $sql_update;
    protected $sql_delete;

    abstract public function get($id);

    public function fetch($id, $idProperty = 'id') {
        try {
            $aero = new Aero();

            if (isset($this -> sql_selectID))
                $aero -> sql = $this -> sql_selectID;
            else
                $aero -> sql = $this -> sql_select . ' WHERE id=:id';

            $aero -> execute(array(':'.$idProperty=>$id));
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

    public function delete($id, $idProperty = 'id') {
        try {
            $aero = new Aero();
            $aero -> sql = $this -> sql_delete;
            if (is_array($id)) {
                $aero -> execute($id);
            } else {
                $aero -> execute(array(':'.$idProperty=>$id));
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

    protected $sql_insert = 'INSERT INTO `airports` (`iata`, `name`, `longitude`, `latitude`, `timezone`, `country_id`) VALUES (:iata, :name, :longitude, :latitude, :timezone, :country_id)';
    protected $sql_select = 'SELECT `a`.*, `c`.`name` as `country` FROM `airports` `a` JOIN country `c` ON `a`.`country_id`=`c`.`abbr`';
    protected $sql_selectID = 'SELECT `a`.*, `c`.`name` as `country` FROM `airports` `a` JOIN country `c` ON `a`.`country_id`=`c`.`abbr` WHERE `a`.`iata`=:iata';
    protected $sql_update = 'UPDATE `airports` SET `iata`=:iata, `name`=:name, `longitude`=:longitude, `latitude`=:latitude, `timezone`=:timezone, `country_id`=:country_id WHERE `iata`=:old_iata';
    protected $sql_delete = 'DELETE FROM `airports` WHERE `iata`=:iata';

    public function get($id = null) {
        if ($id) {
            $result = $this -> fetch($id, 'iata');

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

    protected $sql_insert = '
        INSERT INTO `flights` (
            `id`,
            `code`,
            `departure`,
            `arrival`,
            `departure_date`,
            `arrival_date`,
            `fare`
        ) VALUES (
            NULL,
            :code,
            :departure,
            :arrival,
            :departure_date,
            :arrival_date,
            :fare
        )';

    protected $sql_select = 'SELECT f.id, f.code, a.name AS departure, a.timezone AS departure_timezone, a.iata AS departure_iata, b.name AS arrival, b.timezone AS arrival_timezone, b.iata AS arrival_iata, f.departure_date, f.arrival_date, f.fare FROM `flights` f INNER JOIN `airports` a ON (f.departure=a.iata) INNER JOIN `airports` b ON (f.arrival=b.iata)';
    protected $sql_selectID = 'SELECT f.id, f.code, a.name AS departure, a.iata AS departure_iata, b.name AS arrival, b.iata AS arrival_iata, f.departure_date, f.arrival_date, f.fare FROM `flights` f INNER JOIN `airports` a ON (f.departure=a.iata) INNER JOIN `airports` b ON (f.arrival=b.iata) WHERE f.id=:id';
    protected $sql_update = 'UPDATE `flights` SET `code`=:code, `departure`=:departure, `arrival`=:arrival, `departure_date`=:departure_date, `arrival_date`=:arrival_date, `fare`=:fare WHERE `id`=:id';
    protected $sql_delete = 'DELETE FROM `flights` WHERE `id`=:id';

    public function get($id = null) {
        if ($id) {
            $result = $this -> fetch($id);

            $this -> id = $result -> id;
            $this -> code = $result -> code;
            $this -> departure = $result -> departure;
            $this -> departure_date = $result -> departure_date;
            $this -> arrival = $result -> arrival;
            $this -> arrival_date = $result -> arrival_date;
            $this -> fare = $result -> fare;
        } else {
            $result = $this -> fetchAll();
        }
        return $result;
    }
}

class Plan extends AbstractAero {
    public $user_id;
    public $flight_id;

    protected $sql_insert = 'INSERT INTO `plans` (`user_id`, `flight_id`) VALUES (:user_id, :flight_id)';
    protected $sql_select = 'SELECT f.id, f.code, a.name AS departure, a.timezone AS departure_timezone, b.name AS arrival, b.timezone AS arrival_timezone, f.departure_date, f.arrival_date, f.fare FROM `plans` p INNER JOIN `flights` f ON (p.flight_id=f.id) INNER JOIN `airports` a ON (f.departure=a.iata) INNER JOIN `airports` b ON (f.arrival=b.iata) WHERE `user_id`=:id';
    protected $sql_update = '';
    protected $sql_delete = 'DELETE FROM `plans` WHERE `user_id`=:user_id AND `flight_id`=:flight_id';

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
            $aero -> sql = 'SELECT * FROM `plans` WHERE `user_id`=:user_id AND `flight_id`=:flight_id';
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
            $aero -> sql = 'SELECT * FROM `plans` WHERE `user_id`=:user_id AND `flight_id`=:flight_id';
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

    protected $sql_insert = 'INSERT INTO `country` (`abbr`, `name`) VALUES (:abbr, :name)';
    protected $sql_select = 'SELECT * FROM `country`';
    protected $sql_selectID = 'SELECT * FROM `country` WHERE `abbr`=:abbr';
    protected $sql_update = 'UPDATE `country` SET `name`=:name, `abbr`=:abbr WHERE `abbr`=:old_abbr';
    protected $sql_delete = 'DELETE FROM `country` WHERE `abbr`=:abbr';

    public function get($id = null) {
        if ($id) {
            $result = $this -> fetch($id, 'abbr');

            $this -> name  = $result -> name;
            $this -> abbr = $result -> abbr;
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
            null AS flight3_time, ';


    public $no_total = '
        f1.arrival_date AS arrival_date,
        TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date) AS flight_time,
        0 AS transfer,
        TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date) AS total_time,
        f1.fare AS total_fare ';

    public $no_join = '
        FROM `flights` `f1`
        JOIN `airports` `a1` ON `f1`.`departure`=`a1`.`iata`
        JOIN `airports` `b1` ON `f1`.`arrival`=`b1`.`iata` ';

    public $no_where = '
        WHERE
                `f1`.`departure`=:departure
            AND `f1`.`arrival`=:arrival
        ';

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
            null AS flight3_time, ';

    public $one_total = '
            f2.arrival_date AS arrival_date,
            ADDTIME(
                TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date),
                TIMEDIFF(CONVERT_TZ(f2.arrival_date, b2.timezone, a2.timezone), f2.departure_date)
            ) AS flight_time,
            TIMEDIFF(f2.departure_date, f1.arrival_date) AS transfer,

            ADDTIME(
                ADDTIME(
                    TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date),
                    TIMEDIFF(CONVERT_TZ(f2.arrival_date, b2.timezone, a2.timezone), f2.departure_date)
                ),
                TIMEDIFF(f2.departure_date, f1.arrival_date)
            ) AS total_time,

            (f1.fare + f2.fare)*0.9 AS total_fare ';

    public $one_join = '
        FROM `flights` `f1`
        JOIN `flights` `f2`
            ON `f1`.`arrival`=`f2`.`departure`
            AND `f2`.`arrival`=:arrival
            AND (`f1`.`arrival_date` + INTERVAL 2 HOUR) <= `f2`.`departure_date`
        JOIN `airports` `a1` ON `f1`.`departure`=`a1`.`iata`
        JOIN `airports` `b1` ON `f1`.`arrival`=`b1`.`iata`
        JOIN `airports` `a2` ON `f2`.`departure`=`a2`.`iata`
        JOIN `airports` `b2` ON `f2`.`arrival`=`b2`.`iata` ';

    public $one_where = '
        WHERE
                `f1`.`departure`=:departure
        ';

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
            TIMEDIFF(CONVERT_TZ(f3.arrival_date, b3.timezone, a3.timezone), f3.departure_date) AS flight3_time, ';

    public $two_total = '
            f3.arrival_date AS arrival_date,
            ADDTIME(
                ADDTIME(
                    TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date),
                    TIMEDIFF(CONVERT_TZ(f2.arrival_date, b2.timezone, a2.timezone), f2.departure_date)
                ),
                TIMEDIFF(CONVERT_TZ(f3.arrival_date, b3.timezone, a3.timezone), f3.departure_date)
            ) AS flight_time,
            ADDTIME(
                TIMEDIFF(f2.departure_date, f1.arrival_date),
                TIMEDIFF(f3.departure_date, f2.arrival_date)
            ) AS transfer,

            ADDTIME(
                ADDTIME(
                    ADDTIME(
                        TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date),
                        TIMEDIFF(CONVERT_TZ(f2.arrival_date, b2.timezone, a2.timezone), f2.departure_date)
                    ),
                    TIMEDIFF(CONVERT_TZ(f3.arrival_date, b3.timezone, a3.timezone), f3.departure_date)
                ),
                ADDTIME(
                    TIMEDIFF(f2.departure_date, f1.arrival_date),
                    TIMEDIFF(f3.departure_date, f2.arrival_date)
                )
            ) AS total_time,
            (f1.fare + f2.fare + f3.fare)*0.8 AS total_fare ';

    public $two_join = '
        FROM `flights` `f1`
        JOIN `flights` `f2`
            ON `f1`.`arrival`=`f2`.`departure`
            AND (`f1`.`arrival_date` + INTERVAL 2 HOUR) <= `f2`.`departure_date`
        JOIN `flights` `f3`
            ON `f2`.`arrival`=`f3`.`departure`
            AND (`f2`.`arrival_date` + INTERVAL 2 HOUR) <= `f3`.`departure_date`
            AND `f3`.`arrival`=:arrival
        JOIN `airports` `a1` ON `f1`.`departure`=`a1`.`iata`
        JOIN `airports` `b1` ON `f1`.`arrival`=`b1`.`iata`
        JOIN `airports` `a2` ON `f2`.`departure`=`a2`.`iata`
        JOIN `airports` `b2` ON `f2`.`arrival`=`b2`.`iata`
        JOIN `airports` `a3` ON `f3`.`departure`=`a3`.`iata`
        JOIN `airports` `b3` ON `f3`.`arrival`=`b3`.`iata` ';

    public $two_where = '
        WHERE
                `f1`.`departure`=:departure
        ';

    public $night1 = ' AND
         TIME(`f1`.`arrival_date`) < TIME(`f2`.`departure_date`)
         AND DATE(`f1`.`arrival_date`) = DATE(`f2`.`departure_date`) ';

    public $night2 = ' AND
         TIME(`f1`.`arrival_date`) < TIME(`f2`.`departure_date`)
         AND DATE(`f1`.`arrival_date`) = DATE(`f2`.`departure_date`) ';

    public $no_select = '
        null AS code4,
        null AS departure4_date,
        null AS arrival4_date,
        null AS fare4,
        null AS departure4_timezone, null AS departure4_iata,
        null AS arrival4_timezone, null AS arrival4_iata,
        null AS flight4_time,

        null AS code5,
        null AS departure5_date,
        null AS arrival5_date,
        null AS fare5,
        null AS departure5_timezone, null AS departure5_iata,
        null AS arrival5_timezone, null AS arrival5_iata,
        null AS flight5_time,

        null AS code6,
        null AS departure6_date,
        null AS arrival6_date,
        null AS fare6,
        null AS departure6_timezone, null AS departure6_iata,
        null AS arrival6_timezone, null AS arrival6_iata,
        null AS flight6_time, ';

    public $no_round_select = '
        f4.code AS code4,
        f4.departure_date AS departure4_date,
        f4.arrival_date AS arrival4_date,
        f4.fare AS fare4,
        a4.timezone AS departure4_timezone, a4.iata AS departure4_iata,
        b4.timezone AS arrival4_timezone, b4.iata AS arrival4_iata,
        TIMEDIFF(CONVERT_TZ(f4.arrival_date, b4.timezone, a4.timezone), f4.departure_date) AS flight4_time,

        null AS code5,
        null AS departure5_date,
        null AS arrival5_date,
        null AS fare5,
        null AS departure5_timezone, null AS departure5_iata,
        null AS arrival5_timezone, null AS arrival5_iata,
        null AS flight5_time,

        null AS code6,
        null AS departure6_date,
        null AS arrival6_date,
        null AS fare6,
        null AS departure6_timezone, null AS departure6_iata,
        null AS arrival6_timezone, null AS arrival6_iata,
        null AS flight6_time, ';

    public $no_round_total = '
        f1.arrival_date AS arrival_date,
        ADDTIME(
            TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date),
            TIMEDIFF(CONVERT_TZ(f4.arrival_date, b4.timezone, a4.timezone), f4.departure_date)
        ) AS flight_time,
        0 AS transfer,
        ADDTIME(
            TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date),
            TIMEDIFF(CONVERT_TZ(f4.arrival_date, b4.timezone, a4.timezone), f4.departure_date)
        ) AS total_time,
        (f1.fare + f4.fare) AS total_fare ';

    public $no_round_join = '
        JOIN `flights` `f4`
            ON `f4`.`departure`=`f1`.`arrival`
            AND `f4`.`arrival`=`f1`.`departure`
            AND (`f1`.`arrival_date` + INTERVAL 2 HOUR) <= `f4`.`departure_date`
        JOIN `airports` `a4` ON `f4`.`departure`=`a4`.`iata`
        JOIN `airports` `b4` ON `f4`.`arrival`=`b4`.`iata` ';

    public $one_round_select = '
        f4.code AS code4,
        f4.departure_date AS departure4_date,
        f4.arrival_date AS arrival4_date,
        f4.fare AS fare4,
        a4.timezone AS departure4_timezone, a4.iata AS departure4_iata,
        b4.timezone AS arrival4_timezone, b4.iata AS arrival4_iata,
        TIMEDIFF(CONVERT_TZ(f4.arrival_date, b4.timezone, a4.timezone), f4.departure_date) AS flight4_time,

        f5.code AS code5,
        f5.departure_date AS departure5_date,
        f5.arrival_date AS arrival5_date,
        f5.fare AS fare5,
        a5.timezone AS departure5_timezone, a5.iata AS departure5_iata,
        b5.timezone AS arrival5_timezone, b5.iata AS arrival5_iata,
        TIMEDIFF(CONVERT_TZ(f5.arrival_date, b5.timezone, a5.timezone), f5.departure_date) AS flight5_time,

        null AS code6,
        null AS departure6_date,
        null AS arrival6_date,
        null AS fare6,
        null AS departure6_timezone, null AS departure6_iata,
        null AS arrival6_timezone, null AS arrival6_iata,
        null AS flight6_time, ';

    public $one_round_total = '
        f2.arrival_date AS arrival_date,
        ADDTIME(
            ADDTIME(
                TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date),
                TIMEDIFF(CONVERT_TZ(f2.arrival_date, b2.timezone, a2.timezone), f2.departure_date)
            ),
            ADDTIME(
                TIMEDIFF(CONVERT_TZ(f4.arrival_date, b4.timezone, a4.timezone), f4.departure_date),
                TIMEDIFF(CONVERT_TZ(f5.arrival_date, b5.timezone, a5.timezone), f5.departure_date)
            )
        ) AS flight_time,

        ADDTIME(
            TIMEDIFF(f2.departure_date, f1.arrival_date),
            TIMEDIFF(f5.departure_date, f4.arrival_date)
        ) AS transfer,

        ADDTIME(
            ADDTIME(
                ADDTIME(
                    TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date),
                    TIMEDIFF(CONVERT_TZ(f2.arrival_date, b2.timezone, a2.timezone), f2.departure_date)
                ),
                ADDTIME(
                    TIMEDIFF(CONVERT_TZ(f4.arrival_date, b4.timezone, a4.timezone), f4.departure_date),
                    TIMEDIFF(CONVERT_TZ(f5.arrival_date, b5.timezone, a5.timezone), f5.departure_date)
                )
            ),
            ADDTIME(
                TIMEDIFF(f2.departure_date, f1.arrival_date),
                TIMEDIFF(f5.departure_date, f4.arrival_date)
            )
        ) AS total_time,
        (f1.fare + f2.fare + f4.fare + f5.fare) * 0.9 AS total_fare ';

    public $one_round_join = '
        JOIN `flights` `f4`
            ON `f4`.`departure`=`f2`.`arrival`
            AND `f4`.`arrival`=`f2`.`departure`
            AND (`f2`.`arrival_date` + INTERVAL 2 HOUR) <= `f4`.`departure_date`
        JOIN `flights` `f5`
            ON `f5`.`departure`=`f1`.`arrival`
            AND `f5`.`arrival`=`f1`.`departure`
            AND (`f4`.`arrival_date` + INTERVAL 2 HOUR) <= `f5`.`departure_date`
        JOIN `airports` `a4` ON `f4`.`departure`=`a4`.`iata`
        JOIN `airports` `b4` ON `f4`.`arrival`=`b4`.`iata`
        JOIN `airports` `a5` ON `f5`.`departure`=`a5`.`iata`
        JOIN `airports` `b5` ON `f5`.`arrival`=`b5`.`iata` ';

    public $two_round_select = '
        f4.code AS code4,
        f4.departure_date AS departure4_date,
        f4.arrival_date AS arrival4_date,
        f4.fare AS fare4,
        a4.timezone AS departure4_timezone, a4.iata AS departure4_iata,
        b4.timezone AS arrival4_timezone, b4.iata AS arrival4_iata,
        TIMEDIFF(CONVERT_TZ(f4.arrival_date, b4.timezone, a4.timezone), f4.departure_date) AS flight4_time,

        f5.code AS code5,
        f5.departure_date AS departure5_date,
        f5.arrival_date AS arrival5_date,
        f5.fare AS fare5,
        a5.timezone AS departure5_timezone, a5.iata AS departure5_iata,
        b5.timezone AS arrival5_timezone, b5.iata AS arrival5_iata,
        TIMEDIFF(CONVERT_TZ(f5.arrival_date, b5.timezone, a5.timezone), f5.departure_date) AS flight5_time,

        f6.code AS code6,
        f6.departure_date AS departure6_date,
        f6.arrival_date AS arrival6_date,
        f6.fare AS fare6,
        a6.timezone AS departure6_timezone, a6.iata AS departure6_iata,
        b6.timezone AS arrival6_timezone, b6.iata AS arrival6_iata,
        TIMEDIFF(CONVERT_TZ(f6.arrival_date, b6.timezone, a6.timezone), f6.departure_date) AS flight6_time, ';

    public $two_round_total = '
        f3.arrival_date AS arrival_date,
        ADDTIME(
            ADDTIME(
                ADDTIME(
                    TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date),
                    TIMEDIFF(CONVERT_TZ(f2.arrival_date, b2.timezone, a2.timezone), f2.departure_date)
                ),
                TIMEDIFF(CONVERT_TZ(f3.arrival_date, b3.timezone, a3.timezone), f3.departure_date)
            ),
            ADDTIME(
                ADDTIME(
                    TIMEDIFF(CONVERT_TZ(f4.arrival_date, b4.timezone, a4.timezone), f4.departure_date),
                    TIMEDIFF(CONVERT_TZ(f5.arrival_date, b5.timezone, a5.timezone), f5.departure_date)
                ),
                TIMEDIFF(CONVERT_TZ(f6.arrival_date, b6.timezone, a6.timezone), f6.departure_date)
            )
        ) AS flight_time,

        ADDTIME(
            ADDTIME(
                TIMEDIFF(f2.departure_date, f1.arrival_date),
                TIMEDIFF(f3.departure_date, f2.arrival_date)
            ),
            ADDTIME(
                TIMEDIFF(f5.departure_date, f4.arrival_date),
                TIMEDIFF(f6.departure_date, f5.arrival_date)
            )
        ) AS transfer,

        ADDTIME(
            ADDTIME(
                ADDTIME(
                    ADDTIME(
                        TIMEDIFF(CONVERT_TZ(f1.arrival_date, b1.timezone, a1.timezone), f1.departure_date),
                        TIMEDIFF(CONVERT_TZ(f2.arrival_date, b2.timezone, a2.timezone), f2.departure_date)
                    ),
                    TIMEDIFF(CONVERT_TZ(f3.arrival_date, b3.timezone, a3.timezone), f3.departure_date)
                ),
                ADDTIME(
                    ADDTIME(
                        TIMEDIFF(CONVERT_TZ(f4.arrival_date, b4.timezone, a4.timezone), f4.departure_date),
                        TIMEDIFF(CONVERT_TZ(f5.arrival_date, b5.timezone, a5.timezone), f5.departure_date)
                    ),
                    TIMEDIFF(CONVERT_TZ(f6.arrival_date, b6.timezone, a6.timezone), f6.departure_date)
                )
            ),
            ADDTIME(
                ADDTIME(
                    TIMEDIFF(f2.departure_date, f1.arrival_date),
                    TIMEDIFF(f3.departure_date, f2.arrival_date)
                ),
                ADDTIME(
                    TIMEDIFF(f5.departure_date, f4.arrival_date),
                    TIMEDIFF(f6.departure_date, f5.arrival_date)
                )
            )
        ) AS total_time,
        (f1.fare + f2.fare + f3.fare + f4.fare + f5.fare + f6.fare) * 0.8 AS total_fare ';

    public $two_round_join = '
        JOIN `flights` `f4`
            ON `f4`.`departure`=`f3`.`arrival`
            AND `f4`.`arrival`=`f3`.`departure`
            AND (`f3`.`arrival_date` + INTERVAL 2 HOUR) <= `f4`.`departure_date`
        JOIN `flights` `f5`
            ON `f5`.`departure`=`f2`.`arrival`
            AND `f5`.`arrival`=`f2`.`departure`
            AND (`f4`.`arrival_date` + INTERVAL 2 HOUR) <= `f5`.`departure_date`
        JOIN `flights` `f6`
            ON `f6`.`departure`=`f1`.`arrival`
            AND `f6`.`arrival`=`f1`.`departure`
            AND (`f5`.`arrival_date` + INTERVAL 2 HOUR) <= `f6`.`departure_date`
        JOIN `airports` `a4` ON `f4`.`departure`=`a4`.`iata`
        JOIN `airports` `b4` ON `f4`.`arrival`=`b4`.`iata`
        JOIN `airports` `a5` ON `f5`.`departure`=`a5`.`iata`
        JOIN `airports` `b5` ON `f5`.`arrival`=`b5`.`iata`
        JOIN `airports` `a6` ON `f6`.`departure`=`a6`.`iata`
        JOIN `airports` `b6` ON `f6`.`arrival`=`b6`.`iata` ';

    public function get($id = null) {
        return null;
    }

    public function search($value, $trans_time, $round_trip, $order, $asc_desc, $night) {
        try {
            $aero = new Aero();

            if ($round_trip) {
                $this -> no_stop =
                    $this -> no_stop .
                    $this -> no_round_select .
                    $this -> no_round_total .
                    $this -> no_join .
                    $this -> no_round_join .
                    $this -> no_where;

                $this -> one_stop =
                    $this -> one_stop .
                    $this -> one_round_select .
                    $this -> one_round_total .
                    $this -> one_join .
                    $this -> one_round_join .
                    $this -> one_where;

                $this -> two_stop =
                    $this -> two_stop .
                    $this -> two_round_select .
                    $this -> two_round_total .
                    $this -> two_join .
                    $this -> two_round_join .
                    $this -> two_where;
            } else {
                $this -> no_stop =
                    $this -> no_stop .
                    $this -> no_select .
                    $this -> no_total .
                    $this -> no_join .
                    $this -> no_where;

                $this -> one_stop =
                    $this -> one_stop .
                    $this -> no_select .
                    $this -> one_total .
                    $this -> one_join .
                    $this -> one_where;

                $this -> two_stop =
                    $this -> two_stop .
                    $this -> no_select .
                    $this -> two_total .
                    $this -> two_join .
                    $this -> two_where;
            }

            switch ($trans_time){
                case 0:
                    $aero -> sql = $this -> no_stop;
                    break;
                case 1:
                    if ($night) {
                        $aero -> sql =
                            $this -> no_stop .
                            " UNION " .
                            $this -> one_stop;
                    } else {
                        $aero -> sql =
                            $this -> no_stop .
                            " UNION " .
                            $this -> one_stop .
                            $this -> night1;
                    }
                    break;
                case 2:
                    if ($night) {
                        $aero -> sql =
                            $this -> no_stop .
                            " UNION " .
                            $this -> one_stop .
                            " UNION " .
                            $this -> two_stop;
                    } else {
                        $aero -> sql =
                            $this -> no_stop .
                            " UNION " .
                            $this -> one_stop .
                            $this -> night1 .
                            " UNION " .
                            $this -> two_stop .
                            $this -> night2;
                    }
                    break;
                default:
                    $aero -> sql = $this -> no_stop;
            }

            if ($order)
                $asc_desc = $asc_desc . ", ";

            $aero -> sql =
                $aero -> sql .
                " ORDER BY " .
                $order .
                " " .
                $asc_desc .
                " total_fare, departure1_date, arrival_date";

            $aero -> execute($value);
            return array('sql' => $aero -> sql, 'data' => $aero -> query -> fetchAll());
            //return $aero -> query -> fetchAll();
            //return array('sql' => $aero -> sql);
        } catch(PDOException $e) {
            echo 'Error[' . $e->getCode() . ']: ' . $e->getMessage();
        }
    }
}

?>
