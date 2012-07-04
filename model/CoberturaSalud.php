<?php


class CoberturaSalud {
    //put your code here
    
    private $id_cobertura_salud;
    private $id_personal_tercero;
    private $nombre_cobertura;
    private $fecha_inicio;
    private $fecha_fin;
    
    function __construct() {
        $this->id_cobertura_salud=null;
        $this->id_personal_tercero=null;
        $this->nombre_cobertura=null;
        $this->fecha_inicio = null;
        $this->fecha_fin = null;
    }
    
    public function getId_cobertura_salud() {
        return $this->id_cobertura_salud;
    }

    public function setId_cobertura_salud($id_cobertura_salud) {
        $this->id_cobertura_salud = $id_cobertura_salud;
    }

    public function getId_personal_tercero() {
        return $this->id_personal_tercero;
    }

    public function setId_personal_tercero($id_personal_tercero) {
        $this->id_personal_tercero = $id_personal_tercero;
    }

    public function getNombre_cobertura() {
        return $this->nombre_cobertura;
    }

    public function setNombre_cobertura($nombre_cobertura) {
        $this->nombre_cobertura = $nombre_cobertura;
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
