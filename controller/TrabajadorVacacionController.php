<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    require_once '../dao/PlameDao.php';
    require_once '../dao/PlameDeclaracionDao.php';
    require_once '../dao/DeclaracionDconceptoDao.php';
    require_once '../dao/TrabajadorPdeclaracionDao.php';

    //Conceptos afectos
    require_once '../dao/PlameDetalleConceptoEmpleadorMaestroDao.php';
    require_once '../controller/ConfController.php';
    require_once '../controller/ConfConceptosController.php';

    // IDE CONFIGURACION  -- generarConfiguracion(periodo);
    require_once '../dao/ConfAsignacionFamiliarDao.php';
    require_once '../dao/ConfSueldoBasicoDao.php';
    require_once '../dao/ConfEssaludDao.php';
    require_once '../dao/ConfOnpDao.php';
    require_once '../dao/ConfUitDao.php';

    //nuevas tablas
    require_once '../model/TrabajadorGratificacion.php';
    require_once '../dao/TrabajadorGratificacionDao.php';

    require_once '../model/DeclaracionDConceptoGrati.php';
    require_once '../dao/DeclaracionDConceptoGratiDao.php';

    // boleta gratifiacion
    require_once '../dao/EstablecimientoDao.php';
    require_once '../dao/EmpresaCentroCostoDao.php';
    require_once '../dao/EstablecimientoDireccionDao.php';
    require_once '../dao/DetalleRegimenPensionarioDao.php';
    require_once '../dao/PersonaDireccionDao.php';
    require_once '../dao/DetallePeriodoLaboralDao.php';
    require_once '../dao/RegistroPorConceptoDao.php';
    //afp boleta
    require_once '../dao/ConfAfpDao.php';
    
    //ZIP
    require_once '../util/zip/zipfile.inc.php';
    
    
}

if ($op == "vacacion") {

    $response = vacacion();
} else if ($op == "boleta_vacacion") {
    //boletaGratificacion();
}


function vacacion(){
    
    $periodo = $_REQUEST['periodo'];
    generarConfiguracion($periodo);
    //$periodo_rel = $periodo; //periodo_relativo
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    
    
    
    
    
    
    
}

