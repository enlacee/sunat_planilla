<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");


$op = $_REQUEST["oper"];
if ($op) {
    //require_once '../model/Persona.php';
    require_once ('../dao/AbstractDao.php');
    require_once '../dao/EstablecimientoDao.php';
    require_once ('../dao/EstablecimientoDireccionDao.php');
    require_once('../model/Establecimiento.php');

//REGISTRAR DIRECCION
    require_once('../model/Direccion.php');
    require_once('../model/EstablecimientoDireccion.php');
}

$response = NULL;

if ($op == "cargar_tabla") {
    $response = cargar_tabla(); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "cargar_tabla2") {
    $response = cargar_tabla2();
} elseif ($op == "add") {
    $response = nuevo();
} elseif ($op == "edit") {
    $response = editar();
} elseif ($op == "del") {
    $id = $_REQUEST['id_establecimiento'];
    $response = isset($id) ? eliminar($id) : "ERROR SCRIPT";
} elseif ($op == "04") {
    $response = existeRucDuplicado($_REQUEST['txt_ruc']);
} elseif ($op == "validar_codigo") {
    $response = existeCodigoEstablecimiento();
}


//echo json_encode($response);
echo (!empty($response)) ? json_encode($response) : '';
/* * **********categoria*************** */

function nuevo() {

    $emp = new Establecimiento();
    $emp->setId_empleador($_REQUEST['id_empleador']);
    $emp->setId_tipo_establecimiento($_REQUEST['cbo_tipo_establecimiento']);
    $emp->setCod_establecimiento($_REQUEST['txt_cod_establecimiento']);
    $emp->setActividad_riesgo_sctr($_REQUEST['rbtn_actividad_riesgo']); //opcional
    $emp->setFecha_creacion(date("Y-m-d"));

    $dao = new EstablecimientoDao();
    $id_establecimiento = $dao->registrarEstablecimiento($emp);

    $rpta = $dao->numeroDeCodigoEstablecimientoPorIdEmpleador($emp->getId_empleador(), $emp->getCod_establecimiento());
//echo $id_establecimiento;
//OTRO
    $est_d = new EstablecimientoDireccion();

    $est_d->setId_establecimiento($id_establecimiento);
    $est_d->setCod_ubigeo_reinec($_REQUEST['cbo_distrito']);
    $est_d->setCod_via($_REQUEST['cbo_tipo_via']);
    $est_d->setNombre_via($_REQUEST['txt_nombre_via']);
    $est_d->setNumero_via($_REQUEST['txt_numero_via']);
    $est_d->setDepartamento($_REQUEST['txt_dpto']);
    $est_d->setInterior($_REQUEST['txt_interior']);
    $est_d->setManzanza($_REQUEST['txt_manzana']);
    $est_d->setLote($_REQUEST['txt_lote']);
    $est_d->setKilometro($_REQUEST['txt_kilometro']);
    $est_d->setBlock($_REQUEST['txt_block']);
    $est_d->setEstapa($_REQUEST['txt_etapa']);
    $est_d->setCod_zona($_REQUEST['cbo_tipo_zona']);
    $est_d->setNombre_zona($_REQUEST['txt_zona']);
    $est_d->setReferencia($_REQUEST['txt_referencia']);




    if (isset($id_establecimiento)) {
        $dao_est_d = new EstablecimientoDireccionDao();
        return $dao_est_d->registrarEstablecimientoDireccion($est_d);
    }


    //return $rpta;
}

function editar() {

    $emp = new Establecimiento();

    $emp->setId_empleador($_REQUEST['id_empleador']);
    $emp->setId_establecimiento($_REQUEST['id_establecimiento']);
    $emp->setCod_establecimiento($_REQUEST['txt_cod_establecimiento']);
    $emp->setId_tipo_establecimiento($_REQUEST['cbo_tipo_establecimiento']);
    $emp->setFecha_creacion(date('Y-m-d'));

    $dao = new EstablecimientoDao();
    $dao->actualizarEstablecimiento($emp);
//--------
    //----------
    //----------

    $obj = new EstablecimientoDireccion();

    $obj->getId_establecimiento();
    $obj->setCod_ubigeo_reinec($_REQUEST['cbo_distrito']);
    $obj->setCod_via($_REQUEST['cbo_tipo_via']);
    $obj->setNombre_via($_REQUEST['txt_nombre_via']);
    $obj->setNumero_via($_REQUEST['txt_numero_via']);
    $obj->setDepartamento($_REQUEST['txt_dpto']);
    $obj->setInterior($_REQUEST['txt_interior']);
    $obj->setManzanza($_REQUEST['txt_manzana']);
    $obj->setLote($_REQUEST['txt_lote']);
    $obj->setKilometro($_REQUEST['txt_kilometro']);
    $obj->setBlock($_REQUEST['txt_block']);
    $obj->setEstapa($_REQUEST['txt_etapa']);
    $obj->setCod_zona($_REQUEST['cbo_tipo_zona']);
    $obj->setNombre_zona($_REQUEST['txt_zona']);
    $obj->setReferencia($_REQUEST['txt_referencia']);
    $obj->setId_establecimiento($_REQUEST['id_establecimiento']); // ES = $obj->getId_establecimiento_direccion();


    $dao = new EstablecimientoDireccionDao();
    $rpta = $dao->actualizarEstablecimientoDireccion($obj);

    return $rpta;
}

