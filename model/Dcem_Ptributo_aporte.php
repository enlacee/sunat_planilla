<?php

class Dcem_Ptributo_aporte {

    //put your code here
    private $id_dcem_ptributo_aporte;
    private $id_ptrabajador;
    private $id_detalle_concepto_empleador_maestro;
    private $monto;

    public function __construct() {
        $this->id_dcem_ptributo_aporte = null;
        $this->id_ptrabajador = null;
        $this->id_detalle_concepto_empleador_maestro = null;
        $this->monto = null;
    }
    
    public function getId_dcem_ptributo_aporte() {
        return $this->id_dcem_ptributo_aporte;
    }

    public function setId_dcem_ptributo_aporte($id_dcem_ptributo_aporte) {
        $this->id_dcem_ptributo_aporte = $id_dcem_ptributo_aporte;
    }

    public function getId_ptrabajador() {
        return $this->id_ptrabajador;
    }

    public function setId_ptrabajador($id_ptrabajador) {
        $this->id_ptrabajador = $id_ptrabajador;
    }

    public function getId_detalle_concepto_empleador_maestro() {
        return $this->id_detalle_concepto_empleador_maestro;
    }

    public function setId_detalle_concepto_empleador_maestro($id_detalle_concepto_empleador_maestro) {
        $this->id_detalle_concepto_empleador_maestro = $id_detalle_concepto_empleador_maestro;
    }

    public function getMonto() {
        return $this->monto;
    }

    public function setMonto($monto) {
        $this->monto = $monto;
    }


    
    
    
    
    
    
    
    
    
    

}

?>
