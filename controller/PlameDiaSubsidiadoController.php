<?php
//class PlameDiaSubsidiadoController {}
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    require_once '../model/PdiaSubsidiado.php';
    require_once '../dao/PdiaSubsidiadoDao.php';
}


$response = NULL;

if ($op == "edit") {
    $response = editarPdiaSubsidiado();
} else if ($op == "dual") {
    dualPdiaSubsidiado();
} else if( $op == ""){
    
}

echo (!empty($response)) ? json_encode($response) : '';

function dualPdiaSubsidiado() {
    // nuevo o Actualizar
    $id_pjoranada_laboral = $_REQUEST['id_pjornada_laboral']; //id_pjoranada_laboral

    $id_dia_subsidiado = $_REQUEST['pdia_subsidiado'];
    $estado = $_REQUEST['estado'];
    $ds_cantidad_dia = $_REQUEST['ds_cantidad_dia'];
    $cbo_ds_tipo_suspension = $_REQUEST['cbo_ds_tipo_suspension'];
    //update
    $txt_txt_cbo_ds_tipo_suspension = $_REQUEST['txt_cbo_ds_tipo_suspension'];


    $dao = new PdiaSubsidiadoDao();
    $model = new PdiaSubsidiado();

    for ($i = 0; $i < count($estado); $i++) {

        if ($estado[$i] == 0) { //Registrar   
            echo "estado = 0";
            //$model = new PdiaSubsidiado();
            $model->setId_pjornada_laboral($id_pjoranada_laboral);
            $model->setCantidad_dia($ds_cantidad_dia[$i]);
            $model->setCod_tipo_suspen_relacion_laboral($cbo_ds_tipo_suspension[$i]);
            
            echo "<pre>";
            print_r($model);
            echo "</pre>";
            //DAO
            $dao->registrar($model);
        } else { //Actualizar 
            echo "estado = 1";
            //$model = new PdiaSubsidiado();
            $model->setId_pdia_subsidiado($id_dia_subsidiado[$i]);
            $model->setCod_tipo_suspen_relacion_laboral($txt_txt_cbo_ds_tipo_suspension[$i]);
            $model->setCantidad_dia($ds_cantidad_dia[$i]);

            $dao->actualizar($model);
        }
    }//ENDFOR
}



function buscarDiaSPor_IdPjornadaLaboral($id_pjoranada_laboral){
    $dao = new PdiaSubsidiadoDao();
    $data = $dao->busacar_IdPjorandaLaboral($id_pjoranada_laboral);    
    
    $arreglo = array();    
    
    for($i=0; $i<count($data);$i++){ 
        $model = new PdiaSubsidiado();
        $model->setId_pdia_subsidiado($data[$i]['id_pdia_subsidiado']);
        $model->setId_pjornada_laboral($data[$i]['id_pjornada_laboral']);
        $model->setCantidad_dia($data[$i]['cantidad_dia']);
        $model->setCod_tipo_suspen_relacion_laboral($data[$i]['cod_tipo_suspen_relacion_laboral']);
                
        $arreglo[] =$model;         
    }    
//echo "<pre>arreglo";
//print_r($arreglo);
//echo "</pre>";    
    return $arreglo;    
    
}

?>
