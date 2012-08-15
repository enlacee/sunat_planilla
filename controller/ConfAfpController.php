<?php
/*
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    //CONTROLLER
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    require_once '../dao/Conf.php';
    
}

$responce = NULL;

if ($op == "cargar_tabla") {

    $responce = listarConfUit();
} else if ($op == "edit") {
    $responce = edit();
} else if ($op == "add"){
    $responce = add();    
} else if($op == "del"){
    $responce = del(); 
}

echo (!empty($responce)) ? json_encode($responce) : '';

function listarConfUit() {
    $dao = new ConfUitDao();

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

        $param = $rec["id_conf_uit"];
        $_01 = $rec['valor'];
        $_02 = $rec['fecha'];
        //hereee
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $key,
            $param,
            $_01,
            $_02
        );
        $i++;
    }

    return $response;
}

function add(){
    $fecha_vigencia = $_REQUEST['fecha'];
    $valor = $_REQUEST['valor'];
    $fecha_vigencia = getFechaPatron($fecha_vigencia, "Y-m-d");
    
    $dao = new ConfUitDao();
    return $dao->registrar($valor, $fecha_vigencia);
    
}

function edit(){
    $id = $_REQUEST['id'];
    $valor = $_REQUEST['valor'];
    $fecha_vigencia = $_REQUEST['fecha'];
    $fecha_vigencia = getFechaPatron($fecha_vigencia, "Y-m-d");
    
    $dao = new ConfUitDao();
    return $dao->actualizar($id, $valor, $fecha_vigencia);
}

function del(){
    $id = $_REQUEST['id'];
    $dao = new ConfUitDao();
    return $dao->eliminar($id);
}
*/
?>
