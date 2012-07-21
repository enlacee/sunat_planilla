<?php

class Dcem_Pingreso {

    //put your code here

    private $id_dcem_pingreso;
    private $id_ptrabajador;
    private $id_detalle_concepto_empleador_maestro;
    private $devengado;
    private $pagado;

    public function __construct() {
        $this->id_dcem_pingreso = null;
        $this->id_ptrabajador = null;
        $this->id_detalle_concepto_empleador_maestro = null;
        $this->devengado = null;
        $this->pagado = null;
    }

    public function getId_dcem_pingreso() {
        return $this->id_dcem_pingreso;
    }

    public function setId_dcem_pingreso($id_dcem_pingreso) {
        $this->id_dcem_pingreso = $id_dcem_pingreso;
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

    public function getDevengado() {
        return $this->devengado;
    }

    public function setDevengado($devengado) {
        $this->devengado = $devengado;
    }

    public function getPagado() {
        return $this->pagado;
    }

    public function setPagado($pagado) {
        $this->pagado = $pagado;
    }

}

?>
