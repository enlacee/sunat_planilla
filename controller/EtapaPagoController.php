<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

//CONTROLLER
// IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

//ETAPA PAGO
    require_once '../dao/EtapaPagoDao.php';
    require_once '../model/EtapaPago.php';

    require_once '../dao/PlameDeclaracionDao.php';
    require_once '../dao/PlameDao.php';
//15cena no genera xq tiene vacacion
    require_once '../dao/VacacionDao.php';

//PAGO
    require_once '../dao/PagoDao.php';
    require_once '../model/Pago.php';

//EPAGO TRABAJADOR
    require_once '../dao/PeriodoRemuneracionDao.php';


//ultimo recurso
    require_once '../controller/PlameTrabajadorController.php';
    require_once '../dao/PtrabajadorDao.php';

//---
    require_once '../dao/RegistroPorConceptoDao.php';

//variables conceptos
    require_once '../controller/ConfConceptosController.php';

    //configuracion sueldo basico ++
    // IDE CONFIGURACION 
    require_once '../dao/ConfAsignacionFamiliarDao.php';
    require_once '../dao/ConfSueldoBasicoDao.php';
    require_once '../dao/ConfEssaludDao.php';
    require_once '../dao/ConfOnpDao.php';
    require_once '../dao/ConfUitDao.php';

    require_once '../controller/ConfController.php';
}

$response = NULL;

if ($op == "trabajador_por_etapa") {
    $response = listarTrabajadoresPorEtapa();
} else if ($op == "trabajador_por_mes") {
// Re-comentado
//      lista de trabajadores dentro del mes:
    $response = listar_15_Mes();
} else if ($op == "registrar_etapa") {

    $response = registrarTrabajadoresPorEtapa();
} else if ($op == "cargar_tabla") {

    $response = cargartabla();
} else if ($op == "del") {
    $response = del_etapaPago();
}

echo (!empty($response)) ? json_encode($response) : '';

function del_etapaPago() {
    $dao = new EtapaPagoDao();
    $dao->eliminar($_REQUEST['id_etapa_pago']);
}

//-----------
function listarTrabajadoresPorEtapa() {
//=========================================================================//
    $ID_DECLARACION = $_REQUEST['id_declaracion'];
    $COD_PERIODO_REMUNERACION = $_REQUEST['cod_periodo_remuneracion'];

    if ($COD_PERIODO_REMUNERACION == '2') { // 2 =quincena        
//$dao = new EtapaPagoDao();
//$id_etapa_pago = $dao->buscarEtapaPago_ID($ID_DECLARACION, $COD_PERIODO_REMUNERACION, 1);
//if (is_array($id_etapa_pago) && count($data_etapapago) == 0) { // Registrar 1era QUINCENA
        $response = listar_15(1, $ID_DECLARACION, $COD_PERIODO_REMUNERACION);
    }
//=========================================================================//
    return $response;
}

function registrarTrabajadoresPorEtapa() {

//=========================================================================//
    //VARIABLES    
    $ID_DECLARACION = $_REQUEST['id_declaracion'];
    $COD_PERIODO_REMUNERACION = $_REQUEST['cod_periodo_remuneracion'];
    $ids_trabajador = $_REQUEST['ids'];

    //DAO
    $daoPlame = new PlameDeclaracionDao();
    $data_d = $daoPlame->buscar_ID($ID_DECLARACION);
    $PERIODO = $data_d['periodo'];


    generarConfiguracion($PERIODO);


    if ($COD_PERIODO_REMUNERACION == '2') { // 2 = SIEMPRE PRIMERA quincena
//========================================================================//
        $FECHAX = getFechasDePago($PERIODO);
        $FECHA = array();
//========================================================================//
        if (true/* count($data_id_etapa_pago) == 0 */) {
            $FECHA['inicio'] = $FECHAX['first_day'];
            $FECHA['fin'] = $FECHAX['second_weeks'];
//================================

            $dao = new EtapaPagoDao();
            $id_etapa_pago = $dao->buscarEtapaPago_ID($ID_DECLARACION, 2, 1);

            if (is_null($id_etapa_pago)) {

                $model = new EtapaPago();
                $model->setId_pdeclaracion($ID_DECLARACION);
                $model->setCod_periodo_remuneracion($COD_PERIODO_REMUNERACION);
                $model->setFecha_inicio($FECHA['inicio']);
                $model->setFecha_fin($FECHA['fin']);
                $model->setGlosa("Primera Quincena");
                $model->setTipo("1");
                $model->setFecha_creacion(date("Y-m-d"));

                $id_etapa_pago = $dao->registrar($model);
            }
//--------------------------------
            registrar_15($ID_DECLARACION, $PERIODO, $id_etapa_pago, null, $FECHA['inicio'], $FECHA['fin'], $ids_trabajador);
        } else {
            echo "UN CASO INCONTROLABLE QUINCENA! En tabla Etapa de PAGO";
        }
    }
}

