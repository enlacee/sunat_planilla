<?php

class Prestamo {
   
    private $id_prestamo;
    private $id_empleador;
    private $id_trabajador;
    private $valor;
    private $num_cuota;
    private $fecha_inicio;
    private $estado;
    private $fecha_creacion;
    
    
    function __construct() {
     $this->id_prestamo=null;
     $this->id_empleador=null;
     $this->id_trabajador=null;
     $this->valor=null;
     $this->num_cuota=null;
     $this->fecha_inicio=null;
     $this->estado=null;
     $this->fecha_creacion=null;
    }
    
    public function getId_prestamo() {
        return $this->id_prestamo;
    }

    public function setId_prestamo($id_prestamo) {
        $this->id_prestamo = $id_prestamo;
    }

    public function getId_empleador() {
        return $this->id_empleador;
    }

    public function setId_empleador($id_empleador) {
        $this->id_empleador = $id_empleador;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getNum_cuota() {
        return $this->num_cuota;
    }

    public function setNum_cuota($num_cuota) {
        $this->num_cuota = $num_cuota;
    }

    public function getFecha_inicio() {
        return $this->fecha_inicio;
    }

    public function setFecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
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



    
    
}

?>
