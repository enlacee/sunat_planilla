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

    //DATO BASICO CALCULO
    require_once '../dao/ConfPeriodoRemuneracionDao.php';
}

$response = NULL;

if ($op == "trabajador_por_etapa") {

    $response = listarTrabajadoresPorEtapa();
} else if ($op == "registrar_etapa") {
    $response = registrarTrabajadoresPorEtapa();
} else if ($op == "cargar_tabla") {

    $response = cargartabla();
}

echo (!empty($response)) ? json_encode($response) : '';

//-----------
function listarTrabajadoresPorEtapa() {
    //=========================================================================//
    $ID_DECLARACION = $_REQUEST['id_declaracion'];
    $COD_PERIODO_REMUNERACION = $_REQUEST['cod_periodo_remuneracion'];
     //echo "ID_DECLARACION = " . $ID_DECLARACION;
     //echo "  COD_PERIODO_REMUNERACION =" . $COD_PERIODO_REMUNERACION;   
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
        $data_etapapago = $dao->buscarEtapaPago($ID_DECLARACION, $COD_PERIODO_REMUNERACION);
        //echo "<pre>";
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
        }
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

function registrar_15($tipo, $ID_DECLARACION, $COD_PERIODO_REMUNERACION) {
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
    // DAO
    $dao_plame = new PlameDao();
    $data_tra = $dao_plame->listarTrabajadoresPorPeriodo_global(ID_EMPLEADOR_MAESTRO, $FECHA['inicio'], $FECHA['fin'], $COD_PERIODO_REMUNERACION);


    //----------------------******************-----------------------------------
    $estado = false;
    echo "<pre>";
    print_r($data_tra);
    echo "counteo =" . count($data_tra);
    echo "<pre>";

    if (count($data_tra) >= 1) {
        ////EVALUA SI ES NECESARIO UN TRY CATH!!!!!!!!!!!!!   
        echo "sss count =" . count($data_tra);
        $datafor = array();
        for ($i = 0; $i < count($data_tra); $i++) {// PRIMERO 
            ECHO "I =" . $i;
            $z = 0;
            for ($j = 0; $j < count($data_tra); $j++) {// SEGUNDO
                ECHO "J =" . $j;

                if ($data_tra[$i]['id_persona'] == $data_tra[$j]['id_persona']) { //$i = x AHI ENCUNETRA TODO
                    echo "<<<<" . $data_tra[$j]['id_persona'] . ">>>>";
                    $datafor[$i]['id_persona'] = $data_tra[$j]['id_persona'];
                    $datafor[$i][$z]['fecha_inicio'] = $data_tra[$j]['fecha_inicio'];
                    $datafor[$i][$z]['fecha_fin'] = $data_tra[$j]['fecha_fin'];

                    $z++;
                    if ((count($data_tra) - 1) == $j) {
                        echo "BREAK";
                        break;
                    }
                    // }//EIF2
                }//EIF1
            }
        }
        //----------------------------------------------------------------------
        //-- Variables globales
        echo "FECHA INICI" . $FECHA['inicio'];
        echo "FECHA FIN" . $FECHA['fin'];

        $p_fi = $FECHA['inicio'];
        $p_fi_time = strtotime($p_fi);

        $p_ff = $FECHA['fin'];
        $p_ff_time = strtotime($p_ff);

        $tra_unico = retornan_Id_Persona_UnicoEtapaPago($data_tra);
        $min_periodo = array();
        echo "trabajador UNICO".count($tra_unico);
        echo "<pre>";
        print_r($tra_unico);
        echo "<pre> unico FIN";
        //--- INICIO REGISTRAR ETAPA DE PAGO -------//
        //DAO
        $dao_ep = new EtapaPagoDao();
        //01 Pregunta si existe
        $ID_ETAPA_PAGO = $dao_ep->buscarEtapaPago2($ID_DECLARACION, $COD_PERIODO_REMUNERACION, $tipo);

        if (empty($ID_ETAPA_PAGO) || is_null($ID_ETAPA_PAGO)) {
            $obj = new EtapaPago();
            $obj->setId_pdeclaracion($ID_DECLARACION);
            $obj->setCod_periodo_remuneracion($COD_PERIODO_REMUNERACION);
            $obj->setFecha_inicio($FECHA['inicio']);
            $obj->setFecha_fin($FECHA['fin']);
            $obj->setFecha_creacion(date("Y-m-d"));
            $obj->setTipo($tipo);
            $obj->setGlosa("QUINCENA NRO " . $tipo);

            $ID_ETAPA_PAGO = $dao_ep->registrar($obj);
        }
        //--- FINAL REGISTRAR ETAPA DE PAGO -------//



        for ($i = 0; $i < count($tra_unico); $i++) {

            for ($j = 0; $j < count($datafor); $j++) { //FOR 1x
                //echo "ENTRO J == ".$j.";
                if ($tra_unico[$i]['id_persona'] == $datafor[$j]['id_persona']) {//ok unico
                    //echo "encontro id_persona";                  
                    $conteo_datafor = count($datafor[$j]) - 1;
                    //echo "ENTRO J == [".$j."]";

                    for ($x = 0; $x < ($conteo_datafor); $x++) {

                        //:: VARIABLES ::                       
                        $fi = $datafor[$j][$x]['fecha_inicio'];
                        $fi_time = strtotime($fi);

                        $ff = $datafor[$j][$x]['fecha_fin'];
                        $ff_time = strtotime($ff); //Return FALSE error
                        //VAR GLOB
                        $f1 = null;
                        $f2 = null;
                        echo "datafor echa_inicio 111   =  " . $datafor[$j][$x]['fecha_inicio'];
                        echo "FECHA INICIO 2222 =  " . $p_fi;
                        if ($fi_time == $p_fi_time) {
                            echo "FECHA MADRE == " . $p_fi;
                            $f1 = $fi_time;
                        } else if ($fi_time > $p_fi_time) {
                            $f1 = $fi_time;
                        } else if ($fi_time < $p_fi_time) {
                            $f1 = $p_fi_time;
                        } else {
                            $f1 = "error critico";
                        }

                        if ($ff_time) {

                            if ($ff_time == $p_ff_time) {//SI ESTA ESTABLECIDO   rpta bd                        
                                $f2 = $ff_time;
                            } else if ($ff_time > $p_ff_time) { //sino 
                                $f2 = $p_ff_time;
                            } else if ($ff_time < $p_ff_time) {
                                $f2 = $ff_time;
                            } else {
                                $f2 = "error critico";
                            }
                        } else {
                            $f2 = $p_ff_time;
                        }


                        ECHO"/////////////////////////";
                        echo "MAYOR FECHA FIN #" . date("Y-m-d", $f2);

                        echo "MAYOR FECHA inicio #" . date("Y-m-d", $f1);
                        ECHO"/////////////////////////";

                        $dia_f2 = date("j", $f2);
                        $dia_f1 = date("j", $f1);

                        //echo "ANIBAL = ".$ff_time."==".$ff."#DIA F2 = ".date("j",$f2);
                        $RESTA_DIA = ($dia_f2 - $dia_f1) + 1;  //Añade 1 Dia MAS
                        //---
                        $min_periodo[$i][$x]['id_persona'] = $tra_unico[$i]['id_persona'];
                        $min_periodo[$i][$x]['dia_laborado'] = $RESTA_DIA;

                        $min_periodo[$i][$x]['fecha_inicio'] = $datafor[$j][$x]['fecha_inicio']; //date("Y-m-d",$f1);
                        $min_periodo[$i][$x]['fecha_fin'] = $datafor[$j][$x]['fecha_fin']; //date("Y-m-d",$f2);
                        //---
                        //break;
                    }

                    //##########################################################

                    break; //SI ECONTRO BREAKKKK ok FOR 1X ELIMINADO                      
                }
            }//END FOR
        }


        //----------------------------------------------------------------------

        echo "SALIO";
        echo "<pre>";
        echo "<hr>min_periodo";
        print_r($min_periodo);
        echo "</pre>";
        //INICIO NEW CON ID_UNICOS  Y periodos y dias laborados dentro del MES.
        //------INICIO    
        //PASO 01
        //$id_pdeclaracion = $dao->registrar($id_empleador_maestro, $periodo);

        for ($i = 0; $i < count($tra_unico); $i++) { // UNICO
            $dias_laborados = 0;
            $data_obj_ppl = array();

            for ($j = 0; $j < count($min_periodo[$i]); $j++) {

                if ($tra_unico[$i]['id_persona'] == $min_periodo[$i][$j]['id_persona']) {

                    echo "fecha_inicio " . $min_periodo[$i][$j]['fecha_inicio'];
                    echo " **************************** ";
                    echo "fecha fin = " . $min_periodo[$i][$j]['fecha_fin'];

                    /* $model_ppl = new JornadaLaboral();
                      //$model_ppl->setId_ptrabajador($tra_unico[$i]['id_trabajador']);
                      $model_ppl->setFecha_inicio($min_periodo[$i][$j]['fecha_inicio']);
                      $model_ppl->setFecha_fin($min_periodo[$i][$j]['fecha_fin']);

                      $data_obj_ppl[] = $model_ppl; */
                    $dias_laborados = $dias_laborados + $min_periodo[$i][$j]['dia_laborado'];
                }
            }


            //?????????
            echo "***************************************************";
            echo "<pre> ULTIMOOOOO";
            print_r($data_obj_ppl);
            echo "</pre>";
            echo "dia laborado = " . $dias_laborados;
            echo "***************************************************";



            //CALCULAR SUELDO
            //--------------------------------------------
            $daoConfpr = new ConfPeriodoRemuneracionDao(); //BUSCAR PERIODO DE PAGO 7 , 15
            $data = $daoConfpr->getDatosBasicosdeCalculo(ID_EMPLEADOR_MAESTRO, $COD_PERIODO_REMUNERACION);

            //---------------------------------------------------//
            $tipo_moneda = getTipoMonedaPago($data['valor']);
            //---------------------------------------------------//
            $numero = number_format($data['valor'], 3);

            if ($tipo_moneda == "%") {
                $percent = ($numero) ? $numero : 0;
                //$dia = $data['dia'];            
                $SUELDO = $tra_unico[$i]['monto_remuneracion'];

                if ($dias_laborados >= $data['dia']) { //15 == 15
                    // $SUELDO_CAL = $SUELDO * ($percent/100); 
                } else if ($dias_laborados < $data['dia']) {
                    $porcentaje_x_dia = ($percent / $data['dia']); //SIEMPRE 15
                    $percent = ($porcentaje_x_dia * $dias_laborados);
                    //$SUELDO_CAL = $SUELDO * ($percent/100);                
                }
                //--------------------------------------------
                $SUELDO_CAL = $SUELDO * ($percent / 100);
            } else {
                // NULL otro calculo
            }


            //OKkkkkkkkkkk
            $objPago = new Pago();
            $objPago->setId_etapa_pago($ID_ETAPA_PAGO);
            $objPago->setId_trabajador($tra_unico[$i]['id_trabajador']);
            $objPago->setId_empresa_centro_costo($tra_unico[$i]['id_empresa_centro_costo']);
            
            $objPago->setValor($SUELDO_CAL);
            $objPago->setValor_total($SUELDO_CAL);
            $objPago->setDescuento(0);
            //$objPago->setDescripcion();
            $objPago->setDia_total($dias_laborados);
            $objPago->setDia_laborado($dias_laborados);
            $objPago->setDia_nosubsidiado(0);
            $objPago->setOrdinario_hora($dias_laborados * 8);
            $objPago->setOrdinario_min(0);            
            //$objPago->setSobretiempo_hora();
            //$objPago->setSobretiempo_min();                    
            $objPago->setEstado(0); //NO PAGA AUN SOLO GENERO
            //ACT
            //DAO
            $daopago = new PagoDao();
            $daopago->registrar($objPago);
        }


        //--------------------------------------------------------------------------
        //------FIN
        $estado = true;
    } else {
        $estado = false;
    }
//----------------------******************-----------------------------------   

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

        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_pago.php?id_etapa_pago=" . $param. "&id_pdeclaracion=".$_00."','#CapaContenedorFormulario')";

        // $js2 = "javascript:eliminarPersona('" . $param . "')";		
        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>
		</div>';
        //hereee
        //$_02 = '<a href="javascript:add_15('.$param.',\''.$_01.'\')" title = "Agregar UNICO Adelanto 15">1era 15</a>';
        // $_04 = '<a href="javascript:cargar_pagina(\'sunat_planilla/view-empresa/view_etapaPago.php?id_declaracion='.$param.'&periodo='.$_01.'\',\'#CapaContenedorFormulario\')"title = "VER">Ver</a>';
        $_05 = "ST-ADD";
        $_06 = "ST-Edit";

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
function buscar_ID_EtapaPago($id_etapa_pago){
    
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