///-------------------- 
function listar_15($tipo, $ID_DECLARACION, $COD_PERIODO_REMUNERACION) {
//========================================================================//
    $dao = new PlameDeclaracionDao();
    $data_d = $dao->buscar_ID($ID_DECLARACION);

    $FECHA_PERIODO = $data_d['periodo'];
    $FECHAX = getFechasDePago($FECHA_PERIODO);

    $FECHA = array();
    if ($tipo == '1') {
        $FECHA['inicio'] = $FECHAX['first_day'];
        $FECHA['fin'] = $FECHAX['second_weeks'];
    } else if ($tipo == '2') {
        $FECHA['inicio'] = $FECHAX['second_weeks_mas1']; //SUMAR 1 DIA para = 16/01/2012 a 31/01/2012
        $FECHA['fin'] = $FECHAX['last_day'];
    }
//========================================================================//
//---------------------------------------------------------------------
//ALGORITMO
//--------------------Inicio Configuracion Basica---------------------------
//Variables
//$periodo = ($_REQUEST['periodo']) ? $_REQUEST['periodo'] : "08/1988";
// $fecha_ISO = "01/" . $periodo;    // DIA/MES/ANIO
//$FECHA = getMesInicioYfin($fecha_ISO);
//--------------------Final Configuracion Basica----------------------------
    $dao_plame = new PlameDao();
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


    $count = $dao_plame->listarTrabajadoresPorPeriodo_global_grid_Count(ID_EMPLEADOR_MAESTRO, $FECHA['inicio'], $FECHA['fin'], $WHERE);

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
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;


    $i = 0;

//llena en al array
    $lista = array();
    $lista = $dao_plame->listarTrabajadoresPorPeriodo_global_grid(ID_EMPLEADOR_MAESTRO, $FECHA['inicio'], $FECHA['fin'], $WHERE, $start, $limit, $sidx, $sord);

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
        $_08 = $rec['monto_remuneracion'];
        $_09 = $rec['descripcion'];
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
            $_09
//utf8_encode($opciones),
//$_06
        );
        $i++;
    }
    return $response;
}

// GRID personal por mes = similar a listar_15()
function listar_15_Mes() {

//========================================================================//
//echoo($_REQUEST);
    $ID_DECLARACION = $_REQUEST['id_pdeclaracion'];
    // SOLO UTILIZADO EN RPC2 'registro por concepto'
    $tipo = $_REQUEST['tipo'];
    
    
    $dao = new PlameDeclaracionDao();
    $data_d = $dao->buscar_ID($ID_DECLARACION);
    
    $FECHA = getFechasDePago($data_d['periodo']);

//echoo($FECHA);
//$FECHA['first_day'];
//$FECHA['last_day'];

    $dao_plame = new PlameDao();
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

    $count = $dao_plame->listarTrabajadoresPorPeriodo_global_grid_Mes_Count(ID_EMPLEADOR_MAESTRO, $FECHA['first_day'], $FECHA['last_day'], $WHERE);

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
//$dao_plame->actualizarStock();
// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;


    $i = 0;

//llena en al array
    $lista = array();
    $lista = $dao_plame->listarTrabajadoresPorPeriodo_global_grid_Mes(ID_EMPLEADOR_MAESTRO, $FECHA['first_day'], $FECHA['last_day'], $WHERE, $start, $limit, $sidx, $sord);

// ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return null;
    }
    
    foreach ($lista as $rec) {
        $param = $rec["id_trabajador"];
        $_01 = $rec["cod_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];
        $_06 = $rec["fecha_inicio"];
        $_07 = $rec["fecha_fin"];

//$js = "javascript:return_modal_anb_prestamo('" . $param . "','" . $_02 . "','" . $_03 . " " . $_04 . " " . $_05 . "')";
        
        $js = "javascript:agregarTrabajador_rpc('" . $param . "')";
        
        switch ($tipo) {
            case 2:
                $js = "javascript:agregarTrabajador_rpc2_phe('" . $param . "')";
                break;
            default:
                break;
        }        
                
        
        
        
        
        $opciones = '<div class="red">
          <span  title="Editar" >
          <a href="' . $js . '">seleccionar</a>
          </span>
          </div>';

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
            $opciones
//utf8_encode($opciones),
//$_06
        );
        $i++;
    }
    return $response;
}

