<?php

class PjornadaLaboral {

    //put your code here
    private $id_pjornada_laboral;
    private $id_ptrabajador;
    private $dia_laborado;
    private $dia_subsidiado;
    private $dia_nosubsidiado;
    private $dia_total;
    private $hora_ordinaria_hh;
    private $hora_ordinaria_mm;
    private $hora_sobretiempo_hh;
    private $hora_sobretiempo_mm;
    private $total_hora_hh;
    private $total_hora_mm;

    function __construct() {
        $this->id_pjornada_laboral = null;
        $this->id_ptrabajador = null;
        $this->dia_laborado = null;
        $this->dia_subsidiado = null;
        $this->dia_nosubsidiado = null;
        $this->dia_total = null;

        $this->hora_ordinaria_hh = null;
        $this->hora_ordinaria_mm = null;
        $this->hora_sobretiempo_hh = null;
        $this->hora_sobretiempo_mm = null;

        $this->total_hora_hh = null;
        $this->total_hora_mm = null;
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

    public function getDia_nosubsidiado() {
        return $this->dia_nosubsidiado;
    }

    public function setDia_nosubsidiado($dia_nosubsidiado) {
        $this->dia_nosubsidiado = $dia_nosubsidiado;
    }

    public function getDia_total() {

        return $this->dia_total;
    }

    public function setDia_total($dia_total) {
        $this->dia_total = $dia_total;
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

    //--
    public function getTotal_hora_hh() {
        return $this->total_hora_hh;
    }

    /* private function setTotal_hora_hh($total_hora_hh) {
      $this->total_hora_hh = $total_hora_hh;
      } */

    public function getTotal_hora_mm() {
        $this->calcularHoraMinuto();
        return $this->total_hora_mm;
    }

    private function setTotal_hora_mm($total_hora_mm) {
        $this->total_hora_mm = $total_hora_mm;
    }

//------------------------------------------------------------------------------
    public static function calcTotal_hora() {


        $rpta = null;
        $hora = 0;
        $min = 0;

        $o_hora = $this->getHora_ordinaria_hh();
        $o_min = $this->getHora_ordinaria_mm();

        $s_hora = $this->getHora_sobretiempo_hh();
        $s_min = $this->getHora_sobretiempo_mm();

        //---
        $hora = $o_hora + $s_hora;
        $min = $o_min + $s_min;

        //$dato_min = $min / 60;
        while (($min / 60) >= 1) {
            $hora++;
            $min - 60;
        }

        $this->total_hora_hh = $hora;
        $this->total_hora_mm = $min;
    }

    public static function calcDia_laborado() {

        /**
         * Dia laborado  =  - dias subsidiados  AND - dias no laborados
         */
        $dia_total = 0;
        $dia_subsidiado = 0;
        $dia_nosubsidiado = 0;

        $dia_total = $this->getDia_total();  //Establecido por default
        //-----

        $dia_subsidiado = $this->getDia_subsidiado();
        $dia_nosubsidiado = $this->getDia_nosubsidiado();

        $dia_total = $dia_total - ($dia_subsidiado + $dia_nosubsidiado);

        $this->setDia_laborado($dia_total);
    }

}

?>
