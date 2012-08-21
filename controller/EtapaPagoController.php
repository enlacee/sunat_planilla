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
    
}

$response = NULL;

if ($op == "trabajador_por_etapa") {

    $response = listarTrabajadoresPorEtapa();
} else if ($op == "registrar_etapa") {
    $response = registrarTrabajadoresPorEtapa();
} else if ($op == "cargar_tabla") {

    $response = cargartabla();
}else if($op == "del"){
    $response = del_etapaPago();
}

echo (!empty($response)) ? json_encode($response) : '';

function del_etapaPago(){
    $dao = new EtapaPagoDao();
    $dao->eliminar($_REQUEST['id_etapa_pago']);
}
//-----------
function listarTrabajadoresPorEtapa() {
    //=========================================================================//
    $ID_DECLARACION = $_REQUEST['id_declaracion'];
    $COD_PERIODO_REMUNERACION = $_REQUEST['cod_periodo_remuneracion'];

    if ($COD_PERIODO_REMUNERACION == '2') { // 2 =quincena        
        $dao = new EtapaPagoDao();
        $data_etapapago = $dao->buscarEtapaPago($ID_DECLARACION, $COD_PERIODO_REMUNERACION);
        //echo "<pre>ETAPA PAGO";
        //var_dump($data_etapapago);
        //echo "</pre>";
        if (is_array($data_etapapago) && count($data_etapapago) == 0) { // Registrar 1era QUINCENA
            $response = listar_15(1, $ID_DECLARACION, $COD_PERIODO_REMUNERACION);
            //registrar_15(1,$ID_DECLARACION,$COD_PERIODO_REMUNERACION);
        } else if (is_array($data_etapapago) && count($data_etapapago) == 1) {//Registrar 2DA QUINCENA
            $response = listar_15(2, $ID_DECLARACION, $COD_PERIODO_REMUNERACION);
            //registrar_15(2,$ID_DECLARACION,$COD_PERIODO_REMUNERACION);        
        } else if (count($data_etapapago) >= 2) { //CASOS controlado de Error
            $response = false;
        } else {
            echo "UN CASO INCONTROLABLE QUINCENA!";
        }
    }
    //=========================================================================//
    return $response;
}

