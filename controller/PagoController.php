<?php
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    //CONTROLLER
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    //PAGO
    require_once '../dao/PagoDao.php';
    require_once '../model/Pago.php';

    
    //CALCULO DE DIAS controller
    require_once '../controller/PlameDiaNoSubsidiadoController.php';
    require_once '../controller/PlameDiaSubsidiadoController.php';
    
}

$response = NULL;

if ($op == "registrar_etapa") {
    $response = registrarTrabajadoresPorEtapa();
} else if ($op == "cargar_tabla") {
    $response = cargartabla();
    
}else if($op == "grid_lineal"){
    $response = cargar_tabla_grid_lineal();
}else if($op == "edit"){
    
    $response = editarPago();
}

echo (!empty($response)) ? json_encode($response) : '';



function cargartabla(){
     $ID_ETAPA_PAGO = $_REQUEST['id_etapa_pago'];

    $dao = new PagoDao();

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

    $lista = array();
    $lista = $dao->listar($ID_ETAPA_PAGO);

    $count = count($lista);

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

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
//print_r($lista);

    foreach ($lista as $rec) {

        $param = $rec["id_pago"];
        $_00 = $rec['id_trabajador'];
        $_01 = $rec['cod_tipo_documento'];
        $_02 = $rec['num_documento'];  
        $_03 = $rec['apellido_paterno'];
        $_04 = $rec['apellido_materno'];
        $_05 = $rec['nombres'];
        $_06 = $rec['dia_total'];
        $_07 = $rec['valor_neto'];
        $_08 = $rec['ccosto']; //Ccosto
        $_09 = $rec['estado'];

        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/editar_trabajador.php?id_pago=" . $param. "&id_trabajador=".$_00."','#detalle_declaracion_trabajador')";

        // $js2 = "javascript:eliminarPersona('" . $param . "')";		
        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>
		</div>';

        //hereee
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_07,
            $_08,
            $opciones
        );
        $i++;
    }

//echo "<pre>";
//print_r($response);
//echo "</pre>";
    return $response;
    
    
}

// VIEW EMPRESA
function buscarPagoPor_ID($id_pago){
    
    $dao = new PagoDao;    
    $data = $dao->buscar_ID($id_pago);
    
    $model = new Pago();
    if($data){
        $model->setId_pago($data['id_pago']);
        $model->setId_etapa_pago($data['id_etapa_pago']);
        $model->setId_trabajador($data['id_trabajador']);
        $model->setId_empresa_centro_costo($data['id_empresa_centro_costo']);
        $model->setSueldo_base($data['sueldo_base']);
        $model->setSueldo($data['sueldo']);
        $model->setSueldo_neto($data['sueldo_neto']);        
        
        $model->setDescuento($data['descuento']);
        $model->setDescripcion($data['descripcion']);
        $model->setDia_total($data['dia_total']);
        $model->setDia_laborado($data['dia_laborado']);
        /*$model->setDia_nosubsidiado($data['dia_nosubsidiado']);
        $model->setDia_laborado($data['dia_laborado']);*/
        $model->setOrdinario_hora($data['ordinario_hora']);
        $model->setOrdinario_min($data['ordinario_min']);
        $model->setSobretiempo_hora($data['sobretiempo_hora']);
        $model->setSobretiempo_min($data['sobretiempo_min']);
        $model->setEstado($data['estado']);
        $model->setId_empresa_centro_costo($data['id_empresa_centro_costo']);
        $model->setFecha_modificacion($data['fecha_modificacion']);
    }
    return $model;
    
    
    
    
    function CalcularSueldo($dia_total,$dia_falto){
       // $dia = 
        
    }
    
    
}


// GRID SIN PIE 
function cargar_tabla_grid_lineal(){
      $ID_PAGO = $_REQUEST['id_pago'];

    $dao = new PagoDao();

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

    $lista = array();
    $lista = $dao->buscar_ID_GRID_LINEAL($ID_PAGO);

    $count = count($lista);

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

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
//print_r($lista);
  

    foreach ($lista as $rec) {
        $param = $rec["id_pago"];        
        $dia_total = $rec['dia_total'];
        
        
        
       $dao1 = new PdiaSubsidiadoDao();
       $dia_subsidiado = $dao1->busacar_IdPago($param,"SUMA");
       
       $dao2 =new PdiaNoSubsidiadoDao();
       $dia_NOsubsidiado = $dao2->buscar_IdPago($param,"SUMA");
      
       
       $dia_laborado_calc = $dia_total - ($dia_subsidiado +$dia_NOsubsidiado);
        //$_00 = $rec['id_trabajador'];
        $_01 = $rec['cod_tipo_documento'];
        $_02 = $rec['num_documento'];  
        $_03 = $rec['apellido_paterno'];
        $_04 = $rec['apellido_materno'];
        $_05 = $rec['nombres'];
        $_06 = $dia_laborado_calc;
        $_07 = $rec['sueldo']; //INGRESOS
        $_08 = $rec['descuento'];//$rec['descuento']; 
        $_09 = $rec['sueldo_neto']; //$rec['valor_neto'];
        $_10 = $rec['estado'];

        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/editar_trabajador.php?id_pago=" . $param. "&id_trabajador=".$_00."','#detalle_declaracion_trabajador')";

        // $js2 = "javascript:eliminarPersona('" . $param . "')";		
        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>
		</div>';

        //hereee
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
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
            $_10
            //$opciones*/
        );
        $i++;
    }

//echo "<pre>";
//print_r($response);
//echo "</pre>";
    return $response;   
    
}




//editar

function editarPago(){
    echo "<pre>";
    print_r($_REQUEST);
    echo "</pre>";
    $ID_PAGO = $_REQUEST['id_pago'];
    
    $model = new Pago();
    $model->setId_pago($ID_PAGO);
    //$model->setId_trabajador($data[]);
    //$model->setValor($valor) //NO CAMBIA
    $model->setDescuento($_REQUEST['descuento']); 
    $model->setSueldo_neto($_REQUEST['total_ingreso']);
    $model->setDescripcion($_REQUEST['descripcion']);
    //$model->setDia_laborado($_REQUEST['dia_laborado']);
    $model->setOrdinario_hora($_REQUEST['hora_ordinaria_hh']);
    $model->setOrdinario_min($_REQUEST['hora_ordinaria_mm']);
    $model->setSobretiempo_hora($_REQUEST['hora_sobretiempo_hh']);
    $model->setSobretiempo_min($_REQUEST['hora_sobretiempo_mm']);
    $model->setFecha_modificacion(date("Y-m-d H:i:s"));
    //$model->set
    
    echo "<pre>MODEL";
    print_r($model);
    echo "</pre>";
    
    $dao = new PagoDao();
    return $dao->actualizar($model);
    
    
}


?>
