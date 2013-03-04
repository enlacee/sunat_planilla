<?php

session_start();
$op = $_REQUEST["oper"];
if ($op) {
    //Empleador
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    // vacacion detalle
    require_once '../dao/VacacionDetalleDao.php';
    require_once '../dao/VacacionDao.php';
    //require_once '../model/Vacacion.php';
}

$response = NULL;

if ($op == "cargar_tabla") {
    $response = cargar_tabla();
} else if ($op == "del") {
    $response = deleteVD();
}
echo (!empty($response)) ? json_encode($response) : '';

function cargar_tabla() {

    //$ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $PERIODO = $_REQUEST['periodo'];
    $anio = getFechaPatron($PERIODO, 'Y');
    $id_trabajador = $_REQUEST['id_trabajador'];

    $daov = new VacacionDao();
    $id_pdeclaracion_vacacion_base = $daov->getPdeclaracionBase($id_trabajador, $anio);
    //echoo($id_pdeclaracion_vacacion_base);
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
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

    $daovd = new VacacionDetalleDao();
    $count = $daovd->listarCount($id_pdeclaracion_vacacion_base/* $ID_PDECLARACION */, $id_trabajador, $WHERE);

    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        //$total_pages = 0;
    }

    if ($page > $total_pages)
        $page = $total_pages;

    $start = $limit * $page - $limit;
    //valida
    if ($start < 0)
        $start = 0;

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;
    $lista = array();
    $lista = $daovd->listar($id_pdeclaracion_vacacion_base/* $ID_PDECLARACION */, $id_trabajador, $WHERE, $start, $limit, $sidx, $sord);
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
    foreach ($lista as $rec) {
        $param = $rec["id_vacacion_detalle"];
        $_01 = getFechaPatron($rec["fecha_inicio"], "d/m/Y");
        $_02 = getFechaPatron($rec["fecha_fin"], "d/m/Y");
        $_03 = $rec["dia"];
        $js4 = "javascript:eliminarDetalleVacacion($param)";
        $_04 = '<a href="' . $js4 . '" class="divEliminar" ></a>';
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04
        );
        $i++;
    }
    return $response;
}

function deleteVD() {
    $id = $_REQUEST['id'];
    $dao = new VacacionDetalleDao();
    $response->rpta = $dao->del($id);
    return $response;
}

?>
