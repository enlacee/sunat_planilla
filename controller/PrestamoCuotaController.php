<?php
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    // IDE_EMPLEADOR_MAESTRO
    //require_once '../controller/ideController.php';
    require_once '../model/Prestamo.php';
    require_once '../model/PrestamoCuota.php';
    require_once '../dao/PrestamoDao.php';
    require_once '../dao/PrestamoCuotaDao.php';
}

$response = NULL;
if ($op == 'edit') {
    $response = editPrestamoCuota();
}
echo (!empty($response)) ? json_encode($response) : '';


function editPrestamoCuota(){
    //$_REQUEST['id_prestamo'];
    //$_REQUEST['id_prestamo_cuota'];
    //$_REQUEST['montoc'];
    //$_REQUEST['montoc_variable'];
    //$monto_prestamo = $_REQUEST['monto_prestamo'];
    //$num_cuota = $_REQUEST['num_cuota'];        
    //$periodo = $_REQUEST['periodo'];
    
    $obj = new PrestamoCuota();
    $obj->setId_prestamo($_REQUEST['id_prestamo']);
    $obj->setId_prestamo_cutoa($_REQUEST['id_prestamo_cuota']);    
    
    $obj->setMonto_variable($_REQUEST['montoc_variable']);
    $obj->setFecha_calc( getFechaPatron($_REQUEST['fecha_calc'], 'Y-m-d') );
    
    $dao = new PrestamoCuotaDao();
    echoo($obj);
    $dao->edit($obj);

        
    return true;
}
//view
function listaCuotas($id_prestamo){    
     
    $dao = new PrestamoCuotaDao();
    $data = $dao->buscar_idPadre($id_prestamo);
    //var_dump($data);
    $dataobj = array();
    for($i=0;$i<count($data);$i++):
        $obj = new PrestamoCuota();
        $obj->setId_prestamo($id_prestamo);
        $obj->setId_prestamo_cutoa($data[$i]['id_prestamo_cutoa']);
        $obj->setMonto($data[$i]['monto']);
        $obj->setMonto_variable($data[$i]['monto_variable']);        
        $obj->setFecha_calc($data[$i]['fecha_calc']);
        $obj->setFecha_pago($data[$i]['fecha_pago']);        
        $obj->setEstado($data[$i]['estado']);
        $dataobj[] = $obj;
    endfor;
    
    return $dataobj;
    
}


?>
