<?php
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    //DECLARACION
    require_once '../dao/PlameDeclaracionDao.php';
}

$responce = NULL;
if ($op == "config") {
    $response = config();
}
echo (!empty($response)) ? json_encode($response) : '';

function config() {
    //variables
    $confMonth = $_REQUEST['month'];
    $confYear = $_REQUEST['year'];

    if ($_SESSION['gRucEmpresa'] && $_SESSION['sunat_empleador']) {
        $periodo = $confYear . "-" . $confMonth . "-01";       
        $dao = new PlameDeclaracionDao();
        $data = $dao->Buscar_IDPeriodo(ID_EMPLEADOR_MAESTRO, $periodo);
        if (is_array($data)) {
            $_SESSION['sunat_empleador']['config'] = array(
                'month' => $confMonth,
                'year' => $confYear,
                'id_pdeclaracion' => $data['id_pdeclaracion'],
                'periodo' => $data['periodo'],
            );
            $response->rpta = true;
        } else {
            $response->rpta = false;
        }
    }
    return $response;
}
