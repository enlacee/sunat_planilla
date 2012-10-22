<?php

abstract class AbstractDao extends PDO {
/*
    // singleton
    private static $instancia = null;

    public static function getInstancia(){
        if( self::$instancia == null ){
            try {
                self::$instancia = new AbstractDao();
            } catch (Exception $e) {
                throw $e;
            }
        }
        return self::$instancia;
    }
    */
    // singleton   
    
    protected $pdo = null;

    function __construct() {
        /* // $param = parse_ini_file("../conf/connect.ini", TRUE);
          $dsn = 'mysql:host=localhost;dbname=db';
          $username ='root';
          $passwd = '';
          $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
          
        parent::__construct($dsn, $username, $passwd, $options);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);        
        */
        //--- before
        $this->pdo = new PDO("mysql:host=localhost;dbname=db", "root", "",
                        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

    }
    //$this->pdo->lastInsertId();

    
    
}

?>
