<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of persona
 *
 * @author conta 1
 */
class Persona {

    //put your code here
    private $id_persona;
    private $id_empleador;
    private $cod_pais_emisor_documentos;
    private $cod_tipo_documento;
    private $cod_nacionalidad;
    private $num_documento;
    private $fecha_nacimiento;
    private $apellido_paterno;
    private $apellido_materno;
    private $nombres;
    private $sexo;
    private $id_estado_civil;
    private $cod_telefono_codigo_nacional;
    private $telefono;
    private $correo;    
    private $tabla_trabajador;
    private $tabla_pensionista;
    private $tabla_personal_formacion_laboral;
    private $tabla_personal_terceros;
    private $estado;
    private $fecha_creacion;
    private $fecha_modificacion;
    private $fecha_baja;
    
    function __construct() {
        $this->id_persona = null;
        $this->id_empleador=null;
        $this->cod_pais_emisor_documentos = null;
        $this->cod_tipo_documento = null;
        $this->cod_nacionalidad = null;
        $this->num_documento = null;
        $this->fecha_nacimiento = null;
        $this->apellido_paterno = null;
        $this->apellido_materno = null;
        $this->nombres = null;
        $this->sexo = null;
        $this->cod_telefono_codigo_nacional = null;
        $this->telefono = null;
        $this->id_estado_civil = null;
        $this->tabla_trabajador = null;
        $this->tabla_pensionista = null;
        $this->tabla_personal_formacion_laboral = null;
        $this->tabla_personal_terceros = null;
        $this->estado = null;
        $this->fecha_creacion=null;
        $this->fecha_modificacion=null;
        $this->fecha_baja =null;
    }
    
  
    public function getId_persona() {
        return $this->id_persona;
    }

    public function setId_persona($id_persona) {
        $this->id_persona = $id_persona;
    }

    public function getId_empleador() {
        return $this->id_empleador;
    }

    public function setId_empleador($id_empleador) {
        $this->id_empleador = $id_empleador;
    }

    public function getCod_pais_emisor_documentos() {
        return $this->cod_pais_emisor_documentos;
    }

    public function setCod_pais_emisor_documentos($cod_pais_emisor_documentos) {
        $this->cod_pais_emisor_documentos = $cod_pais_emisor_documentos;
    }

    public function getCod_tipo_documento() {
        return $this->cod_tipo_documento;
    }

    public function setCod_tipo_documento($cod_tipo_documento) {
        $this->cod_tipo_documento = $cod_tipo_documento;
    }

    public function getCod_nacionalidad() {
        return $this->cod_nacionalidad;
    }

    public function setCod_nacionalidad($cod_nacionalidad) {
        $this->cod_nacionalidad = $cod_nacionalidad;
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

    public function getTabla_trabajador() {
        return $this->tabla_trabajador;
    }

    public function setTabla_trabajador($tabla_trabajador) {
        $this->tabla_trabajador = $tabla_trabajador;
    }

    public function getTabla_pensionista() {
        return $this->tabla_pensionista;
    }

    public function setTabla_pensionista($tabla_pensionista) {
        $this->tabla_pensionista = $tabla_pensionista;
    }

    public function getTabla_personal_formacion_laboral() {
        return $this->tabla_personal_formacion_laboral;
    }

    public function setTabla_personal_formacion_laboral($tabla_personal_formacion_laboral) {
        $this->tabla_personal_formacion_laboral = $tabla_personal_formacion_laboral;
    }

    public function getTabla_personal_terceros() {
        return $this->tabla_personal_terceros;
    }

    public function setTabla_personal_terceros($tabla_personal_terceros) {
        $this->tabla_personal_terceros = $tabla_personal_terceros;
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

    public function getFecha_modificacion() {
        return $this->fecha_modificacion;
    }

    public function setFecha_modificacion($fecha_modificacion) {
        $this->fecha_modificacion = $fecha_modificacion;
    }

    public function getFecha_baja() {
        return $this->fecha_baja;
    }

    public function setFecha_baja($fecha_baja) {
        $this->fecha_baja = $fecha_baja;
    }

 
    
}

?>
