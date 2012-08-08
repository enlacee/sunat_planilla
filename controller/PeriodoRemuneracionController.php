<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");
$op = $_REQUEST["oper"];

if ($op) {  // ES NEDD XQ SINO SE DUPLICAN LOS REQUIRE!!!
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    require_once '../dao/PeriodoRemuneracionDao.php';
    require_once '../model/PeriodoRemuneracion.php';
}

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla();
} else if ($op == "edit") {
    $responce = editar();
} else {
    //echo "oper INCORRECTO COMBO";
}

//echo count($responce);
echo (!empty($responce)) ? json_encode($responce) : '';

function cargar_tabla() {
    $dao = new PeriodoRemuneracionDao();

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

    $lista = array();
    $lista = $dao->listar($WHERE);
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
        
            $param = $rec["cod_periodo_remuneracion"];
            $_01 = $rec['descripcion'];
            $_02 = $rec['tasa_pago'];
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

function editar() {
    
    $obj = new PeriodoRemuneracion();
    $obj->setCod_periodo_remuneracion($_REQUEST['id']);
    $obj->setTasa_pago($_REQUEST['tasa_pago']);
    
    $dao = new PeriodoRemuneracionDao();
    $dao->actualizar($obj);
    
    
}


// VIEW 

function listarPeriodoRemuneracion(){
    
    $dao = new PeriodoRemuneracionDao();
    $data = $dao->listar();
    return $data;    
    
}

?>
