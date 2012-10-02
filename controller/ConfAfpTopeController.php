<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    //require_once '../ds/MyPDO.php';

    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    require_once '../dao/ConfAfpTopeDao.php';
}

$response = NULL;

if ($op == "cargar_tabla") {

    $response = listarAfpTope();
} else if ($op == "edit") {
    $response = edit();
} else if ($op == "add") {
    $response = add();
} else if ($op == "del") {
    $response = del();
}

echo (!empty($response)) ? json_encode($response) : '';


function listarAfpTope(){
     $dao = new ConfAfpTopeDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction

    $WHERE = null;
    if (isset($_GET['searchField']) && ($_GET['searchString'] != null)) {

        $operadores["eq"] = "=";
        $operadores["ne"] = "<>";
        $operadores["lt"] = "<";
        $operadores["le"] = "<=";
        $operadores["gt"] = ">";
        $operadores["ge"] = ">=";
        $operadores["cn"] = "LIKE";
        if ($_GET['searchOper'] == "cn")
            $WHERE = "WHERE " . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . " '%" . $_GET['searchString'] . "%' ";
        else
            $WHERE = "WHERE " . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . "'" . $_GET['searchString'] . "'";
    }


    if (!$sidx)
        $sidx = 1;

    
    $count = $dao->listarCounteo($WHERE);

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
    
    //llena en al array
    $lista = array();    
    $lista = $dao->listar($WHERE, $start, $limit, $sidx, $sord);
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

        $param = $rec["id_conf_afp_tope"];
        $_01 = $rec['valor'];
        $_02 = $rec['fecha'];        
        //hereee
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $_00,
            $param,
            $_01,
            $_02,

        );
        $i++;
    }

    return $response;
}


function add(){
    
    $fecha_vigencia = $_REQUEST['fecha'];
    $valor = $_REQUEST['valor'];
    $fecha_vigencia = getFechaPatron($fecha_vigencia, "Y-m-d");
    
    $dao = new ConfAfpTopeDao();
    return $dao->add($valor, $fecha_vigencia);
    
}

function edit(){
    $id = $_REQUEST['id'];
    $valor = $_REQUEST['valor'];
    $fecha_vigencia = $_REQUEST['fecha'];
    $fecha_vigencia = getFechaPatron($fecha_vigencia, "Y-m-d");
    
    $dao = new ConfAfpTopeDao();
    return $dao->edit($id, $valor, $fecha_vigencia);
}

function del(){
    $id = $_REQUEST['id'];
    $dao = new ConfAfpTopeDao();
    return $dao->eliminar($id);
}


?>
