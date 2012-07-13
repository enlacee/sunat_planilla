<?php

$op = $_REQUEST["oper"];
if ($op) {
    //session_start();
    //IDE_EMPLEADOR_MAESTRO
    //require_once '../controller/ideController.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/PlameDetalleConceptoAfectacionDao.php';
}

$responce = NULL;
if ($op == "edit") {
    $responce = editarDetalleConceptoAfectacion();
}

echo (!empty($responce)) ? json_encode($responce) : '';

function listarDetalleConceptoAfectacion($cod_detalle_concepto) {
    $dao = new PlameDetalleConceptoAfectacionDao();
    return $dao->listar($cod_detalle_concepto);
}

function editarDetalleConceptoAfectacion() {

// $dao = new PlameDetalleConceptoAfectacionDao();
    return true;
    //return $dao->actualizar($id);


    $seleccionado = $_REQUEST['seleccionado'];
    $cod_afectacion = $_REQUEST['cod_afectacion'];
//    echo "<pre>";
//    print_r($chek_box);
//    echo "</pre>";
    $counteo = count($seleccionado);
    $dao = new PlameDetalleConceptoEmpleadorMaestroDao();

    for ($i = 0; $i < $counteo; $i++) {
        if( ){ ???? //si codigo == 1  //OK;
            $dao->actualizar($cod_afectacion[$i]);
        }
    }
}

?>
