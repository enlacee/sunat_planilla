<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/PlameDeclaracionDao.php';


    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    
    //CONCEPTOS    
    require_once '../dao/PlameDetalleConceptoEmpleadorMaestroDao.php';
    
    // TABLAS MUCHOS A MUCHOS
    require_once '../model/PTrabajador.php';
    require_once '../model/Dcem_Pingreso.php';
    require_once '../model/Dcem_Pdescuento.php';
    require_once '../model/Dcem_Ptributo_aporte.php';
    
    require_once '../model/PjornadaLaboral.php';
    
    
    require_once '../dao/Dcem_PingresoDao.php';
    require_once '../dao/Dcem_PdescuentoDao.php';
    require_once '../dao/Dcem_Ptributo_aporteDao.php';
    require_once '../dao/PjoranadaLaboralDao.php';
    
    require_once '../dao/PtrabajadorDao.php';
    
    
    require_once '../dao/PlameDao.php';
    
    
}

$response = NULL;

if ($op == "nueva_declaracion") {
    //$response = cargar_tabla(); 
} else if ($op == "add") {

    $post_fecha = "01/" . $_REQUEST['periodo'];

    $periodo = getFechaPatron($post_fecha, "Y-m-d");

    // Se Registra el Periodo mes/anio 

    if (nuevaDeclaracion(ID_EMPLEADOR_MAESTRO, $periodo)) {
        $response->rows[0]['estado'] = "true";
    } else {
        $response->rows[0]['estado'] = "false";
    }

    $response->rows[0]['data_mes'] = getMesInicioYfin($periodo);
    
    
} else if ($op == "cargar_tabla_ptrabajador") {
    //CARGAR JQGRID ptrabajadores
    cargar_tabla_ptrabajador();
}


echo (!empty($response)) ? json_encode($response) : '';

function existeDeclaracion() {
    $dao = new PlameDeclaracionDao();
    $dao->existeDeclaracion();
}

//FUNCION ADCIONAL
function nuevaDeclaracion($id_empleador_maestro, $periodo) {
    
    $estado = false;

    $dao = new PlameDeclaracionDao();
    $rpta = $dao->existeDeclaracion($id_empleador_maestro, $periodo);
    //$rpta = 1; Existe Declaracion en este Periodo (1 o mas  = 2 ERROR!!!!!!!);
    
    if ($rpta == 0) { //NO existe periodo
        $id_pdeclaracion = $dao->registrar($id_empleador_maestro, $periodo);

        //--------
        //Listar los trabajadores y registrarlos
        //listar y registrar tablas muchos a muchos
        //--------
      // echo "<<<<------>>>><br> ".$id_pdeclaracion; 
       
        //LISTAR TRABAJADORES 
        
        $Daoo = new PlameDao();
        
        $FECHA = getMesInicioYfin($periodo);        
        $id_trabajador = $Daoo->listarTrabajadoresPorPeriodo($id_empleador_maestro, $FECHA['mes_fin']);
        
        //echo "<pre>";
        //print_r($FECHA);        
        //var_dump($id_trabajador);        
        //echo "</pre>";
        
        for($i=0; $i< count($id_trabajador);$i++ ){
        
            registrarPTrabajadores($FECHA['mes_fin'], $id_pdeclaracion, $id_trabajador[$i]['id_trabajador'], $id_empleador_maestro);
        
        }
        
        
        $estado  = true;
    } else {
        $estado = false;
    }

    return $estado;
}