function eliminar($id) {//OK
    $dao = new EstablecimientoDao();
    $resp = $dao->eliminarEstablecimiento($id);

    return $resp;
}

function cargar_tabla() {
    $ID_EMPLEADOR = $_REQUEST['id_empleador'];
    $dao_empleador = new EstablecimientoDao();

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

    $count = $dao_empleador->cantidadEstablecimiento($id);

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

    //$dao_empleador->actualizarStock();

    $lista = $dao_empleador->listarEstablecimientos($ID_EMPLEADOR, $WHERE, $start, $limit, $sidx, $sord);

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;  /* break; */
    }


    foreach ($lista as $rec) { /* dato retorna estos datos */

        $param = $rec["id_establecimiento"];
        $_01 = $rec["id_empleador"];
        $_02 = $rec["cod_establecimiento"];
        $_03 = $rec["ruc_empleador"];
        $_04 = $rec['nombre_establecimiento'];
        $_05 = $rec['fecha_creacion'];
        //new

        $js = "javascript:cargar_pagina('sunat_planilla/view/edit_establecimiento.php?id_empleador=" . $_01 . "&id_establecimiento=" . $param . "','#CapaContenedorFormulario')";

        $js2 = "javascript:editarEmpresaCentroCosto('" . $param . "')";

        $js3 = "javascript:newEmpresaCentroCosto('" . $param . "')";

        $opciones = '<div id="divEliminar_Editar">				
				<span  title="Editar" >
				<a href="' . $js . '"><img src="images/edit.png"/></a>
				</span>				
				&nbsp;
		</div>';
        
        
        $opciones2 = '<div id="divEliminar_Editar">				

                    <span  title="Centro de Costo" >
                            <a href="' . $js2 . '"><img src="images/cost_center.png"/></a>
                            </span>
                            &nbsp; 
                    <span  title="Agregar Centro de Costo" >
                            <a href="' . $js3 . '"><img src="images/new_reg.gif"/></a>
                            </span>
                            &nbsp;

		</div>';

        /*        $opciones = '<div id="divEliminar_Editar">				
          <span  title="Editar" >
          <a href="' . $js . '"><img src="images/edit.png"/></a>
          </span>
          &nbsp;
          <span  title="Cancelar" >
          <a href="' . $js2 . '"><img src="images/cancelar.png"/></a>
          </span>
          &nbsp;
          <span  title="Dar Baja" >
          <a href="'.$js3.'"><img src="images/baja.png"/>baja</a>
          </span>
          </div>';
         */

        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            utf8_encode($opciones),
            utf8_encode($opciones2)
        );

        $i++;
    }

    return $response;
}

function buscaEmpleadorPorRuc($ruc) {

    $dao_empleador = new EstablecimientoDao();
// CAMBIO DE BUSQUE SOLO  RPTA JSON!!
    return $dao_empleador->buscaEmpleadorPorRuc2($ruc);

    //var_dump($lista);
}

//*******************************
// Validaciones
//*******************************

function existeRucDuplicado($ruc) {
    $dao = new EstablecimientoDao();
    $rpta = $dao->existeRucDuplicado($ruc);
    return($rpta);
}

function buscarEmpleadorPorId($id) {
    $empleador = new Empleador();
    $dao = new EstablecimientoDao();
    $data = $dao->buscarEmpleadorPorId($id);

    $empleador->setId_empleador($data['id_empleador']);
    $empleador->setId_tipo_empleador($data['id_tipo_empleador']);
    $empleador->setRuc($data['ruc']);
    $empleador->setRazon_social($data['razon_social']);
    $empleador->setId_tipo_sociedad_comercial($data['id_tipo_sociedad_comercial']);
    $empleador->setNombre_comercial($data['nombre_comercial']);
    $empleador->setCod_tipo_actividad($data['cod_tipo_actividad']);
    $empleador->setCod_telefono_codigo_nacional($data['cod_telefono_nacional']);
    $empleador->setTelefono($data['telefono']);
    $empleador->setCorreo($data['correo']);
    $empleador->setEmpresa_dedica($data['empresa_dedica']);
    $empleador->setSenati($data['senati']);
    $empleador->setremype($data['remype']);
    $empleador->setTrabajador_sin_rp($data['trabajador_sin_rp']);
    $empleador->setActividad_riesgo_sctr($data['actividad_riesgo_sctr']);
    $empleador->setTrabajadores_sctr($data['trabajadores_sctr']);
    $empleador->setPersona_discapacidad($data['persona_discapacidad']);
    $empleador->setAgencia_empleo($data['agencia_empleo']);
    $empleador->setDesplaza_personal($data['desplaza_personal']);
    $empleador->setTerceros_desplaza_usted($data['terceros_desplaza_usted']);
    $empleador->setEstado_empleador($data['estado_empleador']);
    $empleador->setFecha_creacion($data['fecha_creacion']);


    return $empleador;
}

