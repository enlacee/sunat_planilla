<?php
class ParatiFamilia {
    private $id_para_ti_familia;
    private $id_empleador;
    private $id_trabajador;
    private $id_tipo_para_ti_familia;
    private $estado;
    private $fecha_inicio;
    private $fecha_creacion;
    
    
    function __construct() {
     $this->id_para_ti_familia=null;
     $this->id_empleador=null;
     $this->id_trabajador=null;
     $this->id_tipo_para_ti_familia=null;
     $this->estado=null;
     $this->fecha_inicio = null;
     $this->fecha_creacion=null;
    }
    
    public function getId_para_ti_familia() {
        return $this->id_para_ti_familia;
    }

    public function setId_para_ti_familia($id_para_ti_familia) {
        $this->id_para_ti_familia = $id_para_ti_familia;
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

    public function getId_tipo_para_ti_familia() {
        return $this->id_tipo_para_ti_familia;
    }

    public function setId_tipo_para_ti_familia($id_tipo_para_ti_familia) {
        $this->id_tipo_para_ti_familia = $id_tipo_para_ti_familia;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getFecha_inicio() {
        return $this->fecha_inicio;
    }

    public function setFecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }





    
    
    
}

?>
