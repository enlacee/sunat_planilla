<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetalleEstablecimientoVinculado
 *
 * @author conta 1
 */
class EstablecimientoVinculado {
    //put your code here
    
    private $id_establecimiento_vinculado;
    private $id_empleador_destaque;
    private $id_establecimiento;
    private $realizan_trabajo_de_riesgo;
    Private $estado;
    
    
    function __construct() {
     $this->id_establecimiento_vinculado=null;
     $this->id_empleador_destaque=null;
     $this->id_establecimiento=null;
     $this->realizan_trabajo_de_riesgo=null;
     $this->estado=null;
    }
    
    public function getId_establecimiento_vinculado() {
        return $this->id_establecimiento_vinculado;
    }

    public function setId_establecimiento_vinculado($id_establecimiento_vinculado) {
        $this->id_establecimiento_vinculado = $id_establecimiento_vinculado;
    }

    public function getId_empleador_destaque() {
        return $this->id_empleador_destaque;
    }

    public function setId_empleador_destaque($id_empleador_destaque) {
        $this->id_empleador_destaque = $id_empleador_destaque;
    }

    public function getId_establecimiento() {
        return $this->id_establecimiento;
    }

    public function setId_establecimiento($id_establecimiento) {
        $this->id_establecimiento = $id_establecimiento;
    }

    public function getRealizan_trabajo_de_riesgo() {
        return $this->realizan_trabajo_de_riesgo;
    }

    public function setRealizan_trabajo_de_riesgo($realizan_trabajo_de_riesgo) {
        $this->realizan_trabajo_de_riesgo = $realizan_trabajo_de_riesgo;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }


    

}

?>
