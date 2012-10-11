<?php
class RegistroPorConcepto {
    //put your code here
    private $id_registro_por_concepto;
    private $id_pdeclaracion;
    private $id_trabajador;
    private $cod_detalle_concepto;
    private $valor;
    private $estado;
    private $fecha_creacion;
    
    
    function __construct() {
     $this->id_registro_por_concepto=null;
     $this->id_pdeclaracion=null;
     $this->id_trabajador=null;
     $this->cod_detalle_concepto=null;
     $this->valor=null;
     $this->estado=null;
     $this->fecha_creacion=null;
    }
    
    public function getId_registro_por_concepto() {
        return $this->id_registro_por_concepto;
    }

    public function setId_registro_por_concepto($id_registro_por_concepto) {
        $this->id_registro_por_concepto = $id_registro_por_concepto;
    }

    public function getId_pdeclaracion() {
        return $this->id_pdeclaracion;
    }

    public function setId_pdeclaracion($id_pdeclaracion) {
        $this->id_pdeclaracion = $id_pdeclaracion;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getCod_detalle_concepto() {
        return $this->cod_detalle_concepto;
    }

    public function setCod_detalle_concepto($cod_detalle_concepto) {
        $this->cod_detalle_concepto = $cod_detalle_concepto;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }


    
    

    
    
    

    
}

?>
