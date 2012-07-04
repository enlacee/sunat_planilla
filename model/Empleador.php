<?php

class Empleador {

    //put your code here    
    private $id_empleador;
    private $id_tipo_empleador;
    private $cod_telefono_codigo_nacional;
    private $ruc;
    private $razon_social;
    private $id_tipo_sociedad_comercial;
    private $nombre_comercial;
    private $cod_tipo_actividad;
    private $telefono;
    private $correo;
    private $empresa_dedica;
    private $senati;
    private $remype;
    private $remype_tipo_empresa;
    private $trabajador_sin_rp;
    private $actividad_riesgo_sctr;
    private $trabajadores_sctr;
    private $persona_discapacidad;
    private $agencia_empleo;
    private $desplaza_personal;
    private $terceros_desplaza_usted;
    private $estado_empleador;
    private $fecha_creacion;

    function __construct() {

        $this->id_empleador = null;
        $this->id_tipo_empleador = null;
        $this->cod_telefono_codigo_nacional = null;
        $this->ruc = null;
        $this->razon_social = null;
        $this->id_tipo_sociedad_comercial = null;
        $this->nombre_comercial = null;
        $this->cod_tipo_actividad = null;
        $this->telefono = null;
        $this->correo = null;
        $this->empresa_dedica = null;
        $this->senati = null;
        $this->remype = null;
        $this->remype_tipo_empresa = null;
        $this->trabajador_sin_rp = null;
        $this->actividad_riesgo_sctr = null;
        $this->trabajadores_sctr = null;
        $this->persona_discapacidad = null;
        $this->agencia_empleo = null;
        $this->desplaza_personal = null;
        $this->terceros_desplaza_usted = null;
        $this->estado_empleador = null;
        $this->fecha_creacion = null;
    }

    public function getId_empleador() {
        return $this->id_empleador;
    }

    public function setId_empleador($id_empleador) {
        $this->id_empleador = $id_empleador;
    }

    public function getId_tipo_empleador() {
        return $this->id_tipo_empleador;
    }

    public function setId_tipo_empleador($id_tipo_empleador) {
        $this->id_tipo_empleador = $id_tipo_empleador;
    }

    public function getCod_telefono_codigo_nacional() {
        return $this->cod_telefono_codigo_nacional;
    }

    public function setCod_telefono_codigo_nacional($cod_telefono_codigo_nacional) {
        $this->cod_telefono_codigo_nacional = $cod_telefono_codigo_nacional;
    }

    public function getRuc() {
        return $this->ruc;
    }

    public function setRuc($ruc) {
        $this->ruc = $ruc;
    }

    public function getRazon_social() {
        return $this->razon_social;
    }

    public function setRazon_social($razon_social) {
        $this->razon_social = $razon_social;
    }

    public function getId_tipo_sociedad_comercial() {
        return $this->id_tipo_sociedad_comercial;
    }

    public function setId_tipo_sociedad_comercial($id_tipo_sociedad_comercial) {
        $this->id_tipo_sociedad_comercial = $id_tipo_sociedad_comercial;
    }

    public function getNombre_comercial() {
        return $this->nombre_comercial;
    }

    public function setNombre_comercial($nombre_comercial) {
        $this->nombre_comercial = $nombre_comercial;
    }

    public function getCod_tipo_actividad() {
        return $this->cod_tipo_actividad;
    }

    public function setCod_tipo_actividad($cod_tipo_actividad) {
        $this->cod_tipo_actividad = $cod_tipo_actividad;
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

    public function getEmpresa_dedica() {
        return $this->empresa_dedica;
    }

    public function setEmpresa_dedica($empresa_dedica) {
        $this->empresa_dedica = $empresa_dedica;
    }

    public function getSenati() {
        return $this->senati;
    }

    public function setSenati($senati) {
        $this->senati = $senati;
    }

    public function getRemype() {
        return $this->remype;
    }

    public function setRemype($remype) {
        $this->remype = $remype;
    }

    public function getRemype_tipo_empresa() {
        return $this->remype_tipo_empresa;
    }

    public function setRemype_tipo_empresa($remype_tipo_empresa) {
        $this->remype_tipo_empresa = $remype_tipo_empresa;
    }

    public function getTrabajador_sin_rp() {
        return $this->trabajador_sin_rp;
    }

    public function setTrabajador_sin_rp($trabajador_sin_rp) {
        $this->trabajador_sin_rp = $trabajador_sin_rp;
    }

    public function getActividad_riesgo_sctr() {
        return $this->actividad_riesgo_sctr;
    }

    public function setActividad_riesgo_sctr($actividad_riesgo_sctr) {
        $this->actividad_riesgo_sctr = $actividad_riesgo_sctr;
    }

    public function getTrabajadores_sctr() {
        return $this->trabajadores_sctr;
    }

    public function setTrabajadores_sctr($trabajadores_sctr) {
        $this->trabajadores_sctr = $trabajadores_sctr;
    }

    public function getPersona_discapacidad() {
        return $this->persona_discapacidad;
    }

    public function setPersona_discapacidad($persona_discapacidad) {
        $this->persona_discapacidad = $persona_discapacidad;
    }

    public function getAgencia_empleo() {
        return $this->agencia_empleo;
    }

    public function setAgencia_empleo($agencia_empleo) {
        $this->agencia_empleo = $agencia_empleo;
    }

    public function getDesplaza_personal() {
        return $this->desplaza_personal;
    }

    public function setDesplaza_personal($desplaza_personal) {
        $this->desplaza_personal = $desplaza_personal;
    }

    public function getTerceros_desplaza_usted() {
        return $this->terceros_desplaza_usted;
    }

    public function setTerceros_desplaza_usted($terceros_desplaza_usted) {
        $this->terceros_desplaza_usted = $terceros_desplaza_usted;
    }

    public function getEstado_empleador() {
        return $this->estado_empleador;
    }

    public function setEstado_empleador($estado_empleador) {
        $this->estado_empleador = $estado_empleador;
    }

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

}

?>
