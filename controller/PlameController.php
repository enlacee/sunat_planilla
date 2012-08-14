<?php

//session_start();

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../dao/AbstractDao.php';
    require_once '../dao/PlameDao.php';

    //CONTROLLER
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    require_once '../util/funciones.php';
    
}

$responce = NULL;

if ($op == "trabajador_por_periodo") {
    $responce = listarTrabajadoresPorPeriodo();
    
}

echo (!empty($responce)) ? json_encode($responce) : '';
//------------------
function listarTrabajadoresPorPeriodo() {

    //--------------------Inicio Configuracion Basica---------------------------
    //Variables
    $periodo = ($_REQUEST['periodo']) ? $_REQUEST['periodo'] : "08/1988";
    $fecha_ISO = "01/" . $periodo;    // DIA/MES/ANIO

    $FECHA = getMesInicioYfin($fecha_ISO);

    //--------------------Final Configuracion Basica----------------------------


    $dao_plame = new PlameDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction
    // $WHERE = "";

    /*
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
     */
    if (!$sidx)
        $sidx = 1;

    //llena en al array
    $lista = array();
    $lista = $dao_plame->listarTrabajadoresPorPeriodo(ID_EMPLEADOR_MAESTRO, $FECHA['mes_inicio'], $FECHA['mes_fin']);

    $count = count($lista);
    //$count = $dao_plame->cantidadTrabajadoresPorPeriodo(ID_EMPLEADOR_MAESTRO, $FECHA['mes_inicio'],$FECHA['mes_fin']);
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



    //$dao_plame->actualizarStock();
// CONTRUYENDO un JSON
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return null;
    }


    foreach ($lista as $rec) {
        //echo "enntro en for each   ".$rec['id_trabajador'];

        $param = $rec["id_trabajador"];
        $_01 = $rec["cod_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];
        $_06 = $rec["fecha_inicio"];
        $_07 = $rec["fecha_fin"];
        // $_06 = $rec['tipo'];
        /*        //new
          $onclickEditar = 'onclick="editarProducto(' . $param . ')"';
          $onclickEliminar = 'onclick="eliminarEmpleador(' . $param . ')"';


          $js = "javascript:cargar_pagina('sunat_planilla/view/edit_empleador.php?id_empleador=" . $param . "','#CapaContenedorFormulario')";

          $js2 = "javascript:eliminarEmpleador('" . $param . "')";

          $j3 = "javascript:cargar_pagina('sunat_planilla/view/view_establecimiento.php?id_empleador=" . $param . "','#CapaContenedorFormulario')";



          if ($param == ID_EMPLEADOR) {

          $opciones = '<div id="divEliminar_Editar">
          <span  title="Editar" >
          <a href="' . $js . '"><img src="images/edit.png"/></a>
          </span>
          &nbsp;
          <span  title="Nuevo Establecimiento" >
          <a href="' . $j3 . '"><img src="images/page.png"/></a>
          </span>

          </div>';
          } else {
          //  if ($_06 != 'MAESTRO') {
          $opciones = '<div id="divEliminar_Editar">
          &nbsp;
          <span  title="ADM" >
          <img src="images/icons/login.png"/>
          </span>
          &nbsp;
          &nbsp;
          <span  title="Nuevo Establecimiento" >
          <a href="' . $j3 . '"><img src="images/page.png"/></a>
          </span>

          </div>';
          }

         */

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_07
                //utf8_encode($opciones),
                //$_06
        );

        $i++;
    }

    return $responce;
}








?>
