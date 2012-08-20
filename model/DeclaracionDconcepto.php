<?php

class DeclaracionDconcepto {

    //put your code here

    private $id_declaracion_dconcepto;
    private $id_trabajador_pdeclaracion;
    private $cod_detalle_concepto;
    private $monto_devengado;
    private $monto_pagado;

    function __construct() {
        $this->id_declaracion_dconcepto = null;
        $this->id_trabajador_pdeclaracion = null;
        $this->cod_detalle_concepto = null;
        $this->monto_devengado = null;
        $this->monto_pagado = null;
    }

    
    public function getId_declaracion_dconcepto() {
        return $this->id_declaracion_dconcepto;
    }

    public function setId_declaracion_dconcepto($id_declaracion_dconcepto) {
        $this->id_declaracion_dconcepto = $id_declaracion_dconcepto;
    }

    public function getId_trabajador_pdeclaracion() {
        return $this->id_trabajador_pdeclaracion;
    }

    public function setId_trabajador_pdeclaracion($id_trabajador_pdeclaracion) {
        $this->id_trabajador_pdeclaracion = $id_trabajador_pdeclaracion;
    }

    public function getCod_detalle_concepto() {
        return $this->cod_detalle_concepto;
    }

    public function setCod_detalle_concepto($cod_detalle_concepto) {
        $this->cod_detalle_concepto = $cod_detalle_concepto;
    }

    public function getMonto_devengado() {
        return $this->monto_devengado;
    }

    public function setMonto_devengado($monto_devengado) {
        $this->monto_devengado = $monto_devengado;
    }

    public function getMonto_pagado() {
        return $this->monto_pagado;
    }

    public function setMonto_pagado($monto_pagado) {
        $this->monto_pagado = $monto_pagado;
    }



}

?>
