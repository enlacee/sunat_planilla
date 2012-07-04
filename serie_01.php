<?php 
session_start();
require_once 'model/Trabajador.php';


$trabajador = new Trabajador();
$trabajador->setId_trabajador("10001");
$trabajador->setCod_tipo_pago("soles");
$trabajador->setEstado("activo");

$trabajador2 = new Trabajador();
$trabajador2->setId_trabajador("20002");
$trabajador2->setCod_tipo_pago("dolares");
$trabajador2->setEstado("inactivo");


$arreglo = array();

$arreglo[0] = $trabajador;
$arreglo[1] = $trabajador2;


$data_serial = serialize($arreglo);

$_SESSION['data_serial'] = $data_serial;


header('Location: serie_02.php');


?>