<?php
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../dao/AbstractDao.php';
    require_once '../dao//RegistroPorConceptoDao.php';
    require_once '../dao/RegistroConceptoEDao.php';

    
    require_once '../dao/Concepto_E_EmpleadorDao.php';
    require_once '../model/RegistroConceptoE.php';

    //trabajador
    require_once '../dao/TrabajadorDao.php';
    require_once '../controller/ideController.php';
}

$response = NULL;

if ($op == "add") {
    $response = registrarRPCE();
} else if ($op == "cargar_tabla") {
    $response = listarRPCE();
} else if ($op == "edit") {
    $response = editRPCE();
} else if ($op == "edit-estado") {
    $response = editEstadoRPCE();
}

echo (!empty($response)) ? json_encode($response) : '';

    //--------------------------------------------------------------------------
    function buscar_ID_ConceptoEmpleador($id_empleador,$id_concepto_e_empleador){
       // echo "\n\nid_empleador id_empleador id_empleador = $id_empleador";
      //  echo "\n\nid_concepto_e id_concepto_e id_concepto_e = $id_concepto_e_empleador";

        $dao = new Concepto_E_EmpleadorDao();
        $id =  $dao->buscarId_ConceptoEmpleador($id_empleador, $id_concepto_e_empleador);
        return $id;
        
    }
    //--------------------------------------------------------------------------

function registrarRPCE() {
    //var_dump($_REQUEST);
 
    $rpta->estado = "default";
    // 01 
    $num_documento = $_REQUEST['num_documento'];
    $cod_tipo_documento = $_REQUEST['tipoDoc'];
    $id_concepto_e_empleador_empleador = $_REQUEST['cod_detalle_concepto']; // = id_concepto_e_empleador
    
    

    $dao_tra = new TrabajadorDao();

    $dao_tra = new TrabajadorDao();
    //echo "ID_EMPLEADOR ".ID_EMPLEADOR;
    $data = $dao_tra->buscarTrabajador($num_documento, $cod_tipo_documento,ID_EMPLEADOR);
    

    if (isset($data['id_trabajador'])) {

        //Pregunta si esta registrado en  :: registros_por_conceptos
        $dao = new RegistroConceptoEDao();
        $datax = $dao->buscar_ID_trabajador($data['id_trabajador'], $id_concepto_e_empleador_empleador); //  !!!!  1!   !!!
        //echo "encontroo trabajador unico en este concepto";
        //var_dump($datax);

        if (is_null($datax['id_registro_concepto_e'])) {

            $model = new RegistroConceptoE();
            $model->setId_concepto_e_empleador($id_concepto_e_empleador_empleador);
            $model->setId_trabajador($data['id_trabajador']);
            $model->setFecha_creacion(date("Y-m-d  H:i:s"));

            $dao = new RegistroConceptoEDao();
            $rpta->estado = $dao->add($model);
        } else if (isset($datax['id_registro_concepto_e'])) {

            //trabajador ya esta registrado..
            $rpta->estado = false;
            $rpta->mensaje = "El Trabajador ya se encuentra Registrado en esta Lista.";
        }
        //$id_trabajador = $data['id_trabajador'];
        // 02
    } else {       
        // trabajador no existe en base de datos:
        $rpta->estado = false;//null; //FALSE
        $rpta->mensaje ="El trabajador no existe,\no ya fue dado de baja.";
    }
    //var_dump( $rpta);    
    //echo "=".$rpta;
    return $rpta;//($rpta == true) ? "true" : "false";
 
}

function listarRPCE() {
    $id_concepto_e_empleador = $_REQUEST['cod_detalle_concepto'];
    $id_empleador = ID_EMPLEADOR;
    /*
    echo "id_concepto_e = $id_concepto_e_empleador"."\n<br>";
    echo "ID_EMPLEADOR = ".ID_EMPLEADOR;
    */
    
    //$id_concepto_e_empleador = buscar_ID_ConceptoEmpleador($id_empleador, $id_concepto_e_empleador);
    //echo "\n\n--------------------\n\n";
    //var_dump($id_concepto_e_empleador_empleador);
    //echo "\n\n--------------------\n\n";
    
    $dao = new RegistroConceptoEDao();

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

    $lista = array();

    $lista = $dao->listar($id_concepto_e_empleador);

    $count = count($lista);

    // $count = $count['numfilas'];
    if ($count > 0) {
        $total_pages = ceil($count / $limit); //CONTEO DE PAGINAS QUE HAY
    } else {
        //$total_pages = 0;
    }
    //valida
    if ($page > $total_pages)
        $page = $total_pages;

    // calculate the starting position of the rows
    $start = $limit * $page - $limit; // do not put $limit*($page - 1)
    //valida
    if ($start < 0)
        $start = 0;

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
//print_r($lista);

    foreach ($lista as $rec) {

        $param = $rec["id_registro_concepto_e"];
        $_01 = $rec['cod_tipo_documento'];
        $_02 = $rec['num_documento'];
        $_03 = $rec['apellido_paterno'];
        $_04 = $rec['apellido_materno'];
        $_05 = $rec['nombres'];
        $_06 = $rec['valor'];
        $_07 = $rec['estado'];

        // $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/editar_trabajador.php?id_pago=" . $param . "&id_trabajador=" . $_00 . "','#detalle_declaracion_trabajador')";

        $opciones = null;
        if ($_07 == 1) {
            $js = "javascript:editar_EstadoRPCE('" . $param . "',0)";
            $opciones = '<div id="divEliminar_Editar">
          <span  title="Activo" >
          <a href="' . $js . '" class="ui-icon ui-icon-circle-check" ></a>
          </span>
          </div>';
        } else if ($_07 == 0 || $_07 == null) {
            $js = "javascript:editar_EstadoRPCE('" . $param . "',1)";
            $opciones = '<div id="divEliminar_Editar">
          <span  title="Inactivo"  >
          <a href="' . $js . '" class="ui-icon ui-icon-circle-close" ></a>
          </span>
          </div>';
        }


        //hereee
        $response->rows[$i]['id'] = $param; //$param;
        $response->rows[$i]['cell'] = array(
            $key,
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $opciones
                //$_07,
                //$_08,
                //$opciones
        );
        $i++;
    }

//echo "<pre>";
//print_r($response);
//echo "</pre>";
    return $response;
}

function editRPCE() {
    $id = $_REQUEST['id'];
    $valor = $_REQUEST['valor'];

    $obj = new RegistroConceptoE();
    $obj->setId_registro_concepto_e($id);
    $obj->setValor($valor);

    $dao = new RegistroConceptoEDao();
    return $dao->edit($obj);
}

function editEstadoRPCE() {
    $obj = new RegistroConceptoE();
    $obj->setId_registro_concepto_e($_REQUEST['id']);
    $obj->setEstado($_REQUEST['estado']);
    $dao = new RegistroConceptoEDao();
    //$dao = new RegistroPorConceptoDao();
    return $dao->edit_estado($obj);
}

?>
