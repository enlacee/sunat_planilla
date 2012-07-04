<?php

class Establecimiento {

    //put your code here
    private $id_establecimiento;
    private $cod_establecimiento;
    private $id_empleador;
    private $id_tipo_establecimiento;
    private $actividad_riesgo_sctr;
    private $fecha_creacion;

    function __construct() {
        $this->id_establecimiento = null;
        $this->cod_establecimiento = null;
        $this->id_empleador = null;
        $this->id_tipo_establecimiento = null;
        $this->actividad_riesgo_sctr = null;  //fuck!!!!
        $this->fecha_creacion = null;
    }
    
    public function getId_establecimiento() {
        return $this->id_establecimiento;
    }

    public function setId_establecimiento($id_establecimiento) {
        $this->id_establecimiento = $id_establecimiento;
    }

    public function getCod_establecimiento() {
        return $this->cod_establecimiento;
    }

    public function setCod_establecimiento($cod_establecimiento) {
        $this->cod_establecimiento = $cod_establecimiento;
    }

    public function getId_empleador() {
        return $this->id_empleador;
    }

    public function setId_empleador($id_empleador) {
        $this->id_empleador = $id_empleador;
    }

    public function getId_tipo_establecimiento() {
        return $this->id_tipo_establecimiento;
    }

    public function setId_tipo_establecimiento($id_tipo_establecimiento) {
        $this->id_tipo_establecimiento = $id_tipo_establecimiento;
    }

    public function getActividad_riesgo_sctr() {
        return $this->actividad_riesgo_sctr;
    }

    public function setActividad_riesgo_sctr($actividad_riesgo_sctr) {
        $this->actividad_riesgo_sctr = $actividad_riesgo_sctr;
    }

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

 

}

?>
