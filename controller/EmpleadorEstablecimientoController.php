<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");


$op = $_REQUEST["oper"];
if ($op) {
    //require_once '../model/Persona.php';
    require_once ('../dao/AbstractDao.php');
    require_once ('../dao/EstablecimientoDao.php');
    require_once ('../dao/EstablecimientoDireccionDao.php');
    require_once('../model/Establecimiento.php');

//REGISTRAR DIRECCION
    require_once('../model/Direccion.php');
    require_once('../model/EstablecimientoDireccion.php');
}

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla(); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "edit") {
    $responce = editar();
} else {
    echo "<p>variable OPER no esta definido</p>";
}


echo json_encode($responce);
/* * **********categoria*************** */

function editar() {

    $id = $_REQUEST['id'];
    $actividad_de_riesgo = ($_REQUEST['realizaran_actividad_riesgo'] == 'Si') ? 1 : 0;

    //echo   $actividad_de_riesgo ." ".$id_establecimiento;  

    $dao = new EstablecimientoDao();
    $rpta = $dao->actualizarEstablecimiento_2($id, $actividad_de_riesgo);

    return $rpta;
}

//-------------------------------------------------
function cargar_tabla() {
    $ID_EMPLEADOR = $_REQUEST['id_empleador'];
    $dao_empleador = new EstablecimientoDao();

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

    $count = $dao_empleador->cantidadEstablecimiento($id);

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

    //$dao_empleador->actualizarStock();

    $lista = $dao_empleador->listarEstablecimientos2($ID_EMPLEADOR, $WHERE, $start, $limit, $sidx, $sord);

// CONTRUYENDO un JSON
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $responce;  /* break; */
    }


    foreach ($lista as $rec) { /* dato retorna estos datos */

        $param = $rec["id_establecimiento"];
        $_01 = $rec["id_empleador"];
        $_02 = $rec["cod_establecimiento"];
        $_03 = ($rec['realizaran_actividad_riesgo']) ? 'Si' : 'No';

        //new
        //here
        $ubigeo_nombre_via = $rec["ubigeo_nombre_via"];
        $nombre_via = $rec['nombre_via'];
        $numero_via = $rec['numero_via'];

        $ubigeo_nombre_zona = $rec['ubigeo_nombre_zona'];
        $nombre_zona = $rec['nombre_zona'];
        $etapa = $rec['etapa'];
        $manzana = $rec['manzana'];
        $blok = $rec['blok'];
        $lote = $rec['lote'];

        $departamento = $rec['departamento'];
        $interior = $rec['interior'];

        $kilometro = $rec['kilometro'];

        $ubigeo_departamento = str_replace('DEPARTAMENTO', '', $rec['ubigeo_departamento']);
        $ubigeo_provincia = $rec['ubigeo_provincia'];
        $ubigeo_distrito = $rec['ubigeo_distrito'];

        //---------------Inicio Cadena String----------//
        $cadena = '';

        $cadena .= (isset($ubigeo_nombre_via)) ? '' . $ubigeo_nombre_via . ' ' : '';
        $cadena .= (isset($nombre_via)) ? '' . $nombre_via . ' ' : '';
        $cadena .= (isset($numero_via)) ? '' . $numero_via . ' ' : '';

        $cadena .= (isset($ubigeo_nombre_zona)) ? '' . $ubigeo_nombre_zona . ' ' : '';
        $cadena .= (isset($nombre_zona)) ? '' . $nombre_zona . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';

        $cadena .= (isset($manzana)) ? 'MZA. ' . $manzana . ' ' : '';
        $cadena .= (isset($blok)) ? '' . $blok . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';
        $cadena .= (isset($lote)) ? 'LOTE. ' . $lote . ' ' : '';

        $cadena .= (isset($departamento)) ? '' . $departamento . ' ' : '';
        $cadena .= (isset($interior)) ? '' . $interior . ' ' : '';
        $cadena .= (isset($kilometro)) ? '' . $kilometro . ' ' : '';

        $cadena .= (isset($ubigeo_departamento)) ? '' . $ubigeo_departamento . '-' : '';
        $cadena .= (isset($ubigeo_provincia)) ? '' . $ubigeo_provincia . '-' : '';
        $cadena .= (isset($ubigeo_distrito)) ? '' . $ubigeo_distrito . ' ' : '';

        //$cadena = strtoupper($cadena);
        //---------------Inicio Cadena String----------//
        //
        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $ubigeo_departamento,
            $_03
                //utf8_encode($opciones)
        );
        //unset($cadena);
        $i++;
    }

    return $responce;
}

?>