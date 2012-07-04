<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DetalleServicioPrestado2
 *
 * @author Anibal
 */
final class DetalleServicioPrestado1 extends DetalleServicioPrestado2 {

    //put your code here
    private $id_detalle_servicio_prestado;

    function __construct() {
        //  parent::__construct();
        $this->id_detalle_servicio_prestado = null;
        
        $this->id_empleador_maestro_empleador = null;
        $this->cod_tipo_actividad = null;
        $this->estado = null;
        $this->fecha_inicio = null;
        $this->fecha_final = null;
    }

    public function getId_detalle_servicio_prestado() {
        return $this->id_detalle_servicio_prestado;
    }

    public function setId_detalle_servicio_prestado($id_detalle_servicio_prestado) {
        $this->id_detalle_servicio_prestado = $id_detalle_servicio_prestado;
    }

}

?>
