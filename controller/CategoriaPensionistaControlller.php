<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];
if ($op) {
    //Empleador
    //require_once '../model/Empleador.php';
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/EmpleadorDao.php';

    // Categoria Trabajador
    require_once '../model/Pensionista.php';
    require_once '../dao/PensionistaDao.php';

    //Periodo
    require_once '../model/DetallePeriodoLaboral.php';
    require_once '../model/DetallePeriodoLaboralPensionista.php';
    require_once '../dao/DetallePeriodoLaboralPensionistaDao.php';
}

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla(); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "edit") {

    $responce = editarPensionista();
} else {

    //echo "-_-";
}


//echo json_encode($responce);

/**
 * SIN LIBRERIA
 * @param type $ID_PERSONA 
 */
function nuevoPensionista($ID_PERSONA) {
   /* 
    $obj = new Pensionista();
    $obj->setId_persona($ID_PERSONA);
    $obj->setCod_situacion(1); //activo
    $obj->setEstado('INACTIVO');

    $dao = new PensionistaDao();
    $ID_PENSIONISTA = $dao->registrar($obj);

    // ---- sub (periodo) laboral Pensionista
    $objx = new DetallePeriodoLaboralPensionista();
    $objx->setId_pensionista($ID_PENSIONISTA);
    $objx->setCod_motivo_baja_registro(0);
    
    $dao = new DetallePeriodoLaboralPensionistaDao();
    return $dao->registrar($objx);
    */
     
}



function editarPensionista() {

    //Identificador
    $id_pensionista = $_REQUEST['id_pensionista'];

    $pen = new Pensionista();
    $pen->setId_pensionista($id_pensionista);

    $pen->setCod_regimen_pensionario($_REQUEST['cbo_regimen_pensionario']);
    $pen->setCod_tipo_trabajador($_REQUEST['cbo_tipo_pensionista']); //Pensionista o cesante
    $pen->setCod_tipo_pago($_REQUEST['cbo_tipo_pago']);
    $pen->setCuspp($_REQUEST['txt_CUSPP2']);

    // Detalle 1 #Periodo Laboral
    $detalle_1 = new DetallePeriodoLaboralPensionista();
    $detalle_1->setId_pensionista($id_pensionista);
    $detalle_1->setId_detalle_periodo_laboral_pensionista($_REQUEST['id_detalle_periodo_laboral_pensionista']);
    $detalle_1->setCod_motivo_baja_registro($_REQUEST['cbo_plaboral_pensionista_motivo_baja_base']);
    $detalle_1->setFecha_inicio(getFechaPatron($_REQUEST['txt_plaboral_pensionista_finicio_base'], "Y-m-d"));
    $detalle_1->setFecha_fin(getFechaPatron($_REQUEST['txt_plaboral_pensionista_ffin_base'], "Y-m-d"));


    //------------------------------------------
    // Actualizar Pensionista
    $dao = new PensionistaDao();
    $dao->actualizar($pen);

    //-----------------------------------------
    // Actualizar Periodo
    //echo "<pre>";
    //var_dump($detalle_1);
    //echo "</pre>";

    $dao1 = new DetallePeriodoLaboralPensionistaDao();
    $dao1->actualizar($detalle_1);

    return true;
}

function buscarPensionistaPorIdPersona($id_persona) {
    $dao = new PensionistaDao();
    /* buscaTrabajadorPorIdPersona */
    $data = $dao->buscaPensionistaPorIdPersona($id_persona);
    //echo "CONTROLLER DAO";
    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";
    $model = new Pensionista();
    $model->setId_pensionista($data['id_pensionista']);
    $model->setId_persona($data['id_persona']);
    $model->setCod_tipo_trabajador($data['cod_tipo_trabajador']);
    $model->setCod_regimen_pensionario($data['cod_regimen_pensionario']);
    $model->setCod_tipo_pago($data['cod_tipo_pago']);
    $model->setEstado($data['estado']);
    $model->setCuspp($data['cuspp']);
    $model->setCod_situacion($data['descripcion_abreviada']);

//var_dump($model);

    return $model;
}
