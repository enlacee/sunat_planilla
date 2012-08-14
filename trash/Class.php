<?php

class Web{
    
    private $titulo;
    
    
    function __construct() {
       $titulo = "nullO";
    }
    public function getTitulo(){
        return $this->titulo;
    }
    public function setTitulo(){
        return $this->titulo;
    }
    
    
    //heree
    public function getNombreAutor(){
        return "ANIBAL COPITAN NORABUENA";
    }    
    public static  function getName(){
        return time();
    }
    
}


echo Web::getName();
echo "<hr>";



?>
