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

    /*
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
     */

    //MODEL PperiodoLaboral
    require_once ('../model/PperiodoLaboral.php');
    require_once '../dao/PperiodoLaboralDao.php';

    //PAGO DAO
    require_once '../dao/PagoDao.php';

    //etapa de pago
    require_once '../dao/EtapaPagoDao.php';
    require_once '../model/EtapaPago.php';


    //rpc
    require_once '../dao/RegistroPorConceptoDao.php';
    require_once '../model/RegistroPorConcepto.php';
}

$response = NULL;

if ($op == "cargar_tabla") {
    $response = cargar_tabla_pdeclaracio(ID_EMPLEADOR_MAESTRO);
} else if ($op == "add") { //READY
    $post_fecha = "01/" . $_REQUEST['periodo'];
    $periodo = getFechaPatron($post_fecha, "Y-m-d");
    $response = nuevaDeclaracionPeriodo(ID_EMPLEADOR_MAESTRO, $periodo);
} else if ($op == "add-data-ptrabajadores") { //en realidad es UNIR DATOS
    // $tipo
    // 1 = Elimina y crea denuevo declaracion : 16/08/2012
    // 2 = Actualiza por CREAR
    $tipo = ($_REQUEST['declaracionRectificadora']) ? $_REQUEST['declaracionRectificadora'] : '1'; // 1

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
} else if ($op == "cargar_tabla_ptrabajador") {
    //CARGAR JQGRID ptrabajadores
    cargar_tabla_ptrabajador();
} else if ($op == "cargar_tabla_empresa") {
    $anio = $_REQUEST['anio'];
    $response = cargar_tabla_empresa(ID_EMPLEADOR_MAESTRO, $anio);
} else if ($op == "PM") {
    // $_REQUEST['id_pdeclaracion']
    $ID_DECLARACION = $_REQUEST['id_declaracion'];
    $periodo = $_REQUEST['periodo'];
    //$periodo = getFechaPatron($post_fecha, "Y-m-d");
    //--------------------------------------------------------------------------

    $daopt = new PtrabajadorDao();
    $data_ptrabajadores = $daopt->listarPor_ID_declaracion($ID_DECLARACION);

    if (count($data_ptrabajadores) > 0) {
        $daopt->eliminarPtrabajadorPor_id_declaracion($ID_DECLARACION);
    }
    ///ready
    nuevaDeclaracion(ID_EMPLEADOR_MAESTRO, $periodo, $ID_DECLARACION);
} else if ($op == "del-id_pdeclaracion") {

    $response = del_pdeclaracion();
} else if ($op == "baja-cerrar_mes") {
    //echoo($_REQUEST);    
    //--------------------------------------------------------------------------
    $conceptos = array('100', '200', '300', '400', '500', '600', '700', '900');
    $response = cerrarMes($conceptos);
}


echo (!empty($response)) ? json_encode($response) : '';
/*
  function existeDeclaracion() {
  $dao = new PlameDeclaracionDao();
  $dao->existeDeclaracion();
  } */

function del_pdeclaracion() {

    $dao = new PlameDeclaracionDao();
    return $dao->del($_REQUEST['id_pdeclaracion']);
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

        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_declaracion.php?id_pdeclaracion=" . $param . "&periodo=" . $periodo . "','#CapaContenedorFormulario')";
        $js2 = "javascript:cargar_pagina('sunat_planilla/view-plame/edit_declaracion.php?id_pdeclaracion=" . $param . "&periodo=" . $periodo . "','#CapaContenedorFormulario')";
        $js3 = "";

        $modificar = '<div id="">
          <span  title="Editar" >
          <a href="' . $js . '"><img src="images/edit.png"/></a>
          </span>
          &nbsp;
          </div>'
        ;

        $modificar2 = '<div id="">
		<span  title="Editar" >
		<a href="' . $js2 . '"><img src="images/edit.png"/></a>
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
            utf8_encode($modificar2),
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
    $lista = $dao->listarGrid($id_empleador_maestro, $anio, $WHERE, $start, $limit, $sidx, $sord);

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

    foreach ($lista as $rec) {

        $param = $rec["id_pdeclaracion"];
        if($param == ID_PDECLARACION){
        $_01 = $rec['periodo']; //getFechaPatron($rec['periodo'], "m/Y");
        $_02 = getFechaPatron($rec['periodo'], "m/Y");
        $_03 = $rec['estado'];
        $js4 = "javascript:cargar_pagina('sunat_planilla/view-plame/edit_declaracion.php?id_declaracion=" . $param . "&periodo=" . $_01 . "&estado=" . $rec['estado'] . "','#CapaContenedorFormulario')";
        $_04 = '<a href="' . $js4 . '" class="divEditar" ></a>';

        $js5 = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_pago.php?id_pdeclaracion=" . $param . "&periodo=" . $rec['periodo'] . "','#CapaContenedorFormulario')";
        $_05 = '<a href="' . $js5 . '" class="divEditar" ></a>';

        $_06 = '<a href="javascript:cargar_pagina(\'sunat_planilla/view-empresa/new_etapaPago.php?id_declaracion=' . $param . '&periodo=' . $rec['periodo'] . '\',\'#CapaContenedorFormulario\')"title = "Operaciones">Oper</a>';
        $_07 = '<a href="javascript:cargar_pagina(\'sunat_planilla/view-empresa/view_registro_por_concepto.php?id_declaracion=' . $param . "&periodo=" . $rec['periodo'] . '\',\'#CapaContenedorFormulario\')"><span class ="red">RC</span></a>';
        $_08 = '<a href="javascript:cargar_pagina(\'sunat_planilla/view-empresa/view_registro_concepto_e.php?id_declaracion=' . $param . '&periodo=' . $rec['periodo'] . '\',\'#CapaContenedorFormulario\')"><span class ="red">RC 2</span></a>';

        $js9 = "javascript:cargar_pagina('sunat_planilla/view-empresa/view_vacacion_2.php?id_pdeclaracion=" . $param . "&periodo=" . $rec['periodo'] . "','#CapaContenedorFormulario')";
        $_09 = '<a href="' . $js9 . '" class="divEditar" ></a>';


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
        );
        $i++;
        }
    }
    return $response;
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
    //PASO 01   existe periodo?    
    $dao = new PlameDeclaracionDao();
    //echo $id_empleador_maestro;
    $num_declaracion = $dao->existeDeclaracion($id_empleador_maestro, $periodo);
    $rpta = false;

    if ($num_declaracion == 0) {
        $rpta = true;
        $dao->registrar($id_empleador_maestro, $periodo);
    }

    return $rpta;
}

