<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];

if ($op) {
    session_start();
   require_once '../dao/AbstractDao.php'; 
   require_once '../dao/PlameConceptoDetalleDao.php';
   
    //IDE_EMPLEADOR_MAESTRO
    //require_once '../controller/ideController.php';
}

$responce = NULL;

if ($op == "cargar_tabla") {
    
    $responce = cargar_tabla($_REQUEST['id_concepto']);
   
}

//echo count($responce);
echo (!empty($responce)) ? json_encode($responce) : '';

function cargar_tabla($cod_concepto) {

    $dao = new PlameConceptoDetalleDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // 

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

    $count = $dao->cantidad($cod_concepto);

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

    $lista = $dao->listar($cod_concepto,$start, $limit, $sidx, $sord);

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

        
        $param = $rec["cod_detalle_concepto"]; 
        $_00 = $rec["cod_concepto"];
        $_01 = $rec["descripcion"];

        //new
        $js = "javascript:editarAfectacion('".$param."')";

        $opciones = '<div id="divEliminar_Editar">				
				<span  title="Detalle Concepto" >
				<a href="' . $js . '"><img src="images/search2.png"/></a>
				</span>	
		</div>';

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_00,
            $_01,            
            utf8_encode($opciones)
        );

        $i++;
    }

    return $responce;
}



// Usando Desde VISTA
function listarDetalleConcepto($cod_concepto){
    
    $dao = new PlameDetalleConceptoDao();
    return  $dao->listar($cod_concepto);
      
}

function cantidadDetalleConcepto($cod_concepto){
    $dao = new PlameDetalleConceptoDao();
    return $dao->cantidad($cod_concepto);
    
}


?>
