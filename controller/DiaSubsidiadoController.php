<?php
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    require_once '../model/DiaSubsidiado.php';
    require_once '../dao/DiaSubsidiadoDao.php';
}


$response = NULL;

if ($op == "edit") {
    
} else if ($op == "dual") {
    dualDiaSubsidiado();
} else if( $op == "del"){
    $response = eliminarDiaSubsidiado();
}

echo (!empty($response)) ? json_encode($response) : '';

function dualDiaSubsidiado() {
    // nuevo o Actualizar
    echoo($_REQUEST);
    
    $id_pago = $_REQUEST['id_pago']; //id_pjoranada_laboral

    $id_dia_subsidiado = $_REQUEST['pdia_subsidiado'];
    $estado = $_REQUEST['estado'];
    $ds_cantidad_dia = $_REQUEST['ds_cantidad_dia'];
    $cbo_ds_tipo_suspension = $_REQUEST['cbo_ds_tipo_suspension'];
    $ds_finicio = $_REQUEST['ds_finicio'];
    $ds_ffin = $_REQUEST['ds_ffin'];
    //update
    $txt_txt_cbo_ds_tipo_suspension = $_REQUEST['txt_cbo_ds_tipo_suspension'];


    $dao = new DiaSubsidiadoDao();
    $model = new DiaSubsidiado();

    for ($i = 0; $i < count($estado); $i++) {

        if ($estado[$i] == 0) { //Registrar   
            echo "estado = 0";
            //$model = new PdiaSubsidiado();
            $model->setId_trabajador_pdeclaracion($id_pago);
            $model->setCantidad_dia($ds_cantidad_dia[$i]);
            $model->setCod_tipo_suspen_relacion_laboral($cbo_ds_tipo_suspension[$i]);
            $model->setFecha_inicio(getFechaPatron($ds_finicio[$i],"Y-m-d"));
            $model->setFecha_fin(getFechaPatron($ds_ffin[$i],"Y-m-d"));
            
            //DAO
            $dao->registrar($model);
        } else { //Actualizar 
            echo "estado = 1";
            //$model = new PdiaSubsidiado();
            $model->setId_dia_subsidiado($id_dia_subsidiado[$i]);
            $model->setCod_tipo_suspen_relacion_laboral($txt_txt_cbo_ds_tipo_suspension[$i]);
            $model->setCantidad_dia($ds_cantidad_dia[$i]);
            $model->setFecha_inicio(getFechaPatron($ds_finicio[$i],"Y-m-d"));
            $model->setFecha_fin(getFechaPatron($ds_ffin[$i],"Y-m-d"));

            $dao->actualizar($model);
        }
    }//ENDFOR
}



function buscarDiaSPor_IdTrabajadorPdeclaracion($id){
    $dao = new DiaSubsidiadoDao();
    $data = $dao->buscar_IdTrabajadorPdeclaracion($id);    
    
    $arreglo = array();    
    
    for($i=0; $i<count($data);$i++){ 
        $model = new DiaSubsidiado();
        $model->setId_dia_subsidiado($data[$i]['id_dia_subsidiado']);
        $model->setId_trabajador_pdeclaracion($data[$i]['id_trabajador_pdeclaracion']);
        $model->setCantidad_dia($data[$i]['cantidad_dia']);
        $model->setCod_tipo_suspen_relacion_laboral($data[$i]['cod_tipo_suspen_relacion_laboral']);
        $model->setFecha_inicio($data[$i]['fecha_inicio']);
        $model->setFecha_fin($data[$i]['fecha_fin']);
        //$model->SetEstado($data[$i]['estado']);
                
        $arreglo[] =$model;         
    }    
//echo "<pre>arreglo";
//print_r($arreglo);
//echo "</pre>";    
    return $arreglo;    
    
}


function eliminarDiaSubsidiado(){
    $id = $_REQUEST['id'];    
    $dao = new DiaSubsidiadoDao();    
    return $dao->eliminar($id);
    
}
?>