function registrar_15($ID_PDECLARACION, $PERIODO, $id_etapa_pago, $id_etapa_pago_antes, $FECHA_INICIO, $FECHA_FIN, $ids) {
    //echoo($_REQUEST);
    echo "\nPeriodo Declaracion== ".$PERIODO;
    
    $rpta = false;
    // DAO
    $dao_plame = new PlameDao();
    $dao_pago = new PagoDao();

    $data_pd['periodo'] = $PERIODO;

//|-------------------------------------------------------------------------
//| Aki para mejorar. la aplicacion debe de preguntar por un Trabajador en 
//| concreto:
//|
//| XQ esta funcion devuelve una lista de trabajadores. Si la persona tubiera
//| por registros de trabajador. el sistema crearia :
//| reportes de la persona.. duplicadooooo.
//|-------------------------------------------------------------------------
    $data_traa = $dao_plame->listarTrabajadoresPorPeriodo_global(ID_EMPLEADOR_MAESTRO, $FECHA_INICIO, $FECHA_FIN);


// Solo enviara datos LA Segunda Quincena!!!!
    if ($id_etapa_pago_antes) {
        $_data_id_trabajador = $dao_pago->listar_HIJO($id_etapa_pago_antes);

        $data_filtro = array();
        for ($i = 0; $i < count($_data_id_trabajador); $i++) {
            for ($j = 0; $j < count($data_traa); $j++) {
                if ($_data_id_trabajador[$i]['id_trabajador'] == $data_traa[$j]['id_trabajador']) {
                    $data_filtro[] = $data_traa[$j];
                    break;
                }
            }
        }

        $data_tra = array_values($data_filtro);
        ECHO "DATOS FILTRADO POR SEGUNDA QUINCENA";
    } else {
        ECHO "DATOS FILTRADO POR PRIMERA QUINCENA";
        $data_tra = array_values($data_traa);
    }

//-------------------------------------------------------------------
// ID seleccionados en el Grid    
    if (isset($ids)) {
        echo "<pre>[idsS]  Que Usted Selecciono en el Grid\n";
        print_r($ids);
        echo "</pre>";
//------- filtro-------//
        $ids_tra = array();
        for ($i = 0; $i < count($ids); $i++) {
            for ($j = 0; $j < count($data_tra); $j++) {
                if ($ids[$i] == $data_tra[$j]['id_trabajador']) {

                    $ids_tra[] = $data_tra[$j];
                    break;
                }
            }
        }
        $data_tra = null;
        $data_tra = $ids_tra; //array_values($data_traa);  
    }


    echoo($data_tra);


//========== ELIMINAR LO QUE YA EXISTE en BD ===================//

    $data_id_tra_db = $dao_pago->listar_HIJO($id_etapa_pago);


    if (count($data_id_tra_db) > 0) { // =============== MEJORAR ->buesqueda binariaaaaaaaaaaaaaaaaaaa!
        $data_tra_ref = $data_tra;

        for ($i = 0; $i < count($data_id_tra_db); $i++):

            for ($j = 0; $j < count($data_tra_ref); $j++):

                if ($data_id_tra_db[$i]['id_trabajador'] == $data_tra_ref[$j]['id_trabajador']):
                    $data_tra_ref[$j]['id_trabajador'] = null;

                    break;
                endif;
            endfor;

        endfor;
        $data_tra = null;
        $data_tra = array_values($data_tra_ref);
    }


//-------------------------------------------------------------------
    //$dao_pd = new PlameDeclaracionDao();
    //$data_pd = $dao_pd->buscar_ID($ID_PDECLARACION);
// Lista trabajadores que le corresponde vacacion
    $dao_vac = new VacacionDao();

    $arreglo = array();
    $arreglo = getMesInicioYfin($data_pd['periodo']); //periodo        
    $mes_inicio = $arreglo['mes_inicio'];
    $mes_fin = $arreglo['mes_fin'];
    echo "\n##############################\n";
    echo "\nmes_inicio = $mes_inicio\n";
    echo "\nmes_fin = $mes_fin\n";
    echo "\n##############################\n";
    // FECHAS MANDO!.
    $id_1 = $dao_vac->listaIdsTraVacacionesFProgramada($mes_inicio, $mes_fin);
    $id_2 = $dao_vac->listaIdsTraVacacionesFProgramadaFin($mes_inicio, $mes_fin);

    $ids_tra_vacaciones = array_unico_ordenado($id_1, $id_2);
    
    echo "\n-----------------------------\n";
    echoo($id_1);
    echo "\n-----------------------------\n";
    echoo($id_2);
    echo "\n-----------------------------\n";
    echoo($ids_tra_vacaciones);
    echo "\n-----------------------------\n";
    

    if (count($data_tra) >= 1) {

        $dao_rpc = new RegistroPorConceptoDao();

        for ($i = 0; $i < count($data_tra); $i++) {
            if ($data_tra[$i]['id_trabajador'] != null) {
                
                $model = new Pago();
                
                //VARIABLES
                $dias_vacacion = 0;
                
                //SUELDO X DEFAUL
                if($data_tra[$i]['monto_remuneracion_fijo']): //  = 1
                    
                else:
                   $data_tra[$i]['monto_remuneracion'] = sueldoDefault($data_tra[$i]['monto_remuneracion']); 
                endif;
                
//-------------------------------FIN VACACIONES---------------------------------                  
                if (in_array($data_tra[$i]['id_trabajador'], $ids_tra_vacaciones)) {  //TIENE VACACION
                    echo "\n\n    TRABAJADOR ENTRO EN VACACION  \n\n";
                    
                    $tra_fvacacion_calc = null;
                    $tra_fvacacion_calc = $dao_vac->listarVacacionesEnRango($data_tra[$i]['id_trabajador']);

                    
                    $fecha_programada = $tra_fvacacion_calc['fecha_programada'];
                    $fecha_programada_fin = $tra_fvacacion_calc['fecha_programada_fin'];
                    //----------------------------------------------------------
                    $tra_finicio = ( getFechaPatron($fecha_programada, "m") == getFechaPatron($data_pd['periodo'], "m") ) ? $fecha_programada : null;
                    $tra_ffinal = ( getFechaPatron($fecha_programada_fin, "m") == getFechaPatron($data_pd['periodo'], "m") ) ? $fecha_programada_fin : null;


                    $finicio = ($tra_finicio == null) ? $FECHA_INICIO : $tra_finicio;
                    $ffin = ($tra_ffinal == null) ? $FECHA_FIN : $tra_ffinal;

                    //$a = getFechaPatron($fecha_inicio,"d");
                    //$b = getFechaPatron($fecha_fin,"d");
                    
                    $dias_vacacion = numDiasVacaciones($FECHA_INICIO, $FECHA_FIN, $finicio, $ffin);
                    //----------------------------------------------------------
                    echo "\n\nNUM DE VACACIONES RPTA ES = $dias_vacacion \n\n";
                    echo "\nVAC :: id_trabajador = ".$data_tra[$i]['id_trabajador']."\n";
                    
                    echo "\n id_pdeclaracion = $ID_PDECLARACION\n";
                    echo "\n\n\n...........";

                    echo "\nFECHA_INICIO = $FECHA_INICIO";
                    echo "\n";
                    echo "\nFECHA_FIN = $FECHA_FIN";
                    echo "\n";
                    
                    // BAJA CONCEPTO ADELANTO  
                    if ($dias_vacacion > 0) {
                        echo "\nDar baja concepto adelanto 50% OFF\n ";                                            
                        $dao_rpc->baja($data_tra[$i]['id_trabajador'], $ID_PDECLARACION,'0701');
                        
                    } else if (getFechaPatron($fecha_programada, "m") == getFechaPatron($data_pd['periodo'], "m") || getFechaPatron($fecha_programada_fin, "m") == getFechaPatron($data_pd['periodo'], "m")) {
                        echo "\nelse BAJA ELSE BAJA\n";
                        $dao_rpc->baja($data_tra[$i]['id_trabajador'], $ID_PDECLARACION,'0701');
                    }
                    
                }else{
                    
                     echo "\n\n    TRABAJADOR ID =".$data_tra[$i]['id_trabajador']." NO VACACION! \n\n";
                    
                }
//-------------------------------FIN VACACIONES---------------------------------  

                if ($data_tra[$i]['fecha_inicio'] > $FECHA_INICIO) {
                    
                } else if ($data_tra[$i]['fecha_inicio'] <= $FECHA_INICIO) {
                    $data_tra[$i]['fecha_inicio'] = $FECHA_INICIO;
                }
//---           
                $fecha_fin_15 = null;
                if (is_null($data_tra[$i]['fecha_fin'])) {
                    $data_tra[$i]['fecha_fin'] = $FECHA_FIN;
                } else if ($data_tra[$i]['fecha_fin'] >= $FECHA_FIN) { //INSUE
                    $data_tra[$i]['fecha_fin'] = $FECHA_FIN;
                } else if ($data_tra[$i]['fecha_fin'] < $FECHA_FIN) {
                    $fecha_fin_15 = $data_tra[$i]['fecha_fin'];
                }

             
                
                $a = getDayThatYear($data_tra[$i]['fecha_inicio']);
                $b = getDayThatYear($data_tra[$i]['fecha_fin']);

                $dia_laborado = ($b - $a) + 1;
                echo "\n".$data_tra[$i]['id_trabajador']."dia_laborado sin vacacion = $dia_laborado\n";

                //---//
                // 01 varia num dias pero no calculos!!
                $dia_laborado_bd = $dia_laborado;
                // Sacar de comentario para establecer como solo trabajados                
                //---//

                if ($dias_vacacion > 0) {
                    //$dia_laborado = $dias_vacacion;
                    $dia_vacacion = $dias_vacacion;
                    $dia_laborado = $dia_laborado - $dias_vacacion;
                    $dia_no_laborado = ($dia_laborado == 0) ? 0 : $dias_vacacion;  //OK
                } else {
                    $dia_vacacion = 0;
                    //$dia_no_laborado = 15;
                    $dia_no_laborado = 15 - $dia_laborado;
                }

                /******************************************************************************** */
//02             /**/
                $dia_laborado_util = $dia_laborado;
                /** Sacar de comentario para establecer como solo trabajados
                 */
                /******************************************************************************** */

                echo "\ndia_vacacion = $dia_vacacion\n";
                echo "\ndia_laborado = $dia_laborado\n";
                echo "\ndia_no_laborado = $dia_no_laborado\n";

                echo "\nDATOS A COSULTAR PARA CONCEPTO DE ADELANTO\n";

                $model->setId_trabajador($data_tra[$i]['id_trabajador']);
                $model->setId_etapa_pago($id_etapa_pago);
                $model->setDia_laborado($dia_laborado_util);
                $model->setDia_total($dia_laborado_bd);
                $model->setDia_nosubsidiado($dia_vacacion);
                //$model->setDia_total($dia_laborado_bd);
                $model->setSueldo_base($data_tra[$i]['monto_remuneracion']);

//--------------------------------------------------------------------------------------- 
                echo "\n\n\n\n";
                echo "ID_PDECLARACION = $ID_PDECLARACION";
                echo "data_tra[$i]['id_trabajador'] = ".$data_tra[$i]['id_trabajador'];
                echo "\n\n\n\n";
                $datax = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $data_tra[$i]['id_trabajador'], C701);
                $dataxx = (($datax['valor']==null)) ? 50 : $datax['valor'];
                
                echo "BUSCADO EN buscar_RPC_PorTrabajador:\n";
                echoo($dataxx);
                /*if($datax['valor']<=0){
                    $dataxx = 0;
                }else if(is_null($datax['valor'])){
                    $dataxx = 50;
                }else{
                   $dataxx = $datax['valor'];
                }*/
                
                $numero = number_format($dataxx, 2);
//----------------------------------------------------------------------------------------


                $SUELDO = $data_tra[$i]['monto_remuneracion'];

                echo "\nSUELDO for calc = " . $SUELDO . "\n";


                if (getFechaPatron($FECHA_INICIO, "d") == '01') {// 1 QUINCENA HAY DESCUENTO  
                    
                    $SUELDO_CAL = 0;
                    $SUELDO_VAC = 0;

                    //$dia_no_laborado = 15- $dia_laborado;

                    $percent = ($numero) ? $numero : 0;
                    
                    if($dia_laborado > 0)://CONTROLAR EXECPCION
                    
                    if ($dia_laborado >= 15) {//CON %
                        $SUELDO_CAL = $SUELDO * ($percent / 100);
                    } else if ($dia_laborado < 15) { //SIN %
                        $DESCTO = ($SUELDO / 30) * $dia_no_laborado;
                        $SUELDO_CAL = $SUELDO * (50 / 100); // 50%
                        $SUELDO_CAL = $SUELDO_CAL - $DESCTO;
                    }
                    endif;
//..........................................................
                    // Solo en primera quincena se redondea:
                    $round_sueldo = array();
                    $round_sueldo = getRendondeoEnSoles($SUELDO_CAL);

                    if ($round_sueldo['decimal'] > 0) {
                        $dao_plame->editMontoDevengadoTrabajador($data_tra[$i]['id_trabajador'], $round_sueldo['decimal']);
                        $SUELDO_CAL = $round_sueldo['numero'];
                    }
//..........................................................
                    //Sueldo Vacacion
                    if ($dia_vacacion >= 15) {
                        $SUELDO_VAC = $SUELDO * ($percent / 100);
                    } else if ($dia_vacacion < 15) {
                        $SUELDO_VAC = ($SUELDO / 30) * $dia_vacacion;
                    }


                    ECHO "\n\nSUELDO_calc = $SUELDO_CAL\n";
                    echo "\nSUELDO VACACION = " . $SUELDO_VAC;
                } else {// 2 QUINCENA HAY DESCUENTO
                    // mes mando = 
                    $master_a = getDayThatYear($mes_inicio);
                    $master_b = getDayThatYear($mes_fin);
                    $master_dia_lab = ($master_b - $master_a) + 1;
                    
                    $SUELDO_CAL = 0;
                    $SUELDO_VAC = 0;
                    
                    //echo "\n sueldo en 2da quincena SUELDO _1 = ".$SUELDO."\n";
                    //echo "\n sueldo en 2da quincena SUELDO _2 = ".$data_tra[$i]['monto_remuneracion']."\n";
                    
                    echo "\nmes_inicio = $mes_inicio";
                    echo "\nmes_fin = $mes_fin";
                    echo "\nmaster_dia_lab  = $master_dia_lab \n";

                    /**/
                    //$dia_laborado_bd = $dia_laborado;
                    /** Sacar de comentario para establecer como solo trabajados
                     */
                    switch ($master_dia_lab) {
                        case 28:
                            $dia_laborado = $dia_laborado + 2; //30
                            $dia_no_laborado = $dia_no_laborado - 2; //30-2=28
                            echo "add = 2";
                            break;
                        case 29:
                            $dia_laborado = $dia_laborado + 1; //30
                            $dia_no_laborado = $dia_no_laborado - 1; //30-1 = 29
                            echo "add = 1";
                            break;
                        case 30:
                            $dia_laborado = $dia_laborado; //30
                            break;
                        case 31:
                            $dia_laborado = $dia_laborado - 1; //30                        
                            echo "add = -1";
                            break;
                        default:
                            break;
                    }



                    echo "\n*dia_laborado* = $dia_laborado\n";
                    echo "\n*dia_no_laborado* = $dia_no_laborado\n";

                    //$dia_no_laborado = 15 - $dia_laborado;
                    $percent = ($numero) ? $numero : 0;
                    $percent = 100 - $percent;
                    echo "percent  despues# = " . $percent;
                    
                    
                    if ($dia_laborado >= 15) {
                        $SUELDO_CAL = $SUELDO * ($percent / 100);
                    } else if ($dia_laborado < 15 && $dia_laborado > 0) {
                        $DESCTO = ($SUELDO / 30) * $dia_no_laborado;
                        $SUELDO_CAL = $SUELDO * ($percent / 100); // 50%
                        $SUELDO_CAL = $SUELDO_CAL - $DESCTO;
                    } else { //numero negativo
                        $dia_laborado = 0;
                    }

//..........................................................
// Toma en cuenta si hay devengado para sumar a sueldo.
                    if ($data_tra[$i]['monto_devengado'] > 0) {
                        $SUELDO_CAL = $SUELDO_CAL + $data_tra[$i]['monto_devengado'];
                        //devengado = 0
                        $dao_plame->editMontoDevengadoTrabajador($data_tra[$i]['id_trabajador'], 0.00);
                    }
//..........................................................
                    //Sueldo Vacacion
                    if ($dia_vacacion >= 15) {
                        $SUELDO_VAC = $SUELDO * ($percent / 100);
                    } else if ($dia_vacacion < 15) {
                        $SUELDO_VAC = ($SUELDO / 30) * $dia_vacacion;
                    }
                }


                echo "\n ID_TRABAJADOR = ".$data_tra[$i]['id_trabajador']."\n";
                echo "\nSUELDO = " . $SUELDO;
                echo "\nSUELDO VACACION = " . $SUELDO_VAC;
                echo "\npercent  despues = " . $percent;
                echo "\nDESCTO = " . $DESCTO;
                echo "\nSUELDO_CAL  definitivo= " . $SUELDO_CAL;
                echo "\ndia_laborado = " . $dia_laborado;
                echo "\n";
                echo "\ndia_laborado_bd = " . $dia_laborado_bd;
                echo "\ndia_no_laborado = " . $dia_no_laborado;

//---------------------------------------------------------------------------------------
                $model->setSueldo($SUELDO_CAL);
                $model->setSueldo_vacacion($SUELDO_VAC);
                //$model->setSueldo_neto($SUELDO_CAL);
                $model->setOrdinario_hora((/*$dia_laborado_bd*/ $dia_laborado* 8));
                $model->setEstado(0);
                $model->setId_empresa_centro_costo($data_tra[$i]['id_empresa_centro_costo']);
                $model->setFecha_creacion(date("Y-m-d H:i:s"));
                
                $model->setFecha_fin_15($fecha_fin_15);
                //Dao
                $dao_pago->registrar($model);
                $rpta = true;
//}End trabajador Vacacion
            }
        }
    }



    return $rpta;
}

