<?php

class Aero {

    var $db, $sql, $query;

    function Aero($sql = null){

        $db_host = "localhost";
        $db_name = "";
        $db_user = "";
        $db_password = "";
        $dsn = "mysql:host=$db_host;dbname=$db_name";

        try {
            $this -> db = new PDO($dsn, $db_user, $db_password);
            $this -> db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this -> sql = $sql;
        } catch(PDOException $e) {
            echo 'Error[' . $e->getCode() . ']: ' . $e->getMessage();
            //echo 'ERROR: ' . $e->getMessage();
            exit();
        }
    }

    public function execute($parameter=null){
        try {
            $this -> query = $this -> db -> prepare($this -> sql);
            $this -> query -> execute($parameter);
        } catch(PDOException $e) {
            echo 'Error[' . $e->getCode() . ']: ' . $e->getMessage();
            //echo 'ERROR: ' . $e->getMessage();
            exit();
        }
    }

}

?>
