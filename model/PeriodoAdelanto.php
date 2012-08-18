<?php
class PeriodoAdelanto {
    //put your code here
    private $id_periodo_adelanto;
    private $id_trabajador;
    private $id_pdeclaracion;
    private $id_periodo_pago;
    private $dia_periodo_pago;
    private $dia_laborado;
    private $dia_subsidiado;
    private $dia_nosubsidiado;
    private $dia_total;
    private $sueldo_base;
    private $sueldo;
    private $descuento;
    private $sueldo_neto;
    private $fecha_inicio;
    private $fecha_fin;
    private $ordinario_hora;
    private $ordinario_min;
    private $sobretiempo_hora;
    private $sobretiempo_min;
    private $estado;
    private $descripcion;
    private $id_etapa_mes;
    
    
    function __construct() {
     $this->id_periodo_adelanto=null;
     $this->id_trabajador=null;
     $this->id_pdeclaracion=null;
     $this->id_periodo_pago=null;
     $this->dia_periodo_pago=null;
     $this->dia_laborado=null;
     $this->dia_subsidiado=null;
     $this->dia_nosubsidiado=null;
     $this->dia_total=null;
     $this->sueldo_base=null;
     $this->sueldo=null;
     $this->descuento=null;
     $this->sueldo_neto=null;
     $this->fecha_inicio=null;
     $this->fecha_fin=null;
     $this->ordinario_hora=null;
     $this->ordinario_min=null;
     $this->sobretiempo_hora=null;
     $this->sobretiempo_min=null;
     $this->estado=null;
     $this->descripcion=null;
     $this->id_etapa_mes=null;
    }
    public function getId_periodo_adelanto() {
        return $this->id_periodo_adelanto;
    }

    public function setId_periodo_adelanto($id_periodo_adelanto) {
        $this->id_periodo_adelanto = $id_periodo_adelanto;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getId_pdeclaracion() {
        return $this->id_pdeclaracion;
    }

    public function setId_pdeclaracion($id_pdeclaracion) {
        $this->id_pdeclaracion = $id_pdeclaracion;
    }

    public function getId_periodo_pago() {
        return $this->id_periodo_pago;
    }

    public function setId_periodo_pago($id_periodo_pago) {
        $this->id_periodo_pago = $id_periodo_pago;
    }

    public function getDia_periodo_pago() {
        return $this->dia_periodo_pago;
    }

    public function setDia_periodo_pago($dia_periodo_pago) {
        $this->dia_periodo_pago = $dia_periodo_pago;
    }

    public function getDia_laborado() {
        return $this->dia_laborado;
    }

    public function setDia_laborado($dia_laborado) {
        $this->dia_laborado = $dia_laborado;
    }

    public function getDia_subsidiado() {
        return $this->dia_subsidiado;
    }

    public function setDia_subsidiado($dia_subsidiado) {
        $this->dia_subsidiado = $dia_subsidiado;
    }

    public function getDia_nosubsidiado() {
        return $this->dia_nosubsidiado;
    }

    public function setDia_nosubsidiado($dia_nosubsidiado) {
        $this->dia_nosubsidiado = $dia_nosubsidiado;
    }

    public function getDia_total() {
        return $this->dia_total;
    }

    public function setDia_total($dia_total) {
        $this->dia_total = $dia_total;
    }

    public function getSueldo_base() {
        return $this->sueldo_base;
    }

    public function setSueldo_base($sueldo_base) {
        $this->sueldo_base = $sueldo_base;
    }

    public function getSueldo() {
        return $this->sueldo;
    }

    public function setSueldo($sueldo) {
        $this->sueldo = $sueldo;
    }

    public function getDescuento() {
        return $this->descuento;
    }

    public function setDescuento($descuento) {
        $this->descuento = $descuento;
    }

    public function getSueldo_neto() {
        return $this->sueldo_neto;
    }

    public function setSueldo_neto($sueldo_neto) {
        $this->sueldo_neto = $sueldo_neto;
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

    public function getOrdinario_hora() {
        return $this->ordinario_hora;
    }

    public function setOrdinario_hora($ordinario_hora) {
        $this->ordinario_hora = $ordinario_hora;
    }

    public function getOrdinario_min() {
        return $this->ordinario_min;
    }

    public function setOrdinario_min($ordinario_min) {
        $this->ordinario_min = $ordinario_min;
    }

    public function getSobretiempo_hora() {
        return $this->sobretiempo_hora;
    }

    public function setSobretiempo_hora($sobretiempo_hora) {
        $this->sobretiempo_hora = $sobretiempo_hora;
    }

    public function getSobretiempo_min() {
        return $this->sobretiempo_min;
    }

    public function setSobretiempo_min($sobretiempo_min) {
        $this->sobretiempo_min = $sobretiempo_min;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getId_etapa_mes() {
        return $this->id_etapa_mes;
    }

    public function setId_etapa_mes($id_etapa_mes) {
        $this->id_etapa_mes = $id_etapa_mes;
    }



}

?>
