<?php

class JornadaLaboral {

    //put your code here

    private $id_joranada_laboral;
    private $id_adelanto;
    private $dia_total;
    private $dia_nosubsidiado;
    private $ordinario_hora;
    private $ordinario_min;
    private $sobretiempo_hora;
    private $sobretiempo_min;

    public function __construct() {
        $this->id_joranada_laboral;
        $this->id_adelanto;
        $this->dia_total;
        $this->dia_nosubsidiado;
        $this->ordinario_hora;
        $this->ordinario_min;
        $this->sobretiempo_hora;
        $this->sobretiempo_min;
        ;
    }

    public function getId_joranada_laboral() {
        return $this->id_joranada_laboral;
    }

    public function setId_joranada_laboral($id_joranada_laboral) {
        $this->id_joranada_laboral = $id_joranada_laboral;
    }

    public function getId_adelanto() {
        return $this->id_adelanto;
    }

    public function setId_adelanto($id_adelanto) {
        $this->id_adelanto = $id_adelanto;
    }

    public function getDia_total() {
        return $this->dia_total;
    }

    public function setDia_total($dia_total) {
        $this->dia_total = $dia_total;
    }

    public function getDia_laborado() {
        $a = ($this->dia_total) ? $this->dia_total : 0;
        $b = ($this->dia_nosubsidiado) ? $this->dia_nosubsidiado : 0;
        $rpta = $a - $b;
        return $rpta;
    }

    public function getDia_nosubsidiado() {
        return $this->dia_nosubsidiado;
    }

    public function setDia_nosubsidiado($dia_nosubsidiado) {
        $this->dia_nosubsidiado = $dia_nosubsidiado;
    }

    public function getOrdinario_hora() {
        return $this->ordinario_hora;
    }

    public function setOrdinario_hora($ordinario_hora) {
        $this->ordinario_hora = $ordinario_hora;
    }

    public function getOrdinario_min() {
        return $this->ordinario_min;
    }

    public function setOrdinario_min($ordinario_min) {
        $this->ordinario_min = $ordinario_min;
    }

    public function getSobretiempo_hora() {
        return $this->sobretiempo_hora;
    }

    public function setSobretiempo_hora($sobretiempo_hora) {
        $this->sobretiempo_hora = $sobretiempo_hora;
    }

    public function getSobretiempo_min() {
        return $this->sobretiempo_min;
    }

    public function setSobretiempo_min($sobretiempo_min) {
        $this->sobretiempo_min = $sobretiempo_min;
    }

}

?>
