<?php

class DetallePeriodoLaboralPensionista extends DetallePeriodoLaboral {

    //put your code here
    private $id_detalle_periodo_laboral_pensionista;
    private $id_pensionista;

    function __construct() {
        $this->id_detalle_periodo_laboral_pensionista = null;
        $this->cod_motivo_baja_registro = null;
        $this->id_pensionista = null;
        $this->fecha_inicio = null;
        $this->fecha_fin = null;
    }

    public function getId_detalle_periodo_laboral_pensionista() {
        return $this->id_detalle_periodo_laboral_pensionista;
    }

    public function setId_detalle_periodo_laboral_pensionista($id_detalle_periodo_laboral_pensionista) {
        $this->id_detalle_periodo_laboral_pensionista = $id_detalle_periodo_laboral_pensionista;
    }

    public function getId_pensionista() {
        return $this->id_pensionista;
    }

    public function setId_pensionista($id_pensionista) {
        $this->id_pensionista = $id_pensionista;
    }

}

?>
