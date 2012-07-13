<?php

session_start(); //IMPORTANTE [id_empleador_maestro]



$op = $_REQUEST["oper"];
if ($op) {
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    //tabla 01 EME	
    //--- Inicio Archivo Importantes
    //require_once '../controller/EmpleadorMaestroEmpleadorController.php';
    //--- Inicio Archivo Importantes

    require_once '../dao/EmpleadorDestaqueYourSelfDao.php';
    require_once '../dao/EmpleadorDao.php';

    //tabla 03 	
    require_once '../dao/ServicioPrestadoYourselfDao.php';
    require_once '../model/ServicioPrestadoYourself.php';
    
    
    
    
    
    
    
            //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    //
}

$responce = NULL;


if ($op == "cargar_tabla") {

    $responce = cargar_tablaEmpleadorDestaqueYourself($_REQUEST['id_empleador_maestro']);
} elseif ($op == "add") {
    $responce = nuevoEmpleadorDestaqueYourself();
} elseif ($op == "edit") {
    $responce = editar();
    
} elseif ($op == "lista_emp_dest_yourself") {
    
    $responce = listarEmpleadorDestaqueYourself();
    
} elseif ($op == "lista_establecimiento_yourserlf") {

    $ID_EMPLEADOR = $_REQUEST['id_empleador'];
    $responce = listarEstablecimientoDestaqueYourself($ID_EMPLEADOR);
    
} else {
   // echo "oper INCORRECTO ADIOS";
}

echo json_encode($responce);

// EMPLEADORES QUE ME DESTACAN PERSONAL

function nuevoEmpleadorDestaqueYourself() {
    //--------------------------------------------------------------------------
    $id_empleador = $_REQUEST['id_empleador'];
    $id_empleador_maestro = $_REQUEST['id_empleador_maestro'];
    // PASO 01
    $ID_EMP_D_YSELF = getID_EmpleadorDestaqueYourSelf($id_empleador, $id_empleador_maestro);
    //--------------------------------------------------------------------------
    //PASO 02
    // ID
    $id_detalle_servicio_prestado2 = $_REQUEST['id_detalle_servicio_prestado2']; 
    
    $tipo_sercicio = $_REQUEST['cbo_tipo_servicio_prestado'];
    $fecha_inicio = $_REQUEST['txt_fecha_inicio'];
    $fecha_fin = $_REQUEST['txt_fecha_fin'];

    //MODEL
    $model = new ServicioPrestadoYourself();
    //DAO
    $dao = new ServicioPrestadoYourSelfDao();

    $COUNTEO = count($tipo_sercicio);

    // $eme_dao = new EmpleadorMaestroEmpleadorDao();
    // $eme_dao->actualizarEME_DD2($ID_EMP_D_YSELF, 'ACTIVO');

    for ($i = 0; $i < $COUNTEO; $i++) {

        $model->setId_empleador_destaque_yourself($ID_EMP_D_YSELF);
        //ID tabla
        $model->setId_servicio_prestado_yourself($id_detalle_servicio_prestado2[$i]);
        
        $model->setCod_tipo_actividad($tipo_sercicio[$i]);
        $model->setEstado('ACTIVO');
        //$fecha_iniciox =;
        $model->setFecha_inicio(getFechaPatron($fecha_inicio[$i], 'Y-m-d'));

        $model->setFecha_fin(getFechaPatron($fecha_fin[$i], 'Y-m-d'));

        if ($id_detalle_servicio_prestado2[$i] > 0) { //ID = TABLA Reg Ya guardados en db UPDATE
            $dao->actualizar($model);
        } elseif ($id_detalle_servicio_prestado2[$i] == 0) { //Nuevos Registros
            $dao->registrar($model);
        } else {
            return false;
        }//EndIF
    }//ENDFOR
//-------------------------------------------------------------------
    
    
        //Actualizar EMPLEADOR AHORA SI terceros le DESPLAZA PERSONAL
    $dao_e = new EmpleadorDao();
    $dao_e->empleadorTercerosDesplazaPersonal(ID_EMPLEADOR, 1);
    
    
    return true;
}

