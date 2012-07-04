<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetalleEstablecimientoFormacion
 *
 * @author Anibal
 */
class DetalleEstablecimientoFormacion {
    //put your code here
    
    private $id_detalle_establecimiento_formacion;
    private $id_personal_formacion_laboral;
    private $id_establecimiento;
    
    function __construct() {
        $this->id_detalle_establecimiento_formacion=null;
        $this->id_personal_formacion_laboral=null;
        $this->id_establecimiento=null;
    }
    
    public function getId_detalle_establecimiento_formacion() {
        return $this->id_detalle_establecimiento_formacion;
    }

    public function setId_detalle_establecimiento_formacion($id_detalle_establecimiento_formacion) {
        $this->id_detalle_establecimiento_formacion = $id_detalle_establecimiento_formacion;
    }

    public function getId_personal_formacion_laboral() {
        return $this->id_personal_formacion_laboral;
    }

    public function setId_personal_formacion_laboral($id_personal_formacion_laboral) {
        $this->id_personal_formacion_laboral = $id_personal_formacion_laboral;
    }

    public function getId_establecimiento() {
        return $this->id_establecimiento;
    }

    public function setId_establecimiento($id_establecimiento) {
        $this->id_establecimiento = $id_establecimiento;
    }


    
    
}

?>
