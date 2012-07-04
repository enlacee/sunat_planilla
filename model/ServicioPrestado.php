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
 class ServicioPrestado extends ServicioPrestadoYourself{

    //put your code here
    private $id_servicio_prestado;
    protected $id_empleador_destaque;

    function __construct() {
        $this->id_servicio_prestado =null;
        $this->id_empleador_destaque=null;
        $this->cod_tipo_actividad=null;
        $this->fecha_inicio=null;
        $this->fecha_fin=null;
        $this->estado = null;
    }
    
    public function getId_servicio_prestado() {
        return $this->id_servicio_prestado;
    }

    public function setId_servicio_prestado($id_servicio_prestado) {
        $this->id_servicio_prestado = $id_servicio_prestado;
    }

    public function getId_empleador_destaque() {
        return $this->id_empleador_destaque;
    }

    public function setId_empleador_destaque($id_empleador_destaque) {
        $this->id_empleador_destaque = $id_empleador_destaque;
    }


    
    
   


   



}

?>
