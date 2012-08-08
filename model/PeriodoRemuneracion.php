<?php
class PeriodoRemuneracion {
    //put your code here
    private $cod_periodo_remuneracion;
    private $descripcion;
    private $tasa_pago;
    
    
    function __construct() {
        $this->cod_periodo_remuneracion= null;
        $this->descripcion = null;
        $this->tasa_pago = null;
    }
    
    public function getCod_periodo_remuneracion() {
        return $this->cod_periodo_remuneracion;
    }

    public function setCod_periodo_remuneracion($cod_periodo_remuneracion) {
        $this->cod_periodo_remuneracion = $cod_periodo_remuneracion;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getTasa_pago() {
        return $this->tasa_pago;
    }

    public function setTasa_pago($tasa_pago) {
        $this->tasa_pago = $tasa_pago;
    }


    
    
    
}

?>
