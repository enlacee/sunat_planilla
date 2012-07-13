<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';

    require_once '../dao/AbstractDao.php';
    require_once '../dao/PersonaDao.php';
    require_once '../model/Persona.php';
    //Modelo Direccion
    require_once '../model/Direccion.php';
    require_once '../model/PersonaDireccion.php';
    require_once '../dao/PersonaDireccionDao.php';

    //Categoria Trabajador
    require_once '../model/Trabajador.php';
    require_once '../dao/TrabajadorDao.php';
    //sub 01
    require_once '../model/DetallePeriodoLaboral.php';
    require_once '../dao/DetallePeriodoLaboralDao.php';
    //sub 02
    require_once '../model/DetalleTipoTrabajador.php';
    require_once '../dao/DetalleTipoTrabajadorDao.php';
    //sub 03
    require_once '../model/DetalleEstablecimiento.php';
    require_once '../dao/DetalleEstablecimientoDao.php';
    //sub 04
    require_once '../model/DetalleRegimenSalud.php';
    require_once '../dao/DetalleRegimenSaludDao.php';
    //sub 05
    require_once '../model/DetalleRegimenPensionario.php';
    require_once '../dao/DetalleRegimenPensionarioDao.php';

    //Categoria Pensionista ----------------------------------------------------
    require_once '../model/Pensionista.php';
    require_once '../dao/PensionistaDao.php';
    // require_once '../controller/CategoriaPensionistaControlller.php';
    //dub 01
    require_once '../model/DetallePeriodoLaboral.php';
    require_once '../model/DetallePeriodoLaboralPensionista.php';
    require_once '../dao/DetallePeriodoLaboralPensionistaDao.php';

    //Categoria Personal en  Formacion Laboral --------------------------------------
    require_once '../model/PersonaFormacionLaboral.php';
    require_once '../dao/PersonaFormacionLaboralDao.php';
    //require_once '../controller/CategoriaPFormacionController.php';
    //establecimiento de formacion
    require_once '../model/DetalleEstablecimientoFormacion.php';
    require_once '../dao/DetalleEstablecimientoFormacionDao.php';
    //periodo laboral 
    require_once '../model/DetallePeriodoFormativo.php';
    require_once '../dao/DetallePeriodoFormativoDao.php';

    //---- Categoria Persona de Terceros --------------------------------------
    require_once '../model/personaTercero.php';
    require_once '../dao/PersonaTerceroDao.php';

    //sub 01 
    require_once '../model/PeriodoDestaque.php';
    require_once '../dao/PeriodoDestaqueDao.php';

    //sub 02
    require_once '../model/LugarDestaque.php';
    require_once '../dao/LugarDestaqueDao.php';

    //sub 03
    require_once '../model/CoberturaSalud.php';
    require_once '../dao/CoberturaSaludDao.php';

    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
}


$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla(); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} else if ($op == "cargar_tabla2") {
    $responce = cargar_tabla_2();
} elseif ($op == "add") {
    $responce = nuevaPersona();
} elseif ($op == "edit") {
    $responce = editarPersona();
} elseif ($op == "del") {
    $id = $_REQUEST["id_persona"];
    $responce = eliminarPersona($id);
} elseif ($op == "04") {
    $codigo = $_REQUEST["codigo"];
    validarCodigoProducto($codigo);
} elseif ($op == "select_codigo") {
    echo cargarSelectCodigo();
} elseif ($op == "listar_provincias") {
//listar provincias
    $responce = comboUbigeoProvincias($_REQUEST['id_departamento']);
} elseif ($op == "listar_distritos") {

    $responce = comboUbigeoReniec($_REQUEST['id_provincia']);
} else {
    // echo "oper INCORRECTO";
}


echo (!empty($responce)) ? json_encode($responce) : '';
/* * **********categoria*************** */

