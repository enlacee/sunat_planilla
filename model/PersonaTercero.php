<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of personalTercero
 *
 * @author conta 1
 */
class PersonaTercero {

    //put your code here

    private $id_personal_tercero;
    private $id_persona;
    private $id_empleador_destaque_yoursef;
    private $cobertura_pension;
    private $cod_situacion;
    private $estado;

    function __construct() {

        $this->id_personal_tercero = null;
        $this->id_persona = null;
        $this->id_empleador_destaque_yoursef = null;
        $this->cobertura_pension = null;
        $this->cod_situacion = null;
        $this->estado = null;
    }
    
    public function getId_personal_tercero() {
        return $this->id_personal_tercero;
    }

    public function setId_personal_tercero($id_personal_tercero) {
        $this->id_personal_tercero = $id_personal_tercero;
    }

    public function getId_persona() {
        return $this->id_persona;
    }

    public function setId_persona($id_persona) {
        $this->id_persona = $id_persona;
    }

    public function getId_empleador_destaque_yoursef() {
        return $this->id_empleador_destaque_yoursef;
    }

    public function setId_empleador_destaque_yoursef($id_empleador_destaque_yoursef) {
        $this->id_empleador_destaque_yoursef = $id_empleador_destaque_yoursef;
    }

    public function getCobertura_pension() {
        return $this->cobertura_pension;
    }

    public function setCobertura_pension($cobertura_pension) {
        $this->cobertura_pension = $cobertura_pension;
    }

    public function getCod_situacion() {
        return $this->cod_situacion;
    }

    public function setCod_situacion($cod_situacion) {
        $this->cod_situacion = $cod_situacion;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }



}

?>
