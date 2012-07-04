<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];
if ($op) {
    require_once '../util/funciones.php';	
    require_once '../dao/AbstractDao.php';	
    require_once '../dao/DerechohabienteDireccionDao.php';
	require_once '../model/Direccion.php';
	require_once '../model/DerechohabienteDireccion.php';
	
	//require_once '../dao/DerechohabienteDireccionDao.php';

  //  require_once '../model/Persona.php';
    
  //  Modelo Direccion
  //  require_once '../model/PersonaDireccion.php';
}
/*
  if(!empty ($_REQUEST["datos"])){
  $datos = $_REQUEST["datos"];
  } */

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla($_REQUEST['id_derechohabiente']); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "add") {
    $responce = nuevo();
} elseif ($op == "edit") {
     $responce = editarDerechohabienteDireccion($_REQUEST['id_derechohabiente_direccion']);
} elseif ($op == "del") {
    $id = $_REQUEST["id"];
    eliminar($id);
} else {
    //echo "oper INCORRECTO";
}

echo (!empty($responce)) ? json_encode($responce) :'';



function cargar_tabla($id_derechohabiente) {

    $dao = new DerechohabienteDireccionDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction

    $WHERE = "";

    if (!empty($_GET['searchField']) && ($_GET['searchString'] != null)) {

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

    $count = $dao->cantidadDerechohabienteDireccionesConIdPersona($id_derechohabiente);

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

    $lista = $dao->listarDerechohabienteDirecciones($id_derechohabiente, $WHERE, $start, $limit, $sidx, $sord);

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

//echo "<pre>";
//print_r($rec);
//echo "</pre>";
//echo "<br>";

        if ($rec['estado_direccion'] == 1) {
            $estado_direccion = 'Primera';
        } else {
            $estado_direccion = 'Segunda';
        }


        $param = $rec["id_derechohabiente_direccion"];
        $id_derechohabiente = $rec['id_derechohabiente'];
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
		$referencia = $_rec['referencia'];		
		
		$ubigeo_departamento = str_replace('DEPARTAMENTO', '', $rec['ubigeo_departamento']);
		$ubigeo_provincia = $rec['ubigeo_provincia'];		
		$ubigeo_distrito = $rec['ubigeo_distrito'];

	   $js = "javascript:editarDerechohabienteDireccion('".$param."')";
       
        $opciones = '<div id="divEliminar_Editar">				
				<span  title="Editar" >
				<a href="'.$js.'"><img src="images/edit.png"/></a>
				</span>				
				&nbsp;
				</div>';
        //hereee
		//---------------Inicio Cadena String----------//
		$cadena = '';
		
		$cadena .= (!empty($ubigeo_nombre_via))? ''.$ubigeo_nombre_via.' ' : '';		
		$cadena .= (!empty($nombre_via))? ''.$nombre_via.' ' : '';
		$cadena .= (!empty($numero_via))? ''.$numero_via.' ' : '';
		
		$cadena .= (!empty($ubigeo_nombre_zona))? ''.$ubigeo_nombre_zona.' ' : '';
		$cadena .= (!empty($nombre_zona))? ''.$nombre_zona.' ' : '';
		$cadena .= (!empty($etapa))? ''.$etapa.' ' : '';
		
		$cadena .= (!empty($manzana))? 'MZA. '.$manzana.' ' : '';
		$cadena .= (!empty($blok))? ''.$blok.' ' : '';
		$cadena .= (!empty($etapa))? ''.$etapa.' ' : '';
		$cadena .= (!empty($lote))? 'LOTE. '.$lote.' ' : '';
		
		$cadena .= (!empty($departamento))? ''.$departamento.' ' : '';
		$cadena .= (!empty($interior))? ''.$interior.' ' : '';
		$cadena .= (!empty($kilometro))? ''.$kilometro.' ' : '';
		$cadena .= (!empty($referencia))? ''.$referencia.' ' : '';
		
		
		$cadena .= (!empty($ubigeo_departamento))? ''.$ubigeo_departamento.'-' : '';
		$cadena .= (!empty($ubigeo_provincia))? ''.$ubigeo_provincia.'-' : '';
		$cadena .= (!empty($ubigeo_distrito))? ''.$ubigeo_distrito.' ' : '';
		
		$cadena = strtoupper($cadena);

		//---------------Inicio Cadena String----------//
        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
			$id_derechohabiente,
            $cadena,
			$estado_direccion,
            $opciones

        );
        $i++;
    }

    return $responce;  //RETORNO A intranet.js
	
}




//Buscar PersonaDirecion Por ID

//buscar Persona Direccion

function buscarDerechohabienteDireccionPorId($id){

$obj = new DerechohabienteDireccion();
$dao = new DerechohabienteDireccionDao();
$data = $dao->buscarDerechohabienteDireccionPorId($id);

$obj->setId_derechohabiente_direccion($data['id_derechohabiente_direccion']);
$obj->setId_derechohabiente($data['id_derechohabiente']);
$obj->setCod_ubigeo_reinec($data['cod_ubigeo_reniec']);
$obj->setCod_via($data['cod_via']);
$obj->setNombre_via($data['nombre_via']);
$obj->setNumero_via($data['numero_via']);
$obj->setDepartamento($data['departamento']);
$obj->setInterior($data['interior']);
$obj->setManzanza($data['manzana']);
$obj->setLote($data['lote']);
$obj->setKilometro($data['etapa']);
$obj->setBlock($data['block']);
$obj->setEstapa($data['etapa']);
$obj->setCod_zona($data['cod_zona']);
$obj->setNombre_zona($data['nombre_zona']);
$obj->setReferencia($data['referencia']);
$obj->setReferente_essalud($data['referente_essalud']);
$obj->setEstado_direccion($data['estado_direccion']);
//print_r($data);
return $obj;
}




/**
*
**/
function editarDerechohabienteDireccion($id_derechohabiente_direccion) {


    $obj = new DerechohabienteDireccion();//16
    $obj->setId_derechohabiente_direccion($id_derechohabiente_direccion);
    $obj->setId_derechohabiente($_REQUEST['id_derechohabiente']);
    $obj->setCod_ubigeo_reinec($_REQUEST['cbo_distrito']);
    $obj->setCod_via($_REQUEST['cbo_tipo_via']);
    $obj->setNombre_via($_REQUEST['txt_nombre_via']);
    $obj->setNumero_via($_REQUEST['txt_numero_via']);
    $obj->setDepartamento($_REQUEST['txt_dpto']);
    $obj->setInterior($_REQUEST['txt_interior']);
    $obj->setManzanza($_REQUEST['txt_manzana']);
    $obj->setLote($_REQUEST['txt_lote']);
    $obj->setKilometro($_REQUEST['txt_kilometro']);
    $obj->setBlock($_REQUEST['txt_block']);
    $obj->setEstapa($_REQUEST['txt_etapa']);
    $obj->setCod_zona($_REQUEST['cbo_tipo_zona']);
    $obj->setNombre_zona($_REQUEST['txt_zona']);
    $obj->setReferencia($_REQUEST['txt_referencia']);
    $obj->setReferente_essalud($_REQUEST['rbtn_ref_essalud']); //boolean

    $dao = new DerechohabienteDireccionDao();
    return $dao->actualizarDerechohabienteDireccion($obj);
}


?>