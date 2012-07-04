<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pensionista
 *
 * @author conta 1
 */
class Pensionista {

    //put your code here
    private $id_pensionista;
    private $id_persona;
    private $cod_tipo_trabajador;
    private $cod_regimen_pensionario;
    private $cuspp;
    private $cod_tipo_pago;
    private $cod_situacion;
    private $estado;

    function __construct() {
        $this->id_pensionista = null;
        $this->id_persona = null;
        $this->cod_tipo_trabajador = 0;
        $this->cod_regimen_pensionario = 0;
        $this->cuspp=null;
        $this->cod_tipo_pago = 0;
        $this->cod_situacion = 0;
        $this->estado = 0;
    }

    public function getId_pensionista() {
        return $this->id_pensionista;
    }

    public function setId_pensionista($id_pensionista) {
        $this->id_pensionista = $id_pensionista;
    }

    public function getId_persona() {
        return $this->id_persona;
    }

    public function setId_persona($id_persona) {
        $this->id_persona = $id_persona;
    }

    public function getCod_tipo_trabajador() {
        return $this->cod_tipo_trabajador;
    }

    public function setCod_tipo_trabajador($cod_tipo_trabajador) {
        $this->cod_tipo_trabajador = $cod_tipo_trabajador;
    }

    public function getCod_regimen_pensionario() {
        return $this->cod_regimen_pensionario;
    }

    public function setCod_regimen_pensionario($cod_regimen_pensionario) {
        $this->cod_regimen_pensionario = $cod_regimen_pensionario;
    }

    public function getCuspp() {
        return $this->cuspp;
    }

    public function setCuspp($cuspp) {
        $this->cuspp = $cuspp;
    }

    public function getCod_tipo_pago() {
        return $this->cod_tipo_pago;
    }

    public function setCod_tipo_pago($cod_tipo_pago) {
        $this->cod_tipo_pago = $cod_tipo_pago;
    }

    public function getCod_situacion() {
        return $this->cod_situacion;
    }

    public function setCod_situacion($cod_situacion) {
        $this->cod_situacion = $cod_situacion;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }


    
}

?>
