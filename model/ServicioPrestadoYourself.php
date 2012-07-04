<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetalleServicioPrestado2
 *
 * @author Anibal
 */
 class ServicioPrestadoYourself {

    //put your code here
    private $id_servicio_prestado_yourself;
    private $id_empleador_destaque_yourself;
    protected $cod_tipo_actividad;
    protected $fecha_inicio;
    protected $fecha_fin;
    protected $estado;

    function __construct() {
        $this->id_servicio_prestado_yourself =null;
        $this->id_empleador_destaque_yourself=null;
        $this->cod_tipo_actividad=null;
        $this->fecha_inicio=null;
        $this->fecha_fin=null;
    }
    
    public function getId_servicio_prestado_yourself() {
        return $this->id_servicio_prestado_yourself;
    }

    public function setId_servicio_prestado_yourself($id_servicio_prestado_yourself) {
        $this->id_servicio_prestado_yourself = $id_servicio_prestado_yourself;
    }

    public function getId_empleador_destaque_yourself() {
        return $this->id_empleador_destaque_yourself;
    }

    public function setId_empleador_destaque_yourself($id_empleador_destaque_yourself) {
        $this->id_empleador_destaque_yourself = $id_empleador_destaque_yourself;
    }

    public function getCod_tipo_actividad() {
        return $this->cod_tipo_actividad;
    }

    public function setCod_tipo_actividad($cod_tipo_actividad) {
        $this->cod_tipo_actividad = $cod_tipo_actividad;
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

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }


   


   



}

?>
