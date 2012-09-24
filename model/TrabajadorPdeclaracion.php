<?php

class TrabajadorPdeclaracion extends AbstractDao {

    //put your code here

    private $id_trabajador_pdeclaracion;
    private $id_pdeclaracion;
    private $id_trabajador;
    private $dia_laborado;
    private $dia_total;
    private $ordinario_hora;
    private $ordinario_min;
    private $sobretiempo_hora;
    private $sobretiempo_min;
    private $sueldo;
    private $sueldo_neto;
    private $estado;
    private $descripcion;
    private $fecha_creacion;
    private $fecha_modificacion;
    //
    private $ingreso_5ta_categoria;
    private $cod_tipo_trabajador;
    private $cod_regimen_pensionario;
    private $cod_regimen_aseguramiento_salud;
    private $cod_situacion;
    private $id_empresa_centro_costo;
    private $cod_ocupacion_p;

    function __construct() {
        $this->id_trabajador_pdeclaracion = null;
        $this->id_pdeclaracion = null;
        $this->id_trabajador = null;
        $this->dia_laborado = null;
        $this->dia_total = null;
        $this->ordinario_hora = null;
        $this->ordinario_min = null;
        $this->sobretiempo_hora = null;
        $this->sobretiempo_min = null;
        $this->sueldo = null;
        $this->sueldo_neto = null;
        $this->estado = null;
        $this->descripcion = null;
        $this->fecha_creacion = null;
        $this->fecha_modificacion = null;
        //
        $this->ingreso_5ta_categoria=null;
        $this->cod_tipo_trabajador=null;
        $this->cod_regimen_pensionario=null;
        $this->cod_regimen_aseguramiento_salud=null;
        $this->cod_situacion=null;
         $this->id_empresa_centro_costo=null;
         $this->cod_ocupacion_p=null;        
    }
    public function getId_trabajador_pdeclaracion() {
        return $this->id_trabajador_pdeclaracion;
    }

    public function setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion) {
        $this->id_trabajador_pdeclaracion = $id_trabajador_pdeclaracion;
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

    public function getDia_laborado() {
        return $this->dia_laborado;
    }

    public function setDia_laborado($dia_laborado) {
        $this->dia_laborado = $dia_laborado;
    }

    public function getDia_total() {
        return $this->dia_total;
    }

    public function setDia_total($dia_total) {
        $this->dia_total = $dia_total;
    }

    public function getOrdinario_hora() {
        return $this->ordinario_hora;
    }

    public function setOrdinario_hora($ordinario_hora) {
        $this->ordinario_hora = $ordinario_hora;
    }

    public function getOrdinario_min() {
        return $this->ordinario_min;
    }

    public function setOrdinario_min($ordinario_min) {
        $this->ordinario_min = $ordinario_min;
    }

    public function getSobretiempo_hora() {
        return $this->sobretiempo_hora;
    }

    public function setSobretiempo_hora($sobretiempo_hora) {
        $this->sobretiempo_hora = $sobretiempo_hora;
    }

    public function getSobretiempo_min() {
        return $this->sobretiempo_min;
    }

    public function setSobretiempo_min($sobretiempo_min) {
        $this->sobretiempo_min = $sobretiempo_min;
    }

    public function getSueldo() {
        return $this->sueldo;
    }

    public function setSueldo($sueldo) {
        $this->sueldo = $sueldo;
    }

    public function getSueldo_neto() {
        return $this->sueldo_neto;
    }

    public function setSueldo_neto($sueldo_neto) {
        $this->sueldo_neto = $sueldo_neto;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
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

    public function getIngreso_5ta_categoria() {
        return $this->ingreso_5ta_categoria;
    }

    public function setIngreso_5ta_categoria($ingreso_5ta_categoria) {
        $this->ingreso_5ta_categoria = $ingreso_5ta_categoria;
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

    public function getCod_regimen_aseguramiento_salud() {
        return $this->cod_regimen_aseguramiento_salud;
    }

    public function setCod_regimen_aseguramiento_salud($cod_regimen_aseguramiento_salud) {
        $this->cod_regimen_aseguramiento_salud = $cod_regimen_aseguramiento_salud;
    }

    public function getCod_situacion() {
        return $this->cod_situacion;
    }

    public function setCod_situacion($cod_situacion) {
        $this->cod_situacion = $cod_situacion;
    }

    public function getId_empresa_centro_costo() {
        return $this->id_empresa_centro_costo;
    }

    public function setId_empresa_centro_costo($id_empresa_centro_costo) {
        $this->id_empresa_centro_costo = $id_empresa_centro_costo;
    }

    public function getCod_ocupacion_p() {
        return $this->cod_ocupacion_p;
    }

    public function setCod_ocupacion_p($cod_ocupacion_p) {
        $this->cod_ocupacion_p = $cod_ocupacion_p;
    }


    
    
   
}

?>
