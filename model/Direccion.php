<?php

class Direccion {

    private $id_direccion;
    private $cod_ubigeo_reinec;
    private $cod_via;
    private $nombre_via;
    private $numero_via;
    private $departamento;
    private $interior;
    private $manzanza;
    private $lote;
    private $kilometro;
    private $block;
    private $estapa;
    private $cod_zona;
    private $nombre_zona;
    private $referencia;
    private $referente_essalud;
 //   private $estado_direccion;

    function __construct() {
        $this->id_direccion = null;
        $this->cod_ubigeo_reinec = null;
        $this->cod_via = null;
        $this->nombre_via = null;
        $this->numero_via = null;
        $this->departamento = null;
        $this->interior = null;
        $this->manzanza = null;
        $this->lote = null;
        $this->kilometro = null;
        $this->block = null;
        $this->estapa = null;
        $this->cod_zona = null;
        $this->nombre_zona = null;
        $this->referencia = null;
        $this->referente_essalud = null;
       // $this->estado_direccion = null;

    }
    
    public function getId_direccion() {
        return $this->id_direccion;
    }

    public function setId_direccion($id_direccion) {
        $this->id_direccion = $id_direccion;
    }

    public function getCod_ubigeo_reinec() {
        return $this->cod_ubigeo_reinec;
    }

    public function setCod_ubigeo_reinec($cod_ubigeo_reinec) {
        $this->cod_ubigeo_reinec = $cod_ubigeo_reinec;
    }

    public function getCod_via() {
        return $this->cod_via;
    }

    public function setCod_via($cod_via) {
        $this->cod_via = $cod_via;
    }

    public function getNombre_via() {
        return $this->nombre_via;
    }

    public function setNombre_via($nombre_via) {
        $this->nombre_via = $nombre_via;
    }

    public function getNumero_via() {
        return $this->numero_via;
    }

    public function setNumero_via($numero_via) {
        $this->numero_via = $numero_via;
    }

    public function getDepartamento() {
        return $this->departamento;
    }

    public function setDepartamento($departamento) {
        $this->departamento = $departamento;
    }

    public function getInterior() {
        return $this->interior;
    }

    public function setInterior($interior) {
        $this->interior = $interior;
    }

    public function getManzanza() {
        return $this->manzanza;
    }

    public function setManzanza($manzanza) {
        $this->manzanza = $manzanza;
    }

    public function getLote() {
        return $this->lote;
    }

    public function setLote($lote) {
        $this->lote = $lote;
    }

    public function getKilometro() {
        return $this->kilometro;
    }

    public function setKilometro($kilometro) {
        $this->kilometro = $kilometro;
    }

    public function getBlock() {
        return $this->block;
    }

    public function setBlock($block) {
        $this->block = $block;
    }

    public function getEstapa() {
        return $this->estapa;
    }

    public function setEstapa($estapa) {
        $this->estapa = $estapa;
    }

    public function getCod_zona() {
        return $this->cod_zona;
    }

    public function setCod_zona($cod_zona) {
        $this->cod_zona = $cod_zona;
    }

    public function getNombre_zona() {
        return $this->nombre_zona;
    }

    public function setNombre_zona($nombre_zona) {
        $this->nombre_zona = $nombre_zona;
    }

    public function getReferencia() {
        return $this->referencia;
    }

    public function setReferencia($referencia) {
        $this->referencia = $referencia;
    }

    public function getReferente_essalud() {
        return $this->referente_essalud;
    }

    public function setReferente_essalud($referente_essalud) {
        $this->referente_essalud = $referente_essalud;
    }
/*
    public function getEstado_direccion() {
        return $this->estado_direccion;
    }

    public function setEstado_direccion($estado_direccion) {
        $this->estado_direccion = $estado_direccion;
    }

*/

}

?>
