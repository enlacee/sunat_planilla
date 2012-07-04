<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetallePeriodoFormativo
 *
 * @author conta 1
 */
class DetallePeriodoFormativo {
    //put your code here
    
    private $id_detalle_periodo_formativo;
    private $id_personal_formacion_laboral;
    private $fecha_inicio;
    private $fecha_fin;
    
    function __construct() {
        $this->id_detalle_periodo_formativo=null;
        $this->id_personal_formacion_laboral=null;
        $this->fecha_inicio=null;
        $this->fecha_fin=null;
        ;
    }
    
    public function getId_detalle_periodo_formativo() {
        return $this->id_detalle_periodo_formativo;
    }

    public function setId_detalle_periodo_formativo($id_detalle_periodo_formativo) {
        $this->id_detalle_periodo_formativo = $id_detalle_periodo_formativo;
    }

    public function getId_personal_formacion_laboral() {
        return $this->id_personal_formacion_laboral;
    }

    public function setId_personal_formacion_laboral($id_personal_formacion_laboral) {
        $this->id_personal_formacion_laboral = $id_personal_formacion_laboral;
    }

    public function getFecha_inicio() {
        return $this->fecha_inicio;
    }

    public function setFecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function getFecha_fin() {
        return $this->fecha_fin;
    }

    public function setFecha_fin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }


    
    
}

?>
