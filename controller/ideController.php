<?php

//Obtener Comunmente  id_empleador_maestro
require_once '../dao/EmpleadorMaestroDao.php';
require_once '../controller/EmpleadorMaestroController.php';

// ----------- inicio ----- Identificacion Empleador Sistema -------------//
$ID_EMPLEADOR = $_SESSION['sunat_empleador']['id_empleador'];
$RUC = $_SESSION['sunat_empleador']['ruc'];

// id empleador maestro
$data_empMaestro = buscarIdEmpleadorMaestroPorIDEMPLEADOR($ID_EMPLEADOR);

$ID_EMPLEADOR_MAESTRO = $data_empMaestro['id_empleador_maestro']; //++
// DEFINE
if (is_null($ID_EMPLEADOR) || is_null($RUC) || is_null($ID_EMPLEADOR_MAESTRO)) {
    //header($string, $replace)
    header('Location: index.php');
} else {
    define('ID_EMPLEADOR', $ID_EMPLEADOR);
    define('RUC', $RUC);
    define('ID_EMPLEADOR_MAESTRO', $ID_EMPLEADOR_MAESTRO);
}
// ----------- finall ----- Identificacion Empleador Sistema -------------//

/*  echo "<pre>ID_EMPLEADOR";
  print_r(ID_EMPLEADOR);
  echo "</pre>";
  echo "<hr>";
  echo "<pre>RUC";
  print_r(RUC);
  echo "</pre>";
  echo "<hr>";
  echo "<pre>ID_EMPLEADOR_MAESTRO";
  print_r(ID_EMPLEADOR_MAESTRO);
  echo "</pre>";
  echo "<hr>";
  */
 
?>
