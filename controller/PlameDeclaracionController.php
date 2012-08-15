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
// INGRESO
    require_once '../model/Dcem_Pingreso.php';
    require_once '../dao/Dcem_PingresoDao.php';
    //DESCUENTO
    require_once '../model/Dcem_Pdescuento.php';
    require_once '../dao/Dcem_PdescuentoDao.php';
    //TRIBUTO
    require_once '../model/Dcem_Ptributo_aporte.php';
    require_once '../dao/Dcem_PtributoAporteDao.php';
    //JORNADA LABORAL
    require_once '../dao/PjoranadaLaboralDao.php';

    require_once '../dao/PtrabajadorDao.php';

    require_once '../dao/PlameDao.php';

    //--------------------------------------------------------------------------
    // DATOS PERSONALES DEL TRABAJADOR (Actualidad)
    require_once '../model/Trabajador.php';
    require_once '../dao/TrabajadorDao.php';
    require_once '../controller/CategoriaTrabajadorController.php';

    //--------------- sub detalle_2
    require_once('../model/DetalleTipoTrabajador.php');
    require_once('../dao/DetalleTipoTrabajadorDao.php');
    require_once('../controller/DetalleTipoTrabajadorController.php');

    //--------------- sub detalle_4
    require_once('../model/DetalleRegimenSalud.php');
    require_once('../dao/DetalleRegimenSaludDao.php');
    require_once('../controller/DetalleRegimenSaludController.php');

    //--------------- sub detalle_5
    require_once('../model/DetalleRegimenPensionario.php');
    require_once('../dao/DetalleRegimenPensionarioDao.php');
    require_once('../controller/DetalleRegimenPensionarioController.php');


    //MODEL PperiodoLaboral
    require_once ('../model/PperiodoLaboral.php');
    require_once '../dao/PperiodoLaboralDao.php';

    //PAGO DAO
    require_once '../dao/PagoDao.php';
}

$response = NULL;

if ($op == "cargar_tabla") {
    $response = cargar_tabla_pdeclaracio(ID_EMPLEADOR_MAESTRO);
} else if ($op == "add") { //READY
    $post_fecha = "01/" . $_REQUEST['periodo'];
    $periodo = getFechaPatron($post_fecha, "Y-m-d");
    $response = nuevaDeclaracionPeriodo(ID_EMPLEADOR_MAESTRO, $periodo);
} else if ($op == "add-data-ptrabajadores") { //en realidad es UNIR DATOS
    
    ECHO "DDDDDDDDDDDDDDDDDDDDDDDDDD";
    
    $tipo = $_REQUEST['declaracionRectificadora']; // 1

    $ID_DECLARACION = $_REQUEST['id_declaracion'];
    $post_fecha = "01/" . $_REQUEST['periodo'];
    $periodo = getFechaPatron($post_fecha, "Y-m-d");
    // Se Registra el Periodo mes/anio 
    /* $BANDERA = */
    if ($tipo == '1') { //Elimina rastro y crea otro
        $daopt = new PtrabajadorDao();
        $data_ptrabajadores = $daopt->listarPor_ID_declaracion($ID_DECLARACION);
        ECHO "SSSSSS = ";
        var_dump($data_ptrabajadores);
        
        if (count($data_ptrabajadores) > 0) {
            $daopt->eliminarPtrabajadorPor_id_declaracion($ID_DECLARACION);
        }
        ///ready
        nuevaDeclaracion(ID_EMPLEADOR_MAESTRO, $periodo, $ID_DECLARACION);
    } else if ($tipo == 0) { //UPDATE
        
        
    }



    //registrar_Ptrabajadores_enPdeclaracion(ID_EMPLEADOR_MAESTRO, $ID_DECLARACION, $periodo);

    /*
      if ($BANDERA) {
      $response->rows[0]['estado'] = "true";
      } else {
      $response->rows[0]['estado'] = "false";
      }
      $response->rows[0]['data_mes'] = getMesInicioYfin($periodo);
     */
} else if ($op == "cargar_tabla_ptrabajador") {
    //CARGAR JQGRID ptrabajadores
    cargar_tabla_ptrabajador();
} else if ($op == "cargar_tabla_empresa") {
    $anio = $_REQUEST['anio'];
    $response = cargar_tabla_empresa(ID_EMPLEADOR_MAESTRO, $anio);
}


