<?php

class DeclaracionDConceptoVacacion {
    
    	private $id_declaracion_dconcepto_vacacion;// Int UNSIGNED NOT NULL AUTO_INCREMENT,
	private $id_trabajador_vacacion;// Int UNSIGNED NOT NULL,
	private $cod_detalle_concepto;// Char(4) NOT NULL,
	private $monto_devengado;// Decimal(10,2),
	private $monto_pagado;// Decimal(10,2),
    

        public function getId_declaracion_dconcepto_vacacion() {
            return $this->id_declaracion_dconcepto_vacacion;
        }

        public function setId_declaracion_dconcepto_vacacion($id_declaracion_dconcepto_vacacion) {
            $this->id_declaracion_dconcepto_vacacion = $id_declaracion_dconcepto_vacacion;
        }

        
        public function getId_trabajador_vacacion() {
            return $this->id_trabajador_vacacion;
        }

        public function setId_trabajador_vacacion($id_trabajador_vacacion) {
            $this->id_trabajador_vacacion = $id_trabajador_vacacion;
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
