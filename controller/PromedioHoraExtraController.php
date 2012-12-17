<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../controller/ideController.php';

    //trabajador    
    require_once '../model/PromedioHoraExtra.php';
    require_once '../dao/PromedioHoraExtraDao.php';
    
}

$response = NULL;

if ($op == "cargar_tabla") {
    $response = listarPHE();
} else if ($op == 'add') {
    $response = addPHE();
} else if ($op == 'edit') {
    $response = editPHE();
} else if ($op == 'del') {
    $response = delPHE();
}

echo (!empty($response)) ? json_encode($response) : '';


function listarPHE() {

    $periodo = $_REQUEST['periodo'];
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];

    $dao = new PromedioHoraExtraDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction

    $WHERE = "";

    if (isset($_GET['searchField']) && ($_GET['searchString'] != null)) {

        $operadores["eq"] = "=";
        $operadores["ne"] = "<>";
        $operadores["lt"] = "<";
        $operadores["le"] = "<=";
        $operadores["gt"] = ">";
        $operadores["ge"] = ">=";
        $operadores["cn"] = "LIKE";
        if ($_GET['searchOper'] == "cn")
            $WHERE = "AND " . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . " '%" . $_GET['searchString'] . "%' ";
        else
            $WHERE = "AND " . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . "'" . $_GET['searchString'] . "'";
    }


    if (!$sidx)
        $sidx = 1;

    $count = $dao->listaCount($id_pdeclaracion, $WHERE);

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

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    $lista = array();
    $lista = $dao->lista($id_pdeclaracion, $WHERE, $start, $limit, $sidx, $sord);

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
//print_r($lista);

    foreach ($lista as $rec) {

        $param = $rec["id_promedio_hextras"];
        $_01 = $rec['id_trabajador'];
        $_02 = $rec['num_documento'];
        $_03 = $rec['apellido_paterno'];
        $_04 = $rec['apellido_materno'];
        $_05 = $rec['nombres'];
        $_06 = $rec['monto'];

        // $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/editar_trabajador.php?id_pago=" . $param . "&id_trabajador=" . $_00 . "','#detalle_declaracion_trabajador')";
        //hereee
        $response->rows[$i]['id'] = $param; //$param;
        $response->rows[$i]['cell'] = array(
            $key,
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06
        );
        $i++;
    }

//echo "<pre>";
//print_r($response);
//echo "</pre>";
    return $response;
}

function addPHE() {

    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $id_trabajador = $_REQUEST['id_trabajador'];

    $rpta->estado = "default";
    /**
     * Pregunta si esta registrado en  
     * verifica. No hay Duplicado.
     */
    $dao = new PromedioHoraExtraDao();
    $id = $dao->buscar_ID_trabajador($id_pdeclaracion, $id_trabajador);

    //echoo($datax);

    if (is_null($id)) {

        $model = new PromedioHoraExtra();
        $model->setId_pdeclaracion($id_pdeclaracion);
        $model->setId_trabajador($id_trabajador);        
        $rpta->estado = $dao->add($model);
    } else if (isset($id)) {

        //trabajador ya esta registrado..
        $rpta->estado = false;
        $rpta->mensaje = "El Trabajador ya se encuentra Registrado en esta Lista.";
    }


    return $rpta;
}

function editPHE() {
    
    $id = $_REQUEST['id'];
    $valor = $_REQUEST['monto'];

    $obj = new PromedioHoraExtra();
    $obj->setId_promedio_hextras($id);
    $obj->setMonto($valor);

    $dao = new PromedioHoraExtraDao();
    return $dao->edit($obj);    
}

function delPHE() {
    $dao = new PromedioHoraExtraDao();     
    return $dao->del($_REQUEST['id']);    
}

?>
