<?php

class Pago {

    //put your code here
    private $id_pago;
    private $id_etapa_pago;
    private $id_trabajador;
    private $id_empresa_centro_costo;
   
    private $valor;
    private $descuento;
    private $valor_total;
    private $descripcion;
    private $dia_total;
    private $dia_nosubsidiado;
    private $dia_laborado;
    private $ordinario_hora;
    private $ordinario_min;
    private $sobretiempo_hora;
    private $sobretiempo_min;
    private $estado;

    function __construct() {
        $this->id_pago=null;
        $this->id_etapa_pago=null;
        $this->id_trabajador = null;
        $this->id_empresa_centro_costo=null;
  
        $this->valor=null;
        $this->descuento=null;
        $this->valor_total=null;
        $this->descripcion=null;
        $this->dia_total=null;
        $this->dia_nosubsidiado=null;
        $this->dia_laborado=null;
        $this->ordinario_hora=null;
        $this->ordinario_min=null;
        $this->sobretiempo_hora=null;
        $this->sobretiempo_min=null;
        $this->estado=null;
    }
    
    public function getId_pago() {
        return $this->id_pago;
    }

    public function setId_pago($id_pago) {
        $this->id_pago = $id_pago;
    }

    public function getId_etapa_pago() {
        return $this->id_etapa_pago;
    }

    public function setId_etapa_pago($id_etapa_pago) {
        $this->id_etapa_pago = $id_etapa_pago;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getId_empresa_centro_costo() {
        return $this->id_empresa_centro_costo;
    }

    public function setId_empresa_centro_costo($id_empresa_centro_costo) {
        $this->id_empresa_centro_costo = $id_empresa_centro_costo;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getDescuento() {
        return $this->descuento;
    }

    public function setDescuento($descuento) {
        $this->descuento = $descuento;
    }

    public function getValor_total() {
        return $this->valor_total;
    }

    public function setValor_total($valor_total) {
        $this->valor_total = $valor_total;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getDia_total() {
        return $this->dia_total;
    }

    public function setDia_total($dia_total) {
        $this->dia_total = $dia_total;
    }

    public function getDia_nosubsidiado() {
        return $this->dia_nosubsidiado;
    }

    public function setDia_nosubsidiado($dia_nosubsidiado) {
        $this->dia_nosubsidiado = $dia_nosubsidiado;
    }

    public function getDia_laborado() {
        return $this->dia_laborado;
    }

    public function setDia_laborado($dia_laborado) {
        $this->dia_laborado = $dia_laborado;
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


   

}

?>
