<?php

class DetalleRegimenPensionario {

    private $id_detalle_regimen_pensionario;
    private $id_trabajador;
    private $cod_regimen_pensionario;
    private $CUSPP;
    private $fecha_inicio;
    private $fecha_fin;

    function __construct() {
        $this->id_detalle_regimen_pensionario=null;
        $this->id_trabajador=null;
        $this->cod_regimen_pensionario=null;
        $this->CUSPP=null;
        $this->fecha_inicio=null;
        $this->fecha_fin=null;
    }
    
    


    public function getId_detalle_regimen_pensionario() {
        return $this->id_detalle_regimen_pensionario;
    }

    public function setId_detalle_regimen_pensionario($id_detalle_regimen_pensionario) {
        $this->id_detalle_regimen_pensionario = $id_detalle_regimen_pensionario;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getCod_regimen_pensionario() {
        return $this->cod_regimen_pensionario;
    }

    public function setCod_regimen_pensionario($cod_regimen_pensionario) {
        $this->cod_regimen_pensionario = $cod_regimen_pensionario;
    }

    public function getCUSPP() {
        return $this->CUSPP;
    }

    public function setCUSPP($CUSPP) {
        $this->CUSPP = $CUSPP;
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