//-----------------------------------------------------------------------------
function cargar_tablaEmpleadorDestaqueYourself($ID_EMPLEADOR_MAESTRO) {

    $dao_persona = new EmpleadorDestaqueYourSelfDao();

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

    $count = $dao_persona->cantidad($id_empleador_maestro,$WHERE);

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

    //$dao_persona->actualizarStock();

    $lista = $dao_persona->listar($ID_EMPLEADOR_MAESTRO, $WHERE, $start, $limit, $sidx, $sord);

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

        $param = $rec["id_empleador_destaque_yoursef"];
        $_01 = $rec["id_empleador_maestro"];
        $_02 = $rec["id_empleador"];
        $_03 = $rec["ruc"];
        $_04 = $rec["razon_social"];

        $js = "javascript:cargar_pagina('sunat_planilla/view/new_empleador_dd2.php?ruc_empleador_subordinado=" . $_03 . "','#CapaContenedorFormulario')";
        // $js2 = "javascript:eliminarPersona('" . $param . "')";		
        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>
		</div>';
        //hereee

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $opciones
        );

        $i++;
    }

    return $responce;  //RETORNO A intranet.js
}

//------------------------------------------------------------------------------
//##############################################################################
// NO USAMOS oper 
//##############################################################################
//------------------------------------------------------------------------------


function buscarIdEmpleadorDestaqueYourself($id_empleador_maestro) {

    $dao = new EmpleadorDestaqueYourSelfDao();
    $id = $dao->buscarIdEmpleadorDestaqueYourself($id_empleador_maestro);
    return $id;
}

//--------------------------
function getID_EmpleadorDestaqueYourSelf($id_empleador, $id_empleador_maestro) {

    //--------------------------------------------------------------------------
    $ID_EME = null;
    $dao = new EmpleadorDestaqueYourSelfDao();
    $BANDERA_ID = $dao->existeRelacionCreada($id_empleador, $id_empleador_maestro);
    //--------------------------------------------------------------------------

    if (is_null($BANDERA_ID)) {// = NO EXISTE Empleador Maestro Empleador REGISTRADO   
        $dao_d = new EmpleadorDestaqueYourSelfDao();
        $BANDERA_ID = $dao_d->registrar($id_empleador, $id_empleador_maestro);
    }
    // printr($ID_EME);
    // echo "FINALLL";
    return $BANDERA_ID;
}




//------------------------------------------------------------------------------
//##############################################################################
// Lista de Empleadores que Yo destaco Personal 
//##############################################################################
//------------------------------------------------------------------------------


function listarEmpleadorDestaqueYourself() {

    $ID_EMPLEADOR = $_SESSION['sunat_empleador']['id_empleador'];

    // paso 01 ->Identidicaion Empleador Maestro
    $dao0 = new EmpleadorMaestroDao();
    $data_emaestro = $dao0->buscarIdEmpleadorMaestroPorIDEMPLEADOR($ID_EMPLEADOR);
    $id_empleador_maestro = $data_emaestro['id_empleador_maestro'];

    // paso 02 -> Obtener IDS
    $dao = new EmpleadorDestaqueYourSelfDao();
    $ids = $dao->listaIDEmpleador($id_empleador_maestro);

    // paso 03    
    $lista = array();
    $dao = new EmpleadorDao();

    for ($i = 0; $i < count($ids); $i++) {        
        $lista[$i] = $dao->buscarEmpleadorPorId2($ids[$i]['id_empleador']);
        //ojo
        $lista[$i]['id_empleador_destaque_yoursef'] = $ids[$i]['id_empleador_destaque_yoursef'];
    }
    
    //echo "<pre>";
    //print_r($lista);
    //echo "</pre>";
    
    return $lista;
}