//------
// ESTA FUNCION CREADA PARA aminorar el codigo en la funcion nuevaDeclaracion
function registrarPTrabajadores($FECHA_FIN, $id_pdeclaracion, $id_trabajador, $id_empleador_maestro) {

    // Registrar PTrabajadores
    $obj_1 = new PTrabajador();
    $obj_1->setId_pdeclaracion($id_pdeclaracion);
    $obj_1->setId_trabajador($id_trabajador);

    //DAO
    $dao_pi = new PtrabajadorDao();
    $ID_PTRABAJADOR = $dao_pi->registrar($obj_1);
    
    /*
    echo "<pre>";
    
    print_r($ID_PTRABAJADOR);
    
    echo "</pre>";
    */

 

    //--------------------------------------------------------------------------
    //PASO 01.1  -- INGRESOS listar conceptos
    $dao_dcemi = new PlameDetalleConceptoEmpleadorMaestroDao();
    $data_dcemi = $dao_dcemi->listar_dcem_pingresos($id_empleador_maestro); //CONCEPTO 0100,0200,0300
    //PASO 01.2  -- Registrar
    $dao_i = new Dcem_PingresoDao();

    $obj_i = new Dcem_Pingreso();
    for ($i = 0; $i < count($data_dcemi); $i++) {
        $obj_i->setId_ptrabajador($ID_PTRABAJADOR);
        $obj_i->setId_detalle_concepto_empleador_maestro($data_dcemi[$i]['id_detalle_concepto_empleador_maestro']);
        //DAO
        $dao_i->registrar($obj_i);
    }



 
    //--------------------------------------------------------------------------
    //PASO 02.1  -- DESCUENTOS listar conceptos
    $dao_dcem_d = new PlameDetalleConceptoEmpleadorMaestroDao();
    $data_dcem_d = $dao_dcem_d->listar_dcem_pdescuentos($id_empleador_maestro); //CONCEPTO 0700
    //PASO 02.1 -- Registrar descuentos
    $dao_d = new Dcem_PdescuentoDao();
    
    //echo "<pre>";
    //print_r($data_dcem_d);
   // echo "</pre>";

    $obj_d = new Dcem_Pdescuento();
    for ($i = 0; $i < count($data_dcem_d); $i++) {
        $obj_d->setId_ptrabajador($ID_PTRABAJADOR);
        $obj_d->setId_detalle_concepto_empleador_maestro( $data_dcem_d[$i]['id_detalle_concepto_empleador_maestro'] );
        //DAO
        $dao_d->registrar($obj_d);        
       
    }



    //--------------------------------------------------------------------------
    //PASO 03.1  -- TRIBUTOS Y APORTES listar conceptos
    $dao_dcem_ta = new PlameDetalleConceptoEmpleadorMaestroDao();
    $data_dcem_ta = $dao_dcem_ta->listar_dcem_ptributos_aportes($id_empleador_maestro); //CONCEPTO 0600, 0800
    
    //PASO 03.2 -- Registrar Tributos y Aportes
    $dao_dcem_ta = new Dcem_Ptributo_aporteDao();

    $obj = new Dcem_Ptributo_aporte();
    for ($i = 0; $i < count($data_dcem_ta); $i++) {
        $obj->setId_ptrabajador($ID_PTRABAJADOR);
        $obj->setId_detalle_concepto_empleador_maestro($data_dcem_ta[$i]['id_detalle_concepto_empleador_maestro']);
        //DAO
        $dao_dcem_ta->registrar($obj);
    }

    

    //--------------------------------------------------------------------------
    //PASO 04.1 -- JORNADAS LABORALES
    //variables
    $Month = getFechaPatron($FECHA_FIN, "m");
    $Year = getFechaPatron($FECHA_FIN,"Y");
    
    $dia_laborado = getMonthDays($Month, $Year);
  
    $obj_jl = new PjornadaLaboral();
    $obj_jl->setId_ptrabajador($ID_PTRABAJADOR);
    $obj_jl->setDia_laborado($dia_laborado);
    
    //DAO
    $dao_jl = new PjoranadaLaboralDao();
    $dao_jl->registrar($obj_jl);
    
    
    
    
    
    
    
    

}









// JQGRID
//
//
//
//
function cargar_tabla_ptrabajador() {
    
    $ESTADO = ($_REQUEST['estado']) ? $_REQUEST['estado'] : 0;
    
    $dao_ptrabajador = new PtrabajadorDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction


/*    if ($_REQUEST['estado']) {
        // $estado = "WHERE p.estado = '" . $_REQUEST['estado'] . "' ";
        $WHERE = $estado;
    }
*/

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

    $count = $dao_ptrabajador->cantidad($ESTADO, ID_EMPLEADOR_MAESTRO, $WHERE);

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

    //$dao_ptrabajador->actualizarStock();

    $lista = $dao_ptrabajador->listar($ESTADO,ID_EMPLEADOR_MAESTRO, $WHERE, $start, $limit, $sidx, $sord);

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


        //Condicionar Tipo de Categoria
        $categoria_1 = $rec['categoria_1'];
        $categoria_2 = $rec['categoria_2'];
        $categoria_3 = $rec['categoria_3'];
        $categoria_4 = $rec['categoria_4'];

        $string_cat = "";
        if ($categoria_1 == 'TRA') {
            $string_cat .= 'TRA.';
        }

        if ($categoria_2 == 'PEN') {
            $string_cat .= 'PEN.';
        }

        if ($categoria_3 == 'PFOR') {
            $string_cat .= 'PFOR.';
        }

        if ($categoria_4 == 'PTER') {
            $string_cat .= 'PTER.';
        }

        $param = $rec["id_persona"];
        $_01 = $string_cat;
        $_02 = $rec["nombre_tipo_documento"];
        $_03 = $rec["num_documento"];
        $_04 = $rec["apellido_paterno"];
        $_05 = $rec["apellido_materno"];

        $_06 = $rec["nombres"];
        $_07 = $rec["fecha_nacimiento"];
        $_08 = $rec["sexo"];
        $_09 = $rec["estado"];

        $_10 = $rec['cod_situacion'];

        $js = "javascript:cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona=" . $param . "&cod_situacion=".$_10."','#CapaContenedorFormulario')";
        $js2 = "javascript:eliminarPersona('" . $param . "')";

        if ($_10 == 1) {            
            $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>				
		&nbsp;
		<!-- <span  title="Cancelar" >
		<a href="' . $js2 . '"><img src="images/cancelar.png"/></a>
		</span>	-->
		</div>';
        } else {
            $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>
                </div>';
        }

        //hereee

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
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
            $opciones
        );

        $i++;
    }

    return $responce;  //RETORNO A intranet.js
}









?>
