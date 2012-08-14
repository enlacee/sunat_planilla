<?php

class Adelanto {

    //put your code here
    private $id_adelanto;
    private $id_declaracion;
    private $id_trabajador;
    private $cod_periodo_remuneracion;
    private $valor;
    private $fecha_inicio;
    private $fecha_fin;
    private $fecha_creacion;
    private $id_empresa_centro_costo;

    public function __construct() {
        $this->id_adelanto = null;
        $this->id_declaracion = null;
        $this->id_trabajador = null;
        $this->cod_periodo_remuneracion = null;
        $this->glosa = null;
        $this->valor = null;
        $this->fecha_inicio = null;
        $this->fecha_fin = null;
        $this->fecha_creacion = null;
        $this->id_empresa_centro_costo = null;
    }

    public function getId_adelanto() {
        return $this->id_adelanto;
    }

    public function setId_adelanto($id_adelanto) {
        $this->id_adelanto = $id_adelanto;
    }

    public function getId_declaracion() {
        return $this->id_declaracion;
    }

    public function setId_declaracion($id_declaracion) {
        $this->id_declaracion = $id_declaracion;
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

    public function getId_empresa_centro_costo() {
        return $this->id_empresa_centro_costo;
    }

    public function setId_empresa_centro_costo($id_empresa_centro_costo) {
        $this->id_empresa_centro_costo = $id_empresa_centro_costo;
    }

}

?>
