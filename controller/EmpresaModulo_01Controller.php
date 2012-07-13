<?php
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    
    require_once '../dao/AbstractDao.php';
    
    //IDE
    require_once '../controller/ideController.php';
    
    require_once '../dao/TrabajadorDao.php';
    
}

$responce = NULL;

if ($op == "cargar_tabla") {   
    $responce = cargar_tabla();
}

echo (!empty($responce)) ? json_encode($responce) : '';

//------------------------------------------------------------------------------
// funciones


function cargar_tabla($ESTADO=1) {

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

    $count = $dao_trabajador->cantidadTrabajador(ID_EMPLEADOR_MAESTRO, $ESTADO, $WHERE);

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

    //$dao_trabajador->actualizarStock();

    $lista = $dao_trabajador->listarTrabajador(ID_EMPLEADOR_MAESTRO,$ESTADO, $WHERE, $start, $limit, $sidx, $sord);

// CONTRUYENDO un JSON
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $responce;  /* break; */
    }
//print_r($lista);

    foreach ($lista as $rec) { /* dato retorna estos datos */


        //Condicionar Tipo de Categoria
        $categoria_1 = $rec['categoria_1'];
        $categoria_2 = $rec['categoria_2'];
        $categoria_3 = $rec['categoria_3'];
        $categoria_4 = $rec['categoria_4'];

        $string_cat = "";
        if ($categoria_1 == 'TRA') {
            $string_cat .= 'TRA.';
        }

        if ($categoria_2 == 'PEN') {
            $string_cat .= 'PEN.';
        }

        if ($categoria_3 == 'PFOR') {
            $string_cat .= 'PFOR.';
        }

        if ($categoria_4 == 'PTER') {
            $string_cat .= 'PTER.';
        }

        $param = $rec["id_trabajador"];
        $_01 = $string_cat;
        $_02 = $rec["nombre_tipo_documento"];
        $_03 = $rec["num_documento"];
        $_04 = $rec["apellido_paterno"];
        $_05 = $rec["apellido_materno"];

        $_06 = $rec["nombres"];
        $_07 = $rec["fecha_nacimiento"];
        $_08 = $rec["sexo"];
        $_09 = $rec["estado"];

        //$js = "javascript:cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona=" . $param . "','#CapaContenedorFormulario')";
        //$js2 = "javascript:eliminarPersona('" . $param . "')";


        //$opciones_1 = '<a href="' . $js . '">Modificar</a>';
        //$opciones_2 = '<a href="' . $js2 . '">Eliminar</a>';
        
        $opciones = $rec['reporte'];
        /*        $opciones = '<div id="divEliminar_Editar">				
          <span  title="Editar" >
          <a href="' . $js . '"><img src="images/edit.png"/></a>
          </span>
          &nbsp;
          <span  title="Cancelar" >
          <a href="' . $js2 . '"><img src="images/cancelar.png"/></a>
          </span>
          </div>';
         */

        //hereee

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
}



?>
