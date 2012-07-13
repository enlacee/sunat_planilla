<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];

if ($op) {
    require_once '../dao/AbstractDao.php';
    require_once '../dao/PlameDetalleConceptoDao.php';
    
    require_once '../dao/PlameDetalleConceptoEmpleadorMaestroDao.php';
    
    //---
   

    //IDE_EMPLEADOR_MAESTRO
    //require_once '../controller/ideController.php';
}
$responce = NULL;

if ($op == "cargar_tabla") {

    //$responce = cargar_tabla($_REQUEST['id_concepto']);
} else if ($op == "edit") {

    $responce = actualizarDetalleConceptoEM();
}

//return
echo (!empty($responce)) ? json_encode($responce) : '';

function actualizarDetalleConceptoEM() {
    
    $chek_box = $_REQUEST['chk_detalle_concepto'];
//    echo "<pre>";
//    print_r($chek_box);
//    echo "</pre>";
    $counteo = count($chek_box);
    $dao = new PlameDetalleConceptoEmpleadorMaestroDao();
    
    for ($i = 0; $i < $counteo; $i++) {
        $dao->actualizar($chek_box[$i]);        
    }
    //$dao = new PlameDetalleConceptoEmpleadorMaestroDao();

}

function listarDetalleConceptoEM($cod_concepto, $id_empleador_maestro) {

    $dao = new PlameDetalleConceptoEmpleadorMaestroDao();
    return $dao->listar($cod_concepto, $id_empleador_maestro);
}

function cantidadDetalleConceptoEM($cod_concepto, $id_empleador_maestro) {

    $dao = new PlameDetalleConceptoEmpleadorMaestroDao();
    return $dao->cantidad($cod_concepto, $id_empleador_maestro);
}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

function registrarDetalleConceptoEM($id_empleador_maestro) {

    $dao = new PlameDetalleConceptoEmpleadorMaestroDao();
    $data = $dao->buscarID($id_empleador_maestro);

    if (is_null($data)) { // NO Existe Empleador null
        // Paso 01 = listar Detalle Conceptos         
        $dao_1 = new PlameDetalleConceptoDao();
        $d_detalle_concepto = $dao_1->listarConceptos();

        // Paso 02 = Registrar
        $counteo = count($d_detalle_concepto);

        for ($i = 0; $i < $counteo; $i++) {

            $dao->registrar($id_empleador_maestro, $d_detalle_concepto[$i]['cod_detalle_concepto']);
        }
    }
}

/*

  //-----------------------------------------------------------------------------
  // ALGORITMO PARA CREAR LA TABLA   ==  Table: detalle_conceptos_afectaciones
  //-----------------------------------------------------------------------------

  //DEMO PRUEBA PARA  EMPLEADOR 0
  require_once '../dao/PlameDetalleConceptoAfectacionDao.php';
  require_once '../dao/PlameDetalleConceptoDao.php';
  require_once '../dao/PlameAfectacionDao.php';

  function Table_detalle_conceptos_afectaciones() {

  $dao = new PlameDetalleConceptoAfectacionDao();
  //  $data = $dao->buscarID($id_empleador_maestro);

  if (true){
  //-------
  $dao_0 = new PlameDetalleConceptoDao();
  $cod_detalle_concepto = $dao_0->listarX();

  // ------
  $dao_1 = new PlameAfectacionDao();
  $cod_afectacion = $dao_1->listar();

  $counteox = count($cod_detalle_concepto);
  $counteo = count($cod_afectacion);

  for ($j = 0; $j < $counteox; $j++) {
  for ($i = 0; $i < $counteo; $i++) {
  $dao->registrar($cod_detalle_concepto[$j]['cod_detalle_concepto'], $cod_afectacion[$i]['cod_afectacion']);
  }
  }
  }

  $dao = null;
  }
 */
?>
