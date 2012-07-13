<?php

session_start(); //IMPORTANTE [id_empleador_maestro]



$op = $_REQUEST["oper"];
if ($op) {
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    require_once '../dao/EmpleadorDestaqueDao.php';
    //Identidicacion
    require_once '../dao/EmpleadorMaestroDao.php';
    require_once '../dao/EmpleadorDao.php';

    //tabla 03 	SERVICIOS
    require_once '../dao/ServicioPrestadoDao.php';
    require_once '../model/ServicioPrestadoYourself.php'; //herencia
    require_once '../model/ServicioPrestado.php';
    //
    //Establecimiento
    require_once '../model/EstablecimientoVinculado.php';
    require_once '../dao/EstablecimientoVinculadoDao.php';

    //Buscar Empleador Seleccionado con Establecimiento
    require_once '../controller/DetalleEstablecimientoController.php';
    
    
    
    
    
    
    
        //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
}
//DetalleServicioPrestado1Dao
$responce = NULL;


if ($op == "cargar_tabla") {
    $responce = cargar_tablaDD1($_REQUEST['id_empleador_maestro']);
} elseif ($op == "add") {
    $responce = nuevoEmpleadorDestaque();
} elseif ($op == "edit") {
    $responce = editar();
} elseif ($op == "lista_emp_dest") {
    $responce = listarEmpleadorDestaque();
} elseif ($op == "lista_establecimiento") {

    $ID_EMPLEADOR = $_REQUEST['id_empleador'];
    $responce = listarEstablecimientoDestaque($ID_EMPLEADOR);
} else {
    //echo "oper INCORRECTO ADIOS";
}

echo (!empty ($responce)) ? json_encode($responce) : '';

// EMPLEADORES QUE ME DESTACAN PERSONAL

function nuevoEmpleadorDestaque() {
    //--------------------------------------------------------------------------
    $id_empleador = $_REQUEST['id_empleador']; //Empleador Subordinado
    $id_empleador_maestro = $_REQUEST['id_empleador_maestro'];
    // PASO 01
    $ID_EMP_DESTAQUE = getID_EmpleadorDestaque($id_empleador, $id_empleador_maestro);
    //$ID_EMP_DESTAQUE = getID_EME($id_empleador, $id_empleador_maestro);
    //--------------------------------------------------------------------------
    //PASO 02
    $id_detalle_servicio_prestado = $_REQUEST['id_detalle_servicio_prestado']; // ID
    $tipo_sercicio = $_REQUEST['cbo_tipo_servicio_prestado'];
    $fecha_inicio = $_REQUEST['txt_fecha_inicio'];
    $fecha_fin = $_REQUEST['txt_fecha_fin'];

    //MODEL
    //printr($id_detalle_servicio_prestado);
    $model = new ServicioPrestado();
    //$model->
    //DAO
    $dao = new ServicioPrestadoDao();

    //--- FINALL -> SERVICIOS
    $COUNTEO = count($tipo_sercicio);

    for ($i = 0; $i < $COUNTEO; $i++) {

        $model->setId_empleador_destaque($ID_EMP_DESTAQUE);
        //ID
        $model->setId_servicio_prestado($id_detalle_servicio_prestado[$i]);
        $model->setCod_tipo_actividad($tipo_sercicio[$i]);
        $model->setEstado('ACTIVO');

        $model->setFecha_inicio(getFechaPatron($fecha_inicio[$i], 'Y-m-d'));

        $model->setFecha_fin(getFechaPatron($fecha_fin[$i], 'Y-m-d'));

        if ($id_detalle_servicio_prestado[$i] > 0) {
            $dao->actualizar($model);
        } elseif ($id_detalle_servicio_prestado[$i] == 0) { //Nuevos Registros
            $dao->registrar($model);
        } else {
            return false;
        }//EndIF
    }//ENDFOR
//- - - - - - - - - - --   -- - - - - -- -- - - - -- - - - - - - - -  -- - - - - - - - - - - - -  - -- -  //	
//		Registrar Establecimientos
//- - - - - - - - - - --   -- - - - - -- -- - - - -- - - - - - - - -  -- - - - - - - - - - - - -  - -- -  //		
    //PASO 02
    $id_detalle_establecimiento = $_REQUEST['id_detalle_establecimiento']; // ID
    $establecimiento = $_REQUEST['cbo_establecimiento'];
    $esCenTraRieEstab = $_REQUEST['esCenTraRieEstab'];

    //MODEL   
    $model = new EstablecimientoVinculado();

    //DAO
    $dao = new EstablecimientoVinculadoDao();

    //--- FINALL -> SERVICIOS
    //printr($establecimiento);
    $COUNTEO = count($establecimiento);
    for ($i = 0; $i < $COUNTEO; $i++) {

        $model->setId_empleador_destaque($ID_EMP_DESTAQUE);
        $model->setId_establecimiento($id_detalle_establecimiento[$i]);
        $array = preg_split("/[|]/", $establecimiento[$i]);
        $array[0]; // cod_establecimiento
        $array[1]; // id_establecimiento

        $model->setId_establecimiento($array[1]);
        $model->setRealizan_trabajo_de_riesgo($esCenTraRieEstab[$i]);
        $model->setEstado('ACTIVO'); //true

        if ($id_detalle_establecimiento[$i] > 0) {
            $dao->actualizar($model);
        } elseif ($id_detalle_establecimiento[$i] == 0) { //Nuevos Registros
            $dao->registrar($model);
        } else {
            return false;
        }//EndIF
    }//ENDFOR
//- - - - - - - - - - --   -- - - - - -- -- - - - -- - - - - - - - -  -- - - - - - - - - - - - -  - -- -  //
    
    
    
    //Actualizar EMPLEADOR AHORA SI DESPLAZA PERSONAL
    $dao_e = new EmpleadorDao();
    $dao_e->empleadorDesplazaPersonal(ID_EMPLEADOR, 1);
    
    
    
    
    

    return true;
}

