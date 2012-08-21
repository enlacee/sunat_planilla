<?php
class ConfAfp {
   
    private $id_conf_afp;
    private $cod_regimen_pensionario;
    private $aporte_obligatorio;
    private $comision;
    private $prima_seguro;
    private $fecha;
    private $fecha_creacion;
    
    function __construct() {
     $this->id_conf_afp=null;
     $this->cod_regimen_pensionario=null;
     $this->aporte_obligatorio=null;
     $this->comision=null;
     $this->prima_seguro=null;
     $this->fecha=null;
     $this->fecha_creacion=null;
    }
    
    public function getId_conf_afp() {
        return $this->id_conf_afp;
    }

    public function setId_conf_afp($id_conf_afp) {
        $this->id_conf_afp = $id_conf_afp;
    }

    public function getCod_regimen_pensionario() {
        return $this->cod_regimen_pensionario;
    }

    public function setCod_regimen_pensionario($cod_regimen_pensionario) {
        $this->cod_regimen_pensionario = $cod_regimen_pensionario;
    }

    public function getAporte_obligatorio() {
        return $this->aporte_obligatorio;
    }

    public function setAporte_obligatorio($aporte_obligatorio) {
        $this->aporte_obligatorio = $aporte_obligatorio;
    }

    public function getComision() {
        return $this->comision;
    }

    public function setComision($comision) {
        $this->comision = $comision;
    }

    public function getPrima_seguro() {
        return $this->prima_seguro;
    }

    public function setPrima_seguro($prima_seguro) {
        $this->prima_seguro = $prima_seguro;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }


    
}

?>
