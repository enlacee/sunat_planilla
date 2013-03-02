<?php

class PrestamoCuota {

    private $id_prestamo_cutoa;
    private $id_prestamo;
    private $monto;
    private $monto_variable;
    private $fecha_calc;
    private $estado;

    public function getId_prestamo_cutoa() {
        return $this->id_prestamo_cutoa;
    }

    public function setId_prestamo_cutoa($id_prestamo_cutoa) {
        $this->id_prestamo_cutoa = $id_prestamo_cutoa;
    }

    public function getId_prestamo() {
        return $this->id_prestamo;
    }

    public function setId_prestamo($id_prestamo) {
        $this->id_prestamo = $id_prestamo;
    }

    public function getMonto() {
        return $this->monto;
    }

    public function setMonto($monto) {
        $this->monto = $monto;
    }

    public function getMonto_variable() {
        return $this->monto_variable;
    }

    public function setMonto_variable($monto_variable) {
        $this->monto_variable = $monto_variable;
    }

    public function getFecha_calc() {
        return $this->fecha_calc;
    }

    public function setFecha_calc($fecha_calc) {
        $this->fecha_calc = $fecha_calc;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

}

?>
