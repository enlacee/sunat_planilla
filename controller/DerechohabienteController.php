<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];

if ($op) {
    session_start();    

    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/DerechohabienteDao.php';
    require_once '../model/Derechohabiente.php';
//DIRECCION
    require_once '../dao/DerechohabienteDireccionDao.php';
    require_once '../model/Direccion.php';
    require_once '../model/DerechohabienteDireccion.php';

//IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    
}

$responce = NULL;

if ($op == "cargar_tabla") {

    $responce = cargar_tabla($_REQUEST['id_persona']); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "add") {
    $responce = nuevoDerechohabiente();
} elseif ($op == "edit") {
    $responce = editar();
} elseif ($op == "del") {
    $responce = eliminar($_REQUEST["id"]);
} elseif ($op == "term") {
    $tipodoc = $_REQUEST['cbo_tipo_documento'];
    $id_empleador = $_REQUEST['id_empleador'];
    $term = $_REQUEST['term'];
    $responce = autocomplete($id_empleador, $term);
} elseif ($op == "buscar") {
    $tipodoc = $_REQUEST['tipo_documento'];
    $num_documento = $_REQUEST['num_documento'];
    $id_empleador_maestro = ID_EMPLEADOR_MAESTRO;

  $responce =  buscarDerechoHabienteNumDocumento($tipodoc, $id_empleador_maestro, $num_documento );


} elseif ($op == "baja") {
    $id = $_REQUEST['id'];
    $responce = baja($id);
} elseif ($op == "04") {
    $codigo = $_REQUEST["codigo"];
    validarCodigoProducto($codigo);
} elseif ($op == "select_codigo") {
    echo cargarSelectCodigo();
} else {
   // echo "<p>variable OPER no esta definido</p>";
}


echo (!empty ($responce)) ? json_encode($responce) : '';
/* * **********categoria*************** */

function nuevoDerechohabiente() {

    $obj_dh = new Derechohabiente();

    $obj_dh->setId_persona($_REQUEST['id_persona']);
    $obj_dh->setCod_tipo_documento($_REQUEST['cbo_tipo_documento']);
    $obj_dh->setNum_documento($_REQUEST['txt_num_documento']);
    $obj_dh->setFecha_nacimiento(getFechaPatron($_REQUEST['txt_fecha_nacimiento'], "Y-m-d"));
    $obj_dh->setCod_pais_emisor_documentos($_REQUEST['cbo_pais_emisor_documento']);
    $obj_dh->setApellido_paterno($_REQUEST['txt_apellido_paterno']);
    $obj_dh->setApellido_materno($_REQUEST['txt_apellido_materno']);
    $obj_dh->setNombres($_REQUEST['txt_nombre']);
    $obj_dh->setSexo($_REQUEST['rbtn_sexo']);
    $obj_dh->setId_estado_civil($_REQUEST['cbo_estado_civil']);
    //$obj_dh->setCod_nacionalidad($_REQUEST['cbo_Nacionalidad']);

    $obj_dh->setCod_vinculo_familiar($_REQUEST['cbo_vinculo_familiar']);
    $obj_dh->setCod_documento_vinculo_familiar($_REQUEST['cbo_documento_vinculo_familiar']);
    $obj_dh->setVf_num_documento($_REQUEST['txt_vf_num_documento']);
    $obj_dh->setVf_mes_concepcion($_REQUEST['txt_vf_mes_concepcion']);

    $cod_telefono_nac = ( empty($_REQUEST['cbo_telefono_codigo_nacional']) ) ? 0 : $_REQUEST['cbo_telefono_codigo_nacional'];
    $obj_dh->setCod_telefono_codigo_nacional($cod_telefono_nac);
    $obj_dh->setTelefono($_REQUEST['txt_telefono']);
    $obj_dh->setCorreo($_REQUEST['txt_correo_electronico']);

    $obj_dh->setCod_motivo_baja_derechohabiente(0);
    $obj_dh->setCod_situacion($_REQUEST['cbo_situacion']);
    $obj_dh->setEstado('INACTIVO');
    $obj_dh->setFecha_creacion(date("Y-m-d H:i:s"));

    //DAO 1
    $dao_dh = new DerechohabienteDao();
    $id_derechohabiente = $dao_dh->registrarDerechohabiente($obj_dh);

    //DAO 2
    $dao_dh_direccion = new DerechohabienteDireccionDao();

    for ($i = 0; $i < 1; $i++) {
        if ($i == 0) {
            // Personas CON DIRECCION (01) Principal 
            $obj_per_direccion = new DerechohabienteDireccion();
            $obj_per_direccion->setId_derechohabiente($id_derechohabiente);
            $obj_per_direccion->setCod_ubigeo_reinec(0);
            $obj_per_direccion->setCod_via(0);
            $obj_per_direccion->setCod_zona(0);
            $obj_per_direccion->setNombre_zona('');
            $obj_per_direccion->setReferencia('');
            $obj_per_direccion->setEstado_direccion(1);

            //DAO
            $dao_dh_direccion->registrarDerechohabienteDireccion($obj_per_direccion);
        }
        // Personas Direcciones
        $obj_per_direccion = new DerechohabienteDireccion();
        $obj_per_direccion->setId_derechohabiente($id_derechohabiente);
        $obj_per_direccion->setCod_ubigeo_reinec(0);
        $obj_per_direccion->setCod_via(0);
        $obj_per_direccion->setCod_zona(0);
        $obj_per_direccion->setEstado_direccion(2);

        //DAO
        $dao_dh_direccion->registrarDerechohabienteDireccion($obj_per_direccion);
    }

    //--RPTA
    $rpta['id_derechohabiente'] = $id_derechohabiente;

    return $rpta;
}

