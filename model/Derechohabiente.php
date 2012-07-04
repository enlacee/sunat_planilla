<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of derechoHabientes
 *
 * @author conta 1
 */
class Derechohabiente {

    //put your code here
    private $id_derechohabiente;
    private $id_persona;
    private $cod_tipo_documento;
    private $cod_pais_emisor_documentos;
    private $cod_vinculo_familiar;
    private $cod_documento_vinculo_familiar;
    private $num_documento;
    private $fecha_nacimiento;
    private $apellido_paterno;
    private $apellido_materno;
    private $nombres;
    private $sexo;
    private $id_estado_civil;
    private $vf_num_documento;
    private $vf_mes_concepcion;
    private $cod_telefono_codigo_nacional;
    private $telefono;
    private $correo;
    private $cod_motivo_baja_derechohabiente;
    private $cod_situacion;
    private $estado;
    private $fecha_creacion;
    private $fecha_baja;
    



    function __construct() {
        $this->id_derechohabiente = null;
        $this->id_persona = null;
        $this->cod_tipo_documento = null;
        $this->cod_pais_emisor_documentos = null;
        $this->cod_vinculo_familiar = null;
        $this->cod_documento_vinculo_familiar = null;
        $this->num_documento = null;
        $this->fecha_nacimiento = null;
        $this->apellido_paterno = null;
        $this->apellido_materno = null;
        $this->nombres = null;
        $this->sexo = null;
        $this->id_estado_civil=null;
        $this->vf_num_documento = null;
        $this->vf_mes_concepcion = null;
        $this->cod_telefono_codigo_nacional = null;
        $this->telefono = null;
        $this->correo = null;
        $this->cod_motivo_baja_derechohabiente = null;
        $this->cod_situacion = null;
        $this->estado = null;
        $this->fecha_creacion=null;
        $this->fecha_baja = null;
    }
    
    public function getId_derechohabiente() {
        return $this->id_derechohabiente;
    }

    public function setId_derechohabiente($id_derechohabiente) {
        $this->id_derechohabiente = $id_derechohabiente;
    }

    public function getId_persona() {
        return $this->id_persona;
    }

    public function setId_persona($id_persona) {
        $this->id_persona = $id_persona;
    }

    public function getCod_tipo_documento() {
        return $this->cod_tipo_documento;
    }

    public function setCod_tipo_documento($cod_tipo_documento) {
        $this->cod_tipo_documento = $cod_tipo_documento;
    }

    public function getCod_pais_emisor_documentos() {
        return $this->cod_pais_emisor_documentos;
    }

    public function setCod_pais_emisor_documentos($cod_pais_emisor_documentos) {
        $this->cod_pais_emisor_documentos = $cod_pais_emisor_documentos;
    }

    public function getCod_vinculo_familiar() {
        return $this->cod_vinculo_familiar;
    }

    public function setCod_vinculo_familiar($cod_vinculo_familiar) {
        $this->cod_vinculo_familiar = $cod_vinculo_familiar;
    }

    public function getCod_documento_vinculo_familiar() {
        return $this->cod_documento_vinculo_familiar;
    }

    public function setCod_documento_vinculo_familiar($cod_documento_vinculo_familiar) {
        $this->cod_documento_vinculo_familiar = $cod_documento_vinculo_familiar;
    }

    public function getNum_documento() {
        return $this->num_documento;
    }

    public function setNum_documento($num_documento) {
        $this->num_documento = $num_documento;
    }

    public function getFecha_nacimiento() {
        return $this->fecha_nacimiento;
    }

    public function setFecha_nacimiento($fecha_nacimiento) {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function getApellido_paterno() {
        return $this->apellido_paterno;
    }

    public function setApellido_paterno($apellido_paterno) {
        $this->apellido_paterno = $apellido_paterno;
    }

    public function getApellido_materno() {
        return $this->apellido_materno;
    }

    public function setApellido_materno($apellido_materno) {
        $this->apellido_materno = $apellido_materno;
    }

    public function getNombres() {
        return $this->nombres;
    }

    public function setNombres($nombres) {
        $this->nombres = $nombres;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getId_estado_civil() {
        return $this->id_estado_civil;
    }

    public function setId_estado_civil($id_estado_civil) {
        $this->id_estado_civil = $id_estado_civil;
    }

    public function getVf_num_documento() {
        return $this->vf_num_documento;
    }

    public function setVf_num_documento($vf_num_documento) {
        $this->vf_num_documento = $vf_num_documento;
    }

    public function getVf_mes_concepcion() {
        return $this->vf_mes_concepcion;
    }

    public function setVf_mes_concepcion($vf_mes_concepcion) {
        $this->vf_mes_concepcion = $vf_mes_concepcion;
    }

    public function getCod_telefono_codigo_nacional() {
        return $this->cod_telefono_codigo_nacional;
    }

    public function setCod_telefono_codigo_nacional($cod_telefono_codigo_nacional) {
        $this->cod_telefono_codigo_nacional = $cod_telefono_codigo_nacional;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function getCod_motivo_baja_derechohabiente() {
        return $this->cod_motivo_baja_derechohabiente;
    }

    public function setCod_motivo_baja_derechohabiente($cod_motivo_baja_derechohabiente) {
        $this->cod_motivo_baja_derechohabiente = $cod_motivo_baja_derechohabiente;
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

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getFecha_baja() {
        return $this->fecha_baja;
    }

    public function setFecha_baja($fecha_baja) {
        $this->fecha_baja = $fecha_baja;
    }


   

}

?>
