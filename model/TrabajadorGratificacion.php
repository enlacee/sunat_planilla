<?php

class TrabajadorGratificacion {
    //put your code here
    private $id_trabajador_grati;
    private $id_trabajador;
    private $fecha;
    private $fecha_creacion;
    
    function __construct() {
        $this->id_trabajador_grati = null;
        $this->id_trabajador=null;
        $this->fecha=null;
        $this->fecha_creacion=null;
    }
    
    public function getId_trabajador_grati() {
        return $this->id_trabajador_grati;
    }

    public function setId_trabajador_grati($id_trabajador_grati) {
        $this->id_trabajador_grati = $id_trabajador_grati;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

    
}

?>
