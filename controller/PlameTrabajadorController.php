<?php
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../dao/AbstractDao.php';
    require_once '../dao/PtrabajadorDao.php';
    //CONTROLLER
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    require_once '../util/funciones.php';
}

$responce = NULL;
if ($op == "cargar_tabla") {
    //--------------------Inicio Configuracion Basica---------------------------
    //Variables
    $periodo = ($_REQUEST['periodo']) ? $_REQUEST['periodo'] : "08/1988";
    $fecha_ISO = "01/" . $periodo;    // DIA/MES/ANIO
    $FECHA = getMesInicioYfin($fecha_ISO);
    //--------------------Final Configuracion Basica----------------------------
    
    $responce = listarPtrabajadores(ID_EMPLEADOR_MAESTRO, $FECHA['mes_inicio']);
}

echo (!empty($responce)) ? json_encode($responce) : '';

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
        $_01 = $rec["cod_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];
        $_06 = $rec["dia_laborado"];

        $estado = "A";

        $js = "javascript:cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/edit_trabajador.php?id_ptrabajador=" . $param . "','#detalle_declaracion_trabajador')";

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


//------------------ FN_VIEW
function buscar_IDPtrabajador($id_ptrabajador){
    /*
    $dao = new PtrabajadorDao();
    $dao->
    */        
    $dao = new PtrabajadorDao();
    $data = $dao->buscar_ID($id_ptrabajador);
    
    $model = new PTrabajador();
    $model->setId_ptrabajador($data['id_ptrabajador']);
    $model->setId_pdeclaracion($data['id_pdeclaracion']);
    $model->setId_trabajador($data['id_trabajador']);
    $model->setAporta_essalud_vida($data['aporta_essalud_vida']);    
    $model->setAporta_asegura_tu_pension($data['aporta_asegura_tu_pension']);
    $model->setDomiciliado($data['domiciliado']);
    $model->setIngreso_5ta_categoria($data['ingreso_5ta_categoria']);    
    
    return $model;
    
    
}