//---
function listarEstablecimientoDestaqueYourself($id_empleador) {
    
    if (!isset($id_empleador) || is_null($id_empleador)) {        
        $id_empleador = $_SESSION['sunat_empleador']['id_empleador'];
    }

    // paso 01 ->Identidicaion     
    $dao = new EmpleadorDao();
    $rec = $dao->buscarEstablecimientoEmpleadorPorId($id_empleador);

    $lista = array();

    $counteo = count($rec);
    for ($i = 0; $i < $counteo; $i++) {
        $ubigeo_nombre_via = $rec[$i]["ubigeo_nombre_via"];
        $nombre_via = $rec[$i]['nombre_via'];
        $numero_via = $rec[$i]['numero_via'];

        $ubigeo_nombre_zona = $rec[$i]['ubigeo_nombre_zona'];

        $nombre_zona = $rec[$i]['nombre_zona'];
        $etapa = $rec[$i]['etapa'];
        $manzana = $rec[$i]['manzana'];
        $blok = $rec[$i]['blok'];
        $lote = $rec[$i]['lote'];

        $departamento = $rec[$i]['departamento'];
        $interior = $rec[$i]['interior'];

        $kilometro = $rec[$i]['kilometro'];
        $referencia = $rec[$i]['referencia'];

        $ubigeo_departamento = str_replace('DEPARTAMENTO', '', $rec[$i]['ubigeo_departamento']);
        $ubigeo_provincia = $rec[$i]['ubigeo_provincia'];
        $ubigeo_distrito = $rec[$i]['ubigeo_distrito'];

        //---------------Inicio Cadena String----------//
        $cadena = '';
        $cadena .= (isset($ubigeo_nombre_via)) ? '' . $ubigeo_nombre_via . ' ' : '';
        $cadena .= (isset($nombre_via)) ? '' . $nombre_via . ' ' : '';
        $cadena .= (isset($numero_via)) ? '' . $numero_via . ' ' : '';

        $cadena .= (isset($ubigeo_nombre_zona)) ? '' . $ubigeo_nombre_zona . ' ' : '';
        $cadena .= (isset($nombre_zona)) ? '' . $nombre_zona . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';

        $cadena .= (isset($manzana) && !empty($manzana)) ? 'MZA. ' . $manzana . ' ' : '';
        $cadena .= (isset($blok)) ? '' . $blok . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';
        $cadena .= (isset($lote) && !empty($lote)) ? 'LOTE. ' . $lote . ' ' : '';

        $cadena .= (isset($departamento)) ? '' . $departamento . ' ' : '';
        $cadena .= (isset($interior)) ? '' . $interior . ' ' : '';
        $cadena .= (isset($kilometro)) ? '' . $kilometro . ' ' : '';
        $cadena .= (isset($referencia)) ? '' . $referencia . ' ' : '';

        $cadena .= (isset($ubigeo_departamento)) ? '' . $ubigeo_departamento . '-' : '';
        $cadena .= (isset($ubigeo_provincia)) ? '' . $ubigeo_provincia . '-' : '';
        $cadena .= (isset($ubigeo_distrito)) ? '' . $ubigeo_distrito . ' ' : '';

        $cadena = strtoupper($cadena);

        //------------------------------------------------------------------------------------------

        $lista[$i]['cod_establecimiento'] = $rec[$i]['cod_establecimiento'];
        $lista[$i]['tipo_establecimiento'] = $rec[$i]['tipo_establecimiento_descripcion'];
        $lista[$i]['id_empleador'] = $rec[$i]['id_establecimiento'];

        $lista[$i]['id'] = $rec[$i]['id_establecimiento'] . "|" . $rec[$i]['id_tipo_establecimiento'] . "|" . $rec[$i]['cod_establecimiento'];
        $lista[$i]['descripcion'] = $cadena;
    }//EndFOR

    return $lista;
}

//-----------------
/**
 *
 * @param type $id_trabajador Identidicacion
 * @param type $id_detalle_establecimiento
 * @return type 
 */
function buscarID_EMP_EmpleadorDestaqueYourselfPorTrabajador($id_trabajador, $id_detalle_establecimiento) {
    $dao = new DetalleEstablecimientoDao();
    $id_empleador = $dao->buscarID_EMP_EmpleadorDestaquePorTrabajador($id_trabajador, $id_detalle_establecimiento);
    return $id_empleador;
}



?>