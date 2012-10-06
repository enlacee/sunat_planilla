<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    require_once '../model/ParatiFamilia.php';
    require_once '../dao/PrestamoDao.php';
    require_once '../dao/ParatiFamiliaDao.php';
}

$response = NULL;
if ($op == 'cargar_tabla_trabajador') {
    $response = listarTrabajadores();
} else if ($op == 'cargar_tabla') {
    $response = listar();
} else if ($op == "add") {
    $response = add();
} else if ($op == "edit") {
    $response = edit();
}

echo (!empty($response)) ? json_encode($response) : '';

// Lista a seleccionar grid
function listarTrabajadores() {

    // Reutilizando clase!
    $dao = new PrestamoDao();
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];

    $WHERE = '';
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

    $count = $dao->listarTrabajadorCount(ID_EMPLEADOR_MAESTRO, $WHERE);

    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        //$total_pages = 0;
    }
    //valida
    if ($page > $total_pages)
        $page = $total_pages;

    // calculate the starting position of the rows
    $start = $limit * $page - $limit;
    //valida
    if ($start < 0)
        $start = 0;
    //88888888888888888888888888888888888888888888888888888888888888888888888888
    // podria usarse categoria trabajaador para cargar los mismos trabajadores...
    $lista = array();
    $lista = $dao->listarTrabajador(ID_EMPLEADOR_MAESTRO, $WHERE, $start, $limit, $sidx, $sord);

    //listar de trabajadores que tienen un prestamo vigente
    $dao_ptf = new ParatiFamiliaDao();
    $press = $dao_ptf->listarTrabajador_Registrados(ID_EMPLEADOR);
    //88888888888888888888888888888888888888888888888888888888888888888888888888
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
        $_01 = $rec["nombre_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];


        $bandera = false;
        for ($j = 0; $j < count($press); $j++) {

            if ($param == $press[$j]['id_trabajador']) {
                $bandera = true;
                break;
            }
        }


        if ($bandera == false) {
                      
            $js = "javascript:return_modal_anb_paraTifamilia('" . $param . "','" . $_02 . "','" . $_03 . " " . $_04 . " " . $_05 . "')";
            $opciones = '<div class="red">
          <span  title="Editar" >
          <a href="' . $js . '">seleccionar</a>
          </span>
          &nbsp;
          </div>';
        } else {
            $opciones = '<div class="red">
          <span  title="Editar" >
          <a href="#"> - </a>
          </span>
          &nbsp;
          </div>';
        }




        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $opciones
        );
        $i++;
    }

    return $responce;
}

function listar() {
    $dao = new ParatiFamiliaDao(); //PrestamoDao();
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];

    $WHERE = '';
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

    $count = $dao->listarCount(ID_EMPLEADOR, $WHERE);

    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        //$total_pages = 0;
    }
    //valida
    if ($page > $total_pages)
        $page = $total_pages;

    // calculate the starting position of the rows
    $start = $limit * $page - $limit;
    //valida
    if ($start < 0)
        $start = 0;

    $lista = array();
    $lista = $dao->listar(ID_EMPLEADOR, $WHERE, $start, $limit, $sidx, $sord);

    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $responce;
    }
    
    foreach ($lista as $rec) {

        $param = $rec["id_para_ti_familia"];
        $_00 = $rec["id_trabajador"];
        //$_01 = $rec["cod_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];
        $_06 = $rec["descripcion"];
        $_07 = ($rec["estado"] == 1) ? 'ACTIVO' : 'INACTIVO';


        //$js = "javascript:return_modal_anb_prestamo('".$param ."','".$_02."','".$_03." ".$_04." ".$_05."')";
        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_cparatifamilia.php?id_para_ti_familia=" . $param . "?id_trabajador=" . $_00 . "','#CapaContenedorFormulario')";
        $opciones = '<div id="">
          <span  title="Editar" >
          <a class = "divEditar" href="' . $js . '">e</a>
          </span>          
          </div>'
        ;

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            //$_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_07,
            $opciones
        );
        $i++;
    }

    return $responce;
}

function add() {

    $obj = new ParatiFamilia();
    $obj->setId_empleador(ID_EMPLEADOR);
    $obj->setId_trabajador($_REQUEST['id_trabajador']);
    $obj->setId_tipo_para_ti_familia($_REQUEST['cbo_tipo_para_tifamilia']);
    $obj->setEstado(1);
    
    $fecha_inicio = "01/".$_REQUEST['fecha_inicio'];
    
    $obj->setFecha_inicio(getFechaPatron($fecha_inicio, "Y-m-d") );
    $obj->setFecha_creacion(date("Y-m-d"));

    //DAO
    $dao = new ParatiFamiliaDao();
    return $dao->add($obj);
}

function edit() {
    
    $id = $_REQUEST['id'];
    $id_tipo_para_ti_familia = $_REQUEST['cbo_tipo_para_tifamilia'];

    //dao 
    $dao = new ParatiFamiliaDao();
    return $dao->edit($id, $id_tipo_para_ti_familia);
    
}

//-----------------------------------------------
//-----------------------------------------------
// view

function buscar_IdParaTiFamilia($id) {

    $dao = new ParatiFamiliaDao();
    $data = $dao->buscar_data_id($id);
    return $data;
}

?>
