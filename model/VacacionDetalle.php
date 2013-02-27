<?php

class VacacionDetalle {
    
    private $id_vacacion_detalle;
    private $id_vacacion;
    private $fecha_inicio;
    private $fecha_fin;
    private $dia;
    private $fecha_creacion;
    private $estado;
    
    public function getId_vacacion_detalle() {
        return $this->id_vacacion_detalle;
    }

    public function setId_vacacion_detalle($id_vacacion_detalle) {
        $this->id_vacacion_detalle = $id_vacacion_detalle;
    }

    public function getId_vacacion() {
        return $this->id_vacacion;
    }

    public function setId_vacacion($id_vacacion) {
        $this->id_vacacion = $id_vacacion;
    }

    public function getFecha_inicio() {
        return $this->fecha_inicio;
    }

    public function setFecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function getFecha_fin() {
        return $this->fecha_fin;
    }

    public function setFecha_fin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }

    public function getDia() {
        return $this->dia;
    }

    public function setDia($dia) {
        $this->dia = $dia;
    }

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }


    
    
    
    
}

?>
