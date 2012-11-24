<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Trabajador
 *
 * @author conta 1
 */
class Trabajador {
    //put your code here
    private $id_trabajador;
    private $id_persona;
    private $cod_regimen_laboral;
    private $cod_nivel_educativo;
    private $cod_categorias_ocupacionales;
    private $id_ocupacion_2;
    private $cod_ocupacion;
    private $cod_tipo_contrato;
    private $cod_tipo_pago;
    private $cod_periodo_remuneracion;    
    private $monto_remuneracion;
    private $id_establecimiento;
    private $jornada_laboral;
    private $situacion_especial;
    private $discapacitado;
    private $sindicalizado;
    private $percibe_renta_5ta_exonerada;
    private $aplicar_convenio_doble_inposicion;
    private $cod_convenio;
    private $cod_situacion;
    private $estado;
    private $id_empresa_centro_costo;
    
    function __construct() {
     $this->id_trabajador=null;
     $this->id_persona=null;
     $this->cod_regimen_laboral=0;
     $this->cod_categorias_ocupacionales=0;
     $this->cod_nivel_educativo=0;
     $this->id_ocupacion_2=0;
     $this->cod_ocupacion =0;
     
     $this->cod_tipo_contrato=0;
     $this->cod_tipo_pago=0;
     $this->cod_periodo_remuneracion=0;     
     $this->monto_remuneracion=0;
     $this->id_establecimiento=0;
     $this->jornada_laboral=0;
     $this->situacion_especial=0;
     $this->discapacitado=0;
     $this->sindicalizado=0;
     $this->percibe_renta_5ta_exonerada=0;
     $this->aplicar_convenio_doble_inposicion=0;
     $this->cod_convenio = null;
     $this->cod_situacion =null;
     $this->estado = null;
     $this->id_empresa_centro_costo = null;
    }
    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getId_persona() {
        return $this->id_persona;
    }

    public function setId_persona($id_persona) {
        $this->id_persona = $id_persona;
    }

    public function getCod_regimen_laboral() {
        return $this->cod_regimen_laboral;
    }

    public function setCod_regimen_laboral($cod_regimen_laboral) {
        $this->cod_regimen_laboral = $cod_regimen_laboral;
    }

    public function getCod_nivel_educativo() {
        return $this->cod_nivel_educativo;
    }

    public function setCod_nivel_educativo($cod_nivel_educativo) {
        $this->cod_nivel_educativo = $cod_nivel_educativo;
    }

    public function getCod_categorias_ocupacionales() {
        return $this->cod_categorias_ocupacionales;
    }

    public function setCod_categorias_ocupacionales($cod_categorias_ocupacionales) {
        $this->cod_categorias_ocupacionales = $cod_categorias_ocupacionales;
    }

    public function getId_ocupacion_2() {
        return $this->id_ocupacion_2;
    }

    public function setId_ocupacion_2($id_ocupacion_2) {
        $this->id_ocupacion_2 = $id_ocupacion_2;
    }

    public function getCod_ocupacion() {
        return $this->cod_ocupacion;
    }

    public function setCod_ocupacion($cod_ocupacion) {
        $this->cod_ocupacion = $cod_ocupacion;
    }

    public function getCod_tipo_contrato() {
        return $this->cod_tipo_contrato;
    }

    public function setCod_tipo_contrato($cod_tipo_contrato) {
        $this->cod_tipo_contrato = $cod_tipo_contrato;
    }

    public function getCod_tipo_pago() {
        return $this->cod_tipo_pago;
    }

    public function setCod_tipo_pago($cod_tipo_pago) {
        $this->cod_tipo_pago = $cod_tipo_pago;
    }

    public function getCod_periodo_remuneracion() {
        return $this->cod_periodo_remuneracion;
    }

    public function setCod_periodo_remuneracion($cod_periodo_remuneracion) {
        $this->cod_periodo_remuneracion = $cod_periodo_remuneracion;
    }

    public function getMonto_remuneracion() {
        return $this->monto_remuneracion;
    }

    public function setMonto_remuneracion($monto_remuneracion) {
        $this->monto_remuneracion = $monto_remuneracion;
    }

    public function getId_establecimiento() {
        return $this->id_establecimiento;
    }

    public function setId_establecimiento($id_establecimiento) {
        $this->id_establecimiento = $id_establecimiento;
    }

    public function getJornada_laboral() {
        return $this->jornada_laboral;
    }

    public function setJornada_laboral($jornada_laboral) {
        $this->jornada_laboral = $jornada_laboral;
    }

    public function getSituacion_especial() {
        return $this->situacion_especial;
    }

    public function setSituacion_especial($situacion_especial) {
        $this->situacion_especial = $situacion_especial;
    }

    public function getDiscapacitado() {
        return $this->discapacitado;
    }

    public function setDiscapacitado($discapacitado) {
        $this->discapacitado = $discapacitado;
    }

    public function getSindicalizado() {
        return $this->sindicalizado;
    }

    public function setSindicalizado($sindicalizado) {
        $this->sindicalizado = $sindicalizado;
    }

    public function getPercibe_renta_5ta_exonerada() {
        return $this->percibe_renta_5ta_exonerada;
    }

    public function setPercibe_renta_5ta_exonerada($percibe_renta_5ta_exonerada) {
        $this->percibe_renta_5ta_exonerada = $percibe_renta_5ta_exonerada;
    }

    public function getAplicar_convenio_doble_inposicion() {
        return $this->aplicar_convenio_doble_inposicion;
    }

    public function setAplicar_convenio_doble_inposicion($aplicar_convenio_doble_inposicion) {
        $this->aplicar_convenio_doble_inposicion = $aplicar_convenio_doble_inposicion;
    }

    public function getCod_convenio() {
        return $this->cod_convenio;
    }

    public function setCod_convenio($cod_convenio) {
        $this->cod_convenio = $cod_convenio;
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

    public function getId_empresa_centro_costo() {
        return $this->id_empresa_centro_costo;
    }

    public function setId_empresa_centro_costo($id_empresa_centro_costo) {
        $this->id_empresa_centro_costo = $id_empresa_centro_costo;
    }

    
}


?>
