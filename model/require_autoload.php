<?php
require_once '../util/funciones.php';

function __autoload($clas_name){
    require_once "$clas_name.php";
}

$d = new Derechohabiente();
$d->setApellido_materno("Copitan");
echoo($d);

?>
