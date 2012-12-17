<?php

function buscarIdEmpleadorMaestroPorIDEMPLEADOR($id_empleador) {

    $dao = new EmpleadorMaestroDao();
    $data = $dao->buscarIdEmpleadorMaestroPorIDEMPLEADOR($id_empleador);
    return $data;
}

?>