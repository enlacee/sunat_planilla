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
    require_once '../dao/Dcem_PtributoAporteDao.php';
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
}

$response = NULL;

if ($op == "cargar_tabla") {
    $response = cargar_tabla(ID_EMPLEADOR_MAESTRO);
} else if ($op == "add") {

    $post_fecha = "01/" . $_REQUEST['periodo'];

    $periodo = getFechaPatron($post_fecha, "Y-m-d");

    // Se Registra el Periodo mes/anio 
    $BANDERA = nuevaDeclaracion(ID_EMPLEADOR_MAESTRO, $periodo);

    if ($BANDERA) {
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
    $FECHA = getMesInicioYfin($periodo);

    //PASO 01   existe periodo?
    $dao = new PlameDeclaracionDao();
    $num_declaracion = $dao->existeDeclaracion($id_empleador_maestro, $periodo);

    //paso 02 Num Trabajadores > 1 ?    
    $dao_plame = new PlameDao();
    $num_trabajadores = $dao_plame->cantidadTrabajadoresPorPeriodo($id_empleador_maestro, $FECHA['mes_fin']);
    //$num_trabajadores = $dao->contarTrabajadoresEnPeriodo($id_empleador_maestro, $periodo);


    $rpta = false;

    if ($num_declaracion == 0) {
        if ($num_trabajadores >= 1) {
            $rpta = true;
        }
    }



    if ($rpta == true) { //EVALUA SI ES NECESARIO UN TRY CATH!!!!!!!!!!!!!
        //PASO 01   existe periodo?
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


        for ($i = 0; $i < count($id_trabajador); $i++) {

            registrarPTrabajadores($FECHA['mes_fin'], $id_pdeclaracion, $id_trabajador[$i]['id_trabajador'], $id_empleador_maestro);
        }


        $estado = true;
    } else {
        $estado = false;
    }

    return $estado;
}

//------
// ESTA FUNCION CREADA PARA aminorar el codigo en la funcion nuevaDeclaracion
function registrarPTrabajadores($FECHA_FIN, $id_pdeclaracion, $id_trabajador, $id_empleador_maestro) {

    /**
     * Datos Personales actuales del Trabajador 
     */
    //UNO
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
    //variables
    $Month = getFechaPatron($FECHA_FIN, "m");
    $Year = getFechaPatron($FECHA_FIN, "Y");

    $dia_laborado = getMonthDays($Month, $Year);

    $obj_jl = new PjornadaLaboral();
    $obj_jl->setId_ptrabajador($ID_PTRABAJADOR);
    $obj_jl->setDia_laborado($dia_laborado);

    //DAO
    $dao_jl = new PjoranadaLaboralDao();
    $dao_jl->registrar($obj_jl);
}

function cargar_tabla($id_empleador_maestro) { //cargarTablaPDeclaraciones
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction

    if (!$sidx)
        $sidx = 1;

    //llena en al array
    $lista = array();

    $dao = new PlameDeclaracionDao();
    $lista = $dao->listar($id_empleador_maestro);
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

        $js = "javascript:cargar_pagina('sunat_planilla/view-plame/edit_declaracion.php?periodo=" . $periodo . "','#CapaContenedorFormulario')";
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
            utf8_encode($eliminar),
            utf8_encode($archivo)
        );

        $i++;
    }

    return $responce;
}

?>
