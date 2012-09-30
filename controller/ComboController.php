<?php
//session_start();
//header("Content-Type: text/html; charset=utf-8");
$op = $_REQUEST["oper"];

if ($op) {  // ES NEDD XQ SINO SE DUPLICAN LOS REQUIRE!!!
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/ComboDao.php';
    require_once '../dao/EstablecimientoDao.php';
//    require_once '../model/Persona.php';
}

$responce = null;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla(); /*     * *** DATOS ARRAY guardados AKIIIIIIII ** */
} elseif ($op == "listar_provincias") {
//listar provincias
    $responce = comboUbigeoProvincias($_REQUEST['id_departamento']);
} elseif ($op == "listar_distritos") {
    $responce = comboUbigeoReniec($_REQUEST['id_provincia']);
} elseif ($op == 'listar_doc_vinculosf') {
    $responce = comboDocumentoVinculoFamiliar($_REQUEST['id']);
}

echo (!empty($responce)) ? json_encode($responce) : '';

/**
 *   -----------------------------------------------------------------------------------------
 * 	FUNCIONES COMBO_BOX
 * 	-----------------------------------------------------------------------------------------
 * */
//OJO oPTIMIZAR  crear clases!!!! 


function comboTipoDocumento() {

    $dao_persona = new ComboDao();
    $arreglo = $dao_persona->comboTipoDocumento();

    //ID A ELIMINAR
    $id = array('11','06');
    $counteo = count($id);
    for ($i = 0; $i < $counteo; $i++) {
        //------------------------------------------
        foreach ($arreglo as $indice) {
            if ($id[$i] == $indice['cod_tipo_documento']) {
                //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                unset($id[$i]);
                //Elimina _arreglo GET indice Primero				
                $clave = array_search($indice, $arreglo);
                unset($arreglo[$clave]);
                break;
            }
        }
    }

    return $arreglo;
}

function comboPaisEmisorDocumento() {

    $dao_persona = new ComboDao();
    $data = $dao_persona->comboPaisEmisorDocumento();

    if ($data) {
        return $data;
    } else {
        return "Error Server";
    }
}

function comboTelefonoCodigoNacional() {

    $dao_persona = new ComboDao();
    $data = $dao_persona->comboTelefonoCodigoNacional();

    if ($data) {
        return $data;
    } else {
        return "Error Server";
    }
}

function comboNacionalidades() {

    $dao_persona = new ComboDao();
    $data = $dao_persona->comboNacionalidades();

    if ($data) {
        return $data;
    } else {
        return "Error Server";
    }
}

/**
 *
 * */
function comboUbigeoDepartamentos() {

    $dao_persona = new ComboDao();
    $data = $dao_persona->comboUbigeoDepartamentos();

    if ($data) {

        $i = 0;
        $lista = array();
        foreach ($data as $indice) {
            $search = "DEPARTAMENTO";
            $cadena = str_replace($search, '', $indice['descripcion']);
            $lista[$i]['cod_departamento'] = $indice['cod_departamento'];
            $lista[$i]['descripcion'] = $cadena;
            $i++;
        }
        return $lista;
    } else {
        return "Error Server";
    }
}

/**
 *
 * */
function comboUbigeoProvincias($id_departamento = null) { //HERE variable alternativo
    $dao_persona = new ComboDao();
    $data = $dao_persona->comboUbigeoProvincias($id_departamento);
    return $data;
}

/**
 * tabla = ubigeo_reniec -> Distritos 
 * */
function comboUbigeoReniec($id_provincia = null) { //HERE variable alternativo
    $dao_persona = new ComboDao();
    $data = $dao_persona->comboUbigeoReniec($id_provincia);
    return $data;
}

function comboEstadosCiviles() {
    $dao_persona = new ComboDao();
    $data = $dao_persona->comboEstadosCiviles();

    if ($data) {
        return $data;
    } else {
        return "Error Server";
    }
}

/**
 *   ---------------------------------------------------------------------------------
 * 	Formulario 2 Direccion
 *   ---------------------------------------------------------------------------------
 * */
function comboVias() {

    $dao_persona = new ComboDao();
    return $data = $dao_persona->comboVias();
}

function comboZonas() {

    $dao_persona = new ComboDao();
    return $data = $dao_persona->comboZonas();
}

/**
 * ---------------------------------------------------------------------------------------------
 * 	Formulario Combo box Derechohabientes
 * ---------------------------------------------------------------------------------------------
 * */
function comboVinculoFamiliar() {

    $dao_persona = new ComboDao();
    return $data = $dao_persona->comboVinculoFamiliar();
}

function comboDocumentoVinculoFamiliar($id) {

    $dao_persona = new ComboDao();
    return $data = $dao_persona->comboDocumentoVinculoFamiliar($id);
}

/**
 * ---------------------------------------------------------------------------------------------
 * 	 Combo box Establecimiento
 * ---------------------------------------------------------------------------------------------
 * */
function comboEstablecimiento($id_empleador, $edit = false) {
    $dao_establecimiento = new EstablecimientoDao();

    $num = $dao_establecimiento->numeroDeEstablecimientoFISCAL($id_empleador); //RETORNA TRUE SI YA ESTA REGISTRADO!

    $dao_persona = new ComboDao();
    $arreglo = $dao_persona->comboEstablecimiento();

    if ($edit == true) {
        return $arreglo;
    }

    //printr($num);
    if ($num == 1) {

        //ID A ELIMINAR
        $id = array('1');
        $counteo = count($id);
        for ($i = 0; $i < $counteo; $i++) {
            //------------------------------------------
            foreach ($arreglo as $indice) {
                if ($id[$i] == $indice['id_tipo_establecimiento']) {
                    //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                    unset($id[$i]);
                    //Elimina _arreglo GET indice Primero				
                    $clave = array_search($indice, $arreglo);
                    unset($arreglo[$clave]);
                    break;
                }
            }
        }
        //return $arreglo;
    } else if ($num == 0) {//ENDIF //Filtra lista el combo  Eliminando : domicilio fiscal
        //ID A ELIMINAR
        $id = array('2', '3', '4', '5', '6');
        $counteo = count($id);
        for ($i = 0; $i < $counteo; $i++) {
            //------------------------------------------
            foreach ($arreglo as $indice) {
                if ($id[$i] == $indice['id_tipo_establecimiento']) {
                    //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                    unset($id[$i]);
                    //Elimina _arreglo GET indice Primero				
                    $clave = array_search($indice, $arreglo);
                    unset($arreglo[$clave]);
                    break;
                }
            }
        }
    } else {//ENDIF
        unset($arreglo); //Error UNSET
    }
    return $arreglo;
}

/**
 * ---------------------------------------------------------------------------------------------
 * 	 Combo box Establecimiento
 * ---------------------------------------------------------------------------------------------
 * */
?>