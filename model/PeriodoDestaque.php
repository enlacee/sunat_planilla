<?php


class PeriodoDestaque {

    private $id_periodo_destaque;
    private $id_personal_tercero;
    private $fecha_inicio;
    private $fecha_fin;
    
    
    function __construct() {
        $this->id_periodo_destaque=null;
        $this->id_personal_tercero=null;
        $this->fecha_inicio=null;
        $this->fecha_fin=null;
    }
    
    public function getId_periodo_destaque() {
        return $this->id_periodo_destaque;
    }

    public function setId_periodo_destaque($id_periodo_destaque) {
        $this->id_periodo_destaque = $id_periodo_destaque;
    }

    public function getId_personal_tercero() {
        return $this->id_personal_tercero;
    }

    public function setId_personal_tercero($id_personal_tercero) {
        $this->id_personal_tercero = $id_personal_tercero;
    }

    public function getFecha_inicio() {
        return $this->fecha_inicio;
    }

    public function setFecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function getFecha_fin() {
        return $this->fecha_fin;
    }

    public function setFecha_fin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }


}

?>
