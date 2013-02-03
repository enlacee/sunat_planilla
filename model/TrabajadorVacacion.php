<?php

class TrabajadorVacacion {

private $id_trabajador_vacacion; // Int UNSIGNED NOT NULL AUTO_INCREMENT,
private $id_pdeclaracion; // Int UNSIGNED NOT NULL,
private $id_trabajador; // Int UNSIGNED NOT NULL,
private $fecha_inicio;
private $fecha_fin;
private $num_dia;
private $fecha_creacion; // Date,    


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


public function getNum_dia() {
    return $this->num_dia;
}

public function setNum_dia($num_dia) {
    $this->num_dia = $num_dia;
}

public function getFecha_creacion() {
    return $this->fecha_creacion;
}

public function setFecha_creacion($fecha_creacion) {
    $this->fecha_creacion = $fecha_creacion;
}



}
?>
