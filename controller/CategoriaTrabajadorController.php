<?php

session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];
if ($op) {
    //Empleador
    //require_once '../model/Empleador.php';
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/EmpleadorDao.php';

    // Categoria Trabajador
    require_once '../model/Trabajador.php';
    require_once '../dao/TrabajadorDao.php';

    // -- 01 -- Detalle Tipo Perido Laboral
    require_once '../model/DetallePeriodoLaboral.php';
    require_once '../dao/DetallePeriodoLaboralDao.php';
    //xxxrequire_once '../controller/DetallePeriodoLaboralController.php';
    // -- 01 -- Detalle Tipo Trabajdor
    require_once '../model/DetalleTipoTrabajador.php';
    require_once '../dao/DetalleTipoTrabajadorDao.php';

    // -- 01 -- Detalle Establecimiento
    require_once '../model/DetalleEstablecimiento.php';
    require_once '../dao/DetalleEstablecimientoDao.php';

    // -- 01 -- Detalle Regimenes Salud
    require_once '../model/DetalleRegimenSalud.php';
    require_once '../dao/DetalleRegimenSaludDao.php';

    // -- 01 -- Detalle Regimenes Pensionario
    require_once '../model/DetalleRegimenPensionario.php';
    require_once '../dao/DetalleRegimenPensionarioDao.php';

    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
}

$responce = NULL;

if ($op == "cargar_tabla_trabajador") {
    $ESTADO = $_REQUEST['estado'];
    //echo $ESTADO;
    $responce = cargar_tabla_trabajador($ESTADO); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "add") {
    //$responce = nuevoTrabajador();
} elseif ($op == "edit") {

    $responce = editarTrabajador();
} elseif ($op == "del") {
    $id = $_REQUEST["id"];
    $responce = eliminarTrabajador($id);
} else if ($op == "listar_establecimiento_local") {

    $id_empleador = $_REQUEST['id_empleador'];

    if ($_SESSION['sunat_empleador']['id_empleador'] == $id_empleador) {
        $responce = listarEstablecimientoLocalesPorEmpleadorSunat($id_empleador);
    } else {
        $responce = listarEstablecimientoLocalesPorEmpleadorVinculado($id_empleador);
    }
    //$responce = ListarEstablecimientoLocalPorEmpleador();
} else {
    //echo "variable OPER no esta CT definido";
}


echo (!empty($responce)) ? json_encode($responce) : '';
/* * **********categoria*************** */

