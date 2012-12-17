<?php

class PromedioHoraExtra {
    
    private $id_promedio_hextras;
    private $id_pdeclaracion;
    private $id_trabajador;
    private $monto;
    
    function __construct() {
     $this->id_promedio_hextras=null;
     $this->id_declaracion=null;
     $this->id_trabajador=null;
     $this->monto=null;
    }
    
    public function getId_promedio_hextras() {
        return $this->id_promedio_hextras;
    }

    public function setId_promedio_hextras($id_promedio_hextras) {
        $this->id_promedio_hextras = $id_promedio_hextras;
    }

    public function getId_pdeclaracion() {
        return $this->id_pdeclaracion;
    }

    public function setId_pdeclaracion($id_pdeclaracion) {
        $this->id_pdeclaracion = $id_pdeclaracion;
    }

    public function getId_trabajador() {
        return $this->id_trabajador;
    }

    public function setId_trabajador($id_trabajador) {
        $this->id_trabajador = $id_trabajador;
    }

    public function getMonto() {
        return $this->monto;
    }

    public function setMonto($monto) {
        $this->monto = $monto;
    }


    
}

?>
