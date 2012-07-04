<?php

class EmpleadorDestaque extends EmpleadorDestaqueYourSelf{
    
    private $id_empleador_destaque;
    
    function __construct() {
        $this->id_empleador_destaque = null;
        $this->id_empleador =null;
        $this->id_empleador_maestro =null;
        $this->estado=null;
    }
    
    
    public function getId_empleador_destaque() {
        return $this->id_empleador_destaque;
    }

    public function setId_empleador_destaque($id_empleador_destaque) {
        $this->id_empleador_destaque = $id_empleador_destaque;
    }


    
}

?>