function nuevaPersona() {

    if (existePersonaBD($_REQUEST['txt_num_documento'])) {
        return false;
    }
    //$dao_persona = new PersonaDao();
    //$val=validarCodigoProducto($codigo);
    //if($val){
    $obj_persona = new Persona();
    $obj_persona->setId_empleador($_REQUEST['id_empleador']);
    $obj_persona->setCod_tipo_documento($_REQUEST['cbo_tipo_documento']);
    $obj_persona->setNum_documento($_REQUEST['txt_num_documento']);
    $obj_persona->setFecha_nacimiento(getFechaPatron($_REQUEST['txt_fecha_nacimiento'], "Y-m-d"));
    $obj_persona->setCod_pais_emisor_documentos($_REQUEST['cbo_pais_emisor_documento']);
    $obj_persona->setApellido_paterno($_REQUEST['txt_apellido_paterno']);
    $obj_persona->setApellido_materno($_REQUEST['txt_apellido_materno']);
    $obj_persona->setNombres($_REQUEST['txt_nombre']);
    $obj_persona->setSexo($_REQUEST['rbtn_sexo']);
    $obj_persona->setId_estado_civil($_REQUEST['cbo_estado_civil']);
    $obj_persona->setCod_nacionalidad($_REQUEST['cbo_Nacionalidad']);

    $cod_telefono_nac = ( empty($_REQUEST['cbo_telefono_codigo_nacional']) ) ? 0 : $_REQUEST['cbo_telefono_codigo_nacional'];
    $obj_persona->setCod_telefono_codigo_nacional($cod_telefono_nac);
    $obj_persona->setTelefono($_REQUEST['txt_telefono']);
    $obj_persona->setCorreo($_REQUEST['txt_correo_electronico']);

    $obj_persona->setTabla_trabajador(0);
    $obj_persona->setTabla_pensionista(0);
    $obj_persona->setTabla_personal_formacion_laboral(0);
    $obj_persona->setTabla_personal_terceros(0);
    $obj_persona->setEstado('ACTIVO');
    $obj_persona->setFecha_creacion(date("Y-m-d H:i:s"));

    //TODOS los Prestadores por DEFAUTL SE REGISTRARN COMO TRABAJADOR
    $obj_persona->setTabla_trabajador(1);


    //DAO
    $dao_persona = new PersonaDao();
    $ID_PERSONA = $dao_persona->registrarPersona($obj_persona);

    //DAO
    $dao_persona_d = new PersonaDireccionDao();
    // 2 DIrecciones
    for ($i = 0; $i < 1; $i++) {
        if ($i == 0) {
            // Personas CON DIRECCION (01) Principal 
            $obj_per_direccion = new PersonaDireccion();
            $obj_per_direccion->setId_persona($ID_PERSONA);
            $obj_per_direccion->setCod_ubigeo_reinec(0);
            $obj_per_direccion->setCod_via(0);
            $obj_per_direccion->setCod_zona(0);
            $obj_per_direccion->setEstado_direccion(1);
            
            //DEFAUL ESTABLECIDO DIRECCION PARA ESALUD
            $obj_per_direccion->setReferente_essalud(1);
            //DAO
            $dao_persona_d->registrarPersonaDireccion($obj_per_direccion);
        }
        // Personas CON DIRECCION (02) Secundario 
        $obj_per_direccion = new PersonaDireccion();
        $obj_per_direccion->setId_persona($ID_PERSONA);
        $obj_per_direccion->setCod_ubigeo_reinec(0);
        $obj_per_direccion->setCod_via(0);
        $obj_per_direccion->setCod_zona(0);
        $obj_per_direccion->setEstado_direccion(2);

        //DAO
        $dao_persona_d->registrarPersonaDireccion($obj_per_direccion);
    }


    //$rpta['id_persona'] = $ID_PERSONA;
//---------------------------------------------------------------------------------
//------------------ INICIO Registrar TRABAJADOR  ---------------------------------
//     

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


    $dao1 = new DetallePeriodoLaboralDao();
    $dao1->registrarDetallePeriodoLaboral($obj1);

    //--- sub (2) Tipo trabajador
    $obj2 = new DetalleTipoTrabajador();
    $obj2->setCod_tipo_trabajador(0);
    $obj2->setId_trabajador($ID_TRA);

    $dao2 = new DetalleTipoTrabajadorDao();
    $dao2->registrarDetalleTipoTrabajador($obj2);

    //--- sub (3) Tipo trabajador
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

    $dao4 = new DetalleRegimenSaludDao();
    $dao4->registrarDetalleRegimenSalud($obj4);

    //--- sub(5) Regimen Pensionario
    $obj5 = new DetalleRegimenPensionario();
    $obj5->setId_trabajador($ID_TRA);
    $obj5->setCod_regimen_pensionario(0);

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





    //RETURN
    return $rpta['id_persona'] = $ID_PERSONA;
}

function editarPersona() {

    $dao_persona = new PersonaDao();

    $obj_persona = new Persona();

    $obj_persona->setId_persona($_REQUEST['id_persona']);
    $obj_persona->setCod_tipo_documento($_REQUEST['cbo_tipo_documento']);
    $obj_persona->setNum_documento($_REQUEST['txt_num_documento']);
    $obj_persona->setFecha_nacimiento(getFechaPatron($_REQUEST['txt_fecha_nacimiento'], "Y-m-d"));
    $obj_persona->setCod_pais_emisor_documentos($_REQUEST['cbo_pais_emisor_documento']);

    $obj_persona->setApellido_paterno($_REQUEST['txt_apellido_paterno']);
    $obj_persona->setApellido_materno($_REQUEST['txt_apellido_materno']);
    $obj_persona->setNombres($_REQUEST['txt_nombre']);
    $obj_persona->setSexo($_REQUEST['rbtn_sexo']);
    $obj_persona->setId_estado_civil($_REQUEST['cbo_estado_civil']);

    $obj_persona->setCod_nacionalidad($_REQUEST['cbo_Nacionalidad']);
    $obj_persona->setCod_telefono_codigo_nacional($_REQUEST['cbo_telefono_codigo_nacional']);
    $obj_persona->setTelefono($_REQUEST['txt_telefono']);
    $obj_persona->setCorreo($_REQUEST['txt_correo_electronico']);
    $obj_persona->setFecha_modificacion(date("Y-m-d"));
//    echo "<pre>";
//    print_r($obj_persona);
//    echo "</pre>";
    $resp = $dao_persona->actualizarPersona($obj_persona);

    return $resp;
}

