<?php
require_once '../util/funciones.php';

$dia = 32;
switch ($dia) {
    case 28:
        $dia = 30;
        break;
    case 29:
        $dia = 30;
        break;        
    case 30:
        $dia = 30;
        break;
    case 31:
        $dia = 30;
        break;
    default:
        break;
}

echoo($dia);




?>
