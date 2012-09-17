<?php
class RegistroConceptoE {
   
    private $id_registro_concepto_e;
    private $id_concepto_e_empleador;
    private $id_trabajador;
    private $valor;
    private $estado;
    private $fecha_creacion;
    
    
    function __construct() {
     $this->id_registro_concepto_e;
     $this->id_concepto_e_empleador;
     $this->id_trabajador;
     $this->valor;
     $this->estado;
     $this->fecha_creacion;
    }
    
    
    
    
    public function getId_registro_concepto_e() {
        return $this->id_registro_concepto_e;
    }

    public function setId_registro_concepto_e($id_registro_concepto_e) {
        $this->id_registro_concepto_e = $id_registro_concepto_e;
    }

    public function getId_concepto_e_empleador() {
        return $this->id_concepto_e_empleador;
    }

    public function setId_concepto_e_empleador($id_concepto_e_empleador) {
        $this->id_concepto_e_empleador = $id_concepto_e_empleador;
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
