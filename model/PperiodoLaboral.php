<?php

class PperiodoLaboral {
    //put your code here
    private $id_pperiodo_laboral;
    private $id_ptrabajador;
    private $fecha_inicio;
    private $fecha_fin;
    
    
    function __construct() {
        $this->id_pperiodo_laboral =null;
        $this->id_ptrabajador = null;
        $this->fecha_inicio = null;
        $this->fecha_fin = null;
    }
    
    
    
    public function getId_pperiodo_laboral() {
        return $this->id_pperiodo_laboral;
    }

    public function setId_pperiodo_laboral($id_pperiodo_laboral) {
        $this->id_pperiodo_laboral = $id_pperiodo_laboral;
    }

    public function getId_ptrabajador() {
        return $this->id_ptrabajador;
    }

    public function setId_ptrabajador($id_ptrabajador) {
        $this->id_ptrabajador = $id_ptrabajador;
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


    
    
    
}

?>