//-----------------------------------------------------------------------------
//*****************************************************************************
//-----------------------------------------------------------------------------

function cerrarMes($conceptos) {

    $rpta->estado = false;
    //echoo($_REQUEST);
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];

    // paso 01
    //Buscar(id_pdeclaracion = mes - anio ) SINO  Registra un nuevo 
    $dao_pd = new PlameDeclaracionDao();
    $data_pd_hoy = $dao_pd->buscar_ID($id_pdeclaracion);

    $periodo_hoy = null;
    $periodo_futuro = null;
    $id_pdeclaracion_futuro = null;

    if ($data_pd_hoy['id_pdeclaracion']) {

        $periodo_hoy = $data_pd_hoy['periodo'];
        $periodo_futuro = crearFecha($periodo_hoy, 0, 1, 0); //añade un mes
        //$dao_pd->baja($id_pdeclaracion); 
        //$data_pd_fut =array();
        $data_pd_fut = $dao_pd->Buscar_IDPeriodo(ID_EMPLEADOR_MAESTRO, $periodo_futuro);

        //DECISION BINARIA.
        if ($data_pd_fut['id_pdeclaracion']) { //existe declaracion
            $id_pdeclaracion_futuro = $data_pd_fut['id_pdeclaracion'];
        } else { //no existe Y INSERT
            echo "\nno encontro nada INSERT\n";
            $id_pdeclaracion_futuro = $dao_pd->registrar(ID_EMPLEADOR_MAESTRO, $periodo_futuro);
        }

        //----------------------------------------------------------------------        
        // paso 02
        // Listado de codigo concepto detalle SELECCIONADOS admin : 101,102...201,202
        $dao = new PlameDetalleConceptoEmpleadorMaestroDao();
        $seleccionado = array(0, 1);
        $cod_concepto = array();
        $cod_concepto = $dao->view_listarCod_Concepto(ID_EMPLEADOR_MAESTRO, $conceptos, $seleccionado);

        //echoo($cod_concepto);
        //echo "\nlista past\n";
        //paso 03
        //listar si existen en trabajadores registrados en  : registros_por_conceptos
        $num = 0;
        for ($i = 0; $i < count($cod_concepto); $i++) { // --------------- Registrar codigo_detalle_concepto
            // -- Lista de los trabajadores actuales
            $dao_rpc = new RegistroPorConceptoDao();
            $data_rpc = $dao_rpc->listarTrabajador($id_pdeclaracion, $cod_concepto[$i]['cod_detalle_concepto']);

            //echoo($data_rpc);

            if (count($data_rpc) >= 1):
                $num = $num + 1;

                for ($j = 0; $j < count($data_rpc); $j++):
                    // insert all la data  al siguiente periodo.
                    $obj_rpc = new RegistroPorConcepto();
                    $obj_rpc->setId_pdeclaracion($id_pdeclaracion_futuro);
                    $obj_rpc->setId_trabajador($data_rpc[$j]['id_trabajador']);
                    $obj_rpc->setCod_detalle_concepto($data_rpc[$j]['cod_detalle_concepto']);
                    $c = $data_rpc[$j]['cod_detalle_concepto'];

                    if ($c == '0201' || $c == '0304' || $c == '0909' || $c == '0701') {
                        $obj_rpc->setValor($data_rpc[$j]['valor']);
                        // ----- TESTING ---- $c=='0107'
                        //$obj_rpc->setValor(0);
                        //$dao_rpc->add($obj_rpc);
                        //echo "\n<br> ID_TRABAJADOR = ".$data_rpc[$j]['id_trabajador'];                    
                    } else {
                        $obj_rpc->setValor(0);
                    }

                    $dao_rpc->add($obj_rpc);

                endfor;

            endif;
        }



        $rpta->estado = true;
        $rpta->mensaje = "Migracion de datos. se exportaron [$num] data conceptos al sgte. periodo.";
    }

    return $rpta;
}

?>
