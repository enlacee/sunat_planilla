<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';    
    //CONTROLLER
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    //Actualizar Ptrabajador
    require_once '../model/Ptrabajador.php';
    require_once '../dao/PtrabajadorDao.php';

}

$responce = NULL;
if ($op == "edit") {    

    $responce = editar_Ptrabajador();
}


echo (!empty($responce)) ? json_encode($responce) : '';

function editar_Ptrabajador(){
    $model = new Ptrabajador();
    $model->setId_ptrabajador($_REQUEST['id_ptrabajador']);
    $model->setAporta_essalud_vida($_REQUEST['rbtn_essaludvida']);
    $model->setAporta_asegura_tu_pension($_REQUEST['rbtn_apension']);
    $model->setDomiciliado($_REQUEST['rbtn_pt_domiciliado']);
    
    $dao = new PtrabajadorDao();
    return $dao->actualizar($model);
    
}

function buscar_ID_Ptrabajador($id_ptrabajador){
    
    $dao = new PtrabajadorDao();
    $data = $dao->buscar_ID($id_ptrabajador);
    
    $model = new Ptrabajador();
    $model->setId_ptrabajador($data['id_ptrabajador']);
    $model->setId_trabajador($data['id_trabajador']);
    $model->setAporta_essalud_vida($data['aporta_essalud_vida']);
    $model->setAporta_asegura_tu_pension($data['aporta_asegura_tu_pension']);
    $model->setDomiciliado($data['domiciliado']);
    return $model;
    
}

function existeID_TrabajadorPoPtrabajador($id_trabajador){
    
    $dao = new PtrabajadorDao();
    $ID_PTRABAJADOR =  $dao->exite_ID_TRABAJADOR($id_trabajador);        
     
    if(is_null($ID_PTRABAJADOR)){
        $model = new Ptrabajador();
        $model->setId_trabajador($id_trabajador);
        $model->setAporta_essalud_vida(0);
        $model->setAporta_asegura_tu_pension(0);
        $model->setDomiciliado(0);
        $ID_PTRABAJADOR = $dao->registrar($model);
        
    }
    
    return $ID_PTRABAJADOR;
    
}
