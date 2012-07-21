<?php

class PjornadaLaboral {

    //put your code here
    private $id_pjornada_laboral;
    private $id_ptrabajador;
    private $dia_laborado;
    private $dia_subsidiado;
    private $dia_nolaborado_nosubsidiado;
    //private $dia_total;
    private $hora_ordinaria_hh;
    private $hora_ordinaria_mm;
    private $hora_sobretiempo_hh;
    private $hora_sobretiempo_mm;

    //private $total_hh;
    //private $total_mm;    

    function __construct() {
         $this->id_pjornada_laboral=null;
         $this->id_ptrabajador=null;
         $this->dia_laborado=null;
         $this->dia_subsidiado=null;
         $this->dia_nolaborado_nosubsidiado=null;
        //private $dia_total;
         $this->hora_ordinaria_hh=null;
         $this->hora_ordinaria_mm=null;
         $this->hora_sobretiempo_hh=null;
         $this->hora_sobretiempo_mm=null;
        //private $total_hh;
        //private $total_mm;
    }
    
    public function getId_pjornada_laboral() {
        return $this->id_pjornada_laboral;
    }

    public function setId_pjornada_laboral($id_pjornada_laboral) {
        $this->id_pjornada_laboral = $id_pjornada_laboral;
    }

    public function getId_ptrabajador() {
        return $this->id_ptrabajador;
    }

    public function setId_ptrabajador($id_ptrabajador) {
        $this->id_ptrabajador = $id_ptrabajador;
    }

    public function getDia_laborado() {
        return $this->dia_laborado;
    }

    public function setDia_laborado($dia_laborado) {
        $this->dia_laborado = $dia_laborado;
    }

    public function getDia_subsidiado() {
        return $this->dia_subsidiado;
    }

    public function setDia_subsidiado($dia_subsidiado) {
        $this->dia_subsidiado = $dia_subsidiado;
    }

    public function getDia_nolaborado_nosubsidiado() {
        return $this->dia_nolaborado_nosubsidiado;
    }

    public function setDia_nolaborado_nosubsidiado($dia_nolaborado_nosubsidiado) {
        $this->dia_nolaborado_nosubsidiado = $dia_nolaborado_nosubsidiado;
    }

    public function getHora_ordinaria_hh() {
        return $this->hora_ordinaria_hh;
    }

    public function setHora_ordinaria_hh($hora_ordinaria_hh) {
        $this->hora_ordinaria_hh = $hora_ordinaria_hh;
    }

    public function getHora_ordinaria_mm() {
        return $this->hora_ordinaria_mm;
    }

    public function setHora_ordinaria_mm($hora_ordinaria_mm) {
        $this->hora_ordinaria_mm = $hora_ordinaria_mm;
    }

    public function getHora_sobretiempo_hh() {
        return $this->hora_sobretiempo_hh;
    }

    public function setHora_sobretiempo_hh($hora_sobretiempo_hh) {
        $this->hora_sobretiempo_hh = $hora_sobretiempo_hh;
    }

    public function getHora_sobretiempo_mm() {
        return $this->hora_sobretiempo_mm;
    }

    public function setHora_sobretiempo_mm($hora_sobretiempo_mm) {
        $this->hora_sobretiempo_mm = $hora_sobretiempo_mm;
    }


    
    
    
    
    
    
    

}

?>
