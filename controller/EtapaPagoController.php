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
    

    
}

$response = NULL;

if ($op == "trabajador_por_etapa") {
    $response = listarTrabajadoresPorEtapa();
    
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

        /* } else {
          echo "UN CASO INCONTROLABLE QUINCENA!";
          }
         */
    }
    //=========================================================================//
    return $response;
}

function registrarTrabajadoresPorEtapa() {

    //=========================================================================//
    $ID_DECLARACION = $_REQUEST['id_declaracion'];
    $COD_PERIODO_REMUNERACION = $_REQUEST['cod_periodo_remuneracion'];
    $ids_trabajador = $_REQUEST['ids'];


    // echo "ID_DECLARACION = " . $ID_DECLARACION;
    // echo "  COD_PERIODO_REMUNERACION =" . $COD_PERIODO_REMUNERACION;   
    if ($COD_PERIODO_REMUNERACION == '2') { // 2 = SIEMPRE PRIMERA quincena
        //========================================================================//
        $daoPlame = new PlameDeclaracionDao();
        $data_d = $daoPlame->buscar_ID($ID_DECLARACION);

        $FECHA_PERIODO = $data_d['periodo'];
        $FECHAX = getFechasDePago($FECHA_PERIODO);
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
            registrar_15($id_etapa_pago, $FECHA['inicio'], $FECHA['fin'], $ids_trabajador);
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
    // $WHERE = "";

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

    //llena en al array
    $lista = array();
    $lista = $dao_plame->listarTrabajadoresPorPeriodo_global(ID_EMPLEADOR_MAESTRO, $FECHA['inicio'], $FECHA['fin'], $WHERE);

    $count = count($lista);
    //$count = $dao_plame->cantidadTrabajadoresPorPeriodo(ID_EMPLEADOR_MAESTRO, $FECHA['mes_inicio'],$FECHA['mes_fin']);
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

function registrar_15($id_etapa_pago, $FECHA_INICIO, $FECHA_FIN, $ids = null) {

    // DAO
    $dao_plame = new PlameDao();
    
    //|-------------------------------------------------------------------------
    //| Aki para mejorar. la aplicacion debe de preguntar por un Trabajador en 
    //| concreto:
    //|
    //| XQ esta funcion devuelve una lista de trabajadores. Si la persona tubiera
    //| por registros de trabajador. el sistema crearia :
    //| reportes de la persona.. duplicadooooo.
    //|-------------------------------------------------------------------------
    $data_traa = $dao_plame->listarTrabajadoresPorPeriodo_global(ID_EMPLEADOR_MAESTRO, $FECHA_INICIO, $FECHA_FIN);


    //TRABAJADORES YA REGISTRADOS
    $dao_pago = new PagoDao();
    $_data_id_trabajador = $dao_pago->listar($id_etapa_pago, "id_trabajador");

    if (count($data_traa) == count($_data_id_trabajador)) {
        echo "DATOS YA SON IGUALES NO PUEDE seguir registrando MAS. [TRUNCADO-QUINCENAL]! ";
        return false;
    }



    //echo "<pre> _data_id_trabajador";
    //print_r($_data_id_trabajador);
    //echo "</pre>";

    /* --------------filtro de  id_trabajadores ------------- */
    for ($i = 0; $i < count($_data_id_trabajador); $i++) {
        for ($j = 0; $j < count($data_traa); $j++) {
            if ($_data_id_trabajador[$i]['id_trabajador'] == $data_traa[$j]['id_trabajador']) {
                unset($data_traa[$j]);
                break;
            }
        }
    }
    $data_tra = array_values($data_traa);
    /* --------------filtro de  id_trabajadores ------------- */

//-------------------------------------------------------------------
    //listar Trabajadores ya listados en $id_etapa_pago    
    echo "<pre>idsS";
    print_r($ids);
    echo "</pre>";
    if (isset($ids)) {
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

    echo "<pre>TRABAJADOR a INSERT";
    print_r($data_tra);
    echo "<pre>";
//-------------------------------------------------------------------



    if (count($data_tra) >= 1) {
        for ($i = 0; $i < count($data_tra); $i++) {

            //---            
            if ($data_tra[$i]['fecha_inicio'] > $FECHA_INICIO) {
                //default
            } else if ($data_tra[$i]['fecha_inicio'] <= $FECHA_INICIO) {
                $data_tra[$i]['fecha_inicio'] = $FECHA_INICIO;
            }
            //---            
            //---            
            if (is_null($data_tra[$i]['fecha_fin'])) {
                $data_tra[$i]['fecha_fin'] = $FECHA_FIN;
            } else if ($data_tra[$i]['fecha_fin'] >= $FECHA_FIN) { //INSUE
                $data_tra[$i]['fecha_fin'] = $FECHA_FIN;
            } else if ($data_tra[$i]['fecha_fin'] < $FECHA_FIN) {
                //default
            }
            //---            



            $cubodin = 0;
            //solo si la fecha de fin es mayor a 15 "segunda quincena" Anomalias
            $_2da_quincena = date("j", strtotime($data_tra[$i]['fecha_fin']));
            if (intval($_2da_quincena) > 15) {

                $num_dia = date("t", strtotime($data_tra[$i]['fecha_fin']));
                if ($num_dia == 28) {
                    $cubodin = 2;
                } else if ($num_dia == 29) {
                    $cubodin = 1;
                }
            }


            $a = getDayThatYear($data_tra[$i]['fecha_inicio']);
            $b = getDayThatYear($data_tra[$i]['fecha_fin']);

            //$data_tra[$i]['dia_laborado'] = 

            $dia_laborado = ($b - $a) + 1 + $cubodin;
            $dia_laborado_data = ($b - $a) + 1; //$data_tra[$i]['dia_laborado'];


            if ($dia_laborado >= 15) { //solo para ver dias NO afecta calc
                $dia_laborado = 15;
            } else {
                //null
            }


            $data_tra[$i]['dia_no_laborado'] = 15 - $dia_laborado;
            $dia_no_laborado = $data_tra[$i]['dia_no_laborado'];


            echo "<pre> -_-DATOS A COSULTAR PARA CONCEPTO DE ADELANTO";
            echo "AFTER ANIBAL 01";
            print_r($data_tra);
            echo "</pre>";
// 01 Registrar Epagos_trabajadores
//02 Registrar Pagos               
            $model = new Pago();
            $model->setId_trabajador($data_tra[$i]['id_trabajador']);
            $model->setId_etapa_pago($id_etapa_pago);
            $model->setDia_laborado($dia_laborado_data);
            $model->setDia_total($dia_laborado_data);
            $model->setSueldo_base($data_tra[$i]['monto_remuneracion']);

//--------------------------------------------------------------------------------------- 
            //$id_ptrabajador = existeID_TrabajadorPoPtrabajador($data_tra[$i]['id_trabajador']);
            //$daopt = new PtrabajadorDao();
            //buscar_ID_Ptrabajador
            //$datax = $daopt->buscar_ID($id_ptrabajador);
            $dao_rpc = new RegistroPorConceptoDao();

           //??????????? 
            $datax = $dao_rpc->buscar_RPC_PorTrabajador($data_tra[$i]['id_trabajador'], C701,1);
            echo "<pre>dataxXx DE CONCEPTO ADELANTO";
            print_r($datax);
            echo "</pre>";
            $dataxx = (is_null($datax['valor'])) ? 50 : $datax['valor'];

            $numero = number_format($dataxx, 2);
//----------------------------------------------------------------------------------------
            //-- Datos basicos --                       
            $SUELDO = $data_tra[$i]['monto_remuneracion'];
            ECHO "\n\n\n\nSUELDO DB =". $SUELDO;

            // 1 Quincena
            if (getFechaPatron($FECHA_INICIO, "d") == '01' || getFechaPatron($FECHA_INICIO, "d") == '1') {
                $percent = ($numero) ? $numero : 0;
                echo "ENTROOO EN primeraA quincena";
                echo "percent  despues# = " . $percent;

                if ($dia_laborado >= 15) {//CON %
                    $SUELDO_CAL = $SUELDO * ($percent / 100);
                } else if ($dia_laborado < 15) { //SIN %
                    $DESCTO = ($SUELDO / 30) * $dia_no_laborado;
                    $SUELDO_CAL = $SUELDO * (50 / 100); // 50%
                    $SUELDO_CAL = $SUELDO_CAL - $DESCTO;
                }
                
                ECHO "\nMONTO A PAGAR ES = ".$SUELDO_CAL;
            } else {// 2 QUINCENA HAY DESCUENTO
                echo "ENTROOO EN segundaA quincena";
                $percent = ($numero) ? $numero : 0;
                $percent = 100 - $percent;
                echo "percent  despues# = " . $percent;

                if ($dia_laborado >= 15) {
                    $SUELDO_CAL = $SUELDO * ($percent / 100);
                } else if ($dia_laborado < 15) {
                    $DESCTO = ($SUELDO / 30) * $dia_no_laborado;
                    $SUELDO_CAL = $SUELDO * ($percent / 100);
                    ; // 50%
                    $SUELDO_CAL = $SUELDO_CAL - $DESCTO;
                }

                //CALC
                //$SUELDO_CAL = $SUELDO_CAL - $DESCTO;
                //$DESCTO = ($SUELDO / 30) * $dia_no_laborado;
            }





            //-------------------------------------------
            //--------------------------------------------
            //$SUELDO_CAL = $SUELDO * ($percent / 100);
            //--------------------------------------------
            echo "SUELDO = " . $SUELDO;
            echo "percent  despues = " . $percent;

            echo "DESCTO = " . $DESCTO;
            echo "SUELDO_CAL  definitivo= " . $SUELDO_CAL;


            //$SUELDO_CAL = $SUELDO_CAL - $DESCTO;
//---------------------------------------------------------------------------------------
            $model->setSueldo($SUELDO_CAL);
            $model->setSueldo_neto($SUELDO_CAL);
            $model->setOrdinario_hora($dia_laborado * 8);
            $model->setEstado(0);
            $model->setId_empresa_centro_costo($data_tra[$i]['id_empresa_centro_costo']);
            $model->setFecha_creacion(date("Y-m-d H:i:s"));

            $dao = new PagoDao();
            $dao->registrar($model);
        }
    }



//----------------------******************-----------------------------------   
//    echo "<pre>";
//    print_r($data_tra);
//    echo "</pre>";


    return "okkKKK";
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
            }
        }
    }
    //----------------------------------------------------------------------        
    return $unico;
}

function cargartabla() {
    $ID_DECLARACION = $_REQUEST['id_declaracion'];

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

        $js2 = "javascript:eliminarEtapaPago('" . $param . "')";
        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar"   >
		<a href="' . $js . '" class="divEditar" ></a>
		</span>              
                
		<span  title="Editar"   >
		<a href="' . $js2 . '" class="divEliminar" ></a>
		</span>

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

?>
