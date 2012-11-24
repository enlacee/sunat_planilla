<?php

class Pago {

    //put your code here
    private $id_pago;
    private $id_trabajador;
    private $id_etapa_pago;
    private $dia_laborado;
    /*private $dia_subsidiado;*/
    private $dia_nosubsidiado;
    private $dia_total;
    private $sueldo_base;
    private $sueldo;
    private $sueldo_vacacion;
    private $descuento;
    private $sueldo_neto;
    private $ordinario_hora;
    private $ordinario_min;
    private $sobretiempo_hora;
    private $sobretiempo_min;
    private $estado;
    private $descripcion;
    private $id_empresa_centro_costo;
    private $fecha_modificacion;
    private $fecha_creacion;
    private $fecha_fin_15;

    function __construct() {
     $this->id_pago=null;
     $this->id_trabajador=null;
     $this->id_etapa_pago=null;
     $this->dia_laborado=null;
     /*$this->dia_subsidiado=null;*/
     $this->dia_nosubsidiado=null;
     $this->dia_total=null;
     $this->sueldo_base=null;
     $this->sueldo=null;
     $this->sueldo_vacacion=null;
     $this->descuento=null;
     $this->sueldo_neto=null;
     $this->ordinario_hora=null;
     $this->ordinario_min=null;
     $this->sobretiempo_hora=null;
     $this->sobretiempo_min=null;
     $this->estado=null;
     $this->descripcion=null;
     $this->id_empresa_centro_costo=null;
     $this->fecha_modificacion=null;
     $this->fecha_creacion=null;
     $this->fecha_fin_15=null;
    }

    public function getId_pago() {
        return $this->id_pago;
    }

    public function setId_pago($id_pago) {
        $this->id_pago = $id_pago;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getId_etapa_pago() {
        return $this->id_etapa_pago;
    }

    public function setId_etapa_pago($id_etapa_pago) {
        $this->id_etapa_pago = $id_etapa_pago;
    }

    public function getDia_laborado() {
        return $this->dia_laborado;
    }

    public function setDia_laborado($dia_laborado) {
        $this->dia_laborado = $dia_laborado;
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

    public function getSueldo_vacacion() {
        return $this->sueldo_vacacion;
    }

    public function setSueldo_vacacion($sueldo_vacacion) {
        $this->sueldo_vacacion = $sueldo_vacacion;
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

    public function getId_empresa_centro_costo() {
        return $this->id_empresa_centro_costo;
    }

    public function setId_empresa_centro_costo($id_empresa_centro_costo) {
        $this->id_empresa_centro_costo = $id_empresa_centro_costo;
    }

    public function getFecha_modificacion() {
        return $this->fecha_modificacion;
    }

    public function setFecha_modificacion($fecha_modificacion) {
        $this->fecha_modificacion = $fecha_modificacion;
    }

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getFecha_fin_15() {
        return $this->fecha_fin_15;
    }

    public function setFecha_fin_15($fecha_fin_15) {
        $this->fecha_fin_15 = $fecha_fin_15;
    }


}
?>
