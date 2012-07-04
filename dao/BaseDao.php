<?php
// Start of BaseDao v.1.0.0dev

/**
 * Conexion con tu Base de Datos PDO 
 * See Example: 'class productoDAO extends BaseDao { }'
 * 
 *  $url  = "mysql:host=localhost;dbname=vmotivos";
 *  $user = "root";
 *  $pass = "";
 *  BaseDao::__construct($url,$user,$pass);  
 *  $stm = $this->pdo->prepare($query);
 *  $stm->execute(); * 
 * 
 * @link http://www.goole.com.pe
 */

class BaseDao {

    protected $pdo = null;
	//variabkes Base Datos
	private $url;
	private $user;
	private $pass;        
        protected $CURDATE;

    function __construct($url=null, $user=null, $pass=null) {
        $param = parse_ini_file("../conf/connect.ini", TRUE);
        $this->url  = ( $url==null ) ? $param["DATABASE"]["URL"] : $url;
        $this->user = ( $user==null) ? $param["DATABASE"]["USER"] : $user;
        $this->pass = ( $pass==null) ? $param["DATABASE"]["PASSWORD"] : $pass;

        $this->pdo = new PDO($this->url, $this->user, $this->pass);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
    }
    
    
    protected function sqliteConnect($url=null){
        
        $param = parse_ini_file("../conf/connect.ini", TRUE);
        
        $this->url  = ( $url==null ) ? $param["SQLITE"]["URL"] : $url;

        $this->pdo = new PDO($this->url);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
        
        //$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT );  
        //$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        
        $this->CURDATE = date("Y-m-d H:i:s");
 
        
    }

    
}

?>
