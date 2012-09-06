<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    //CONTROLLER
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    //Actualizar Ptrabajador
    require_once '../model/Ptrabajador.php';
    require_once '../dao/PtrabajadorDao.php';
}

$responce = NULL;
if ($op == "edit") {

    $responce = editar_Ptrabajador();
}


echo (!empty($responce)) ? json_encode($responce) : '';

function editar_Ptrabajador() {
    $model = new Ptrabajador();
    $model->setId_ptrabajador($_REQUEST['id_ptrabajador']);
    $model->setAdelanto($_REQUEST['adelanto']);
    $model->setAsignacion_familiar($_REQUEST['rbtn_afamiliar']);
    $ptf = $_REQUEST['rbtn_ptf'];
    $model->setPara_ti_familia($ptf);
    if ($ptf == "1") {
        $model->setPara_ti_familia_op($_REQUEST['rbtn_tipo_ptf']);
    } else if ($ptf == "0") {
        $model->setPara_ti_familia_op(null);
    }
    
    $model->setAporta_essalud_vida($_REQUEST['rbtn_essaludvida']);
    $model->setAporta_asegura_tu_pension($_REQUEST['rbtn_apension']);
    $model->setDomiciliado($_REQUEST['rbtn_pt_domiciliado']);

    $dao = new PtrabajadorDao();
    return $dao->actualizar($model);
}

function buscar_ID_Ptrabajador($id_ptrabajador) {

    $dao = new PtrabajadorDao();
    $data = $dao->buscar_ID($id_ptrabajador);

    $model = new Ptrabajador();
    $model->setId_ptrabajador($data['id_ptrabajador']);
    $model->setId_trabajador($data['id_trabajador']);
    $model->setAdelanto($data['adelanto']);
    $model->setAsignacion_familiar($data['asignacion_familiar']);
    $model->setPara_ti_familia($data['para_ti_familia']);
    $model->setPara_ti_familia_op($data['para_ti_familia_op']);
    $model->setAporta_essalud_vida($data['aporta_essalud_vida']);
    $model->setAporta_asegura_tu_pension($data['aporta_asegura_tu_pension']);
    $model->setDomiciliado($data['domiciliado']);
    return $model;
}

function existeID_TrabajadorPoPtrabajador($id_trabajador) {

    $dao = new PtrabajadorDao();
    $ID_PTRABAJADOR = $dao->exite_ID_TRABAJADOR($id_trabajador);

    if (is_null($ID_PTRABAJADOR)) {
        $model = new Ptrabajador();
        $model->setId_trabajador($id_trabajador);
        $model->setAdelanto(50);
        $model->setAsignacion_familiar(0);
        $model->setPara_ti_familia(0);

        $model->setAporta_essalud_vida(0);
        $model->setAporta_asegura_tu_pension(0);
        $model->setDomiciliado(0);
        $ID_PTRABAJADOR = $dao->registrar($model);
    }

    return $ID_PTRABAJADOR;
}

//-----------------------------------------------------------------------------
//------------------
function listarPtrabajadores($id_empleador_maestro, $periodo) {


    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction

    if (!$sidx)
        $sidx = 1;

    //llena en al array
    $lista = array();

    $dao = new PtrabajadorDao();
    $lista = $dao->listar($id_empleador_maestro, $periodo);
    $count = count($lista);


    // $count = $count['numfilas'];
    if ($count > 0) {
        $total_pages = ceil($count / $limit); //CONTEO DE PAGINAS QUE HAY
    } else {
        //$total_pages = 0;
    }
    //valida
    if ($page > $total_pages)
        $page = $total_pages;

    // calculate the starting position of the rows
    $start = $limit * $page - $limit; // do not put $limit*($page - 1)
    //valida
    if ($start < 0)
        $start = 0;


    //-------------- LISTA -----------------
// CONTRUYENDO un JSON
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return null;
    }


    foreach ($lista as $rec) {
        $param = $rec["id_ptrabajador"];
        $_00 = $rec["id_trabajador"];
        $_01 = $rec["cod_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];
        $_06 = $rec["dia_laborado"];

        $estado = "A";

        $js = "javascript:cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/edit_trabajador.php?id_ptrabajador=" . $param . "&id_trabajador=" . $_00 . "','#detalle_declaracion_trabajador')";

        $opciones = '<div id="divEliminar_Editar">
          <span  title="Editar" >
          <a href="' . $js . '"><img src="images/edit.png"/></a>
          </span>
          &nbsp;
          </div>';

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            utf8_encode($opciones),
            $estado
        );

        $i++;
    }

    return $responce;
}