function listarEstablecimientoLocalesPorEmpleadorSunat($id_empleador_sunat) {

    //->EmpleadorDAO
    $dao_empleador = new EmpleadorDao();
    $rec = $dao_empleador->buscarEstablecimientoEmpleadorPorId($id_empleador_sunat);

    $lista = array();

    $counteo = count($rec);
    for ($i = 0; $i < $counteo; $i++) {

        //Cargando array
        //$lista[$i]['id_empleador'] = $rec[$i]['id_empleador'];
        //$lista[$i]['id_establecimiento'] = $rec[$i]['id_establecimiento'];
        //$lista[$i]['cod_establecimiento'] = $rec[$i]['cod_establecimiento'];
        //$lista[$i]['id_tipo_establecimiento'] = $rec[$i]['id_tipo_establecimiento'];
        //$lista[$i]['tipo_establecimiento_descripcion'] = $rec[$i]['tipo_establecimiento_descripcion'];
        //$lista[$i]['cod_establecimiento'] = $red['cod_establecimiento'];
        //------------------------------------------------------------------------------------------
        //here
        $ubigeo_nombre_via = $rec[$i]["ubigeo_nombre_via"];
        $nombre_via = $rec[$i]['nombre_via'];
        $numero_via = $rec[$i]['numero_via'];

        $ubigeo_nombre_zona = $rec[$i]['ubigeo_nombre_zona'];

        $nombre_zona = $rec[$i]['nombre_zona'];
        $etapa = $rec[$i]['etapa'];
        $manzana = $rec[$i]['manzana'];
        $blok = $rec[$i]['blok'];
        $lote = $rec[$i]['lote'];

        $departamento = $rec[$i]['departamento'];
        $interior = $rec[$i]['interior'];

        $kilometro = $rec[$i]['kilometro'];
        $referencia = $rec[$i]['referencia'];

        $ubigeo_departamento = str_replace('DEPARTAMENTO', '', $rec[$i]['ubigeo_departamento']);
        $ubigeo_provincia = $rec[$i]['ubigeo_provincia'];
        $ubigeo_distrito = $rec[$i]['ubigeo_distrito'];

        //---------------Inicio Cadena String----------//
        $cadena = '';

        $cadena .= (isset($ubigeo_nombre_via)) ? '' . $ubigeo_nombre_via . ' ' : '';
        $cadena .= (isset($nombre_via)) ? '' . $nombre_via . ' ' : '';
        $cadena .= (isset($numero_via)) ? '' . $numero_via . ' ' : '';

        $cadena .= (isset($ubigeo_nombre_zona)) ? '' . $ubigeo_nombre_zona . ' ' : '';
        $cadena .= (isset($nombre_zona)) ? '' . $nombre_zona . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';

        $cadena .= (isset($manzana) && !empty($manzana)) ? 'MZA. ' . $manzana . ' ' : '';
        $cadena .= (isset($blok)) ? '' . $blok . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';
        $cadena .= (isset($lote) && !empty($lote)) ? 'LOTE. ' . $lote . ' ' : '';

        $cadena .= (isset($departamento)) ? '' . $departamento . ' ' : '';
        $cadena .= (isset($interior)) ? '' . $interior . ' ' : '';
        $cadena .= (isset($kilometro)) ? '' . $kilometro . ' ' : '';
        $cadena .= (isset($referencia)) ? '' . $referencia . ' ' : '';


        $cadena .= (isset($ubigeo_departamento)) ? '' . $ubigeo_departamento . '-' : '';
        $cadena .= (isset($ubigeo_provincia)) ? '' . $ubigeo_provincia . '-' : '';
        $cadena .= (isset($ubigeo_distrito)) ? '' . $ubigeo_distrito . ' ' : '';

        $cadena = strtoupper($cadena);

        //------------------------------------------------------------------------------------------
        $lista[$i]['id'] = $rec[$i]['id_establecimiento'] . "|" . $rec[$i]['id_tipo_establecimiento'] . "|" . $rec[$i]['cod_establecimiento'];
        $lista[$i]['descripcion'] = $cadena;
    }//EndFOR

    return $lista;
}

//---------------
function listarEstablecimientoLocalesPorEmpleadorVinculado($id_empleador) {

    $dao = new TrabajadorDao();
    $rec = $dao->listarEstablecimientosVinculadosPorEmpleador($id_empleador);

    //echo "<pre>";
    //print_r($rec);
    //echo "</pre>";

    $lista = array();
    $cadena = "cadena-direccion";

    $counteo = count($rec);
    for ($i = 0; $i < $counteo; $i++) {

        //$lista[$i]['id_empleador'] = $rec[$i]['id_empleador'];
        //$lista[$i]['id_establecimiento'] = $rec[$i]['id_establecimiento'];
        //$lista[$i]['cod_establecimiento'] = $rec[$i]['cod_establecimiento'];
        //$lista[$i]['id_tipo_establecimiento'] = $rec[$i]['id_tipo_establecimiento'];
        //$lista[$i]['tipo_establecimiento_descripcion'] = $rec[$i]['tipo_establecimiento_descripcion'];
        //$lista[$i]['cod_establecimiento'] = $red['cod_establecimiento'];
        //------------------------------------------------------------------------------------------
        //here
        $ubigeo_nombre_via = $rec[$i]["ubigeo_nombre_via"];
        $nombre_via = $rec[$i]['nombre_via'];
        $numero_via = $rec[$i]['numero_via'];

        $ubigeo_nombre_zona = $rec[$i]['ubigeo_nombre_zona'];

        $nombre_zona = $rec[$i]['nombre_zona'];
        $etapa = $rec[$i]['etapa'];
        $manzana = $rec[$i]['manzana'];
        $blok = $rec[$i]['blok'];
        $lote = $rec[$i]['lote'];

        $departamento = $rec[$i]['departamento'];
        $interior = $rec[$i]['interior'];

        $kilometro = $rec[$i]['kilometro'];
        $referencia = $rec[$i]['referencia'];

        $ubigeo_departamento = str_replace('DEPARTAMENTO', '', $rec[$i]['ubigeo_departamento']);
        $ubigeo_provincia = $rec[$i]['ubigeo_provincia'];
        $ubigeo_distrito = $rec[$i]['ubigeo_distrito'];

        //---------------Inicio Cadena String----------//
        $cadena = '';

        $cadena .= (isset($ubigeo_nombre_via)) ? '' . $ubigeo_nombre_via . ' ' : '';
        $cadena .= (isset($nombre_via)) ? '' . $nombre_via . ' ' : '';
        $cadena .= (isset($numero_via)) ? '' . $numero_via . ' ' : '';

        $cadena .= (isset($ubigeo_nombre_zona)) ? '' . $ubigeo_nombre_zona . ' ' : '';
        $cadena .= (isset($nombre_zona)) ? '' . $nombre_zona . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';

        $cadena .= (isset($manzana) && !empty($manzana)) ? 'MZA. ' . $manzana . ' ' : '';
        $cadena .= (isset($blok)) ? '' . $blok . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';
        $cadena .= (isset($lote) && !empty($lote)) ? 'LOTE. ' . $lote . ' ' : '';

        $cadena .= (isset($departamento)) ? '' . $departamento . ' ' : '';
        $cadena .= (isset($interior)) ? '' . $interior . ' ' : '';
        $cadena .= (isset($kilometro)) ? '' . $kilometro . ' ' : '';
        $cadena .= (isset($referencia)) ? '' . $referencia . ' ' : '';


        $cadena .= (isset($ubigeo_departamento)) ? '' . $ubigeo_departamento . '-' : '';
        $cadena .= (isset($ubigeo_provincia)) ? '' . $ubigeo_provincia . '-' : '';
        $cadena .= (isset($ubigeo_distrito)) ? '' . $ubigeo_distrito . ' ' : '';

        $cadena = strtoupper($cadena);
//-----------------------------------------------------------------------------------------------------

        $lista[$i]['id'] = $rec[$i]['id_establecimiento'] . "|" . $rec[$i]['id_tipo_establecimiento'] . "|" . $rec[$i]['cod_establecimiento'];
        $lista[$i]['descripcion'] = $cadena;
    }//ENDFOR
    //echo "<pre>";
    //print_r($lista);
    //echo "</pre>";	
    return $lista;
}