function editar() {

    //Variables
    $id_detalle_servicio_prestado = $_REQUEST['id_detalle_servicio_prestado'];
    //
    $tipo_sercicio = $_REQUEST['cbo_tipo_servicio_prestado'];
    $fecha_inicio = $_REQUEST['txt_fecha_inicio'];
    $fecha_fin = $_REQUEST['txt_fecha_fin'];
    //MODEL
    $model = new DetalleServicioPrestado1();
    //DAO
    $dao = new DetalleServicioPrestado1Dao();

    $COUNTEO = count($tipo_sercicio);
    for ($i = 0; $i < $COUNTEO; $i++) {
        $model->setId_detalle_servicio_prestado2($id_detalle_servicio_prestado);
        //$model->setId_empleador_maestro_empleado($id_EME);
        $model->setCod_tipo_actividad($tipo_sercicio[i]);
        $model->setFecha_inicio($fecha_inicio[i]);
        $model->setFecha_final($fecha_fin[i]);

        $dao->actualizarDetalleServicioPrestado1($model);
    }


    return true;
}

//-----------------------------------------------------------------------------
function cargar_tablaDD1($ID_EMPLEADOR_MAESTRO) {

    $dao_persona = new EmpleadorDestaqueDao();

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

    $count = $dao_persona->cantidad($id_empleador_maestro, $WHERE, $start, $limit, $sidx, $sord);

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

        $param = $rec["id_empleador_destaque"];
        $_01 = $rec["id_empleador_maestro"];
        $_02 = $rec["id_empleador"];
        $_03 = $rec["ruc"];
        $_04 = $rec["razon_social"];

        $js = "javascript:cargar_pagina('sunat_planilla/view/new_empleador_dd1.php?ruc_empleador_subordinado=" . $rec["ruc"] . "','#CapaContenedorFormulario')";

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


function buscarIdEmpleadorDestaque($id_empleador_maestro) {

    $dao = new EmpleadorDestaqueDao();
    $id = $dao->buscarIdEmpleadorDestaque($id_empleador_maestro);
    return $id;
}

//--------------------------
function getID_EmpleadorDestaque($id_empleador, $id_empleador_maestro) {

    //--------------------------------------------------------------------------    
    $dao = new EmpleadorDestaqueDao();
    $BANDERA_ID = $dao->existeRelacionCreada($id_empleador, $id_empleador_maestro);
    //--------------------------------------------------------------------------

    if (is_null($BANDERA_ID)) {// = NO EXISTE Empleador Maestro Empleador REGISTRADO   
        $dao_d = new EmpleadorDestaqueDao();
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


function listarEmpleadorDestaque() {

    $ID_EMPLEADOR = $_SESSION['sunat_empleador']['id_empleador'];

    // paso 01 ->Identidicaion Empleador Maestro
    $dao0 = new EmpleadorMaestroDao();
    $data_emaestro = $dao0->buscarIdEmpleadorMaestroPorIDEMPLEADOR($ID_EMPLEADOR);
    $id_empleador_maestro = $data_emaestro['id_empleador_maestro'];

    // paso 02 -> Obtener IDS
    $dao = new EmpleadorDestaqueDao();
    $ids = $dao->listaIDEmpleador($id_empleador_maestro);

    //echo "<pre>";
    //print_r($ids);
    //echo "<pre>";
    // paso 03    
    $lista = array();
    $dao = new EmpleadorDao();

    for ($i = 0; $i < count($ids); $i++) {
        $lista[] = $dao->buscarEmpleadorPorId2($ids[$i]);
    }

    return $lista;
}

//---
function listarEstablecimientoDestaque($id_empleador) {
    
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
function buscarID_EMP_EmpleadorDestaquePorTrabajador($id_trabajador, $id_detalle_establecimiento) {
    $dao = new DetalleEstablecimientoDao();
    $id_empleador = $dao->buscarID_EMP_EmpleadorDestaquePorTrabajador($id_trabajador, $id_detalle_establecimiento);
    return $id_empleador;
}

?>