echo (!empty($response)) ? json_encode($response) : '';

function existeDeclaracion() {
    $dao = new PlameDeclaracionDao();
    $dao->existeDeclaracion();
}

//FUNCION ADCIONAL
//FUNCION ADCIONAL
function nuevaDeclaracion($id_empleador_maestro, $periodo, $ID_DECLARACION) {

    $estado = false;

    $FECHA = getMesInicioYfin($periodo);

    echo "FECHA['mes_inicio'] = " . $FECHA['mes_inicio'];
    echo "FECHA['mes_fin'] = " . $FECHA['mes_fin'];

    //PASO 01   existe periodo?    
    //$dao = new PlameDeclaracionDao();
    //$num_declaracion = $dao->existeDeclaracion($id_empleador_maestro, $periodo);
    //paso 02 Num Trabajadores > 1 ?
    $Daoo = new PlameDao();

    $data_tra = $Daoo->listarTrabajadoresPorPeriodo($id_empleador_maestro, $FECHA['mes_inicio'], $FECHA['mes_fin']);

    $num_trabajadores = count($data_tra);
    //$num_trabajadores = $dao->contarTrabajadoresEnPeriodo($id_empleador_maestro, $periodo);


    $rpta = false;
    if ($num_trabajadores >= 1) {
        $rpta = true;
    }


    if ($rpta == true) {
        ////EVALUA SI ES NECESARIO UN TRY CATH!!!!!!!!!!!!!   
        $datafor = array();
        for ($i = 0; $i < count($data_tra); $i++) {// PRIMERO 
            $z = 0;
            for ($j = 0; $j < count($data_tra); $j++) {// SEGUNDO
                if ($data_tra[$i]['id_persona'] == $data_tra[$j]['id_persona']) { //$i = x AHI ENCUNETRA TODO
                    $datafor[$i]['id_persona'] = $data_tra[$j]['id_persona'];
                    $datafor[$i][$z]['fecha_inicio'] = $data_tra[$j]['fecha_inicio'];
                    $datafor[$i][$z]['fecha_fin'] = $data_tra[$j]['fecha_fin'];

                    $z++;
                    if ((count($data_tra) - 1) == $j) {
                        break;
                    }
                    // }//EIF2
                }//EIF1
            }
        }
        //----------------------------------------------------------------------
        //-- Variables globales
        $p_fi = $FECHA['mes_inicio'];
        $p_fi_time = strtotime($p_fi);

        $p_ff = $FECHA['mes_fin'];
        $p_ff_time = strtotime($p_ff);

        $tra_unico = retornan_Id_Persona_Unico($data_tra);
        $min_periodo = array();
        //echo "trabajador UNICO".count($tra_unico);

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

                        if ($fi_time == $p_fi_time) {
                            $f1 = $fi_time;
                        } else if ($fi_time > $p_fi_time) {
                            $f1 = $fi_time;
                        } else if ($fi_time < $p_fi_time) {
                            $f1 = $p_fi_time;
                        } else {
                            $f1 = "error critico";
                        }

                        if ($ff_time) {//SI ESTA ESTABLECIDO   rpta bd                        
                            $f2 = $ff_time;
                        } else { //sino 
                            $f2 = $p_ff_time;
                        }


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
        print_r($min_periodo); //Min 1 Periodo :D
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

                    $model_ppl = new PperiodoLaboral();
                    //$model_ppl->setId_ptrabajador($tra_unico[$i]['id_trabajador']);
                    $model_ppl->setFecha_inicio($min_periodo[$i][$j]['fecha_inicio']);
                    $model_ppl->setFecha_fin($min_periodo[$i][$j]['fecha_fin']);

                    $data_obj_ppl[] = $model_ppl;
                    $dias_laborados = $dias_laborados + $min_periodo[$i][$j]['dia_laborado'];
                }
            }

            /*            //-----------------------
              echo "***************************************************";
              echo "<pre> ULTIMOOOOO";
              print_r($data_obj_ppl);
              echo "</pre>";
              echo "dia laborado = " . $dias_laborados;
              echo "***************************************************";
             */

            ECHO "APUNTO DE REG $i";
            registrarPTrabajadores($tra_unico[$i]['id_trabajador'],$tra_unico[$i]['id_persona'], $ID_DECLARACION, $id_empleador_maestro, $data_obj_ppl, $dias_laborados);
        }


        //--------------------------------------------------------------------------
        //------FIN
        $estado = true;
    } else {
        $estado = false;
    }

    //return $estado;
}