function registrarTrabajadoresPorEtapa() {

    //=========================================================================//
    $ID_DECLARACION = $_REQUEST['id_declaracion'];
    $COD_PERIODO_REMUNERACION = $_REQUEST['cod_periodo_remuneracion'];
    // echo "ID_DECLARACION = " . $ID_DECLARACION;
    // echo "  COD_PERIODO_REMUNERACION =" . $COD_PERIODO_REMUNERACION;   
    if ($COD_PERIODO_REMUNERACION == '2') { // 2 =quincena        
        $dao = new EtapaPagoDao();
        $data_id_etapa_pago = $dao->buscarEtapaPago($ID_DECLARACION, $COD_PERIODO_REMUNERACION);

        //========================================================================//
        $daoPlame = new PlameDeclaracionDao();
        $data_d = $daoPlame->buscar_ID($ID_DECLARACION);

        $FECHA_PERIODO = $data_d['periodo'];
        $FECHAX = getFechasDePago($FECHA_PERIODO);
        $FECHA = array();
        //========================================================================//
        // NO EXISTE = Es la 1era 15
        if (count($data_id_etapa_pago) == 0) {
            $FECHA['inicio'] = $FECHAX['first_day'];
            $FECHA['fin'] = $FECHAX['second_weeks'];
            //================================
            $model = new EtapaPago();
            $model->setId_pdeclaracion($ID_DECLARACION);
            $model->setCod_periodo_remuneracion($COD_PERIODO_REMUNERACION);
            $model->setFecha_inicio($FECHA['inicio']);
            $model->setFecha_fin($FECHA['fin']);
            $model->setGlosa("Primera Quincena");
            $model->setTipo("1");
            $model->setFecha_creacion(date("Y-m-d"));
            
            $id_etapa_pago = $dao->registrar($model);
            //--------------------------------
            registrar_15($id_etapa_pago,$FECHA['inicio'],$FECHA['fin']);
            
            
        } else if (count($data_id_etapa_pago) == 1) { //Segunda QUINCENA
            $FECHA['inicio'] = $FECHAX['second_weeks_mas1']; //SUMAR 1 DIA para = 16/01/2012 a 31/01/2012
            $FECHA['fin'] = $FECHAX['last_day'];
            //================================
            $model = new EtapaPago();
            $model->setId_pdeclaracion($ID_DECLARACION);
            $model->setCod_periodo_remuneracion($COD_PERIODO_REMUNERACION);
            $model->setFecha_inicio($FECHA['inicio']);
            $model->setFecha_fin($FECHA['fin']);
            $model->setGlosa("Segunda Quincena");
            $model->setTipo("2");
            $model->setFecha_creacion(date("Y-m-d"));
            
            $id_etapa_pago = $dao->registrar($model);
            
            //--------------------------------
            registrar_15($id_etapa_pago,$FECHA['inicio'],$FECHA['fin']);
            
        } else if (count($data_id_etapa_pago) >= 2) {
            $response = false;
        } else {
            echo "UN CASO INCONTROLABLE QUINCENA! En tabla Etapa de PAGO";
        }
        
        
      /*  //echo "<pre>";
        //var_dump($data_etapapago);
        //echo "</pre>";
        if (is_array($data_etapapago) && count($data_etapapago) == 0) { // Registrar 1era QUINCENA
            $response = registrar_15(1, $ID_DECLARACION, $COD_PERIODO_REMUNERACION);
        } else if (is_array($data_etapapago) && count($data_etapapago) == 1) {//Registrar 2DA QUINCENA
            $response = registrar_15(2, $ID_DECLARACION, $COD_PERIODO_REMUNERACION);
        } else if (count($data_etapapago) >= 2) { //CASOS controlado de Error
            $response = false;
        } else {
            echo "UN CASO INCONTROLABLE QUINCENA!";
        }*/
    }
    //=========================================================================//
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
    /*
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
     */
    if (!$sidx)
        $sidx = 1;

    //llena en al array
    $lista = array();
    $lista = $dao_plame->listarTrabajadoresPorPeriodo_global(ID_EMPLEADOR_MAESTRO, $FECHA['inicio'], $FECHA['fin'], $COD_PERIODO_REMUNERACION);

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

function registrar_15($id_etapa_pago,$FECHA_INICIO,$FECHA_FIN) {

    // DAO
    $dao_plame = new PlameDao();
    $data_tra = $dao_plame->listarTrabajadoresPorPeriodo_global(ID_EMPLEADOR_MAESTRO, $FECHA_INICIO,$FECHA_FIN);


    //----------------------******************-----------------------------------
    echo "id_etapa_pago = ".$id_etapa_pago;
    echo "<br>";
    $estado = false;
    echo "<pre>ANTES";
    print_r($data_tra);    
    echo "</pre>";

    if (count($data_tra) >= 1) {
        for ($i = 0; $i < count($data_tra); $i++) {

            //---            
            if($data_tra[$i]['fecha_inicio'] > $FECHA_INICIO){
                //default
            }else if($data_tra[$i]['fecha_inicio'] <= $FECHA_INICIO){
                $data_tra[$i]['fecha_inicio'] = $FECHA_INICIO;
            }
            //---            
            //---            
            if (is_null($data_tra[$i]['fecha_fin'])) {
                $data_tra[$i]['fecha_fin'] = $FECHA_FIN;
            }else if($data_tra[$i]['fecha_fin'] >= $FECHA_FIN){ //INSUE
                $data_tra[$i]['fecha_fin'] = $FECHA_FIN;
            }else if($data_tra[$i]['fecha_fin'] < $FECHA_FIN){
                //default
            }
            //---
            $a = getDayThatYear($data_tra[$i]['fecha_inicio']);
            $b = getDayThatYear($data_tra[$i]['fecha_fin']);

            $data_tra[$i]['dia_laborado'] = ($b - $a) + 1;
            $dia_laborado =$data_tra[$i]['dia_laborado'];


   echo "<pre>AFTER";
    print_r($data_tra);    
    echo "</pre>";
// 01 Registrar Epagos_trabajadores


//02 Registrar Pagos               
            $model = new Pago();
            $model->setId_trabajador($data_tra[$i]['id_trabajador']);
            $model->setId_etapa_pago($id_etapa_pago);            
            $model->setDia_laborado($dia_laborado);
            $model->setDia_total($dia_laborado);
            $model->setSueldo_base($data_tra[$i]['monto_remuneracion']);
            
//---------------------------------------------------------------------------------------            
            //---------------------------------------------------//
            $daoPR = new PeriodoRemuneracionDao();
            $data = $daoPR->buscar_ID(2); // 2 = QUINCENAL
            echo "<pre>oooooooooo_pago";
            print_r($data);
            echo "</pre>";
            //---------------------------------------------------//
            $numero = number_format($data['tasa'], 3);
            $percent = ($numero) ? $numero : 0;
            //$dia = $data['dia'];       ---------------------------------- MONTO-REMUNERACION
            $SUELDO = $data_tra[$i]['monto_remuneracion'];

            if ($dia_laborado >= $data['dia']) { //15 == 15
                $SUELDO_CAL = $SUELDO * ($percent / 100);
            } else if ($dia_laborado < $data['dia']) {
                $porcentaje_x_dia = ($percent / $data['dia']); //SIEMPRE 15
                $percent = ($porcentaje_x_dia * $dia_laborado);
                $SUELDO_CAL = $SUELDO * ($percent / 100);
            }
            //--------------------------------------------
            $SUELDO_CAL = $SUELDO * ($percent / 100);
//---------------------------------------------------------------------------------------
            $model->setSueldo($SUELDO_CAL);
            $model->setSueldo_neto($SUELDO_CAL);
            $model->setOrdinario_hora($dia_laborado * 8);
            $model->setEstado(0);
            $model->setId_empresa_centro_costo($data_tra[$i]['id_empresa_centro_costo']);
            $dao = new PagoDao();
            $dao->registrar($model);
            
        }
    }
    
    
    
//----------------------******************-----------------------------------   

    echo "<pre>";
    print_r($data_tra); 
    echo "</pre>";
    
    
    return "okkKKK" . $estado;
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
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>
                &nbsp;
		<span  title="Editar" >
		<a href="' . $js2 . '"><img src="images/cancelar.png"/></a>
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
