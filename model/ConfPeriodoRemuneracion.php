<?php

class ConfPeriodoRemuneracion {
    //put your code here
    private $id_conf_periodo_remuneracion;
    private $cod_periodo_remuneracion;
    private $id_empleador_maestro;
    private $valor;
    private $estado;
    
    
    public function __construct() {
     $this->id_conf_periodo_remuneracion=null;
     $this->cod_periodo_remuneracion=null;
     $this->id_empleador_maestro=null;
     $this->valor=null;
     $this->estado=null;
    }
  
    public function getId_conf_periodo_remuneracion() {
        return $this->id_conf_periodo_remuneracion;
    }

    public function setId_conf_periodo_remuneracion($id_conf_periodo_remuneracion) {
        $this->id_conf_periodo_remuneracion = $id_conf_periodo_remuneracion;
    }

    public function getCod_periodo_remuneracion() {
        return $this->cod_periodo_remuneracion;
    }

    public function setCod_periodo_remuneracion($cod_periodo_remuneracion) {
        $this->cod_periodo_remuneracion = $cod_periodo_remuneracion;
    }

    public function getId_empleador_maestro() {
        return $this->id_empleador_maestro;
    }

    public function setId_empleador_maestro($id_empleador_maestro) {
        $this->id_empleador_maestro = $id_empleador_maestro;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }



    
}

?>
