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

    //etapa de pago
    require_once '../dao/EtapaPagoDao.php';
    require_once '../model/EtapaPago.php';
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


    //--------------------------------------------------------------------------
    ECHO "<pre>sssssss";
    echo "</pre>";
} else if ($op == "cargar_tabla_declaracion_etapa") {
    /**
     * Lista a los trabajadores  por MES 'declaracion mensual'
     *  internamente agrupa las etapas 1 y 2 quincenas
     */
    $response = listarTrabajadoresPorDeclaracionEtapas($_REQUEST['id_pdeclaracion']);
}


echo (!empty($response)) ? json_encode($response) : '';
/*
  function existeDeclaracion() {
  $dao = new PlameDeclaracionDao();
  $dao->existeDeclaracion();
  }*/


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


       
        
        $_03 = '<a href="javascript:cargar_pagina(\'sunat_planilla/view-empresa/new_etapaPago.php?id_declaracion=' . $param . '&periodo=' . $_01 . '\',\'#CapaContenedorFormulario\')"title = "Operaciones">Oper</a>';

         $js ="javascript:cargar_pagina('sunat_planilla/view-plame/edit_declaracion.php?id_declaracion=".$param."&periodo=".$_01."','#CapaContenedorFormulario')";
        //$js = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_pago.php?id_etapa_pago=" . $param . "&id_pdeclaracion=" . $_00 . "','#CapaContenedorFormulario')";
        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar"   >
		<a href="' . $js . '" class="divEditar" ></a>
		</span>              

		</div>';        
        
        
        
        

        $periodo = getFechaPatron($_01, "m/Y");

        //hereee
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $periodo,
            $opciones,
            $_03
        );
        $i++;
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

    $FECHA = getMesInicioYfin($periodo);
    //PASO 01   existe periodo?    
    $dao = new PlameDeclaracionDao();
    $num_declaracion = $dao->existeDeclaracion($id_empleador_maestro, $periodo);
    $rpta = 'false';
    if ($num_declaracion == 0) {
        $rpta = 'true';
    }
    if ($rpta == 'true') {
        $dao->registrar($id_empleador_maestro, $periodo);
    }
    // $response = strval($rpta);

    return strval($rpta); //SOLO 1 = TRUE
}

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//*****************************************************************************
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
// $id_pdeclaracion
// COD CONCEPTO = concepto 0121 = basico
// FUCK DELETEEEEE

/*
 * Lista a los trabajadores por MES == 2 etapas(2quincenas);
 */

function listarTrabajadoresPorDeclaracionEtapas($ID_PDECLARACION) {

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
    $lista = $dao->listarDeclaracionEtapa($ID_PDECLARACION, $WHERE);

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

        $param = $rec["id_pago"];
        $id_trabajador = $rec['id_trabajador'];
        
        $_01 = $rec['cod_tipo_documento'];
        $_02 = $rec['num_documento'];
        $_03 = $rec['apellido_paterno'];
        $_04 = $rec['apellido_materno'];
        $_05 = $rec['nombres'];
        
        $js = "javascript:editarPtrabajador('" . $id_trabajador . "')";
        
        $opciones = '<div id="divEliminar_Editar">				
        <span  title="Editar" >
        <a href="' . $js . '"><img src="images/edit.png"/></a>
        </span>				
        &nbsp;
        </div>';
        
        
        
        //hereee
        $response->rows[$i]['id'] = $id_trabajador;
        $response->rows[$i]['cell'] = array(
            $id_trabajador,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $opciones
        );
        $i++;
    }

    return $response;
}

?>