function getFechaTrabajadoVacaciones($fecha_programada, $fecha_programada_fin, $tipo_vacacion, $periodo) {
//base periodo o fecha declaracion
    $mes_periodo = getFechaPatron($periodo, 'm');

    $dia_programado = getFechaPatron($fecha_programada, 'd');
    $mes_programado = getFechaPatron($fecha_programada, 'm');
    $anio_programado = getFechaPatron($fecha_programada, 'Y');

    $dia_programado_fin = getFechaPatron($fecha_programada_fin, 'd');
    $mes_programado_fin = getFechaPatron($fecha_programada_fin, 'm');
    $anio_programado_fin = getFechaPatron($fecha_programada_fin, 'Y');
    $fecha_inicio = null;
    $fecha_fin = null;
    $vacacion = 'false';
    $tipo = 'false';

//..............................................................

    if (intval($dia_programado) <= 15 && $tipo_vacacion == 2 && intval($mes_periodo) == intval($mes_programado)) { //SEGUNDA 15 -> 16 17 18 19 20 21 22 23... 30 31
        $tipo = '15-1';
        if (intval($dia_programado) == 1):
            $vacacion = "15-vacacion";
        elseif (intval($dia_programado) >= 2):  //quiere decir que esta en el proximo mes.
            $fecha_inicio = getFechaPatron($fecha_programada, "Y-m") . "-01";

            $fecha_fin_crudo = getFechaPatron($fecha_programada, "Y-m") . "-$dia_programado";
            $fecha_fin = crearFecha($fecha_fin_crudo, -1, 0, 0);

        endif;
    } elseif (intval($dia_programado) > 15 && $tipo_vacacion == 2 && intval($mes_periodo) == intval($mes_programado)) {
        $tipo = '15-2';
        if (intval($dia_programado) == 16) {
            $dia_mes = getFechaPatron($fecha_programada, 't');

            if ($dia_mes == 31) {
                $fecha_inicio_crudo = getFechaPatron($fecha_programada, "Y-m") . "-$dia_programado";
                $fecha_inicio = crearFecha($fecha_inicio_crudo, 15, 0, 0);
                $fecha_fin = crearFecha($fecha_inicio_crudo, 15, 0, 0);
            } else { // FEBRERO =28  OTROSMES=30 OK
                $vacacion = "15-vacacion";
            }
        } elseif (intval($dia_programado) >= 17) {
            $fecha_inicio = getFechaPatron($fecha_programada, "Y-m") . "-16";

            $fecha_fin_crudo = getFechaPatron($fecha_programada, "Y-m") . "-$dia_programado";
            $fecha_fin = crearFecha($fecha_fin_crudo, -1, 0, 0);
        }
    }
//..............................................................
//..............................................................
// Solucion para vacacion 15 que se encuentra en el siguiente Mes!.  
//Controlar el mes de final de Vacacion:
    if (intval($dia_programado_fin) <= 15 && $tipo_vacacion == 2 && intval($mes_periodo) != intval($mes_programado)) {
        $tipo = '15-1';
        $vacacion = 'acabo';
        if (intval($dia_programado_fin) >= 1) {
            $fecha_inicio = getFechaPatron($fecha_programada_fin, "Y-m") . "-$dia_programado_fin";
            $fecha_final = null;
        }
    } else if (intval($dia_programado_fin) > 15 && $tipo_vacacion == 2 && intval($mes_periodo) != intval($mes_programado)) {
        $tipo = '15-2';
        $vacacion = 'acabo';
        if (intval($dia_programado_fin) >= 16) {
            $fecha_inicio = getFechaPatron($fecha_programada_fin, "Y-m") . "-$dia_programado_fin";
            $fecha_fin = null;
        }
    }


//==============================================================================
    //MESSUAL
    /*
      if (intval($dia_programado) <= 15 && $tipo_vacacion == 1 && intval($mes_periodo) == intval($mes_programado)) { //SEGUNDA 15 -> 16 17 18 19 20 21 22 23... 30 31
      $tipo = '15-1';


      if (intval($dia_programado) == 1) {

      $vacacion = "15-todo-MENSUAL";

      } elseif (intval($dia_programado) >= 2) {
      $fecha_inicio = getFechaPatron($fecha_programada, "Y-m") . "-01";

      $fecha_fin_crudo = getFechaPatron($fecha_programada, "Y-m") . "-$dia_programado";
      $fecha_fin = crearFecha($fecha_fin_crudo, -1, 0, 0);
      }




      } else */
    if (intval($dia_programado) >= 1 && $tipo_vacacion == 1 && intval($mes_periodo) == intval($mes_programado)) {
        ECHO "ENNTROOOO EN MENSUAL->>>>>>>>>>>>>>>>>>>>>> ok\n";
        //CONDICION 1

        if ($mes_programado_fin == $mes_programado && $anio_programado_fin == $anio_programado) {

            if ($dia_programado_fin <= 15) {
                $fecha_inicio = getFechaPatron($fecha_programada_fin, "Y-m") . "-$dia_programado_fin";
                $fecha_final = null;
                $tipo = '15-1';
            } else {
                $fecha_inicio = getFechaPatron($fecha_programada_fin, "Y-m") . "-$dia_programado_fin";
                $fecha_final = null;
                $tipo = '15-2';
            }
        } else {//siguiente mes
            $tipo = '15-todo_mes';
            $vacacion = "30-vacacion";
        }


        //CONDICION 2
    }
//..............................................................
//..............................................................
// Solucion para vacacion 15 que se encuentra en el siguiente Mes!.  
//Controlar el mes de final de Vacacion:
    if (intval($dia_programado_fin) <= 15 && $tipo_vacacion == 1 && intval($mes_periodo) != intval($mes_programado)) {
        $tipo = '15-1';
        $vacacion = 'acabo-MENSUAL';
        if (intval($dia_programado_fin) >= 1) {
            $fecha_inicio = getFechaPatron($fecha_programada_fin, "Y-m") . "-$dia_programado_fin";
            $fecha_final = null;
        }
    } else if (intval($dia_programado_fin) > 15 && $tipo_vacacion == 1 && intval($mes_periodo) != intval($mes_programado)) {
        $tipo = '15-2';
        $vacacion = 'acabo-MENSUAL';
        if (intval($dia_programado_fin) >= 16) {
            $fecha_inicio = getFechaPatron($fecha_programada_fin, "Y-m") . "-$dia_programado_fin";
            $fecha_fin = null;
        }
    }







    $rpta = array();
    $rpta['fecha_inicio'] = $fecha_inicio;
    $rpta['fecha_fin'] = $fecha_fin;
    $rpta['estado'] = $vacacion;
    $rpta['tipo'] = $tipo;

    return $rpta;
}

