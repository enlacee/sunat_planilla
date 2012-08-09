<?php

class Adelanto {

    //put your code here
    private $id_adelanto;
    private $id_trabajador;
    private $cod_periodo_remuneracion;
    private $dia_total;
    private $dia_laborado;
    private $dia_nolaborado;
    private $valor;
    private $fecha_inicio;
    private $fecha_fin;
    private $fecha_creacion;

    public function __construct() {
        $this->id_adelanto = null;
        $this->id_trabajador = null;
        $this->cod_periodo_remuneracion = null;
        $this->dia_total = null;
        $this->dia_laborado = null;
        $this->dia_nolaborado = null;
        $this->valor = null;
        $this->fecha_inicio = null;
        $this->fecha_fin = null;
        $this->fecha_creacion = null;
    }

    public function getId_adelanto() {
        return $this->id_adelanto;
    }

    public function setId_adelanto($id_adelanto) {
        $this->id_adelanto = $id_adelanto;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getCod_periodo_remuneracion() {
        return $this->cod_periodo_remuneracion;
    }

    public function setCod_periodo_remuneracion($cod_periodo_remuneracion) {
        $this->cod_periodo_remuneracion = $cod_periodo_remuneracion;
    }

    public function getDia_total() {
        return $this->dia_total;
    }

    /*
      public function setDia_total($dia_total) {
      $this->dia_total = $dia_total;
      }
     */

// EDIT
    public function getDia_laborado() {
        $dia_total = (is_numeric($this->dia_total)) ? $this->dia_total : 0;
        $dia_nolaborado = ( is_numeric($this->dia_nolaborado)) ? $this->dia_nolaborado : 0;

        $rpta = $dia_total - $dia_nolaborado;
        return $rpta;
    }

    public function setDia_laborado($dia_laborado) {
        $this->dia_laborado = $dia_laborado;
    }

    public function getDia_nolaborado() {
        return $this->dia_nolaborado;
    }

    public function setDia_nolaborado($dia_nolaborado) {
        $this->dia_nolaborado = $dia_nolaborado;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
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

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

}

?>
