<?php
//session_start();
//header("Content-Type: text/html; charset=utf-8");
$op = $_REQUEST["oper"];

if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    // Identidicando Caracteristicas del Empleador    
    require_once ('../dao/EmpleadorDao.php');

    require_once '../dao/PlameConceptoDao.php';

    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    // require_once '../controller/EmpleadorController.php';
    //plame
    require_once '../dao/PlameDetalleConceptoEmpleadorMaestroDao.php';




    $daoe = new EmpleadorDao();
    $empleador = array();
    $empleador = $daoe->buscaEmpleadorPorRuc(RUC);
}

$responce = NULL;

if ($op == "cargar_tabla") {
    $config = $_REQUEST['config'];
    $responce = cargar_tabla_plame_concepto($empleador, $config);
} else if ($op == "cargar_registro_por_concepto") {

    $conceptos = array('100', '200', '300', '400', '500','600', '700', '900');
    $responce = cargar_tabla_RegistrosPorConcepto(ID_EMPLEADOR_MAESTRO, $conceptos);
}
else if($op == "cerrar_mes"){
    $conceptos = array('100', '200', '300', '400', '500','600', '700', '900');
    cerrarMes($conceptos);
}





echo (!empty($responce)) ? json_encode($responce) : '';

function cargar_tabla_plame_concepto($empleador, $config) {

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

    $lista = $dao->listar($start, $limit, $sidx, $sord);


    //--------------------------------------------------------------------------
    /**
     * lOGICA para Mostrar Conceptos     * 
     * No se maneja la logica de Tipo de Empresa u !! 
     */
    $id = array();

    // Si es = 1  = SECTOR PRIVADO
    if ($empleador['id_tipo_empleador'] == '1') {//SECTOR PRIVADO =2 , U OTROS=3
        $id[] = '2000';
    }
    // Con los datos del empleador = segun estos devolvera determinados conceptos

    if ($empleador['senati'] == 1) { //SI APORTA
        //$num = "";
    } else { // NO APORTA
        $id[] = "0800";
    }

    //AFP
    $id[] = "0600";


    //ID A ELIMINAR 
    //$id = array('0600', $num, '2000');
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

        /**
         * Condicion para Concepto 1000
         */
        //new
        // JS-CONFIG
        if ($config == 'config') {
            $js = "javascript:cargar_pagina('sunat_planilla/view-plame/configuracion/view_detalle_concepto.php?id_concepto=" . $param . "','#detalle_concepto')";
        } else {
            if ($param == '1000') {
                $js = "javascript:cargar_pagina('sunat_planilla/view-plame/detalle/view_detalle_concepto_1000.php?id_concepto=" . $param . "','#detalle_concepto')";
            } else {

                $js = "javascript:cargar_pagina('sunat_planilla/view-plame/detalle/view_detalle_concepto.php?id_concepto=" . $param . "','#detalle_concepto')";
            }
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

function buscar_concepto_por_id($cod_concepto) {

    $dao = new PlameConceptoDao();
    return $dao->buscarID($cod_concepto);
}

function cargar_tabla_RegistrosPorConcepto($id_empleador_maestro, $conceptos) {
    
    //echoo($_REQUEST);
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];

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

    // 02
    $dao = new PlameDetalleConceptoEmpleadorMaestroDao();
    $lista = array();
    $lista = $dao->view_listarConcepto($id_empleador_maestro, $conceptos, 1);

//echo "<pre>";
//print_r($lista);
//echo "</pre>";
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
    $conceptos_workers = array('0105', '0106', '0107','0115', '0201', '0304'/*,'312','406'*/,'407','0604','0612', '0701', '0703', '0704', '0705', '0909');

    foreach ($lista as $rec) {

        $param = $rec["id_detalle_concepto_empleador_maestro"];
        $_01 = $rec['cod_detalle_concepto'];
        $_02 = $rec['descripcion'];

        $opciones = null;
        if (in_array($_01, $conceptos_workers)) {
            //echo "<<<$_01 ===encontro===$conceptos_workers>>>";

            $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/add_registro_por_concepto.php?cod_detalle_concepto=$_01&id_pdeclaracion=$ID_PDECLARACION','#CapaContenedorFormulario')";
            //$js2 = "javascript:eliminarTrabajadorPdeclaracion('" . $param . "')";
            $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar"   >
		<a href="' . $js . '" class="divEditar" ></a>
		</span>   

		</div>';
        }

        //hereee
        //$_02 = '<a href="javascript:add_15('.$param.',\''.$_01.'\')" title = "Agregar UNICO Adelanto 15">1era 15</a>';
        // $_04 = '<a href="javascript:cargar_pagina(\'sunat_planilla/view-empresa/view_etapaPago.php?id_declaracion='.$param.'&periodo='.$_01.'\',\'#CapaContenedorFormulario\')"title = "VER">Ver</a>';
        //hereee
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $opciones
        );
        $i++;
    }

//echo "<pre>";
//print_r($response);
//echo "</pre>";
    return $response;
}







