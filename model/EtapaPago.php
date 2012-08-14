<?php

class EtapaPago {

    //put your code here
    private $id_etapa_pago;
    private $id_pdeclaracion;
    private $cod_periodo_remuneracion;
    private $fecha_inicio;
    private $fecha_fin;
    private $fecha_creacion;
    private $tipo;
    private $glosa;

    public function __construct() {
        $this->id_etapa_pago = null;
        $this->id_pdeclaracion = null;
        $this->cod_periodo_remuneracion = null;
        $this->fecha_inicio = null;
        $this->fecha_fin = null;
        $this->fecha_creacion = null;
        $this->tipo = null;
        $this->glosa = null;
    }
    public function getId_etapa_pago() {
        return $this->id_etapa_pago;
    }

    public function setId_etapa_pago($id_etapa_pago) {
        $this->id_etapa_pago = $id_etapa_pago;
    }

    public function getId_pdeclaracion() {
        return $this->id_pdeclaracion;
    }

    public function setId_pdeclaracion($id_pdeclaracion) {
        $this->id_pdeclaracion = $id_pdeclaracion;
    }

    public function getCod_periodo_remuneracion() {
        return $this->cod_periodo_remuneracion;
    }

    public function setCod_periodo_remuneracion($cod_periodo_remuneracion) {
        $this->cod_periodo_remuneracion = $cod_periodo_remuneracion;
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

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getGlosa() {
        return $this->glosa;
    }

    public function setGlosa($glosa) {
        $this->glosa = $glosa;
    }



}

?>
