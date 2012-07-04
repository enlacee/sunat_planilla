<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmpleadorMaestro
 *
 * @author Anibal
 */
final class EmpleadorMaestro {

    //put your code here

    private $id_empleador_maestro;
    private $id_empleador;
    private $fecha_creacion;

    function __construct() {
        $this->id_empleador_maestro = null;
        $this->id_empleador = null;
        $this->fecha_creacion = null;
    }
    
    public function getId_empleador_maestro() {
        return $this->id_empleador_maestro;
    }

    public function setId_empleador_maestro($id_empleador_maestro) {
        $this->id_empleador_maestro = $id_empleador_maestro;
    }

    public function getId_empleador() {
        return $this->id_empleador;
    }

    public function setId_empleador($id_empleador) {
        $this->id_empleador = $id_empleador;
    }

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }


    

}

?>
