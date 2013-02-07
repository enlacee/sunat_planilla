<?php
class PagoQuincena {
    
  private $id_pago_quincena;
  private $id_pdeclaracion;
  private $id_trabajador;
  private $dia_laborado;
  private $sueldo_base;
  private $sueldo_porcentaje;
  private $sueldo;
  private $devengado;
  private $id_empresa_centro_costo;
  private $fecha_creacion;
  
  public function getId_pago_quincena() {
      return $this->id_pago_quincena;
  }

  public function setId_pago_quincena($id_pago_quincena) {
      $this->id_pago_quincena = $id_pago_quincena;
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

  public function getDia_laborado() {
      return $this->dia_laborado;
  }

  public function setDia_laborado($dia_laborado) {
      $this->dia_laborado = $dia_laborado;
  }

  public function getSueldo_base() {
      return $this->sueldo_base;
  }

  public function setSueldo_base($sueldo_base) {
      $this->sueldo_base = $sueldo_base;
  }

  public function getSueldo_porcentaje() {
      return $this->sueldo_porcentaje;
  }

  public function setSueldo_porcentaje($sueldo_porcentaje) {
      $this->sueldo_porcentaje = $sueldo_porcentaje;
  }

  public function getSueldo() {
      return $this->sueldo;
  }

  public function setSueldo($sueldo) {
      $this->sueldo = $sueldo;
  }

  public function getDevengado() {
      return $this->devengado;
  }

  public function setDevengado($devengado) {
      $this->devengado = $devengado;
  }

  public function getId_empresa_centro_costo() {
      return $this->id_empresa_centro_costo;
  }

  public function setId_empresa_centro_costo($id_empresa_centro_costo) {
      $this->id_empresa_centro_costo = $id_empresa_centro_costo;
  }

  public function getFecha_creacion() {
      return $this->fecha_creacion;
  }

  public function setFecha_creacion($fecha_creacion) {
      $this->fecha_creacion = $fecha_creacion;
  }

    
}

?>
