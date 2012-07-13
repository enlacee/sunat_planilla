<?php

class EstablecimientoCentroCosto {
    
    //put your code here
    private $id_establecimiento_centro_costo;
    private $id_establecimiento;
    private $id_empresa_centro_costo;
    private $seleccionado;
    private $estado;
    
    
    function __construct() {
        $this->id_establecimiento_centro_costo=null;
        $this->id_establecimiento = null;
        $this->id_empresa_centro_costo = null;
        $this->seleccionado=null;
        $this->estado=null;
    }
    
    public function getId_establecimiento_centro_costo() {
        return $this->id_establecimiento_centro_costo;
    }

    public function setId_establecimiento_centro_costo($id_establecimiento_centro_costo) {
        $this->id_establecimiento_centro_costo = $id_establecimiento_centro_costo;
    }

    public function getId_establecimiento() {
        return $this->id_establecimiento;
    }

    public function setId_establecimiento($id_establecimiento) {
        $this->id_establecimiento = $id_establecimiento;
    }

    public function getId_empresa_centro_costo() {
        return $this->id_empresa_centro_costo;
    }

    public function setId_empresa_centro_costo($id_empresa_centro_costo) {
        $this->id_empresa_centro_costo = $id_empresa_centro_costo;
    }

    public function getSeleccionado() {
        return $this->seleccionado;
    }

    public function setSeleccionado($seleccionado) {
        $this->seleccionado = $seleccionado;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }


    
    

}

?>