//------
// ESTA FUNCION CREADA PARA aminorar el codigo en la funcion nuevaDeclaracion
function registrarPTrabajadores($id_trabajador,$id_persona, $id_pdeclaracion, $id_empleador_maestro, $data_obj_ppl, $dia_laborado) {

    /**
     * Datos Personales actuales del Trabajador 
     */
    //UNO
//    echo "<pre>";
//    print_r($data_obj_ppl);
//    echo "</pre>";



    $objTRA = new Trabajador();
    //-- funcion Controlador Trabajador
    $objTRA = buscar_IDTrabajador($id_trabajador);


    //DOS
    //--- sub 2 Tipo Trabajador
    $objTRADetalle_2 = new DetalleTipoTrabajador();
    $objTRADetalle_2 = buscarDetalleTipoTrabajador($id_trabajador);

    //--- sub 4 Regimen Salud
    $objTRADetalle_4 = new DetalleRegimenSalud();
    $objTRADetalle_4 = buscarDetalleRegimenSalud($id_trabajador);

    //--- sub 5 Regimen Pensionario
    $objTRADetalle_5 = new DetalleRegimenPensionario();
    $objTRADetalle_5 = buscarDetalleRegimenPensionario($id_trabajador);


    // Registrar PTrabajadores
    $obj_1 = new PTrabajador();
    $obj_1->setId_pdeclaracion($id_pdeclaracion);
    $obj_1->setId_trabajador($id_trabajador);
    $obj_1->setAporta_essalud_sctr(0);
    $obj_1->setAporta_essalud_vida(0);
    $obj_1->setAporta_asegura_tu_pension(0);
    $obj_1->setDomiciliado(1);
    $obj_1->setIngreso_5ta_categoria(0);

    $obj_1->setCod_tipo_trabajador($objTRADetalle_2->getCod_tipo_trabajador());
    $obj_1->setCod_situacion($objTRA->getCod_situacion());
    $obj_1->setCod_regimen_aseguramiento_salud($objTRADetalle_4->getCod_regimen_aseguramiento_salud());
    $obj_1->setCod_regimen_pensionario($objTRADetalle_5->getCod_regimen_pensionario());


    //DAO
    $dao_pi = new PtrabajadorDao();
    $ID_PTRABAJADOR = $dao_pi->registrar($obj_1);


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
    
    //---------::::::::::::::::::::::::::::::ACtualizar PINGRESOOO::::::::::::::::::::::::::::---------
/*   
    // 01 = sueldo basico  concepto[0121]
    // 02 = asig.familiar  concepto[0201]
    
    $id_dcem_pingreso_0121 =  $dao_i->get_id_dcem_pingreso($id_empleador_maestro, '0121', $ID_PTRABAJADOR);
    //$obj_i = new Dcem_Pingreso();
    $obj_i->setId_dcem_pingreso($id_dcem_pingreso_0121);
    $obj_i->setDevengado(100);
    $obj_i->setPagado(100);
    $dao_i->actualizar($obj_i);
    

    //00000001111111 -> listar parametro sueldo basico...
    
    //00000002222222
    $daoPago = new PagoDao();
    $DATA_TOP = $daoPago->listaGrup_Por_Persona($id_pdeclaracion);
    
*/
     //---------::::::::::::::::::::::::::::::ACtualizar PINGRESOOO::::::::::::::::::::::::::::---------   

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
        $obj_d->setId_detalle_concepto_empleador_maestro($data_dcem_d[$i]['id_detalle_concepto_empleador_maestro']);
        //DAO
        $dao_d->registrar($obj_d);
    }

    //--------------------------------------------------------------------------
    //PASO 03.1  -- TRIBUTOS Y APORTES listar conceptos
    $dao_dcem_ta = new PlameDetalleConceptoEmpleadorMaestroDao();
    $data_dcem_ta = $dao_dcem_ta->listar_dcem_ptributos_aportes($id_empleador_maestro); //CONCEPTO 0600, 0800
    //PASO 03.2 -- Registrar Tributos y Aportes
    $dao_dcem_ta = new Dcem_PtributoAporteDao();

    $obj = new Dcem_Ptributo_aporte();
    for ($i = 0; $i < count($data_dcem_ta); $i++) {
        $obj->setId_ptrabajador($ID_PTRABAJADOR);
        $obj->setId_detalle_concepto_empleador_maestro($data_dcem_ta[$i]['id_detalle_concepto_empleador_maestro']);
        //DAO
        $dao_dcem_ta->registrar($obj);
    }





    //--------------------------------------------------------------------------
    //PASO 04.1 -- JORNADAS LABORALES

    $obj_jl = new PjornadaLaboral();
    $obj_jl->setId_ptrabajador($ID_PTRABAJADOR);
    $obj_jl->setDia_laborado($dia_laborado);
    $obj_jl->setDia_total($dia_laborado);


    //DAO
    $dao_jl = new PjoranadaLaboralDao();
    $dao_jl->registrar($obj_jl);

    //$obj_pl = new PperiodoLaboral();
    $daopl = new PperiodoLaboralDao();

    for ($i = 0; $i < count($data_obj_ppl); $i++) {

        $daopl->registrar($data_obj_ppl[$i], $ID_PTRABAJADOR);
    }
    //--------------------------------------------------------------------------
}

