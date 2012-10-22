<?php
require_once '../util/funciones.php';

$id_trabajador = array(1,2,1,5);
$id_trabajador2  = array(); //null




$array = array_merge($id_trabajador, $id_trabajador2);

$inputarray = array_unique($array);

echoo($array);
echoo($inputarray);

$return = array_values($inputarray);

echoo($return);


$e = array_unico_ordenado($id1, $id2);

echoo($e);


?>
