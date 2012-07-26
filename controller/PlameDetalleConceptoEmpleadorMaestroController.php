<?php
$op = $_REQUEST["oper"];

if ($op) {
    session_start();
    require_once '../dao/AbstractDao.php';
    require_once '../dao/PlameDetalleConceptoDao.php';

    require_once '../dao/PlameDetalleConceptoEmpleadorMaestroDao.php';

    //marcar check
    require_once('../dao/PlameDetalleConceptoEmpleadorMaestroDao.php');
    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
}

$responce = NULL;

if ($op == "edit") {
    $responce = actualizarDetalleConceptoEM();
    
} else if ($op == 'id_check') {

    $cod_detalle_concepto = $_REQUEST['cod_detalle_concepto'];
    //echo ID_EMPLEADOR_MAESTRO;
    $responce = buscarID_CheckConcepto1000_EM(ID_EMPLEADOR_MAESTRO, $cod_detalle_concepto);
} else if ($op == 'actualizar-concepto-1000') {

    $responce = actualizarDetalleConceptoEM1000();
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

    /**
     * Array Seleccionados por Defaul 
     */
    $arreglo1 = array('0105', '0106', '0107', '0118', '0121', '0122', '0201', '0306', '0701', '0702', '0706');
    
    //tributos y aportes. ONP Y AFP    
    $arreglo2 = array('0601', '0602','0604' , '0605', '0606', '0607','0608', '0609', '0611','0612', '0801', '0803','0804','0809');
    
    $arreglo = array_merge($arreglo1, $arreglo2);

    if (is_null($data)) { // NO Existe Empleador null
        // Paso 01 = listar Detalle Conceptos         
        $dao_1 = new PlameDetalleConceptoDao();
        /*
         * PARA QUE TENGA TODOS LOS conceptos sin tener encuenta Tipo de empleador
         *  $dao->listarConceptos();
         */
        $d_detalle_concepto = $dao_1->listarXXX(); //SOLO PARA EMPRESAS :::$dao_1->listarXXX()
        //paso 2.01
        $dao_2 = new PlameAfectacionDao();
        $data_afectacion_13 = $dao_2->listar();

        //paso 2.02
        $dao2x = new PlameDetalleConceptoAfectacionEMDao();


        // Paso 02 = Registrar
        $counteo = count($d_detalle_concepto); //CLAVE

        for ($i = 0; $i < $counteo; $i++) {
            //Registra ALL concepto para este Empleador
            if (in_array($d_detalle_concepto[$i]['cod_detalle_concepto'], $arreglo)) {
                $ID_DCEM = $dao->registrar($id_empleador_maestro, $d_detalle_concepto[$i]['cod_detalle_concepto'],'1');
            }else{
                $dao->registrar($id_empleador_maestro, $d_detalle_concepto[$i]['cod_detalle_concepto'],'0');
            }

            //-------------------------------------------------------------------
            // ------- SEGUNDO FOR
            for ($x = 0; $x < count($data_afectacion_13); $x++) {
                //registrar tabla inexistente
                // $dao2x->registrar($ID_DCEM, $data_afectacion_13[$x]['cod_afectacion']);
            }//EFOR 02
        }//ENDFOR 01
    }
}

/**
 * Utilizando EN concepto 1000 JAVASCRIPT 
 * view-plame/detalle/view_detalle_concepto_1000.php
 * Return id_detalle_concepto_empleador_maestro 
 * correspondiente ah EM
 */
function buscarID_CheckConcepto1000_EM($id_empleador_maestro, $cod_detalle_concepto) {
    $dao = new PlameDetalleConceptoEmpleadorMaestroDao();
    $id = $dao->buscarID_CheckConcepto1000_EM($id_empleador_maestro, $cod_detalle_concepto);
    return $id;
}

function actualizarDetalleConceptoEM1000() {

    $cod_concepto = $_REQUEST['cod_concepto'];


    $descripcion_1000 = $_REQUEST['concepto_descripcion_1000'];
    $estado = $_REQUEST['estado'];
    $id = $_REQUEST['id'];


    $chek_box = $_REQUEST['chk_detalle_concepto']; //ids marcados
    //ACTUALIZAR DESCRIPCION 1000
    // PASO 01
    $daoem = new PlameDetalleConceptoEmpleadorMaestroDao();
    $dataEM = $daoem->listar($cod_concepto, ID_EMPLEADOR_MAESTRO);

    for ($i = 0; $i < count($id); $i++) {
        //if($estado[$i] == 0){
        $daoem->actualizarConceptoDescripcion1000($id[$i], $descripcion_1000[$i]);
        //}
    }


    //LIMPIAR CHECKBOX 0
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

    return 1;
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

    if (true) {
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
