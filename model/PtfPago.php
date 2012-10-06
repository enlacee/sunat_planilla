<?php
class PtfPago{
    
    private $id_ptf_pago;
    private $id_para_ti_familia;
    private $id_pdeclaracion;
    private $valor;
    private $fecha;
    
    
    function __construct() {
     $this->id_ptf_pago=null;
     $this->id_para_ti_familia=null;
     $this->id_pdeclaracion=null;
     $this->valor=null;
     $this->fecha=null;        
    }
       
    public function getId_ptf_pago() {
        return $this->id_ptf_pago;
    }

    public function setId_ptf_pago($id_ptf_pago) {
        $this->id_ptf_pago = $id_ptf_pago;
    }

    public function getId_para_ti_familia() {
        return $this->id_para_ti_familia;
    }

    public function setId_para_ti_familia($id_para_ti_familia) {
        $this->id_para_ti_familia = $id_para_ti_familia;
    }

    public function getId_pdeclaracion() {
        return $this->id_pdeclaracion;
    }

    public function setId_pdeclaracion($id_pdeclaracion) {
        $this->id_pdeclaracion = $id_pdeclaracion;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }


    

    
}

?>