/**
 * Plame concepto controller
 * 
 * THIS lista de cod 
 */
function cerrarMes($conceptos){ //?????
    
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    
    // paso 01
    // Listado de codigo concepto detalle SELECCIONADOS admin : 101,102...201,202
    $dao = new PlameDetalleConceptoEmpleadorMaestroDao();
    $data = array();
    $data = $dao->view_listarCod_Concepto(ID_EMPLEADOR_MAESTRO, $conceptos, 1);
    
    
    // paso 02
    //Buscar(id_pdeclaracion = mes - anio ) SINO  Registra un nuevo 
    $dao_pd = new PlameDeclaracionDao(); 
    $data_pd_hoy = $dao_pd->buscar_ID($id_pdeclaracion);
    $periodo_hoy = null;
    $periodo_futuro = null;
    
    $id_pdeclaracion_futuro = null;
    
    if($data_pd_hoy['id_pdeclaracion']){
        
        $periodo_hoy = $data_pd_hoy['periodo'];
        
        $periodo_futuro = crearFecha($periodo_hoy,0,1,0); //añade un mes
        
        echoo($periodo_futuro);
        
        //Dar de baja Pdeclaracion
        //$dao = new PlameDeclaracionDao();
        //$response = $dao->baja($_REQUEST['id_pdeclaracion']);  
        
        /*
        $data_pd_fut =array();
        $data_pd_fut = $dao_pd->Buscar_IDPeriodo(ID_EMPLEADOR_MAESTRO, $periodo_futuro);
        
        //DECISION BINARIA.
        if($data_pd_fut['id_pdeclaracion']){ //existe declaracion
             $id_pdeclaracion_futuro =  $data_pd_fut['id_pdeclaracion']; 
             
        }else{ //no existe Y INSERT
            
            $id_pdeclaracion_futuro = $dao_pd->registrar(ID_EMPLEADOR_MAESTRO, $periodo_futuro);
        }*/
        
        
   /*     
        //----------------------------------------------------------------------        
    //paso 03
    //listar si existen en trabajadores registrados en  : registros_por_conceptos
    for($i=0;$i<count($data);$i++){
        $dao_rpc = new RegistroPorConceptoDao();
        $data_rpc = $dao_rpc->listarTrabajador($id_pdeclaracion, $data[$i]['cod_detalle_concepto']);
        
        if(!is_null($data_rpc)):
            for($j=0;$j<count($data_rpc);$j++):
                // insert all la data  al siguiente periodo.
                $obj_rpc = new RegistroPorConcepto();
                $obj_rpc->setId_pdeclaracion($id_pdeclaracion_futuro);
                $obj_rpc->setId_trabajador($data_rpc[$j]['id_trabajador']);
                $obj_rpc->setId_registro_por_concepto($data_rpc[$j]['cod_detalle_concepto']);
                $obj_rpc->setValor($data_rpc[$j]['valor']);
                $obj_rpc->setEstado($data_rpc[$j]['estado']);
                       
                // INSERT
                $dao_rpc->add($obj_rpc);
                
            endfor;
            
        endif;
    }        
       */ 
        
    }
    
    
    
}
?>
