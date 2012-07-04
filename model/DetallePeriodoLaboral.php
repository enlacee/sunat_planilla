<?php

class DetallePeriodoLaboral {

    //put your code here

    private $id_detalle_periodo_laboral;
    private $id_trabajador;
    protected $cod_motivo_baja_registro;
    protected $fecha_inicio;
    protected $fecha_fin;

    function __construct() {
        $this->id_detalle_periodo_laboral=null;
        $this->id_trabajador=null;
        $this->cod_motivo_baja_registro=null;
        $this->fecha_inicio=null;
        $this->fecha_fin=null;
    }
    
    public function getId_detalle_periodo_laboral() {
        return $this->id_detalle_periodo_laboral;
    }

    public function setId_detalle_periodo_laboral($id_detalle_periodo_laboral) {
        $this->id_detalle_periodo_laboral = $id_detalle_periodo_laboral;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getCod_motivo_baja_registro() {
        return $this->cod_motivo_baja_registro;
    }

    public function setCod_motivo_baja_registro($cod_motivo_baja_registro) {
        $this->cod_motivo_baja_registro = $cod_motivo_baja_registro;
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
