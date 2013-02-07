<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

//CONTROLLER
// IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

//ETAPA PAGO
    require_once '../dao/EtapaPagoDao.php';
    require_once '../model/EtapaPago.php';

    require_once '../dao/PlameDeclaracionDao.php';
    require_once '../dao/PlameDao.php';
//15cena no genera xq tiene vacacion
    require_once '../dao/VacacionDao.php';

//PAGO
    require_once '../dao/PagoDao.php';
    require_once '../model/Pago.php';

//EPAGO TRABAJADOR
    require_once '../dao/PeriodoRemuneracionDao.php';


//ultimo recurso
    require_once '../controller/PlameTrabajadorController.php';
    require_once '../dao/PtrabajadorDao.php';

//---
    require_once '../dao/RegistroPorConceptoDao.php';

//variables conceptos
    require_once '../controller/ConfConceptosController.php';

    //configuracion sueldo basico ++
    // IDE CONFIGURACION 
    require_once '../dao/ConfAsignacionFamiliarDao.php';
    require_once '../dao/ConfSueldoBasicoDao.php';
    require_once '../dao/ConfEssaludDao.php';
    require_once '../dao/ConfOnpDao.php';
    require_once '../dao/ConfUitDao.php';

    require_once '../controller/ConfController.php';
}

$response = NULL;

if ($op == "trabajador_por_etapa") {
    $response = listar_15JQGRID();
    
}
echo (!empty($response)) ? json_encode($response) : '';


function listar_15JQGRID() {
       
    $ID_PDECLARACION = $_REQUEST['id_declaracion'];
    $COD_PERIODO_REMUNERACION = $_REQUEST['cod_periodo_remuneracion'];
    $PERIODO = $_REQUEST['periodo'];
//========================================================================//

    $FECHAX = getFechasDePago($PERIODO);
    
    if ($COD_PERIODO_REMUNERACION == '2') { // Quincena cod_periodo_remuneracion
        $fecha_inicio = $FECHAX['first_day']; 
        $fecha_fin = $FECHAX['second_weeks'];
        //echo "222222222222222222222222222222222222222222";
        
    }else if($COD_PERIODO_REMUNERACION == '1') { // Mensual //16/01/2012 a 31/01/2012
        $fecha_inicio = $FECHAX['second_weeks_mas1'];
        $fecha_fin = $FECHAX['last_day']; 
        //echo "111111111111111111111111111111111";
    }
    
//========================================================================//

    $dao_plame = new PlameDao();
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


    $count = $dao_plame->listarTrabajadoresPorPeriodo_global_grid_Count(ID_EMPLEADOR_MAESTRO, $fecha_inicio, $fecha_fin, $WHERE);

// $count = $count['numfilas'];
    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    } else {
//$total_pages = 0;
    }
//valida
    if ($page > $total_pages)
        $page = $total_pages;

// calculate the starting position of the rows
    $start = $limit * $page - $limit; 
    
    if ($start < 0)
        $start = 0;
//$dao_plame->actualizarStock();
// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;

    $i = 0;
    //llena en al array
    $lista = array();
    $lista = $dao_plame->listarTrabajadoresPorPeriodo_global_grid(ID_EMPLEADOR_MAESTRO, $fecha_inicio, $fecha_fin, $WHERE, $start, $limit, $sidx, $sord);
    
// ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return null;
    }
    foreach ($lista as $rec) {
//echo "enntro en for each   ".$rec['id_trabajador'];
        $param = $rec["id_trabajador"];
        $_01 = $rec["cod_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];
        $_06 = $rec["fecha_inicio"];
        $_07 = $rec["fecha_fin"];
        $_08 = $rec['monto_remuneracion'];
        $_09 = $rec['descripcion'];

        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_07,
            $_08,
            $_09
        );
        $i++;
    }
    return $response;
}


?>
