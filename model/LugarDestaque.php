<?php


class LugarDestaque {

    private $id_lugar_destaque;
    private $id_personal_tercero;
    private $id_establecimiento;
    

    function __construct() {
        
        $this->id_lugar_destaque=null;
        $this->id_personal_tercero = null;
        $this->id_establecimiento = null;
    }
    
    public function getId_lugar_destaque() {
        return $this->id_lugar_destaque;
    }

    public function setId_lugar_destaque($id_lugar_destaque) {
        $this->id_lugar_destaque = $id_lugar_destaque;
    }

    public function getId_personal_tercero() {
        return $this->id_personal_tercero;
    }

    public function setId_personal_tercero($id_personal_tercero) {
        $this->id_personal_tercero = $id_personal_tercero;
    }

    public function getId_establecimiento() {
        return $this->id_establecimiento;
    }

    public function setId_establecimiento($id_establecimiento) {
        $this->id_establecimiento = $id_establecimiento;
    }



    
    
    
}

?>