//-----------------------------------------------------------------------------------------------------


function editarTrabajador() {

    // datos primarios principales
    $ID_PER = $_REQUEST['id_persona_categoria'];
    $ID_TRA = $_REQUEST['id_trabajador_categoria'];

    //DAO
    $dao_tra = new TrabajadorDao();

    // echo "\ncbo_tipo_pago = " . $_REQUEST['cbo_tipo_pago'];
    // datos ORDEN FORM
    // Detalle 1 #Periodo Laboral
    $detalle_1 = new DetallePeriodoLaboral();
    $detalle_1->setId_detalle_periodo_laboral($_REQUEST['id_detalle_periodo_laboral']);
    //$detalle_1->setId_trabajador($ID_TRA);
    $detalle_1->setFecha_inicio(getFechaPatron($_REQUEST['txt_plaboral_fecha_inicio_base'], "Y-m-d"));
    $detalle_1->setFecha_fin(getFechaPatron($_REQUEST['txt_plaboral_fecha_fin_base'], "Y-m-d"));
    $detalle_1->setCod_motivo_baja_registro($_REQUEST['cbo_plaboral_motivo_baja_base']);

    // Detalle 2 #Detalle Tipo Trabajador
    $detalle_2 = new DetalleTipoTrabajador();
    $detalle_2->setId_detalle_tipo_trabajador($_REQUEST['id_detalle_tipo_trabajador']);
    //$detalle_2->setId_trabajador($ID_TRA);
    $detalle_2->setCod_tipo_trabajador($_REQUEST['cbo_ttrabajador_base']);
    $detalle_2->setFecha_inicio(getFechaPatron($_REQUEST['txt_ttrabajador_fecha_inicio_base'], "Y-m-d"));
    $detalle_2->setFecha_fin(getFechaPatron($_REQUEST['txt_ttrabajador_fecha_fin_base'], "Y-m-d"));

    //datos primarios '1 datos Laborales'
    $tra = new Trabajador();
    $tra->setId_trabajador($ID_TRA);
    $tra->setCod_regimen_laboral($_REQUEST['cbo_regimen_laboral']);
    $tra->setCod_categorias_ocupacionales($_REQUEST['cbo_categoria_ocupacional']);
    $tra->setCod_nivel_educativo($_REQUEST['cbo_nivel_educativo']);
    $tra->setCod_ocupacion($_REQUEST['cboOcupacion']);
    $tra->setCod_tipo_contrato($_REQUEST['cbo_tipo_contrato']);
    $tra->setCod_tipo_pago($_REQUEST['cbo_tipo_pago']);
    $tra->setCod_periodo_remuneracion($_REQUEST['cbo_periodo_pago']);

    $tra->setMonto_remuneracion($_REQUEST['txt_monto_remuneracion_basica_inicial']);
    $tra->setId_establecimiento($_REQUEST['txt_id_establecimiento']);

    //Artilujio 01 - JornadaLaboral=checkbox
    $jlaboral = $_REQUEST['jlaboral'];
    $cadena = "";
    $counteo = count($jlaboral);
    for ($i = 0; $i < $counteo; $i++) {
        $cadena .= $jlaboral[$i] . ",";
    }
    //Artilujio echo	
    $tra->setJornada_laboral($cadena);

    //
    $tra->setSituacion_especial($_REQUEST['rbtn_situacion_especial']);
    $tra->setDiscapacitado($_REQUEST['rbtn_discapacitado']);
    $tra->setSindicalizado($_REQUEST['rbtn_sindicalizado']);
    $tra->setCod_convenio(empty($_REQUEST['cbo_convenio']) ? 0 : $_REQUEST['cbo_convenio'] );


    //--------------------------------------------------------------------------




    /*  $valor = $detalle_1->getFecha_fin();

      if (($valor) && ($_REQUEST['cbo_situacion'] != 0)) { //THIS ESTABLECIDO
      $tra->setCod_situacion(0); // 0 = BAJA O 2 = Conceptos pendientes
      //$tra->setEstado('INACTIVO');
      //echo "CODIGO SITUACION 00000000000000000000000000";
      } else {
      $tra->setCod_situacion(1);
      }
     */
    //--------------------------------------------------------------------------
    // Detalle 3 #Detalle Establecimientos
    //???
    $detalle_3 = new DetalleEstablecimiento();
    $detalle_3->setId_detalle_establecimiento($_REQUEST['id_detalle_establecimiento']);
    $detalle_3->setId_trabajador($ID_TRA);
    $detalle_3->setId_establecimiento($_REQUEST['txt_id_establecimiento']);
    //$detalle_3->setId_detalle_establecimiento( $_REQUEST['id_detalle_establecimiento'] );
    //????
    //datos primarios '2' #Detalle Regimen Salud
    $detalle_4 = new DetalleRegimenSalud();
    //$detalle_3->setId_trabajador($ID_TRA);
    $detalle_4->setId_detalle_regimen_salud($_REQUEST['id_detalle_regimen_salud']);
    $detalle_4->setCod_regimen_aseguramiento_salud($_REQUEST['cbo_regimen_salud_base']);
    $detalle_4->setFecha_inicio(getFechaPatron($_REQUEST['txt_rsalud_fecha_inicio_base'], "Y-m-d"));
    $detalle_4->setFecha_fin(getFechaPatron($_REQUEST['txt_rsalud_fecha_fin_base'], "Y-m-d"));
    $detalle_4->setCod_eps($_REQUEST['cbo_eps_servicios_propios']);

    //detalle 4
    $detalle_5 = new DetalleRegimenPensionario();
    //$detalle_4->setId_trabajador($ID_TRA);
    $detalle_5->setId_detalle_regimen_pensionario($_REQUEST['id_regimen_pensionario']);
    $detalle_5->setCod_regimen_pensionario($_REQUEST['cbo_regimen_pensionario_base']);
    $detalle_5->setCUSPP($_REQUEST['txt_CUSPP']);
    $detalle_5->setFecha_inicio(getFechaPatron($_REQUEST['txt_rpensionario_fecha_inicio_base'], "Y-m-d"));
    $detalle_5->setFecha_fin(getFechaPatron($_REQUEST['txt_rpensionario_fecha_fin_base'], "Y-m-d"));

    //
    $tra->setPercibe_renta_5ta_exonerada($_REQUEST['rbtn_percibe_renta_5ta_exoneradas']);
    $tra->setAplicar_convenio_doble_inposicion($_REQUEST['rbtn_aplica_convenio_doble_inposicion']);

    //-----------------------------------------	
    //CAMBIAR CODIGO SITUACION
    $contador = false;
    if (isset($detalle_1)) {
        if ($detalle_1->getFecha_inicio() != "") {
            if ($detalle_1->getFecha_fin() != "") {
                if ($detalle_1->getCod_motivo_baja_registro() != '0') {
                    $detalle_2->setFecha_fin($detalle_1->getFecha_fin());
                    $detalle_4->setFecha_fin($detalle_1->getFecha_fin());
                    $detalle_5->setFecha_fin($detalle_1->getFecha_fin());
                    $contador = true;
                }
            }
        }
    }   //END FOR

    if ($contador) {
        $dao_tra->actualizarCodigoSituacion($ID_TRA, 0);
    }



    $dao_tra->actualizarTrabajador($tra);

    //-----------------------------------------
    $dao1 = new DetallePeriodoLaboralDao();
    $dao1->actualizarDetallePeriodoLaboral($detalle_1);

    //-----------------------------------------
    $dao2 = new DetalleTipoTrabajadorDao();
    $dao2->actualizarDetalleTipoTrabajador($detalle_2);

    //-----------------------------------------   INICIO
    //Busqueda SIMPRE DEBE HACERLO ELSE ERROR CRITICO!
    /*    if ($detalle_3->getId_detalle_establecimiento() == 0) { //valor por default
      $dao = new DetalleEstablecimientoDao();
      $id_detalle_establecimiento = $dao->buscar_iDDetalleEstablecimiento($ID_TRA);
      }
      // Setear ID principal de la tabla...
      $detalle_3->setId_detalle_establecimiento($id_detalle_establecimiento); */
    $dao3 = new DetalleEstablecimientoDao();
    $dao3->actualizarDetalleEstablecimiento($detalle_3);

    //-----------------------------------------   FINAL
    $dao4 = new DetalleRegimenSaludDao();
    $dao4->actualizarDetalleRegimenSalud($detalle_4);

    //-----------------------------------------
    $dao5 = new DetalleRegimenPensionarioDao();
    $dao5->actualizarDetalleRegimenPensionario($detalle_5);
    
    //-----------------------------------------
    
    return true;
}

