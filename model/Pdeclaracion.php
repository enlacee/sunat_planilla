<?php

class Pdeclaracion {

    //put your code here
    private $id_pdeclaracion;
    private $id_empleador_maestro;
    private $periodo;
    private $fecha_creacion;
    private $fecha_modificacion;
    private $estado;

    function __construct() {
        $this->id_pdeclaracion = null;
        $this->id_empleador_maestro = null;
        $this->periodo = null;
        $this->fecha_creacion = null;
        $this->fecha_modificacion = null;
        $this->estado = null;
    }
    
    
    public function getId_pdeclaracion() {
        return $this->id_pdeclaracion;
    }

    public function setId_pdeclaracion($id_pdeclaracion) {
        $this->id_pdeclaracion = $id_pdeclaracion;
    }

    public function getId_empleador_maestro() {
        return $this->id_empleador_maestro;
    }

    public function setId_empleador_maestro($id_empleador_maestro) {
        $this->id_empleador_maestro = $id_empleador_maestro;
    }

    public function getPeriodo() {
        return $this->periodo;
    }

    public function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getFecha_modificacion() {
        return $this->fecha_modificacion;
    }

    public function setFecha_modificacion($fecha_modificacion) {
        $this->fecha_modificacion = $fecha_modificacion;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }



}

?>
