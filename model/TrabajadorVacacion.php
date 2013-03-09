<?php

class TrabajadorVacacion {

private $id_trabajador_vacacion; // Int UNSIGNED NOT NULL AUTO_INCREMENT,
private $id_pdeclaracion; // Int UNSIGNED NOT NULL,
private $id_trabajador; // Int UNSIGNED NOT NULL,
private $fecha_lineal;
private $dia;
private $sueldo;
private $sueldo_base;
private $proceso_porcentaje;
private $cod_regimen_pensionario;
private $cod_regimen_aseguramiento_salud;
private $id_empresa_centro_costo;
private $id_establecimiento;
private $cod_ocupacion_p;
private $fecha_creacion;
private $fecha_actualizacion;

public function getId_trabajador_vacacion() {
    return $this->id_trabajador_vacacion;
}

public function setId_trabajador_vacacion($id_trabajador_vacacion) {
    $this->id_trabajador_vacacion = $id_trabajador_vacacion;
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

public function getFecha_lineal() {
    return $this->fecha_lineal;
}

public function setFecha_lineal($fecha_lineal) {
    $this->fecha_lineal = $fecha_lineal;
}

public function getDia() {
    return $this->dia;
}

public function setDia($dia) {
    $this->dia = $dia;
}

public function getSueldo() {
    return $this->sueldo;
}

public function setSueldo($sueldo) {
    $this->sueldo = $sueldo;
}

public function getSueldo_base() {
    return $this->sueldo_base;
}

public function setSueldo_base($sueldo_base) {
    $this->sueldo_base = $sueldo_base;
}

public function getProceso_porcentaje() {
    return $this->proceso_porcentaje;
}

public function setProceso_porcentaje($proceso_porcentaje) {
    $this->proceso_porcentaje = $proceso_porcentaje;
}

public function getCod_regimen_pensionario() {
    return $this->cod_regimen_pensionario;
}

public function setCod_regimen_pensionario($cod_regimen_pensionario) {
    $this->cod_regimen_pensionario = $cod_regimen_pensionario;
}

public function getCod_regimen_aseguramiento_salud() {
    return $this->cod_regimen_aseguramiento_salud;
}

public function setCod_regimen_aseguramiento_salud($cod_regimen_aseguramiento_salud) {
    $this->cod_regimen_aseguramiento_salud = $cod_regimen_aseguramiento_salud;
}

public function getId_empresa_centro_costo() {
    return $this->id_empresa_centro_costo;
}

public function setId_empresa_centro_costo($id_empresa_centro_costo) {
    $this->id_empresa_centro_costo = $id_empresa_centro_costo;
}

public function getId_establecimiento() {
    return $this->id_establecimiento;
}

public function setId_establecimiento($id_establecimiento) {
    $this->id_establecimiento = $id_establecimiento;
}

public function getFecha_creacion() {
    return $this->fecha_creacion;
}
public function getCod_ocupacion_p() {
    return $this->cod_ocupacion_p;
}

public function setCod_ocupacion_p($cod_ocupacion_p) {
    $this->cod_ocupacion_p = $cod_ocupacion_p;
}

public function setFecha_creacion($fecha_creacion) {
    $this->fecha_creacion = $fecha_creacion;
}

public function getFecha_actualizacion() {
    return $this->fecha_actualizacion;
}

public function setFecha_actualizacion($fecha_actualizacion) {
    $this->fecha_actualizacion = $fecha_actualizacion;
}



}
?>