/*
 * Validar que el codigo sea el unico 
 */

function existeCodigoEstablecimiento() {

    $_02 = $_REQUEST['txt_cod_establecimiento'];
    $_01 = $_REQUEST['id_empleador'];

    $dao = new EstablecimientoDao();
    $rpta = $dao->numeroDeCodigoEstablecimientoPorIdEmpleador($_01, $_02);
    
    if ($rpta >= 1) {
        $r = true;
    } else if ($rpta == 0) {
        $r = false;
    }
    return $r;
}

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//--------------------FUNCION EXCLUIDA OK---------------------------
//----------------------------------------------------------------------
//----------------------------------------------------------------------

function buscarEstablecimientoPorId($id) {

    $obj = new Establecimiento;
    $dao = new EstablecimientoDao();
    $data = $dao->buscarEstablecimientoPorId($id);


    $obj->setId_establecimiento($data['id_establecimiento']);
    $obj->setCod_establecimiento($data['cod_establecimiento']);
    $obj->setId_empleador($data['id_empleador']);
    $obj->setId_tipo_establecimiento($data['id_tipo_establecimiento']);

    //print_r($obj);
    return $obj;
}

//-------------------------------------------------
function cargar_tabla2() {
    $ID_EMPLEADOR = $_REQUEST['id_empleador'];
    $dao_empleador = new EstablecimientoDao();

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

    $count = $dao_empleador->cantidadEstablecimiento($id);

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

    //$dao_empleador->actualizarStock();

    $lista = $dao_empleador->listarEstablecimientos2($ID_EMPLEADOR, $WHERE, $start, $limit, $sidx, $sord);

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;  /* break; */
    }


    foreach ($lista as $rec) { /* dato retorna estos datos */

        $param = $rec["id_establecimiento"];
        $_01 = $rec["id_empleador"];
        $_02 = $rec["cod_establecimiento"];
        $_03 = ($rec['realizaran_actividad_riesgo']) ? 'Si' : 'No';

        //new
        //here
        $ubigeo_nombre_via = $rec["ubigeo_nombre_via"];
        $nombre_via = $rec['nombre_via'];
        $numero_via = $rec['numero_via'];

        $ubigeo_nombre_zona = $rec['ubigeo_nombre_zona'];
        $nombre_zona = $rec['nombre_zona'];
        $etapa = $rec['etapa'];
        $manzana = $rec['manzana'];
        $blok = $rec['blok'];
        $lote = $rec['lote'];

        $departamento = $rec['departamento'];
        $interior = $rec['interior'];

        $kilometro = $rec['kilometro'];

        $ubigeo_departamento = $rec['ubigeo_departamento'];
        $ubigeo_provincia = $rec['ubigeo_provincia'];
        $ubigeo_distrito = $rec['ubigeo_distrito'];


        $js = "javascript:cargar_pagina('sunat_planilla/view/edit_establecimiento.php?id_establecimiento=" . $param . "','#CapaContenedorFormulario')";

        $js2 = "javascript:eliminarEstablecimiento('" . $param . "')";

        $opciones = '<div id="divEliminar_Editar">				
				<span  title="Editar" >
				<a href="' . $js . '"><img src="images/edit.png"/></a>
				</span>				
				&nbsp;
				<span  title="Cancelar" >
				<a href="' . $js2 . '"><img src="images/cancelar.png"/></a>
				</span>	
		</div>';
        //---------------Inicio Cadena String----------//
        $cadena = '';

        $cadena .= (isset($ubigeo_nombre_via)) ? '' . $ubigeo_nombre_via . ' ' : '';
        $cadena .= (isset($nombre_via)) ? '' . $nombre_via . ' ' : '';
        $cadena .= (isset($numero_via)) ? '' . $numero_via . ' ' : '';

        $cadena .= (isset($ubigeo_nombre_zona)) ? '' . $ubigeo_nombre_zona . ' ' : '';
        $cadena .= (isset($nombre_zona)) ? '' . $nombre_zona . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';

        $cadena .= (isset($manzana)) ? 'MZA. ' . $manzana . ' ' : '';
        $cadena .= (isset($blok)) ? '' . $blok . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';
        $cadena .= (isset($lote)) ? 'LOTE. ' . $lote . ' ' : '';

        $cadena .= (isset($departamento)) ? '' . $departamento . ' ' : '';
        $cadena .= (isset($interior)) ? '' . $interior . ' ' : '';
        $cadena .= (isset($kilometro)) ? '' . $kilometro . ' ' : '';

        $cadena .= (isset($ubigeo_departamento)) ? '' . $ubigeo_departamento . '-' : '';
        $cadena .= (isset($ubigeo_provincia)) ? '' . $ubigeo_provincia . '-' : '';
        $cadena .= (isset($ubigeo_distrito)) ? '' . $ubigeo_distrito . ' ' : '';

        $cadena = strtoupper($cadena);

        //---------------Inicio Cadena String----------//
        //
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $cadena,
            $_03,
            utf8_encode($opciones)
        );

        $i++;
    }

    return $response;
}

?>