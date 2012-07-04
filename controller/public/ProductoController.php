<?php

session_start();
require_once '../dao/AbstractDao.php';
//require_once '../dao/BaseDao.php';
require_once '../dao/productoDAO.php';
require_once '../dao/proveedorDAO.php';

//require_once '../dao/CategoriaDao.php';

//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];
/*
  if(isset ($_REQUEST["datos"])){
  $datos = $_REQUEST["datos"];
  } */

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla(); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "add") {
    nuevoProducto();
} elseif ($op == "edit") {

    editarProducto();
} elseif ($op == "del") {
    $idProducto = $_REQUEST["id"];
    eliminarProducto($idProducto);
} elseif ($op == "04") {
    $codigo = $_REQUEST["codigo"];
    validarCodigoProducto($codigo);
} elseif ($op == "select_codigo") {
    echo cargarSelectCodigo();
}


echo json_encode($responce);
/* * **********categoria*************** */

function nuevoProducto() {


    $producto = new productoDAO();

    $codigo = $_REQUEST['codigo'];
    $nombre = $_REQUEST['nombre'];
	$marca = $_REQUEST['marca'];
    $estado = $_REQUEST['estado'];
	$prove = $_REQUEST['razon_social'];
	
	//$val=validarCodigoProducto($codigo);
   	//if($val){
    	$id_producto = $producto->registrarProducto($codigo, $nombre, $marca, $estado,$prove);
	//	echo "si";
   	//}else{
	//	echo "no";
	//}
}

function editarProducto() {

    $producto = new productoDAO();

    $id_productoP = $_REQUEST['id'];
    $codigoP = $_REQUEST['codigo'];
    $nombreP = $_REQUEST['nombre'];
	$marcaP = $_REQUEST['marca'];
    $estado = $_REQUEST['estado'];
	$prove = $_REQUEST['razon_social'];

    $resp = $producto->actualizarProducto($id_productoP, $codigoP, $nombreP, $marcaP, $estado,$prove);
}

function eliminarProducto($idProducto) {

    $producto = new productoDAO();

    $resp = $producto->eliminarProducto($idProducto);
}

function cargar_tabla() {

  

    $producto = new productoDAO();

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
		if($_GET['searchOper']=="cn")
        	$WHERE = "WHERE ".$_GET['searchField'] ." ". $operadores[$_GET['searchOper']] . " '%" . $_GET['searchString'] . "%' ";
		else
			$WHERE = "WHERE ".$_GET['searchField'] ." ". $operadores[$_GET['searchOper']] . "'" . $_GET['searchString'] . "'";
			
    }

    if (!$sidx)
        $sidx = 1;

    $count = $producto->cantidadProductos($WHERE);

    // $count = $count['numfilas'];
    if ($count > 0) {
        $total_pages = ceil($count / $limit); //CONTEO DE PAGINAS QUE HAY
    } else {
        $total_pages = 0;
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

    //$producto->actualizarStock();

    $lista = $producto->listarProductos($WHERE, $start, $limit, $sidx, $sord);

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
$proveedor = new proveedorDAO();
        $param = $rec["id_producto"];
        $codigo = $rec["codigo"];
        $nombre = $rec["nombre"];
        $marca = $rec["marca"];
		if($rec["estado"]=="A")
        	$estado = "Activo";
		elseif($rec["estado"]=="I")
			$estado = "Inactivo";
			if($rec["fecha_creacion"])
		$fecha_creacion = date("d/m/Y",strtotime($rec["fecha_creacion"]));
		else
		$fecha_creacion = "";
		$prove = $rec["razon_social"];
		       //hereee
        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $codigo,
            $nombre,
            $marca,
            $estado,
			$fecha_creacion,
			$prove
        );

        $i++;
    }

    return $responce;  //RETORNO A intranet.js
}
function cargarSelectCodigo() {


    $proveedor = new proveedorDAO();
    
    $lista_codigos = $proveedor->BuscaProveedores();
    

    $html = '<select name="codigo_articulo_detalle">';
    $html .= '<option value="0" selected="selected" >Elija Opcion</option>';
    foreach ($lista_codigos as $key => $value) {
        $html .='<option value="' . $value['id_proveedor'] . '" >' . $value['razon_social'] . '</option>';
      
    }
    $html.="</select>";

    // $producto = new productoDAO();
     
     
     echo $html;
     
}


?>