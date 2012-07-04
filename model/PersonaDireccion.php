<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonaDireccion
 *
 * @author conta 1
 */
class PersonaDireccion extends Direccion {

    //put your code here

    private $id_persona_direccion;
    private $id_persona;
    private $estado_direccion;

    function __construct() {

        // $this->id_direccion = null;
//        $this->cod_ubigeo_reinec = null;
//        $this->cod_via = null;
//        $this->nombre_via = null;
//        $this->numero_via = null;
//        $this->departamento = null;
//        $this->interior = null;
//        $this->manzanza = null;
//        $this->lote = null;
//        $this->kilometro = null;
//        $this->block = null;
//        $this->estapa = null;
//        $this->cod_zona = null;
//        $this->nombre_zona = null;
//        $this->referencia = null;
//        $this->referente_essalud = null;

        $this->id_persona_direccion = null;
        $this->id_persona = null;
        $this->estado_direccion = null;
    }

    public function getId_persona_direccion() {
        return $this->id_persona_direccion;
    }

    public function setId_persona_direccion($id_persona_direccion) {
        $this->id_persona_direccion = $id_persona_direccion;
    }

    public function getId_persona() {
        return $this->id_persona;
    }

    public function setId_persona($id_persona) {
        $this->id_persona = $id_persona;
    }

    public function getEstado_direccion() {
        return $this->estado_direccion;
    }

    public function setEstado_direccion($estado_direccion) {
        $this->estado_direccion = $estado_direccion;
    }

}

?>
