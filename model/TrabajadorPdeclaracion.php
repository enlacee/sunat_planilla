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
    

    function __construct() {
        $this->id_trabajador_pdeclaracion = null;
        $this->id_pdeclaracion = null;
        $this->id_trabajador = null;
        $this->dia_laborado = null;
        $this->dia_total = null;
        $this->ordinario_hora = null;
        $this->ordinario_min = null;
        $this->sobretiempo_hora=null;
        $this->sobretiempo_min=null;
        $this->sueldo=null;
        $this->sueldo_neto=null;
        $this->estado=null;
        $this->descripcion=null;
        $this->fecha_creacion=null;
        $this->fecha_modificacion=null;
        
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



}

?>
