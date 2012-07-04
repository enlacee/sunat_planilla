<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegimenSalud
 *
 * @author conta 1
 */
class RegimenSalud {
    //put your code here
    private $id_detalle_regimen_salud;
    private $id_trabajador;
    private $cod_regimen_aseguramiento;
    private $fecha_inicio;
    private $fecha_fin;
    
    function __construct() {
        $this->id_detalle_regimen_salud = null;
        $this->id_trabajador = null;
        $this->cod_regimen_aseguramiento = null;
        $this->fecha_inicio=null;
        $this->fecha_fin =null;
    }
    
    
    public function getId_detalle_regimen_salud() {
        return $this->id_detalle_regimen_salud;
    }

    public function setId_detalle_regimen_salud($id_detalle_regimen_salud) {
        $this->id_detalle_regimen_salud = $id_detalle_regimen_salud;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getCod_regimen_aseguramiento() {
        return $this->cod_regimen_aseguramiento;
    }

    public function setCod_regimen_aseguramiento($cod_regimen_aseguramiento) {
        $this->cod_regimen_aseguramiento = $cod_regimen_aseguramiento;
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