//function Auxiliar
function retornan_Id_Persona_UnicoEtapaPago($data_tra) {
    $arrayid = array();
    for ($i = 0; $i < count($data_tra); $i++) {
        $arrayid[] = $data_tra[$i]['id_persona'];
    }
    $listaSimple = array_unique($arrayid);
    $arrayidFinal = array_values($listaSimple);
// Array Unico

    $unico = array();
    for ($i = 0; $i < count($arrayidFinal); $i++) {
        $unico[$i]['id_persona'] = $arrayidFinal[$i];
        $unico[$i]['contador'] = 0;
    }
//----------------------------------------------------------------------
    for ($i = 0; $i < count($unico); $i++) { //ID
        for ($j = 0; $j < count($data_tra); $j++) { //TRA
            if ($unico[$i]['id_persona'] == $data_tra[$j]['id_persona']) {
                $unico[$i]['contador']++;

                $unico[$i]['id_trabajador'] = $data_tra[$j]['id_trabajador'];
                $unico[$i]['cod_periodo_remuneracion'] = $data_tra[$j]['cod_periodo_remuneracion'];
                $unico[$i]['id_empresa_centro_costo'] = $data_tra[$j]['id_empresa_centro_costo'];
                $unico[$i]['monto_remuneracion'] = $data_tra[$j]['monto_remuneracion'];
//continue;
            }
        }
    }
//----------------------------------------------------------------------        
    return $unico;
}