function buscarTrabajadorPorIdPersona($id_persona) {
    $dao = new TrabajadorDao();
    /* buscaTrabajadorPorIdPersona */
    $data = $dao->buscaTrabajadorPorIdPersona($id_persona);
    //echo "CONTROLLER DAO";
    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";
    $model = new Trabajador();
    $model->setId_trabajador($data['id_trabajador']);
    $model->setId_persona($data['id_persona']);
    $model->setCod_regimen_laboral($data['cod_regimen_laboral']);
    $model->setCod_nivel_educativo($data['cod_nivel_educativo']);
    $model->setCod_categorias_ocupacionales($data['cod_categorias_ocupacionales']);

    $model->setCod_ocupacion($data['cod_ocupacion_p']);
    $model->setCod_tipo_contrato($data['cod_tipo_contrato']);
    $model->setCod_tipo_pago($data['cod_tipo_pago']);
    $model->setCod_periodo_remuneracion($data['cod_periodo_remuneracion']);
    $model->setMonto_remuneracion($data['monto_remuneracion']);
    $model->setId_monto_remuneracion($data['id_monto_remuneracion']);

    $model->setId_establecimiento($data['id_establecimiento']);
    $model->setJornada_laboral($data['jornada_laboral']);
    $model->setSituacion_especial($data['situacion_especial']);
    $model->setDiscapacitado($data['discapacitado']);
    $model->setSindicalizado($data['sindicalizado']);

    $model->setPercibe_renta_5ta_exonerada($data['percibe_renta_5ta_exonerada']);
    $model->setAplicar_convenio_doble_inposicion($data['aplicar_convenio_doble_inposicion']);
    $model->setCod_convenio($data['cod_convenio']);

    $model->setEstado($data['estado']);
    $model->setCod_situacion($data['cod_situacion']);

//	var_dump($model);

    return $model;
}

