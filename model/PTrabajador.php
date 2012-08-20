<?php

class Ptrabajador {

   private $id_ptrabajador;
   private $id_trabajador;
   private $aporta_essalud_vida;
   private $aporta_asegura_tu_pension;
   private $domiciliado;
   
   function __construct() {
    $this->id_ptrabajador=null;
    $this->id_trabajador=null;
    $this->aporta_essalud_vida=null;
    $this->aporta_asegura_tu_pension=null;
    $this->domiciliado=null;
}
   

public function getId_ptrabajador() {
    return $this->id_ptrabajador;
}

public function setId_ptrabajador($id_ptrabajador) {
    $this->id_ptrabajador = $id_ptrabajador;
}

public function getId_trabajador() {
    return $this->id_trabajador;
}

public function setId_trabajador($id_trabajador) {
    $this->id_trabajador = $id_trabajador;
}

public function getAporta_essalud_vida() {
    return $this->aporta_essalud_vida;
}

public function setAporta_essalud_vida($aporta_essalud_vida) {
    $this->aporta_essalud_vida = $aporta_essalud_vida;
}

public function getAporta_asegura_tu_pension() {
    return $this->aporta_asegura_tu_pension;
}

public function setAporta_asegura_tu_pension($aporta_asegura_tu_pension) {
    $this->aporta_asegura_tu_pension = $aporta_asegura_tu_pension;
}

public function getDomiciliado() {
    return $this->domiciliado;
}

public function setDomiciliado($domiciliado) {
    $this->domiciliado = $domiciliado;
}


   
    
    
}

?>
