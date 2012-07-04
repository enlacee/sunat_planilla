<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");
require_once ('../dao/AbstractDao.php');
require_once ('../dao/EmpleadorDao.php');
require_once('../model/Empleador.php');


$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
}

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla(); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "add") {
    $responce = nuevoEmpleador();
} elseif ($op == "edit") {
    $responce = editarEmpleador();
} elseif ($op == "del") {
    $id = $_REQUEST["id"];
    $responce = eliminar($id);
} elseif ($op == "04") {
    $responce = existeRucDuplicado($_REQUEST['txt_ruc']);
} elseif ($op == "select_codigo") {
    echo cargarSelectCodigo();
} elseif ($op == "buscar_empleador") {
    $responce = buscaEmpleadorPorRuc($_REQUEST['txt_ruc']);
} else if ($op = "existe-empleador") {
    $responce = existeEmpleadorBD($_REQUEST['ruc']);
} else {
    echo "<p>variable OPER no esta definido</p>";
}


echo json_encode($responce);
/* * **********categoria*************** */

function nuevoEmpleador() {

    if (!existeRucDuplicado($_REQUEST['txt_ruc'])) {

        $emp = new Empleador();
        $emp->setId_tipo_empleador($_REQUEST['cbo_tipo_empleador']);
        $emp->setRuc($_REQUEST['txt_ruc']);
        $emp->setRazon_social($_REQUEST['txt_razon_social']);
        $emp->setId_tipo_sociedad_comercial($_REQUEST['cbo_tipo_sociedad_comercial']);
        $emp->setNombre_comercial($_REQUEST['txt_nombre_comercial']);
        $emp->setCod_tipo_actividad($_REQUEST['cbo_tipo_actividad']);
        $emp->setCod_telefono_codigo_nacional($_REQUEST['cbo_telefono_codigo_nacional']);
        $emp->setTelefono($_REQUEST['txt_telefono']);
        $emp->setCorreo($_REQUEST['txt_correo']);
        $emp->setEmpresa_dedica($_REQUEST['cbo_empresa_dedica']);
        $emp->setActividad_riesgo_sctr($_REQUEST['rbtn_actividad_riesgo']);
//----------------
        $emp->setSenati($_REQUEST['rbtn_senati']);

        $emp->setRemype($_REQUEST['rbtn_remype']);

        //S i Remype es TRUE guarda datos de tipo de remipe.
        if ($emp->setRemype() == 1) {
            $emp->setRemype_tipo_empresa($_REQUEST['rbtn_remype_tipo_empresa']);
        }
        $emp->setTrabajador_sin_rp($_REQUEST['rbtn_sin_rp']);
        $emp->setActividad_riesgo_sctr($_REQUEST['rbtn_actividad_riesgo']);
        $emp->setTrabajadores_sctr($_REQUEST['rbtn_sctr']);
        $emp->setPersona_discapacidad($_REQUEST['rbtn_persona_discapacidad']);
        $emp->setAgencia_empleo($_REQUEST['rbtn_agencia_empleo']);
        $emp->setDesplaza_personal($_REQUEST['rbtn_desplaza_personal']); // 01 Desplaza Personal
        $emp->setTerceros_desplaza_usted($_REQUEST['rbtn_terceros_desplaza_personal']); //02 Terceros desplaza Usted
        $emp->setEstado_empleador('INACTIVO');

//----------------
        //$emp->setEstado_empleador('no-titular');

        $emp->setFecha_creacion(date('Y-m-d'));

        $dao_empleador = new EmpleadorDao();
        $rpta = $dao_empleador->registrarEmpleador($emp);
    } else {
        $rpta = false;
    }//ENDIF

    return $rpta;
}

