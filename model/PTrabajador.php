<?php

class PTrabajador {
    //put your code here
    private $id_ptrabajador;
    private $id_trabajador;
    private $id_pdeclaracion;
    private $aporta_essalud_vida;
    private $aporta_asegura_tu_pension;
    private $domiciliado;
    private $ingreso_5ta_categoria;

    public function __construct() {
        $this->id_ptrabajador = null;
        $this->id_trabajador = null;
        $this->id_pdeclaracion = null;
        $this->aporta_essalud_vida = null;
        $this->aporta_asegura_tu_pension = null;
        $this->domiciliado = null;
        $this->ingreso_5ta_categoria = null;
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

    public function getId_pdeclaracion() {
        return $this->id_pdeclaracion;
    }

    public function setId_pdeclaracion($id_pdeclaracion) {
        $this->id_pdeclaracion = $id_pdeclaracion;
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

    public function getIngreso_5ta_categoria() {
        return $this->ingreso_5ta_categoria;
    }

    public function setIngreso_5ta_categoria($ingreso_5ta_categoria) {
        $this->ingreso_5ta_categoria = $ingreso_5ta_categoria;
    }


    

}

?>