function editar() {

    $obj = new Derechohabiente();
    $dao_dh = new DerechohabienteDao();

    $obj->setId_derechohabiente($_REQUEST['id_derechohabiente']);
    $obj->setId_persona($_REQUEST['id_persona']);
    $obj->setCod_tipo_documento($_REQUEST['cbo_tipo_documento']);
    $obj->setCod_pais_emisor_documentos($_REQUEST['cbo_pais_emisor_documento']);
    $obj->setNum_documento($_REQUEST['txt_num_documento']);
    $obj->setFecha_nacimiento($_REQUEST['txt_fecha_nacimiento']);
    $obj->setApellido_paterno($_REQUEST['txt_apellido_paterno']);
    $obj->setApellido_materno($_REQUEST['txt_apellido_materno']);
    $obj->setNombres($_REQUEST['txt_nombre']);
    $obj->setSexo($_REQUEST['rbtn_sexo']);
    $obj->setId_estado_civil($_REQUEST['cbo_estado_civil']);

    $obj->setCod_vinculo_familiar($_REQUEST['cbo_vinculo_familiar']);
    $obj->setCod_documento_vinculo_familiar($_REQUEST['cbo_documento_vinculo_familiar']);
    $obj->setVf_num_documento($_REQUEST['txt_vf_num_documento']);
    $obj->setVf_mes_concepcion($_REQUEST['txt_vf_mes_concepcion']);

    $obj->setCod_telefono_codigo_nacional($_REQUEST['cbo_telefono_codigo_nacional']);
    $obj->setTelefono($_REQUEST['txt_telefono']);
    $obj->setCorreo($_REQUEST['txt_correo_electronico']);

    //$obj->setCod_motivo_baja_derechohabiente($cod_motivo_baja_derechohabiente);
    $obj->setCod_situacion($_REQUEST['cbo_situacion']);
    //$obj->setEstado($_REQUEST['txt_correo_electronico']);
    //$obj->setFecha_baja($fecha_baja);
    //$obj->setFecha_creacion($fecha_creacion);
    //???--!
    return $dao_dh->actualizar($obj);
}

