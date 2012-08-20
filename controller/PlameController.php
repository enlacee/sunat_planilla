<?php
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../dao/AbstractDao.php';
    require_once '../dao/PlameDao.php';
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    require_once '../util/funciones.php';
    
}
$responce = NULL;

if ($op == "") {
   
    
}

echo (!empty($responce)) ? json_encode($responce) : '';






?>