function cargar_tabla_trabajador($ESTADO) {

    $dao_trabajador = new TrabajadorDao();

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

    $count = $dao_trabajador->cantidadTrabajador(ID_EMPLEADOR_MAESTRO, $ESTADO, $WHERE);

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

    //$dao_trabajador->actualizarStock();

    $lista = $dao_trabajador->listarTrabajador(ID_EMPLEADOR_MAESTRO, $ESTADO, $WHERE, $start, $limit, $sidx, $sord);

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

        $param = $rec["id_trabajador"];
        $_01 = $string_cat;
        $_02 = $rec["nombre_tipo_documento"];
        $_03 = $rec["num_documento"];
        $_04 = $rec["apellido_paterno"];
        $_05 = $rec["apellido_materno"];

        $_06 = $rec["nombres"];
        $_07 = $rec["fecha_nacimiento"];
        $_08 = $rec["sexo"];
        $_09 = $rec["estado"];

        //$js = "javascript:cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona=" . $param . "','#CapaContenedorFormulario')";
        //$js2 = "javascript:eliminarPersona('" . $param . "')";
        //$opciones_1 = '<a href="' . $js . '">Modificar</a>';
        //$opciones_2 = '<a href="' . $js2 . '">Eliminar</a>';

        $opciones = $rec['reporte'];
        /*        $opciones = '<div id="divEliminar_Editar">				
          <span  title="Editar" >
          <a href="' . $js . '"><img src="images/edit.png"/></a>
          </span>
          &nbsp;
          <span  title="Cancelar" >
          <a href="' . $js2 . '"><img src="images/cancelar.png"/></a>
          </span>
          </div>';
         */

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

// ----- VIEW-plame
function buscar_IDTrabajador($id_trabajador) {

    $dao = new TrabajadorDao();
    $data = $dao->buscar_IDTrabajador($id_trabajador);

    $model = new Trabajador();
    $model->setId_trabajador($data['id_trabajador']);
    $model->setId_persona($data['id_persona']);
    $model->setCod_regimen_laboral($data['cod_regimen_laboral']);
    $model->setCod_nivel_educativo($data['cod_nivel_educativo']);
    $model->setCod_categorias_ocupacionales($data['cod_categorias_ocupacionales']);
    $model->setCod_ocupacion($data['cod_ocupacion_p']);
    $model->setCod_tipo_contrato($data['cod_tipo_contrato']);
    $model->setCod_tipo_pago($data['cod_tipo_pago']);
    $model->setCod_periodo_remuneracion($data['cod_periodo_remuneracion']);
    $model->setMonto_remuneracion($data['monto_remuneracion']);
    $model->setId_establecimiento($data['id_establecimiento']);
    $model->setJornada_laboral($data['jornada_laboral']);
    $model->setSituacion_especial($data['situacion_especial']);
    $model->setDiscapacitado($data['discapacitado']);
    $model->setSindicalizado($data['sindicalizado']);
    $model->setPercibe_renta_5ta_exonerada($data['percibe_renta_5ta_exonerada']);
    $model->setAplicar_convenio_doble_inposicion($data['aplicar_convenio_doble_inposicion']);
    $model->setCod_convenio($data['cod_convenio']);
    $model->setCod_situacion($data['cod_situacion']);

    // $model->setCod_tipo_pago($data['cod_tipo_pago']);
    // $model->setCod_periodo_remuneracion($data['cod_periodo_remuneracion']);
    // Falta setear!

    return $model;
}

// REGISTRAR NUEVO:: utilizado en PersonaController
function nuevoTrabajador($ID_PERSONA) {
//---------------------------------------------------------------------------------
//------------------ INICIO Registrar TRABAJADOR  ---------------------------------   

    $obj = new Trabajador();
    $obj->setId_persona($ID_PERSONA);
    // $obj->setEstado('INACTIVO');
    $obj->setCod_convenio(0);
    $obj->setCod_situacion(1);

    $dao = new TrabajadorDao();
    $ID_TRA = $dao->registrarTrabajador($obj);

    //--- sub (1) Periodo Laboral    
    $obj1 = new DetallePeriodoLaboral();
    $obj1->setCod_motivo_baja_registro(0);
    $obj1->setId_trabajador($ID_TRA);
    $obj1->setId_persona($ID_PERSONA);


    $dao1 = new DetallePeriodoLaboralDao();
    $dao1->registrarDetallePeriodoLaboral($obj1);

    //--- sub (2) Tipo trabajador
    $obj2 = new DetalleTipoTrabajador();
    $obj2->setCod_tipo_trabajador(0);
    $obj2->setId_trabajador($ID_TRA);
    $obj2->setId_persona($ID_PERSONA);

    $dao2 = new DetalleTipoTrabajadorDao();
    $dao2->registrarDetalleTipoTrabajador($obj2);

    //--- sub (3) Tipo Establecimiento
    $obj3 = new DetalleEstablecimiento();
    $obj3->setId_trabajador($ID_TRA);
    $obj3->setId_establecimiento(0);

    $dao3 = new DetalleEstablecimientoDao();
    $dao3->registrarDetalleEstablecimiento($obj3);

    //--- sub(4) Regimen de Salud
    $obj4 = new DetalleRegimenSalud();
    $obj4->setId_trabajador($ID_TRA);
    $obj4->setCod_regimen_aseguramiento_salud(0);
    $obj4->setCod_eps(0);
    $obj4->setId_persona($ID_PERSONA);

    $dao4 = new DetalleRegimenSaludDao();
    $dao4->registrarDetalleRegimenSalud($obj4);

    //--- sub(5) Regimen Pensionario
    $obj5 = new DetalleRegimenPensionario();
    $obj5->setId_trabajador($ID_TRA);
    $obj5->setCod_regimen_pensionario(0);
    $obj5->setId_persona($ID_PERSONA);

    $dao5 = new DetalleRegimenPensionarioDao();
    $dao5->registrarDetalleRegimenPensionario($obj5);

//------------------------------------------------------------------------------
//------------------ INICIO Registrar CATEGORIA PENSIONISTA  OK-----------------
//    

    $obj = new Pensionista();
    $obj->setId_persona($ID_PERSONA);
    $obj->setCod_situacion(1); //activo    
    $obj->setEstado('INACTIVO');

    $dao = new PensionistaDao();
    $ID_PENSIONISTA = $dao->registrar($obj);

    // ---- sub (periodo) laboral Pensionista
    $obj = new DetallePeriodoLaboralPensionista();
    $obj->setId_pensionista($ID_PENSIONISTA);
    $obj->setCod_motivo_baja_registro(0);

    $dao = new DetallePeriodoLaboralPensionistaDao();
    $dao->registrar($obj);

    //nuevoPensionista($ID_PERSONA);
//------------------------------------------------------------------------------
//------------------ INICIO Registrar CATEGORIA PERSONA FORMACION  NOT ---------
// 
    // (01) REGISTRO -----------------------------------------
    $obj = new PersonaFormacionLaboral();
    $obj->setId_persona($ID_PERSONA);
    $obj->setCod_situacion(1);
    $obj->setEstado('INACTIVO');

    $dao = new PersonaFormacionLaboralDao();
    $ID_PFORMACION = $dao->registrar($obj);
    $obj = null;

    // (02) REGISTRO ---------------------------------------------
    //Establecimiento en formacion   
    $obj = new DetalleEstablecimientoFormacion();
    $obj->setId_personal_formacion_laboral($ID_PFORMACION);
    $obj->setId_establecimiento(0);

    $dao = new DetalleEstablecimientoFormacionDao();
    $dao->registrar($obj);

    // (03) REGISTRO -------------------------------------------
    $obj = new DetallePeriodoFormativo();
    $obj->setId_personal_formacion_laboral($ID_PFORMACION);

    $dao = new DetallePeriodoFormativoDao();
    $dao->registrar($obj);



//------------------------------------------------------------------------------
//------------------ INICIO Registrar CATEGORIA Personal de Teceros  OK---------
//      
    // (01) REGISTRO -----------------------------------------
    $obj = new personaTercero();
    $obj->setId_persona($ID_PERSONA);
    $obj->setId_empleador_destaque_yoursef(0); //default 
    $obj->setCod_situacion(1);
    $obj->setEstado('INACTIVO');

    $dao = new PersonaTerceroDao();
    $ID_PERSONA_TERCERO = $dao->registrar($obj);
    $obj = null;

    // (02) Periodo Destaque 
    $obj = new PeriodoDestaque();
    $obj->setId_personal_tercero($ID_PERSONA_TERCERO);

    $dao = new PeriodoDestaqueDao();
    $dao->registrar($obj);
    $obj = null;

    // (03) Lugar Destaque
    $objd = new LugarDestaque();
    $objd->setId_personal_tercero($ID_PERSONA_TERCERO);
    $objd->setId_establecimiento(0); //Default

    $dao = new LugarDestaqueDao();
    $dao->registrar($objd);

    // (04) Cobertura Esalud
    $objc = new CoberturaSalud();
    $objc->setId_personal_tercero($ID_PERSONA_TERCERO);

    $daoc = new CoberturaSaludDao();
    $daoc->registrar($objc);



//---------------------------------------------------------------------------------
//------------------ FINAL Registrar TRABAJADOR  ---------------------------------

    return $ID_TRA;
}

?>