function editarEmpleador() {

    $emp = new Empleador();

    $emp->setId_empleador($_REQUEST['id_empleador']);
    $emp->setId_tipo_empleador($_REQUEST['cbo_tipo_empleador']);
    $emp->setRuc($_REQUEST['txt_ruc']);
    $emp->setRazon_social($_REQUEST['txt_razon_social']);
    $emp->setId_tipo_sociedad_comercial($_REQUEST['cbo_tipo_sociedad_comercial']);
    $emp->setNombre_comercial($_REQUEST['txt_nombre_comercial']);
    $emp->setCod_tipo_actividad($_REQUEST['cbo_tipo_actividad']);
    $emp->setCod_telefono_codigo_nacional($_REQUEST['cbo_telefono_codigo_nacional']);
    $emp->setTelefono($_REQUEST['txt_telefono']);
    $emp->setCorreo($_REQUEST['txt_correo']);
    $emp->setEmpresa_dedica($_REQUEST['cbo_empresa_dedica']);
    //heree


    $emp->setSenati($_REQUEST['rbtn_senati']);

    $emp->setRemype($_REQUEST['rbtn_remype']);

    //S i Remype es TRUE guarda datos de tipo de remipe.
    if ($emp->getRemype() == 1) {
        $emp->setRemype_tipo_empresa($_REQUEST['rbtn_remype_tipo_empresa']);
    }
    $emp->setTrabajador_sin_rp($_REQUEST['rbtn_sin_rp']);
    $emp->setActividad_riesgo_sctr($_REQUEST['rbtn_actividad_riesgo']);
    $emp->setTrabajadores_sctr($_REQUEST['rbtn_sctr']);
    $emp->setPersona_discapacidad($_REQUEST['rbtn_persona_discapacidad']);
    $emp->setAgencia_empleo($_REQUEST['rbtn_agencia_empleo']);
    $emp->setDesplaza_personal($_REQUEST['rbtn_desplaza_personal']); // 01 Desplaza Personal
    $emp->setTerceros_desplaza_usted($_REQUEST['rbtn_terceros_desplaza_personal']); //02 Terceros desplaza Usted
    //$emp->setEstado_empleador('no-titular');	

    $dao_empleador = new EmpleadorDao();
    $rpta = $dao_empleador->actualizarEmpleador($emp);

    return $rpta;
}

function eliminar($id) {//OK
    $dao = new EmpleadorDao();
    $resp = $dao->eliminarEmpleador($id);
    return $resp;
}

function cargar_tabla() {

    $dao_empleador = new EmpleadorDao();

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

    $count = $dao_empleador->cantidadEmpleadores($WHERE);

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

    $lista = $dao_empleador->listarEmpleadores($WHERE, $start, $limit, $sidx, $sord);

// CONTRUYENDO un JSON
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $responce;  /* break; */
    }


    foreach ($lista as $rec) { /* dato retorna estos datos */

        $param = $rec["id_empleador"];
        $_01 = $rec["ruc"];
        $_02 = $rec["razon_social"];
        $_03 = $rec["nombre_comercial"];
        $_04 = $rec["telefono"];
        $_05 = $rec["nombre_tipo_empleador"];
        $_06 = $rec['tipo'];
        //new
        $onclickEditar = 'onclick="editarProducto(' . $param . ')"';
        $onclickEliminar = 'onclick="eliminarEmpleador(' . $param . ')"';


        $js = "javascript:cargar_pagina('sunat_planilla/view/edit_empleador.php?id_empleador=" . $param . "','#CapaContenedorFormulario')";

        $js2 = "javascript:eliminarEmpleador('" . $param . "')";

        $j3 = "javascript:cargar_pagina('sunat_planilla/view/view_establecimiento.php?id_empleador=" . $param . "','#CapaContenedorFormulario')";



        if($param == ID_EMPLEADOR){
           
                            $opciones = '<div id="divEliminar_Editar">				
				<span  title="Editar" >
				<a href="' . $js . '"><img src="images/edit.png"/></a>
				</span>				
				&nbsp;
				<span  title="Nuevo Establecimiento" >
				<a href="' . $j3 . '"><img src="images/page.png"/></a>
				</span>
				
		</div>';
            
        }else{
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
        
        

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            utf8_encode($opciones),
            $_06
        );

        $i++;
    }

    return $responce;
}

function buscaEmpleadorPorRuc($ruc) {

    $dao_empleador = new EmpleadorDao();

    $data = $dao_empleador->buscaEmpleadorPorRuc($ruc);

    return $data;
    //var_dump($lista);
}

//*******************************
// Validaciones
//*******************************

function existeRucDuplicado($ruc) {
    $dao = new EmpleadorDao();
    $rpta = $dao->existeRucDuplicado($ruc);
    return($rpta);
}

function buscarEmpleadorPorId($id) {
    $empleador = new Empleador();
    $dao = new EmpleadorDao();
    $data = $dao->buscarEmpleadorPorId($id);

    $empleador->setId_empleador($data['id_empleador']);
    $empleador->setId_tipo_empleador($data['id_tipo_empleador']);
    $empleador->setRuc($data['ruc']);
    $empleador->setRazon_social($data['razon_social']);
    $empleador->setId_tipo_sociedad_comercial($data['id_tipo_sociedad_comercial']);
    $empleador->setNombre_comercial($data['nombre_comercial']);
    $empleador->setCod_tipo_actividad($data['cod_tipo_actividad']);
    $empleador->setCod_telefono_codigo_nacional($data['cod_telefono_codigo_nacional']);
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

function existeEmpleadorBD($ruc) {
    $dao = new EmpleadorDao();
    return $dao->existeEmpleadorBD($ruc);
}

?>