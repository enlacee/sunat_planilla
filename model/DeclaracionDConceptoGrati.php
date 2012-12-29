<?php

class DeclaracionDConceptoGrati {
    
    private $id_declaracion_dconcepto_grati;
    private $id_trabajador_grati;
    private $cod_detalle_concepto;
    private $monto_devengado;
    private $monto_pagado;    
    
    function __construct() {
        $this->id_declaracion_dconcepto_grati = null;
        $this->id_trabajador_grati=null;
        $this->cod_detalle_concepto = null;
        $this->monto_devengado = null;
        $this->monto_pagado = null;
    }
    
    public function getId_declaracion_dconcepto_grati() {
        return $this->id_declaracion_dconcepto_grati;
    }

    public function setId_declaracion_dconcepto_grati($id_declaracion_dconcepto_grati) {
        $this->id_declaracion_dconcepto_grati = $id_declaracion_dconcepto_grati;
    }

    public function getId_trabajador_grati() {
        return $this->id_trabajador_grati;
    }

    public function setId_trabajador_grati($id_trabajador_grati) {
        $this->id_trabajador_grati = $id_trabajador_grati;
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
