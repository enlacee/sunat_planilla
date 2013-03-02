<?php

class PrestamoCuotaPago {

    private $id_prestamo_cuota_pago;
    private $id_prestamo_cutoa;
    private $id_pdeclaracion;
    private $valor;
    private $descripcion;
    private $fecha_creacion;
        
    public function getId_prestamo_cuota_pago() {
        return $this->id_prestamo_cuota_pago;
    }

    public function setId_prestamo_cuota_pago($id_prestamo_cuota_pago) {
        $this->id_prestamo_cuota_pago = $id_prestamo_cuota_pago;
    }

    public function getId_prestamo_cutoa() {
        return $this->id_prestamo_cutoa;
    }

    public function setId_prestamo_cutoa($id_prestamo_cutoa) {
        $this->id_prestamo_cutoa = $id_prestamo_cutoa;
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

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getFecha_creacion() {
        return $this->fecha_creacion;
    }

    public function setFecha_creacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }



}

?>