function cargar_tabla($id_persona) {

    $dao_dh = new DerechohabienteDao();

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

    $count = $dao_dh->cantidadDH($id_persona, $WHERE);

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

    //$dao_dh->actualizarStock();

    $lista = $dao_dh->listarDH($id_persona, $WHERE, $start, $limit, $sidx, $sord);



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

        $param = $rec["id_derechohabiente"];
        $_01 = $rec["id_persona"];
        $_02 = $rec["nombre_documento"];
        $_03 = $rec["num_documento"];
        $_04 = $rec["apellido_paterno"];
        $_05 = $rec["apellido_materno"];
        $_06 = $rec["nombres"];
        $_07 = $rec["fecha_nacimiento"];
        $_08 = $rec['nombre_vinculo_familiar'];
        $_09 = $rec['nombre_situacion'];

        //new

        $js = "javascript:cargar_pagina('sunat_planilla/view/edit_derechohabiente.php?&id_derechohabiente=" . $param . "','#CapaContenedorFormulario')";

        $js2 = "javascript:eliminarDerechohabiente('" . $param . "')";

        $js3 = "javascript:bajaDerechohabiente('" . $param . "')";

        $opciones = '<div id="divEliminar_Editar">				
				<span  title="Editar" >
				<a href="' . $js . '"><img src="images/edit.png"/></a>
				</span>				
				&nbsp;
				<span  title="Cancelar" >
				<a href="' . $js2 . '"><img src="images/cancelar.png"/></a>
				</span>
		</div>';
        //hereee
        /*
          &nbsp;
          <span  title="Dar Baja" >
          <a href="'.$js3.'"><img src="images/baja.png"/></a>
          </span>

         */

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
            utf8_encode($opciones)
        );

        $i++;
    }

    return $responce;  //RETORNO A intranet.js
}

//----
function buscarDerechohabientePorId($id_dh) {

    $dao = new DerechohabienteDao();
    $data = $dao->buscarDerechohabientePorId($id_dh);

    $obj_persona = new Derechohabiente();

    $obj_persona->setId_derechohabiente($data['id_derechohabiente']);
    $obj_persona->setId_persona($data['id_persona']);
    $obj_persona->setCod_tipo_documento($data['cod_tipo_documento']);
    $obj_persona->setCod_pais_emisor_documentos($data['cod_pais_emisor_documento']);
    $obj_persona->setCod_vinculo_familiar($data['cod_vinculo_familiar']);
    $obj_persona->setCod_documento_vinculo_familiar($data['cod_documento_vinculo_familiar']);
    $obj_persona->setNum_documento($data['num_documento']);
    $obj_persona->setFecha_nacimiento($data['fecha_nacimiento']);
    $obj_persona->setApellido_paterno($data['apellido_paterno']);
    $obj_persona->setApellido_materno($data['apellido_materno']);
    $obj_persona->setNombres($data['nombres']);
    $obj_persona->setSexo($data['sexo']);
    $obj_persona->setId_estado_civil($data['id_estado_civil']);
    $obj_persona->setVf_num_documento($data['vf_num_documento']);
    $obj_persona->setVf_mes_concepcion($data['vf_mes_concepcion']);
    $obj_persona->setCod_telefono_codigo_nacional($data['cod_telefono_codigo_nacional']);
    $obj_persona->setTelefono($data['telefono']);
    $obj_persona->setCorreo($data['correo']);
    $obj_persona->setCod_motivo_baja_derechohabiente($data['cod_motivo_baja_derechohabiente']);
    $obj_persona->setCod_situacion($data['cod_situacion']);
    $obj_persona->setEstado($data['estado']);
    $obj_persona->setFecha_baja($data['fecha_baja']);

    return $obj_persona;
}

/**
 *   -----------------------------------------------------------------------------------------
 * 	FUNCIONES COMBO_BOX
 * 	-----------------------------------------------------------------------------------------
 * */
//OJO oPTIMIZAR  crear clases!!!! 



function eliminar($id) {//OK
    $dao = new DerechohabienteDao();
    $resp = $dao->eliminaDH($id);
    return $resp;
}

function baja($id) {//OK
    $dao = new DerechohabienteDao();
    $resp = $dao->bajaDH($id);
    return $resp;
}

//-----------------------------------------
function autocomplete($id_empleador, $term) {
    $dao = new DerechohabienteDao();
    $resp = $dao->autocomplete($id_empleador, $tipodoc, $term);
    return $resp;
}




function buscarDerechoHabienteNumDocumento($tipodoc, $id_empleador_maestro, $num_documento ){
    
    $dao = new DerechohabienteDao();
    $data =  $dao->buscarDerechoHabienteNumDocumento($tipodoc, $id_empleador_maestro, $num_documento);       
    
    return $data['id_persona'];
    
}
?>