<?php

class DiaNoSubsidiado {

    //put your code here
    private $id_dia_nosubsidiado;
    private $id_trabajador_pdeclaracion;
    private $cantidad_dia;
    private $cod_tipo_suspen_relacion_laboral;
    private $estado;
    private $fecha_inicio;
    private $fecha_fin;

    function __construct() {
        $this->id_dia_nosubsidiado = null;
        $this->id_trabajador_pdeclaracion = null;
        $this->cantidad_dia = null;
        $this->cod_tipo_suspen_relacion_laboral = null;
        $this->estado = null;
        $this->fecha_inicio=null;
        $this->fecha_fin = null;
    }
    
    public function getId_dia_nosubsidiado() {
        return $this->id_dia_nosubsidiado;
    }

    public function setId_dia_nosubsidiado($id_dia_nosubsidiado) {
        $this->id_dia_nosubsidiado = $id_dia_nosubsidiado;
    }

    public function getId_trabajador_pdeclaracion() {
        return $this->id_trabajador_pdeclaracion;
    }

    public function setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion) {
        $this->id_trabajador_pdeclaracion = $id_trabajador_pdeclaracion;
    }

    public function getCantidad_dia() {
        return $this->cantidad_dia;
    }

    public function setCantidad_dia($cantidad_dia) {
        $this->cantidad_dia = $cantidad_dia;
    }

    public function getCod_tipo_suspen_relacion_laboral() {
        return $this->cod_tipo_suspen_relacion_laboral;
    }

    public function setCod_tipo_suspen_relacion_laboral($cod_tipo_suspen_relacion_laboral) {
        $this->cod_tipo_suspen_relacion_laboral = $cod_tipo_suspen_relacion_laboral;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
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
