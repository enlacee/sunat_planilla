<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];
if ($op) {
    require_once ('../dao/AbstractDao.php');

    require_once '../model/EstablecimientoCentroCosto.php';
    require_once '../dao/EstablecimientoCentroCostoDao.php';
}

$responce = NULL;

if ($op == "cargar_tabla") {
    //$responce = cargar_tabla();
} else if ($op == "add") {
//     echo "<pre>";
//     print_r($_REQUEST);
//     echo "</pre>";
    $responce = registrarEstablecimientoCentroCosto();
} else if ($op == "edit") {
    
  //   echo "<pre>";
  //   print_r($_REQUEST);
  //   echo "</pre>"; 
     
    $responce = actualizarEstablecimientoCentroCosto();
    
}

echo (!empty($responce)) ? json_encode($responce) : '';

//------------------------------------------------------------------------------
// Funciones

function registrarEstablecimientoCentroCosto() {
   
    $id_establecimiento = $_REQUEST['id_establecimiento'];
    $estado = $_REQUEST['estado'];
    $id_empresa_centro_costo = $_REQUEST['id_empresa_centro_costo'];

    $dao = new EstablecimientoCentroCostoDao();
    for ($i = 0; $i < count($estado); $i++) {
        if ($estado[$i] == "A") {
            $model = new EstablecimientoCentroCosto();
            $model->setId_establecimiento($id_establecimiento);
            $model->setId_empresa_centro_costo($id_empresa_centro_costo[$i]);
            $model->setSeleccionado(1);
            $model->setEstado($estado[$i]);

            $dao->registrar($model);
        }
    }

    return true;
}

function actualizarEstablecimientoCentroCosto() {
   // $id_establecimiento = $_REQUEST['id_establecimiento'];    
    
    $id_establecimiento_centro_costo = $_REQUEST['id_establecimiento_centro_costo'];
    $seleccionado = $_REQUEST['seleccionado'];
    
    //$id_empresa_centro_costo = $_REQUEST['id_empresa_centro_costo'];

    $dao = new EstablecimientoCentroCostoDao($obj);
    
        
    //LIMPIAR check
    for($i=0;$i<count($id_establecimiento_centro_costo); $i++){
        $dao->marcarCheck($id_establecimiento_centro_costo[$i], '0');        
    }
    
    //LLENAR check
    for ($i=0; $i < count($seleccionado); $i++) {
     
            $dao->marcarCheck($seleccionado[$i], '1');
       
    }
    
    
    return true;
    
}

/// funcion usando en VISTA
function listarEstablecimientoCentroCosto($id_establecimiento, $estado) {
    $dao = new EstablecimientoCentroCostoDao();
    return $dao->listar($id_establecimiento, $estado);
}

?>
