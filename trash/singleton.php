<?php
class Ejemplo
{
    // Contenedor Instancia de la Clase
    private static $instance;
 
    // A private constructor; previene creación de objetos vía new
    private function __construct()
    {
        echo 'Soy el constructor';
    }
 
    // EL método singleton 
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
 
        return self::$instance;
    }
 
    // Clone no permitido
    public function __clone()
    {
        trigger_error('Clone no se permite.', E_USER_ERROR);
    }
 
}
 
?>