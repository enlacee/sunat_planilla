<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];

if ($op) {
    session_start();
    require_once '../dao/AbstractDao.php';
    require_once '../dao/PlameDetalleConceptoDao.php';

    require_once '../dao/PlameDetalleConceptoEmpleadorMaestroDao.php';

    //---
    //marcar check
    require_once('../dao/PlameDetalleConceptoEmpleadorMaestroDao.php');
    // require_once('../controller/PlameDetalleConceptoEmpleadorMaestroController.php');
    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
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

    $cod_concepto = $_REQUEST['cod_concepto'];
    $chek_box = $_REQUEST['chk_detalle_concepto']; //ids marcados
    // $id_dc_em = $_REQUEST['id_detalle_concepto_em']; //ids
    // PASO 01
    $daoem = new PlameDetalleConceptoEmpleadorMaestroDao();
    $dataEM = $daoem->listar($cod_concepto, ID_EMPLEADOR_MAESTRO);

    // PASO 02  
    //LIMPIAR
    for ($i = 0; $i < count($dataEM); $i++) {
        $daoem->estado($dataEM[$i]['id_detalle_concepto_empleador_maestro'], "0");
    }


    for ($j = 0; $j < count($chek_box); $j++) {

        for ($i = 0; $i < count($dataEM); $i++) {
            if ($dataEM[$i]['id_detalle_concepto_empleador_maestro'] == $chek_box[$j]) {
                $daoem->estado($chek_box[$j], "1");
            }
        }//EFOR 2
    }//EFOR 1





    return true;
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

    require_once '../dao/PlameDetalleConceptoEmpleadorMaestroDao.php';
    require_once '../dao/PlameDetalleConceptoDao.php';

    //-------------------------------------------------------------------------
    // PlameAfectacionDao
    require_once '../dao/PlameAfectacionDao.php';

    require_once '../dao/PlameDetalleConceptoAfectacionEMDao.php';
    //PlameDetalleConceptoAfectacionEMDao
    //-------------------------------------------------------------------------


    $dao = new PlameDetalleConceptoEmpleadorMaestroDao();
    $data = $dao->buscarID_EmpleadorRegistrado($id_empleador_maestro);



    if (is_null($data)) { // NO Existe Empleador null
        // Paso 01 = listar Detalle Conceptos         
        $dao_1 = new PlameDetalleConceptoDao();
        /*
         * PARA QUE TENGA TODOS LOS conceptos sin tener encuenta Tipo de empleador
         *  $dao->listarConceptos();
         */
        $d_detalle_concepto = $dao_1->listarXXX();//SOLO PARA EMPRESAS :::$dao_1->listarXXX()

        //paso 2.01
        $dao_2 = new PlameAfectacionDao();
        $data_afectacion_13 = $dao_2->listar();

        //paso 2.02
        $dao2x = new PlameDetalleConceptoAfectacionEMDao();


        // Paso 02 = Registrar
        $counteo = count($d_detalle_concepto); //CLAVE

        for ($i = 0; $i < $counteo; $i++) {
            //Registra ALL concepto para este Empleador
            $ID_DCEM = $dao->registrar($id_empleador_maestro, $d_detalle_concepto[$i]['cod_detalle_concepto']);


            //-------------------------------------------------------------------
            // ------- SEGUNDO FOR
            for ($x = 0; $x < count($data_afectacion_13); $x++) {
                //registrar tabla inexistente
               // $dao2x->registrar($ID_DCEM, $data_afectacion_13[$x]['cod_afectacion']);
            }//EFOR 02
        }//ENDFOR 01
    }
}



  //-----------------------------------------------------------------------------
  // ALGORITMO PARA CREAR LA TABLA   ==  Table: detalle_conceptos_afectaciones
  //-----------------------------------------------------------------------------
function Table_detalle_conceptos_afectaciones() {
  //DEMO PRUEBA PARA  EMPLEADOR 0
  require_once '../dao/PlameDetalleConceptoAfectacionDao.php';
  require_once '../dao/PlameDetalleConceptoDao.php';
  require_once '../dao/PlameAfectacionDao.php';
      
      
      
  $dao = new PlameDetalleConceptoAfectacionDao();
  //  $data = $dao->buscarID($id_empleador_maestro);

  if (true){
  //-------
  $dao_0 = new PlameDetalleConceptoDao();
  $cod_detalle_concepto = $dao_0->listarXXXSinAfeccion();

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

?>
