<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetalleEstablecimiento
 *
 * @author conta 1
 */
final class DetalleEstablecimiento {
    //put your code here

    private $id_detalle_establecimiento;
    private $id_trabajador;
    private $id_establecimiento;    
    
    function __construct() {
        $this->id_detalle_establecimiento = null;
        $this->id_trabajador=null;
        $this->id_establecimiento =null;
    }
    
    
    public function getId_detalle_establecimiento() {
        return $this->id_detalle_establecimiento;
    }

    public function setId_detalle_establecimiento($id_detalle_establecimiento) {
        $this->id_detalle_establecimiento = $id_detalle_establecimiento;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getId_establecimiento() {
        return $this->id_establecimiento;
    }

    public function setId_establecimiento($id_establecimiento) {
        $this->id_establecimiento = $id_establecimiento;
    }


}

?>