function cargar_tabla_pdeclaracio($id_empleador_maestro) { //cargarTablaPDeclaraciones
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction

    if (!$sidx)
        $sidx = 1;

    //llena en al array
    $lista = array();

    $dao = new PlameDeclaracionDao();
    $lista = $dao->listarXDeclaracion($id_empleador_maestro);
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


    //-------------- LISTA -----------------
// CONTRUYENDO un JSON
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return null;
    }


    foreach ($lista as $rec) {
        $param = $rec["id_pdeclaracion"];
        $_01 = $rec["periodo"];
        $_02 = $rec["fecha_modificacion"];

        $estado = "A";

        $mes = getFechaPatron($_01, "m");
        $anio = getFechaPatron($_01, "Y");
        $periodo = "$mes/$anio";

        $js = "javascript:cargar_pagina('sunat_planilla/view-plame/edit_declaracion.php?id_declaracion=" . $param . "&periodo=" . $periodo . "','#CapaContenedorFormulario')";
        $js2 = "";
        $js3 = "";

        $modificar = '<div id="">
          <span  title="Editar" >
          <a href="' . $js . '"><img src="images/edit.png"/></a>
          </span>
          &nbsp;
          </div>'
        ;


        $eliminar = '<div id="">
          <span  title="Eliminar" >
          <a href="' . $js2 . '"><img src="images/cancelar.png"/></a>
          </span>          
          </div>'
        ;
        $archivo = '<div id="">
          <span  title="" >
          <a href="' . $js3 . '"><img src="images/disk.png"/></a>
          </span>          
          </div>'
        ;

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $periodo,
            $_02,
            $estado,
            utf8_encode($modificar),
            $null,//utf8_encode($eliminar),
            utf8_encode($archivo)
        );

        $i++;
    }

    return $responce;
}