function cargartabla() {
    $ID_DECLARACION = $_REQUEST['id_declaracion'];
    $estado_pdeclaracion = $_REQUEST['estado_pdeclaracion'];

    $dao = new EtapaPagoDao();

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
    $lista = $dao->listar($ID_DECLARACION);
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

    foreach ($lista as $rec) {

        $param = $rec["id_etapa_pago"];
        $_00 = $rec['id_pdeclaracion'];
        $_01 = $rec['descripcion'];
        $_02 = $rec['fecha_inicio'];
        $_03 = $rec['fecha_fin'];

        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_pago.php?id_etapa_pago=" . $param . "&id_pdeclaracion=" . $_00 . "','#CapaContenedorFormulario')";

        if ($estado_pdeclaracion == '0') {
            $js2_html = null;
        } else {
            $js2 = "javascript:eliminarEtapaPago('" . $param . "')";
            $js2_html = '<span  title="Editar"   >
		<a href="' . $js2 . '" class="divEliminar" ></a>
		</span>';
        }



        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar"   >
		<a href="' . $js . '" class="divEditar" ></a>
		</span> ' . $js2_html . '                
		</div>';




//hereee
//$_02 = '<a href="javascript:add_15('.$param.',\''.$_01.'\')" title = "Agregar UNICO Adelanto 15">1era 15</a>';
// $_04 = '<a href="javascript:cargar_pagina(\'sunat_planilla/view-empresa/view_etapaPago.php?id_declaracion='.$param.'&periodo='.$_01.'\',\'#CapaContenedorFormulario\')"title = "VER">Ver</a>';
//hereee
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
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
function buscar_ID_EtapaPago($id_etapa_pago) {

    $dao = new EtapaPagoDao();
    $data = $dao->buscar_ID($id_etapa_pago);

    $model = new EtapaPago();
    $model->setId_etapa_pago($data['id_etapa_pago']);
    $model->setId_pdeclaracion($data['id_declaracion']);
    $model->setCod_periodo_remuneracion($data['cod_periodo_remuneracion']);
    $model->setFecha_inicio($data['fecha_inicio']);
    $model->setFecha_fin($data['fecha_fin']);
    $model->setFecha_creacion($data['tipo']);
    $model->setGlosa($data['glosa']);

    return $model;
}

function numDiasVacaciones($f1, $f2, $tra_finicio, $tra_ffinal) {
    // =15= 2012-01-01 a 2012-15-01
    // =30= 2012-01-16 a 2012-31-01    
    $a = getFechaPatron($f1, "d");
    $b = getFechaPatron($f2, "d");

    //fecha vacaion
    $day_vi = intval(getFechaPatron($tra_finicio, "d"));
    $day_vf = intval(getFechaPatron($tra_ffinal, "d"));

    //echo "\na = $a<br>";
    //echo "\nb = $b<br>";
    //echo "\n$tra_finicio inicio dia = $day_vi<br>";
    //echo "\n$tra_ffinal final dia = $day_vf<br>";

    // Buscar si esta en rango
    $bandera = false;
    if ($day_vf > $day_vi) { //Feccha de fin tiene q ser Mayor si o si!
        for ($i = $a; $i <= $b; $i++) {
            if ($i == $day_vi || $i == $day_vf):
                $bandera = true;
                break;
            endif;
        }
    }
    //$day_vi =($a ==16) ? 16 : 1;
    //$tipo = ($a >= 16) ? 2 : 1;
    //echo "\ntipo =$tipo<br>";

    $contador = 0;
    if ($bandera) {
        for ($i = $a; $i <= $b; $i++):
            if ($i >= $day_vi):
                $contador++;
                if ($i == $day_vf):
                    //echo "\nbreak $day_vf";
                    break;
                endif;
            endif;
            //echo "\ncontador = $contador<br>\n";
        endfor;
    }
    return $contador;
}

/*
  //periodo
  $periodo = '2012-02-01';
  //rango
  $fecha_inicio = '2012-02-16';
  $fecha_fin = '2012-02-29';

  //fechas de vacacion
  $tra_finicio = '2012-01-05';
  $tra_ffinal = '2012-02-03';

  require_once '../util/funciones.php';


  $tra_finicio= ( getFechaPatron($tra_finicio,"m") == getFechaPatron($periodo, "m") ) ? $tra_finicio : null;
  $tra_ffinal =  ( getFechaPatron($tra_ffinal,"m") == getFechaPatron($periodo, "m") ) ? $tra_ffinal : null;

  $finicio = ($tra_finicio == null) ? $fecha_inicio : $tra_finicio;
  //$ffin = ($tra_ffinal == null) ? $fecha_fin : $tra_ffinal;



  if($tra_ffinal == null){echo " ANIBAL null<br>";
  $ffin = $fecha_fin;

  }else if($tra_ffinal>=$fecha_fin){echo "<br> ANIBAL > <br>";
  $ffin = $fecha_fin;

  }else if($tra_ffinal<$fecha_fin){ echo " <BR>ANIBAL OTRO<br>";
  $ffin = $tra_ffinal;
  }

  $a = getFechaPatron($fecha_inicio,"d");
  $b = getFechaPatron($fecha_fin,"d");



  numDiasVacaciones($fecha_inicio,$fecha_fin, $finicio,$ffin);

 */
?>
