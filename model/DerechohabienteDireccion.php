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
class DerechohabienteDireccion extends Direccion {

    //put your code here

    private $id_derechohabiente_direccion;
    private $id_derechohabiente;
    private $estado_direccion;

    function __construct() {
        parent::__construct();
        $this->id_derechohabiente_direccion = null;
        $this->id_derechohabiente = null;
        $this->estado_direccion =null;
    }
    
    public function getId_derechohabiente_direccion() {
        return $this->id_derechohabiente_direccion;
    }

    public function setId_derechohabiente_direccion($id_derechohabiente_direccion) {
        $this->id_derechohabiente_direccion = $id_derechohabiente_direccion;
    }

    public function getId_derechohabiente() {
        return $this->id_derechohabiente;
    }

    public function setId_derechohabiente($id_derechohabiente) {
        $this->id_derechohabiente = $id_derechohabiente;
    }

    public function getEstado_direccion() {
        return $this->estado_direccion;
    }

    public function setEstado_direccion($estado_direccion) {
        $this->estado_direccion = $estado_direccion;
    }





}

?>
