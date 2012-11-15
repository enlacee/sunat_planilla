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


    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    //Modelo Direccion    
    require_once '../model/Direccion.php';
    require_once '../model/PersonaDireccion.php';
    require_once '../dao/PersonaDireccionDao.php';

    /*
    //CONTROLLER
    //Categoria Trabajador
    require_once '../controller/CategoriaTrabajadorController.php';

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
    
    */
}


$response = NULL;

if ($op == "cargar_tabla") {
    $response = cargar_tabla();
} elseif ($op == "add") {
    $response = nuevaPersona();
} elseif ($op == "add_persona_newtrabajador") {
    $id_persona_existe = $_REQUEST['id_persona_existe'];
    $response = registrarPersonaAntesRegistrada($id_persona_existe);
} elseif ($op == "edit") {
    $response = editarPersona();
} elseif ($op == "del") {
    $id = $_REQUEST["id_persona"];
    $response = eliminarPersona($id);
} elseif ($op == "04") {
    $codigo = $_REQUEST["codigo"];
    validarCodigoProducto($codigo);
} elseif ($op == "select_codigo") {
    echo cargarSelectCodigo();
} elseif ($op == "listar_provincias") {
//listar provincias
    $response = comboUbigeoProvincias($_REQUEST['id_departamento']);
} elseif ($op == "listar_distritos") {
    $response = comboUbigeoReniec($_REQUEST['id_provincia']);
}

echo (!empty($response)) ? json_encode($response) : '';



function registrarPersonaAntesRegistrada($ID_PERSONA) {

    //PASO 01 = preguntamos si todos los trabajadores estan dados de baja.
    $dao = new personaDao();
    $data = $dao->listarTrabajadoresPor_ID_Persona($ID_PERSONA);

    $countEstado = 0;

    for ($i = 0; $i < count($data); $i++) {
        if ($data[$i][cod_situacion] == 1) {
            $countEstado++;
        }
    }
// hereeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee EROROORROROOROR Y NEW TRABAJADOR
    if ($countEstado == 0) {
        nuevoTrabajador($ID_PERSONA);
        echo "PERSONA NO ESTA EN BAJKAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";
        return 'true';
    } else {
        ECHO "PERSONA TIENE UN TRABAJADOR ACTIVO";
        return 'false';
    }
}

function nuevaPersona() {

    $rpta = null;
    //echo "ID_EMPLEADOR_MAESTRO  = " . ID_EMPLEADOR;
    //echo "ID_EMPLEADOR_MAESTRO 2  = " . $_REQUEST['txt_num_documento'];
    //echo "ID_EMPLEADOR_MAESTRO 3 = " . $_REQUEST['cbo_tipo_documento'];    
    $num_doc = $_REQUEST['txt_num_documento'];
    $cod_tipo_documento = $_REQUEST['cbo_tipo_documento'];

    $dao = new PersonaDao();
    $ID_PERSONA = $dao->existePersonaRegistrada(ID_EMPLEADOR, $num_doc, $cod_tipo_documento);

    if (isset($ID_PERSONA)) {
        $rpta->rpta = false;
        $rpta->mensaje = "Persona ya se encuentra registrado.";
    } else {

        $obj_persona = new Persona();
        $_ID_EMPLEADOR = ($_REQUEST['id_empleador']) ? $_REQUEST['id_empleador'] : ID_EMPLEADOR;

        $obj_persona->setId_empleador($_ID_EMPLEADOR);
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
        $obj_persona->setEstado('ACTIVO');
        $obj_persona->setFecha_creacion(date("Y-m-d H:i:s"));

        //DAO
        $dao_persona = new PersonaDao();
        $ID_PERSONA = $dao_persona->registrarPersona($obj_persona);


        if ($ID_PERSONA) {

            // 2 DIrecciones
            //DAO
            $dao_persona_d = new PersonaDireccionDao();
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

            $rpta->rpta = true;
            $rpta->mensaje = 'Se guardo correctamente';
            $rpta->id = $ID_PERSONA;
        } else {
            $rpta->rpta = false;
            $rpta->mensaje = 'Ocurrio un error';
        }
    }//ENDIF

    return $rpta;
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
    $ESTADO = ($_REQUEST['estado']) ? $_REQUEST['estado'] : 0;
    //$ESTADO = ($_REQUEST['estado'] == 1) ? $_REQUEST['estado'] : null;
    $dao_persona = new PersonaDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; 
    $sord = $_GET['sord'];

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

    $count = $dao_persona->cantidadPesonas($ESTADO, ID_EMPLEADOR_MAESTRO, $WHERE);

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

    $lista = $dao_persona->listarPersonas($ESTADO, ID_EMPLEADOR_MAESTRO, $WHERE, $start, $limit, $sidx, $sord);

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

        $param = $rec["id_persona"];
        $_02 = $rec["nombre_tipo_documento"];
        $_03 = $rec["num_documento"];
        $_04 = $rec["apellido_paterno"];
        $_05 = $rec["apellido_materno"];
        $_06 = $rec["nombres"];
        $_08 = $rec["sexo"];

        if (true):
            //$j = "javascript:cargar_pagina('sunat_planilla/view/add_trabajador.php?id_persona=" . $param . "','#CapaContenedorFormulario')";
            $j = "javascript:addTrabajador('$param')";
            $new = '<div>				
		<span  title="Nuevo Trabajador" >
		<a href="' . $j . '"><img src="images/trabajador.png"/></a>
		</span>
		</div>';
        else:
            $new = null;
        endif;
            


        $js = "javascript:cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona=" . $param . "','#CapaContenedorFormulario')";
        $js2 = "javascript:eliminarPersona('" . $param . "')";

        if ($_09 == 1) {
            $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>
		</div>';
        } else {
            $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>
                </div>';
        }

        //hereee

        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_08,
            $_09,
            $opciones,
            $new
        );

        $i++;
    }

    return $response;
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
 * listar Persona con:
 * Periodos laborados 
 */
function listarPersonaConPeriosdoLaborales($id_empleador_maestro,$id_persona){
        // IDE_EMPLEADOR_MAESTRO    
        
    $dao_persona = new PersonaDao();
    $data = $dao_persona->listarPersonaConPeriodoLaboral($id_empleador_maestro, $id_persona);    
    return $data;
    
}




?>