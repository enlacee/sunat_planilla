<?php
require_once("../util/funciones.php");

$hoy= date("Y-m-d");
echo date("Y-m-d", strtotime( "$hoy + 1 day")) ; 

echo "<hr>";

echo "<pre>";
print_r(getFechasDePago("2012-08-01"));
echo "</pre>";
?>