//VIEW-EMPRESA
function cargar_tabla_empresa($id_empleador_maestro, $anio) {
    $dao = new PlameDeclaracionDao();

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
    $lista = $dao->listar($id_empleador_maestro, $anio);

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

        $param = $rec["id_pdeclaracion"];
        $_01 = $rec['periodo'];
        //$_02 = '<a href="javascript:add_15('.$param.',\''.$_01.'\')" title = "Agregar UNICO Adelanto 15">1era 15</a>';
        $_03 = '<a href="javascript:cargar_pagina(\'sunat_planilla/view-empresa/new_etapaPago.php?id_declaracion=' . $param . '&periodo=' . $_01 . '\',\'#CapaContenedorFormulario\')"title = "ADD 15">ADD 15</a>';
        //$_04 = "INSET SNT";
        //$_05 = "UPDATE SNT";

        $periodo = getFechaPatron($_01, "m/Y");

        //hereee
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $periodo,
            $_02,
            $_03,
            $_04,
            $_05
        );
        $i++;
    }

    return $response;
}

//dos
function retornan_Id_Persona_Unico($data_tra) {
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
            }
        }
    }
    //----------------------------------------------------------------------        
    return $unico;
}

// VIEW EMPRESA 
function buscar_ID_Pdeclaracion($id_pdeclaracion) {
    $dao = new PlameDeclaracionDao();
    $data = $dao->buscar_ID($id_pdeclaracion);
    //var_dump($data);
    $model = new Pdeclaracion();
    $model->setId_pdeclaracion($data['id_pdeclaracion']);
    $model->setId_empleador_maestro($data['id_empleador_maestro']);
    $model->setPeriodo($data['periodo']);
    $model->setFecha_creacion($data['fecha_creacion']);
    $model->setFecha_modificacion($data['fecha_modificacion']);
    $model->setEstado($data['estado']);
    return $model;
}

// New view Empresa READY
function nuevaDeclaracionPeriodo($id_empleador_maestro, $periodo) {

    $FECHA = getMesInicioYfin($periodo);
    //PASO 01   existe periodo?    
    $dao = new PlameDeclaracionDao();
    $num_declaracion = $dao->existeDeclaracion($id_empleador_maestro, $periodo);

    //paso 02 Num Trabajadores > 1 ?
    $Daoo = new PlameDao();
    $data_tra = $Daoo->listarTrabajadoresPorPeriodo($id_empleador_maestro, $FECHA['mes_inicio'], $FECHA['mes_fin']);

    $num_trabajadores = count($data_tra);
    //$num_trabajadores = $dao->contarTrabajadoresEnPeriodo($id_empleador_maestro, $periodo);

    $rpta = 'false';
    if ($num_declaracion == 0) {
        if ($num_trabajadores <= 0) {
            //$response->rows[0]['tipo'] = "num_trabajador";
            //$response->rows[0]['estado'] = "false";
        } else if ($num_trabajadores > 0) {
            $rpta = 'true';
        }
    }

    if ($rpta == 'true') {
        /* $response = */ $dao->registrar($id_empleador_maestro, $periodo);
    }

    // $response = strval($rpta);

    return strval($rpta); //SOLO 1 = TRUE
}




//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//*****************************************************************************
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------



    function updateMaster_Pingreso($id_empleador_maestro,$cod_detalle_concepto,$ID_PTRABAJADOR,$VALOR_X){
        $dao_i = new Dcem_PingresoDao();
        $id_dcem_pingreso_0121 =  $dao_i->get_id_dcem_pingreso($id_empleador_maestro, $cod_detalle_concepto, $ID_PTRABAJADOR);
        $obj_i = new Dcem_Pingreso();
        $obj_i->setId_dcem_pingreso($id_dcem_pingreso_0121);
        $obj_i->setDevengado($VALOR_X);
        $obj_i->setPagado($VALOR_X);
        
        $dao_i->actualizar($obj_i);
    }
?>
