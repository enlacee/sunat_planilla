<?php

class DetalleTipoTrabajador {

    //put your code here

    private $id_detalle_tipo_trabajador;
    private $id_trabajador;
    private $cod_tipo_trabajador;
    private $fecha_inicio;
    private $fecha_fin;

    function __construct() {
     $this->id_detalle_tipo_trabajador=null;
     $this->id_trabajador=null;
     $this->cod_tipo_trabajador=null;
     $this->fecha_inicio=null;
     $this->fecha_fin=null;
    }
    
    public function getId_detalle_tipo_trabajador() {
        return $this->id_detalle_tipo_trabajador;
    }

    public function setId_detalle_tipo_trabajador($id_detalle_tipo_trabajador) {
        $this->id_detalle_tipo_trabajador = $id_detalle_tipo_trabajador;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getCod_tipo_trabajador() {
        return $this->cod_tipo_trabajador;
    }

    public function setCod_tipo_trabajador($cod_tipo_trabajador) {
        $this->cod_tipo_trabajador = $cod_tipo_trabajador;
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
