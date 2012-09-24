<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    require_once '../dao/ConfAfpDao.php';
}

$responce = NULL;

if ($op == "cargar_tabla") {

    $responce = listarAfp();
} else if ($op == "edit") {
    $responce = edit();
} else if ($op == "add") {
    $responce = add();
} else if ($op == "del") {
    $responce = del();
}

echo (!empty($responce)) ? json_encode($responce) : '';

function listarAfp() {
    $dao = new ConfAfpDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction




    if (!$sidx)
        $sidx = 1;

    $lista = array();
    $lista = $dao->listar();
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

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
//print_r($lista);

    foreach ($lista as $rec) {

        $param = $rec["id_conf_afp"];
        $_00 = $rec['descripcion_abreviada'];
        $_01 = $rec['aporte_obligatorio'];
        $_02 = $rec['comision'];
        $_03 = $rec['prima_seguro'];
        $_04 = $rec['fecha'];
        //hereee
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_00,
            $_01,
            $_02,
            $_03,
            $_04
        );
        $i++;
    }

    return $response;
}

function add() {
    $cod_regimen_pensionario = $_REQUEST['cod_regimen_pensionario'];
    $fecha_vigencia = $_REQUEST['fecha'];
    $ap_obligatorio = $_REQUEST['aporte_obligatorio'];
    $comision = $_REQUEST['comision'];
    $prima_seguro = $_REQUEST['prima_seguro'];

    $fecha_vigencia = getFechaPatron($fecha_vigencia, "Y-m-d");

    $dao = new ConfAfpDao();
    return $dao->registrar($cod_regimen_pensionario, $ap_obligatorio, $comision, $prima_seguro, $fecha_vigencia);
}

function edit() {
    $id = $_REQUEST['id'];
    $cod_regimen_pensionario = $_REQUEST['cod_regimen_pensionario'];
    $ap_obligatorio = $_REQUEST['aporte_obligatorio'];
    $comision = $_REQUEST['comision'];
    $prima_seguro = $_REQUEST['prima_seguro'];

    $fecha_vigencia = $_REQUEST['fecha'];
    $fecha_vigencia = getFechaPatron($fecha_vigencia, "Y-m-d");

    $dao = new ConfAfpDao();
    return $dao->actualizar($id, $cod_regimen_pensionario, $ap_obligatorio, $comision, $prima_seguro, $fecha_vigencia);
}

function del() {
    /*$id = $_REQUEST['id'];
    $dao = new ConfAfpDao();
    return $dao->eliminar($id);*/
}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// AFP

/* FUCK NO SIRVE REMPLAZADO POR DAO.
function vigenteAfp($id) {

    $dao = new ConfAfpDao();
    $data = $dao->vigenteAfp($id);

    $model = new ConfAfp();
    $model->setId_conf_afp($data['id_conf_afp']);
    $model->setCod_regimen_pensionario($data['cod_regimen_pensionario']);
    $model->setAporte_obligatorio($data['aporte_obligatorio']);
    $model->setComision($data['comision']);
    $model->setPrima_seguro($data['prima_seguro']);
    $model->setFecha($data['fecha']);
    $model->setFecha_creacion($data['fecha_creacion']);
    
    return $model;
}
*/





?>
