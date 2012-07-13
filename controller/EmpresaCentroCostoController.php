<?php
//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];
if ($op) {
    require_once ('../dao/AbstractDao.php');
//
 //   require_once '../model/';
 //   require_once('../dao/EmpresaCentroCostoDao.php');
}

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla();
}else if($op == "add"){
    
    //$response = registrarEmpresaCentroCosto();
}else if($op =="edit"){
    
}

echo (!empty($responce)) ? json_encode($responce) : '';




//------------------------------------------------------------------------------
// Funciones

function listarEmpresaCentroConsto(){
    
    $dao = new EmpresaCentroCostoDao();    
    return $dao->listar();
    
}

function registrarEmpresaCentroCosto(){
    
    $dao = new EmpresaCentroCostoDao();
    return $dao->registrar($descripcion);
}

function actualizarEmpresaCentroCosto(){
    
    $dao = new EmpresaCentroCostoDao();
    
    $dao->actualizar($obj);
}


?>
