<?php
//session_start();
//header("Content-Type: text/html; charset=utf-8");
$op = $_REQUEST["oper"];

if ($op) {
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/Concepto_EDao.php';
    
    // conf.
    require_once '../controller/ideController.php';
}

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tablaConceptosE();
} elseif ($op == "") {
   // $responce = comboUbigeoProvincias($_REQUEST['id_departamento']);
}

echo (!empty($responce)) ? json_encode($responce) : '';


function cargar_tablaConceptosE(){
    
    /*
    
     $dao_trabajador = new TrabajadorDao();

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

     //llena en al array
    $lista = array();
    $lista = $dao_trabajador->listarTrabajador(ID_EMPLEADOR_MAESTRO, $ESTADO, $WHERE, $start, $limit, $sidx, $sord);
    $count = count($lista);
    //$count = $dao_trabajador->cantidadTrabajador(ID_EMPLEADOR_MAESTRO, $ESTADO, $WHERE);

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
    $start = $limit * $page - $limit; // do not put $limit($page - 1)
    //valida
    if ($start < 0)
        $start = 0;

// CONTRUYENDO un JSON
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $responce;  
    }

    foreach ($lista as $rec) {
        $param = $rec["id_trabajador"];
        $_01 = "";
        $_02 = $rec["nombre_tipo_documento"];
        $_03 = $rec["num_documento"];
        $_04 = $rec["apellido_paterno"];
        $_05 = $rec["apellido_materno"];

        $_06 = $rec["nombres"];
        $_07 = $rec["fecha_nacimiento"];
        $_08 = $rec["sexo"];
        $_09 = $rec["estado"];

        $opciones = $rec['reporte'];


        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_07,
            $_08,
            $_09,
            $opciones
        );

        $i++;
    }

    return $responce;  //RETORNO A intranet.js
    */
    
    
}



// view
function listarConceptosE(){
    $dao = new Concepto_EDao();
    $data = $dao->listar();
    return $data;
}


//view
function buscar_ID_ConceptoE($id){
    
    $dao = new Concepto_EDao();
    $data = $dao->buscar_ID($id);
    return $data;
    
    
}