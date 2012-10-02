<?php

/**
 * Description of MyPDO
 *
 * @author Anibal
 */
class MyPDO extends PDO {
    //put your code here
    private static $instancia=null;
    
    public static function getInstancia(){
        
        if(self::$instancia==null){
            self::$instancia = new MyPDO();            
        }
        return self::$instancia;
        
    }

    function __construct() {
        
          $dsn = 'mysql:host=localhost;dbname=db';
          $username ='root';
          $passwd = '';
          $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        
        parent::__construct($dsn, $username, $passwd, $options);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
    }

    
}

?>
