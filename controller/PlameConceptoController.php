<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];

if ($op) {
    session_start();
    
    require_once '../dao/AbstractDao.php';
    require_once ('../dao/EmpleadorDao.php');

    require_once '../dao/PlameConceptoDao.php';

    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    // require_once '../controller/EmpleadorController.php';


    $daoe = new EmpleadorDao();
    $empleador = array();
    $empleador = $daoe->buscaEmpleadorPorRuc(RUC);

//    echo "<br>ruc camuente = " . RUC;
//    echo "<pre>";
//    echo print_r($empleador);
//    echo "</pre>";
}

$responce = NULL;

if ($op == "cargar_tabla") {
    $config = $_REQUEST['config'];
    $responce = cargar_tabla_plame_concepto($empleador,$config);
}

//echo count($responce);
echo (!empty($responce)) ? json_encode($responce) : '';



function cargar_tabla_plame_concepto($empleador,$config) {

    $dao = new PlameConceptoDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord'];



    if (!$sidx)
        $sidx = 1;

    $count = $dao->cantidad();

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

    //$dao->actualizarStock();
    //--------------------------------------------------------------------------
    // Con los datos del empleador = segun estos devolvera determinados conceptos

  //  if ($empleador['senati'] == 1) {
  //      $num = "";
  //  } else {
  //      $num = "0800";
  //  }

    //--------------------------------------------------------------------------


    $lista = $dao->listar($start, $limit, $sidx, $sord);


    //--------------------------------------------------------------------------
    //ID A ELIMINAR
    $id = array('0600', $num, '2000');
    $counteo = count($id);
    for ($i = 0; $i < $counteo; $i++) {
        //------------------------------------------
        foreach ($lista as $indice) {
            if ($id[$i] == $indice['cod_concepto']) {
                //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                unset($id[$i]);
                //Elimina _arreglo GET indice Primero				
                $clave = array_search($indice, $lista);
                unset($lista[$clave]);
                break;
            }
        }
    }

    //-------------------ORDENAR------------------------//
    //-------------------------------------------------------------------------
// CONTRUYENDO un JSON
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $responce;  /* break; */
    }

//    echo "<pre>";
//    print_r($lista);
//    echo "</pre>";

    foreach ($lista as $rec) { /* dato retorna estos datos */

        $param = $rec["cod_concepto"];
        $_01 = $rec["descripcion"];

        //new
        
        // JS-CONFIG
        if($config == 'config'){
            $js ="javascript:cargar_pagina('sunat_planilla/view-plame/configuracion/view_detalle_concepto.php?id_concepto=" . $param . "','#detalle_concepto')";
        }else{ 
            $js = "javascript:cargar_pagina('sunat_planilla/view-plame/detalle/view_detalle_concepto.php?id_concepto=" . $param . "','#detalle_concepto')";
        }
        $opciones = '<div id="divEliminar_Editar">				
				<span  title="Detalle Concepto" >
				<a href="' . $js . '"><img src="images/search2.png"/></a>
				</span>	
		</div>';

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_01,
            utf8_encode($opciones)
        );

        $i++;
    }

    return $responce;
}

?>
