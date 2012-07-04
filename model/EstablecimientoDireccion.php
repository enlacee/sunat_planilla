<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonaDireccion
 *
 * @author conta 1
 */
class EstablecimientoDireccion extends Direccion {

    //put your code here
    private $id_establecimiento_direccion;
    private $id_establecimiento;
    private $estado_direccion;

    function __construct() {
        parent::__construct();        
        $this->id_establecimiento_direccion = null;
        $this->id_establecimiento = null;
        $this->estado_direccion = null;
    }
    
    public function getId_establecimiento_direccion() {
        return $this->id_establecimiento_direccion;
    }

    public function setId_establecimiento_direccion($id_establecimiento_direccion) {
        $this->id_establecimiento_direccion = $id_establecimiento_direccion;
    }

    public function getId_establecimiento() {
        return $this->id_establecimiento;
    }

    public function setId_establecimiento($id_establecimiento) {
        $this->id_establecimiento = $id_establecimiento;
    }

    public function getEstado_direccion() {
        return $this->estado_direccion;
    }

    public function setEstado_direccion($estado_direccion) {
        $this->estado_direccion = $estado_direccion;
    }



  
}

?>
