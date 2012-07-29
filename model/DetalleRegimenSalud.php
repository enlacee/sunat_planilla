<?php

class DetalleRegimenSalud {

    //put your code here

    private $id_detalle_regimen_salud;
    private $id_trabajador;
    private $cod_regimen_aseguramiento_salud;
    private $fecha_inicio;
    private $fecha_fin;
    private $cod_eps;
    private $id_persona;

    function __construct() {
        $this->id_detalle_regimen_salud=null;
        $this->id_trabajador=null;
        $this->cod_regimen_aseguramiento_salud=null;
        $this->fecha_inicio=null;
        $this->fecha_fin=null;
        $this->cod_eps=null;
        $this->id_persona=null;
    }
   
    public function getId_detalle_regimen_salud() {
        return $this->id_detalle_regimen_salud;
    }

    public function setId_detalle_regimen_salud($id_detalle_regimen_salud) {
        $this->id_detalle_regimen_salud = $id_detalle_regimen_salud;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getCod_regimen_aseguramiento_salud() {
        return $this->cod_regimen_aseguramiento_salud;
    }

    public function setCod_regimen_aseguramiento_salud($cod_regimen_aseguramiento_salud) {
        $this->cod_regimen_aseguramiento_salud = $cod_regimen_aseguramiento_salud;
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

    public function getCod_eps() {
        return $this->cod_eps;
    }

    public function setCod_eps($cod_eps) {
        $this->cod_eps = $cod_eps;
    }

    public function getId_persona() {
        return $this->id_persona;
    }

    public function setId_persona($id_persona) {
        $this->id_persona = $id_persona;
    }


  

    

}

?>
