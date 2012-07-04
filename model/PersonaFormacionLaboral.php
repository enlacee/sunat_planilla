<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonalFormacion
 *
 * @author conta 1
 */
class PersonaFormacionLaboral {

    //put your code here

    private $id_personal_formacion_laboral;
    private $id_persona;
    private $cod_nivel_educativo;
    private $id_modalidad_formativa;
    private $id_ocupacion_2;
    private $centro_formacion;
    private $seguro_medico;
    private $presenta_discapacidad;
    private $horario_nocturno;
    private $estado;
    private $cod_situacion;

    function __construct() {
        $this->id_personal_formacion_laboral = null;
        $this->id_persona = null;
        $this->cod_nivel_educativo = 0;
        $this->id_modalidad_formativa = 0;
        $this->id_ocupacion_2 = 0;
        $this->centro_formacion = 0;
        $this->seguro_medico = 0;
        $this->presenta_discapacidad = 0;
        $this->horario_nocturno = 0;
        $this->estado = null;
        $this->cod_situacion = 0;
    }
    public function getId_personal_formacion_laboral() {
        return $this->id_personal_formacion_laboral;
    }

    public function setId_personal_formacion_laboral($id_personal_formacion_laboral) {
        $this->id_personal_formacion_laboral = $id_personal_formacion_laboral;
    }

    public function getId_persona() {
        return $this->id_persona;
    }

    public function setId_persona($id_persona) {
        $this->id_persona = $id_persona;
    }

    public function getCod_nivel_educativo() {
        return $this->cod_nivel_educativo;
    }

    public function setCod_nivel_educativo($cod_nivel_educativo) {
        $this->cod_nivel_educativo = $cod_nivel_educativo;
    }

    public function getId_modalidad_formativa() {
        return $this->id_modalidad_formativa;
    }

    public function setId_modalidad_formativa($id_modalidad_formativa) {
        $this->id_modalidad_formativa = $id_modalidad_formativa;
    }

    public function getId_ocupacion_2() {
        return $this->id_ocupacion_2;
    }

    public function setId_ocupacion_2($id_ocupacion_2) {
        $this->id_ocupacion_2 = $id_ocupacion_2;
    }

    public function getCentro_formacion() {
        return $this->centro_formacion;
    }

    public function setCentro_formacion($centro_formacion) {
        $this->centro_formacion = $centro_formacion;
    }

    public function getSeguro_medico() {
        return $this->seguro_medico;
    }

    public function setSeguro_medico($seguro_medico) {
        $this->seguro_medico = $seguro_medico;
    }

    public function getPresenta_discapacidad() {
        return $this->presenta_discapacidad;
    }

    public function setPresenta_discapacidad($presenta_discapacidad) {
        $this->presenta_discapacidad = $presenta_discapacidad;
    }

    public function getHorario_nocturno() {
        return $this->horario_nocturno;
    }

    public function setHorario_nocturno($horario_nocturno) {
        $this->horario_nocturno = $horario_nocturno;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getCod_situacion() {
        return $this->cod_situacion;
    }

    public function setCod_situacion($cod_situacion) {
        $this->cod_situacion = $cod_situacion;
    }




}

?>