function eliminarPersona($id) {

    $dao_persona = new PersonaDao();
    $rpta = $dao_persona->eliminarPersona($id);

    return $rpta;
}

function cargar_tabla() {

    $dao_persona = new PersonaDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction


    if ($_REQUEST['estado']) {
        // $estado = "WHERE p.estado = '" . $_REQUEST['estado'] . "' ";
        $WHERE = $estado;
    }


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

    $count = $dao_persona->cantidadPesonas(ID_EMPLEADOR_MAESTRO, $WHERE);

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

    //$dao_persona->actualizarStock();

    $lista = $dao_persona->listarPersonas(ID_EMPLEADOR_MAESTRO, $WHERE, $start, $limit, $sidx, $sord);

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

        $param = $rec["id_persona"];
        $_01 = $string_cat;
        $_02 = $rec["nombre_tipo_documento"];
        $_03 = $rec["num_documento"];
        $_04 = $rec["apellido_paterno"];
        $_05 = $rec["apellido_materno"];

        $_06 = $rec["nombres"];
        $_07 = $rec["fecha_nacimiento"];
        $_08 = $rec["sexo"];
        $_09 = $rec["estado"];

        $_10 = $rec['cod_situacion'];

        $js = "javascript:cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona=" . $param . "','#CapaContenedorFormulario')";
        $js2 = "javascript:eliminarPersona('" . $param . "')";

        if ($_10 == 1) {
            $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>				
		&nbsp;
		<!-- <span  title="Cancelar" >
		<a href="' . $js2 . '"><img src="images/cancelar.png"/></a>
		</span>	-->
		</div>';
        } else {
            $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>
                </div>';
        }

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

function buscarPersonaPorId($ID_PERSONA) {

    $dao = new PersonaDao();
    $data = $dao->buscarPersonaPorId($ID_PERSONA);

    $obj_persona = new Persona();
    $obj_persona->setId_persona($data['id_persona']);
    $obj_persona->setId_empleador($data['id_empleador']);
    $obj_persona->setCod_pais_emisor_documentos($data['cod_pais_emisor_documento']);
    $obj_persona->setCod_tipo_documento($data['cod_tipo_documento']);
    $obj_persona->setNum_documento($data['num_documento']);
    $obj_persona->setFecha_nacimiento($data['fecha_nacimiento']);
    $obj_persona->setCod_nacionalidad($data['cod_nacionalidad']);
    $obj_persona->setApellido_paterno($data['apellido_paterno']);
    $obj_persona->setApellido_materno($data['apellido_materno']);

    $obj_persona->setNombres($data['nombres']);
    $obj_persona->setSexo($data['sexo']);
    $obj_persona->setId_estado_civil($data['id_estado_civil']);
    $obj_persona->setCod_telefono_codigo_nacional($data['cod_telefono_codigo_nacional']);
    $obj_persona->setTelefono($data['telefono']);
    $obj_persona->setCorreo($data['correo']);

    $obj_persona->setTabla_trabajador($data['tabla_trabajador']);
    $obj_persona->setTabla_pensionista($data['tabla_pensionista']);
    $obj_persona->setTabla_personal_formacion_laboral($data['tabla_personal_formacion_laboral']);
    $obj_persona->setTabla_personal_terceros($data['tabla_personal_terceros']);

    $obj_persona->setEstado($data['estado']);
    $obj_persona->setFecha_creacion($data['fecha_creacion']);
    $obj_persona->setFecha_modificacion($data['fecha_modificacion']);
    $obj_persona->setFecha_baja($data['fecha_baja']);
    return $obj_persona;
}

/**
 *   -----------------------------------------------------------------------------------------
 * 	FUNCIONES COMBO_BOX
 * 	-----------------------------------------------------------------------------------------
 * */
//OJO oPTIMIZAR  crear clases!!!! 

function existePersonaBD($num_doc) {
    $dao = new PersonaDao();
    return $dao->existePersonaBD($num_doc);
}

?>