<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];
if ($op) {
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/PersonaDireccionDao.php';

    //--Herencia Direccion
    require_once '../model/Direccion.php';
    require_once '../model/PersonaDireccion.php';
}
/*
  if(isset ($_REQUEST["datos"])){
  $datos = $_REQUEST["datos"];
  } */

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla($_REQUEST['id_persona']); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "add") {
    $responce = nuevo();
} elseif ($op == "edit") {
    $responce = editarPersonaDireccion();
} elseif ($op == "del") {
    $id = $_REQUEST["id"];
    eliminar($id);
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

//echo count($responce);

echo (!empty($responce)) ? json_encode($responce) : '';
/* * **********categoria*************** */

function nuevo() {

    //$dao = new PersonaDao();
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
    $obj_persona->setEstado_civil($_REQUEST['rbtn_estado_civil']);
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

    //DAO
    $dao = new PersonaDao();
    $id_persona = $dao->registrarPersona($obj_persona);



    // Personas Direcciones
    $obj_per_direccion = new PersonaDireccion();
    $obj_per_direccion->setId_persona($id_persona);
    $obj_per_direccion->setCod_ubigeo_reinec($_REQUEST['cbo_distrito']);
    $obj_per_direccion->setCod_via($_REQUEST['cbo_tipo_via']);
    $obj_per_direccion->setNombre_via($_REQUEST['txt_nombre_via']);
    $obj_per_direccion->setNumero_via($_REQUEST['txt_numero_via']);
    $obj_per_direccion->setDepartamento($_REQUEST['txt_dpto']);
    $obj_per_direccion->setInterior($_REQUEST['txt_interior']);
    $obj_per_direccion->setManzanza($_REQUEST['txt_manzana']);
    $obj_per_direccion->setLote($_REQUEST['txt_lote']);
    $obj_per_direccion->setKilometro($_REQUEST['txt_kilometro ']);
    $obj_per_direccion->setBlock($_REQUEST['txt_block']);
    $obj_per_direccion->setEstapa($_REQUEST['txt_etapa']);
    $obj_per_direccion->setCod_zona($_REQUEST['cbo_tipo_zona']);
    $obj_per_direccion->setNombre_zona($_REQUEST['txt_zona']);
    $obj_per_direccion->setReferencia($_REQUEST['txt_referencia']);
    $obj_per_direccion->setReferente_essalud($_REQUEST['rbtn_ref_essalud']);


    //DAO
    $dao_d = new PersonaDireccionDao();
    $rpta = $dao_d->registrarPersonaDireccion($obj_per_direccion);

    return $rpta;
}

function editarPersonaDireccion() {


    $obj = new PersonaDireccion(); //16
    $obj->setId_persona_direccion($_REQUEST['id_persona_direccion']);
    $obj->setId_persona($_REQUEST['id_persona']);
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


    $obj->setReferente_essalud($_REQUEST['rbtn_ref_essalud']); //boolean    
    $obj->setEstado_direccion($_REQUEST['txt_estado_direccion']);

    //obj direccion estasdo = 1

    $dao = new PersonaDireccionDao();
    $rpta = $dao->actualizarPersonaDireccion($obj);

    if ($obj->getEstado_direccion() == '2') {
        if ($obj->getReferente_essalud() == '0') {
            //DIRECCION 1 ES = Referente para Centro Asistencia EsSalud:
            // direccion 1
            // direccion 2            
            $dao->actualizarEstadoReferenteEsalud($obj->getId_persona(), 1, 1);
        } else {
            $dao->actualizarEstadoReferenteEsalud($obj->getId_persona(), 1, 0);
        }
    }

    return $rpta;
    ;
}

/* * *
 *
 * *
 */

function cargar_tabla($id_persona) {

    $dao = new PersonaDireccionDao();

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

    $count = $dao->cantidadPesonaDireccionesConIdPersona($id_persona);

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

    //$dao->actualizarStock();

    $lista = $dao->listarPersonaDirecciones($id_persona, $WHERE, $start, $limit, $sidx, $sord);

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

        if ($rec['estado_direccion'] == 1) {
            $estado_direccion = 'Primera';
        } else if ($rec['estado_direccion'] == 2) {
            $estado_direccion = 'Segunda';
        }

        $param = $rec["id_persona_direccion"];
        $id_persona = $rec['id_persona'];
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

        $ubigeo_departamento = str_replace('DEPARTAMENTO', '', $rec['ubigeo_departamento']);
        $ubigeo_provincia = $rec['ubigeo_provincia'];
        $ubigeo_distrito = $rec['ubigeo_distrito'];


        $js = "javascript:editarPersonaDireccion('" . $param . "')";

        $opciones = '<div id="divEliminar_Editar">				
				<span  title="Editar" >
				<a href="' . $js . '"><img src="images/edit.png"/></a>
				</span>				
				&nbsp;
				</div>';
        //hereee
        //---------------Inicio Cadena String----------//
        $cadena = '';

        $cadena .= ($ubigeo_nombre_via != "-") ? $ubigeo_nombre_via : '';
        $cadena .= (!empty($nombre_via)) ? $nombre_via : '';
        $cadena .= (!empty($numero_via)) ? $numero_via : '';

        $cadena .= ($ubigeo_nombre_zona != "-") ? $ubigeo_nombre_zona : '';
        $cadena .= (!empty($nombre_zona)) ? $nombre_zona : '';
        $cadena .= (!empty($etapa)) ? $etapa : '';

        $cadena .= (!empty($manzana)) ? 'MZA. ' . $manzana : '';
        $cadena .= (!empty($blok)) ? $blok : '';
        $cadena .= (!empty($etapa)) ? $etapa : '';
        $cadena .= (!empty($lote)) ? 'LOTE. ' . $lote : '';

        $cadena .= (!empty($departamento)) ? $departamento : '';
        $cadena .= (!empty($interior)) ? $interior : '';
        $cadena .= (!empty($kilometro)) ? $kilometro : '';

        $cadena .= ($ubigeo_departamento != "-") ? $ubigeo_departamento."-" : '';
        $cadena .= ($ubigeo_provincia != "-") ? $ubigeo_provincia."-" : '';
        $cadena .= ($ubigeo_distrito != "-") ? $ubigeo_distrito : '';

        $cadena = strtoupper($cadena);

        //---------------Inicio Cadena String----------//


        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $id_persona,
            $cadena,
            $estado_direccion,
            $opciones
        );

        $i++;
    }

    return $responce;  //RETORNO A intranet.js
}

//Buscar PersonaDirecion Por ID
//buscar Persona Direccion

function buscarPersonaDireccionPorId($id) {

    $obj = new PersonaDireccion();
    $dao = new PersonaDireccionDao();
    $data = $dao->buscarPersonaDireccionPorId($id);

    $obj->setId_persona_direccion($data['id_persona_direccion']);
    $obj->setId_persona($data['id_persona']);
    $obj->setCod_ubigeo_reinec($data['cod_ubigeo_reniec']);
    $obj->setCod_via($data['cod_via']);
    $obj->setNombre_via($data['nombre_via']);
    $obj->setNumero_via($data['numero_via']);
    $obj->setDepartamento($data['departamento']);
    $obj->setInterior($data['interior']);
    $obj->setManzanza($data['manzana']);
    $obj->setLote($data['lote']);
    $obj->setKilometro($data['kilometro']);
    $obj->setBlock($data['block']);
    $obj->setEstapa($data['etapa']);
    $obj->setCod_zona($data['cod_zona']);
    $obj->setNombre_zona($data['nombre_zona']);
    $obj->setReferencia($data['referencia']);
    $obj->setReferente_essalud($data['referente_essalud']);
    $obj->setEstado_direccion($data['estado_direccion']);

    return $obj;
}

?>