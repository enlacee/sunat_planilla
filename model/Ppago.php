<?php

class Ppago {
    private $id_ppago;
    private $id_prestamo;
    private $id_pdeclaracion;
    private $valor;
    private $fecha;
    
    function __construct() {
        $this->id_ppago=null;
        $this->id_prestamo=null;
        $this->id_pdeclaracion=null;
        $this->valor=null;
        $this->fecha=null;
    }
    public function getId_ppago() {
        return $this->id_ppago;
    }

    public function setId_ppago($id_ppago) {
        $this->id_ppago = $id_ppago;
    }

    public function getId_prestamo() {
        return $this->id_prestamo;
    }

    public function setId_prestamo($id_prestamo) {
        $this->id_prestamo = $id_prestamo;
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
