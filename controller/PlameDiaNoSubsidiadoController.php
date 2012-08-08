<?php
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    //-- Dia n subsidiado
    require_once '../dao/PdiaNoSubsidiadoDao.php';
    require_once '../model/PdiaNoSubsidiado.php';
}


$response = NULL;

if ($op == "edit") {
    $response = editarPdiaSubsidiado();
} else if ($op == "dual") {
    dualPdiaNoSubsidiado();
} else if ($op == "") {
    
}

echo (!empty($response)) ? json_encode($response) : '';

function dualPdiaNoSubsidiado() {
    // nuevo o Actualizar
    $id_pjoranada_laboral = $_REQUEST['id_pjornada_laboral']; //id_pjoranada_laboral

    $id_dia_nosubsidiado = $_REQUEST['pdia_nosubsidiado'];
    $estado = $_REQUEST['estado'];
    $dns_cantidad_dia = $_REQUEST['dns_cantidad_dia'];
    $cbo_dns_tipo_suspension = $_REQUEST['cbo_dns_tipo_suspension'];

    //update
    $txt_cbo_dns_tipo_suspension = $_REQUEST['txt_cbo_dns_tipo_suspension'];


    $dao = new PdiaNoSubsidiadoDao();
    $model = new PdiaNoSubsidiado();

    for ($i = 0; $i < count($estado); $i++) {

        if ($estado[$i] == 0) { //Registrar   
            
            $model = new PdiaNoSubsidiado();
            $model->setId_pjornada_laboral($id_pjoranada_laboral);
            $model->setCantidad_dia($dns_cantidad_dia[$i]);
            $model->setCod_tipo_suspen_relacion_laboral($cbo_dns_tipo_suspension[$i]);

            echo "<pre>";
            print_r($model);
            echo "</pre>";
            //DAO
            $dao->registrar($model);
        } else { //Actualizar 
            echo "estado = 1";
            //$model = new PdiaSubsidiado();
            $model->setId_pdia_subsidiado($id_dia_nosubsidiado[$i]);
            $model->setCod_tipo_suspen_relacion_laboral($txt_cbo_dns_tipo_suspension[$i]);
            $model->setCantidad_dia($dns_cantidad_dia[$i]);

            $dao->actualizar($model);
        }
    }//ENDFOR
}







function buscarDiaNoSPor_IdPjornadaLaboral($id_pjoranada_laboral) {
    $dao = new PdiaNoSubsidiadoDao();
    $data = $dao->buscar_IdPjorandaLaboral($id_pjoranada_laboral);

    $arreglo = array();

    for ($i = 0; $i < count($data); $i++) {
        $model = new PdiaNoSubsidiado();
        $model->setId_pdia_nosubsidiado($data[$i]['id_pdia_nosubsidiado']);
        $model->setId_pjornada_laboral($data[$i]['id_pjornada_laboral']);
        $model->setCantidad_dia($data[$i]['cantidad_dia']);
        $model->setCod_tipo_suspen_relacion_laboral($data[$i]['cod_tipo_suspen_relacion_laboral']);

        $arreglo[] = $model;
    }
//echo "<pre>arreglo";
//print_r($arreglo);
//echo "</pre>";    
    return $arreglo;
}

?>
