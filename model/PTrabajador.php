<?php

class Ptrabajador {

    private $id_ptrabajador;
    private $id_trabajador;
    private $asignacion_familiar;
    private $para_ti_familia;
    private $para_ti_familia_op;
    private $aporta_essalud_vida;
    private $aporta_asegura_tu_pension;
    private $domiciliado;

    function __construct() {
        $this->id_ptrabajador = null;
        $this->id_trabajador = null;
        $this->asignacion_familiar=null;
        $this->para_ti_familia = null;
        $this->para_ti_familia_op = null;
        $this->aporta_essalud_vida = null;
        $this->aporta_asegura_tu_pension = null;
        $this->domiciliado = null;
    }
    public function getId_ptrabajador() {
        return $this->id_ptrabajador;
    }

    public function setId_ptrabajador($id_ptrabajador) {
        $this->id_ptrabajador = $id_ptrabajador;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getAsignacion_familiar() {
        return $this->asignacion_familiar;
    }

    public function setAsignacion_familiar($asignacion_familiar) {
        $this->asignacion_familiar = $asignacion_familiar;
    }

    public function getPara_ti_familia() {
        return $this->para_ti_familia;
    }

    public function setPara_ti_familia($para_ti_familia) {
        $this->para_ti_familia = $para_ti_familia;
    }

    public function getPara_ti_familia_op() {
        return $this->para_ti_familia_op;
    }

    public function setPara_ti_familia_op($para_ti_familia_op) {
        $this->para_ti_familia_op = $para_ti_familia_op;
    }

    public function getAporta_essalud_vida() {
        return $this->aporta_essalud_vida;
    }

    public function setAporta_essalud_vida($aporta_essalud_vida) {
        $this->aporta_essalud_vida = $aporta_essalud_vida;
    }

    public function getAporta_asegura_tu_pension() {
        return $this->aporta_asegura_tu_pension;
    }

    public function setAporta_asegura_tu_pension($aporta_asegura_tu_pension) {
        $this->aporta_asegura_tu_pension = $aporta_asegura_tu_pension;
    }

    public function getDomiciliado() {
        return $this->domiciliado;
    }

    public function setDomiciliado($domiciliado) {
        $this->domiciliado = $domiciliado;
    }



}

?>
