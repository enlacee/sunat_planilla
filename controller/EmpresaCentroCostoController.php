<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];
if ($op) {
    require_once ('../dao/AbstractDao.php');
    require_once '../dao/EmpresaCentroCostoDao.php';
//
    //   require_once '../model/';
    //   require_once('../dao/EmpresaCentroCostoDao.php');
}

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla();
} else if ($op == "add") {

    //$response = registrarEmpresaCentroCosto();
} else if ($op == "edit") {
    //
} else if ($op == "lista_centrocosto") {

    $responce = listarCentroCosto($_REQUEST['id_establecimiento']);
}

echo (!empty($responce)) ? json_encode($responce) : '';

function listarCentroCosto($id_establecimiento, $op = null) {

    // paso 01 ->Identidicaion     
    $dao = new EmpresaCentroCostoDao();
    $rec = $dao->listarCCosto_PorIdEstablecimiento($id_establecimiento);

    if ($op == "all") {
        return $rec;
    } else {
        $lista = array();
        $counteo = count($rec);
        for ($i = 0; $i < $counteo; $i++) {
            $cadena = strtoupper($rec[$i]['descripcion']);
            //-------------------------------------------------------
            $lista[$i]['id'] = $rec[$i]['id_empresa_centro_costo'];
            $lista[$i]['descripcion'] = $cadena;
        }//EndFOR

        return $lista;
    }
}

//------------------------------------------------------------------------------
// Funciones

function listarEmpresaCentroConsto() {

    $dao = new EmpresaCentroCostoDao();
    return $dao->listar();
}

function registrarEmpresaCentroCosto() {

    $dao = new EmpresaCentroCostoDao();
    return $dao->registrar($descripcion);
}

function actualizarEmpresaCentroCosto() {

    $dao = new EmpresaCentroCostoDao();

    $dao->actualizar($obj);
}

?>
