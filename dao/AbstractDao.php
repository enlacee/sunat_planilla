<?php

abstract class AbstractDao {

    protected $pdo = null;

    function __construct() {
        /* $param = parse_ini_file("../conf/connect.ini", TRUE);
          $url = $param["DATABASE"]["URL"];
          $user =$param["DATABASE"]["USER"];
          $pass =$param["DATABASE"]["PASSWORD"];
         */
        $this->pdo = new PDO("mysql:host=localhost;dbname=db", "root", "",
                        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
    }

    
    
}

?>
