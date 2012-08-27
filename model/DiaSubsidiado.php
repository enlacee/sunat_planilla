<?php

class DiaSubsidiado {

    //put your code here
    private $id_subsudiado;
    private $id_trabajador_pdeclaracion;
    private $cantidad_dia;
    private $cod_tipo_suspen_relacion_laboral;

    public function __construct() {
        $this->id_subsudiado = null;
        $this->id_trabajador_pdeclaracion = null;
        $this->cantidad_dia = null;
        $this->cod_tipo_suspen_relacion_laboral = null;
    }
    
    public function getId_subsudiado() {
        return $this->id_subsudiado;
    }

    public function setId_subsudiado($id_subsudiado) {
        $this->id_subsudiado = $id_subsudiado;
    }

    public function getId_trabajador_pdeclaracion() {
        return $this->id_trabajador_pdeclaracion;
    }

    public function setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion) {
        $this->id_trabajador_pdeclaracion = $id_trabajador_pdeclaracion;
    }

    public function getCantidad_dia() {
        return $this->cantidad_dia;
    }

    public function setCantidad_dia($cantidad_dia) {
        $this->cantidad_dia = $cantidad_dia;
    }

    public function getCod_tipo_suspen_relacion_laboral() {
        return $this->cod_tipo_suspen_relacion_laboral;
    }

    public function setCod_tipo_suspen_relacion_laboral($cod_tipo_suspen_relacion_laboral) {
        $this->cod_tipo_suspen_relacion_laboral = $cod_tipo_suspen_relacion_laboral;
    }




}

?>
