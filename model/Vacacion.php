<?php
class Vacacion {
    
    private $id_vacacion;
    private $id_trabajador;
    private $fecha;
    private $fecha_programada;
    private $fecha_prograda_fin;
    private $estado;
    private $fecha_creacion;
    private $tipo_vacacion;
    
    function __construct(){
        
     $this->id_vacacion=null;
     $this->id_trabajador=null;
     $this->fecha=null;
     $this->fecha_programada=null;
     $this->fecha_prograda_fin=null;
     $this->estado=null;
     $this->fecha_creacion=null;
     $this->tipo_vacacion=null;
        
    }
    
    public function getId_vacacion() {
        return $this->id_vacacion;
    }

    public function setId_vacacion($id_vacacion) {
        $this->id_vacacion = $id_vacacion;
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

    public function getFecha_programada() {
        return $this->fecha_programada;
    }

    public function setFecha_programada($fecha_programada) {
        $this->fecha_programada = $fecha_programada;
    }

    public function getFecha_prograda_fin() {
        return $this->fecha_prograda_fin;
    }

    public function setFecha_prograda_fin($fecha_prograda_fin) {
        $this->fecha_prograda_fin = $fecha_prograda_fin;
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

    public function getTipo_vacacion() {
        return $this->tipo_vacacion;
    }

    public function setTipo_vacacion($tipo_vacacion) {
        $this->tipo_vacacion = $tipo_vacacion;
    }


    
    
    
    
    
    
    
    
}

?>
