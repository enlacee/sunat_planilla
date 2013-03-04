<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/RegistroPorConceptoDao.php';
    require_once '../model/RegistroPorConcepto.php';
    require_once '../controller/ideController.php';

    //trabajador
    require_once '../dao/TrabajadorDao.php';
    require_once '../dao/PlameDao.php';
}

$response = NULL;

if ($op == "add") {
    $response = registrarRPC();
} else if ($op == "cargar_tabla") {
    $response = listarRPC();
} else if ($op == "edit") {
    $response = editRPC();
} else if ($op == "del") {
    $response = delRPC();
}else if($op=="trabajador_por_mes"){    
    $response = listar_15_Mes();
}

echo (!empty($response)) ? json_encode($response) : '';

function registrarRPC() {
    
    $rpta->estado = "default";
    // 01 
    //echoo($_REQUEST);
    $cod_detalle_concepto = $_REQUEST['cod_detalle_concepto'];
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $id_trabajador = $_REQUEST['id_trabajador'];

    //echoo($_REQUEST);
    //echo "\n\n";
    if (isset($id_trabajador)) {

        /**
         * Pregunta si esta registrado en  :: registros_por_conceptos
         * verifica. No hay Duplicado.
         */
        $dao = new RegistroPorConceptoDao();
        $datax = $dao->buscar_ID_trabajador($id_trabajador,$id_pdeclaracion ,$cod_detalle_concepto);
        
        //echoo($datax);

        if (is_null($datax['id_registro_por_concepto'])) {

            $model = new RegistroPorConcepto();
            $model->setId_pdeclaracion($id_pdeclaracion);
            $model->setCod_detalle_concepto($cod_detalle_concepto);
            $model->setId_trabajador($id_trabajador);
            $model->setFecha_creacion(date("Y-m-d  H:i:s"));
           
            //echo "\nADD\n";
            //echoo($model);
            //$rpta->estado =true;
            $rpta->estado = $dao->add($model);
            
        } else if (isset($datax['id_registro_por_concepto'])) {

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
    return $rpta;
}

function listarRPC() {
    $periodo = $_REQUEST['periodo'];
    $cod_detalle_concepto = $_REQUEST['cod_detalle_concepto'];
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];

    $dao = new RegistroPorConceptoDao();
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
    $count = $dao->listar2_count($cod_detalle_concepto, $id_pdeclaracion, $WHERE);

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
    
    $lista = $dao->listar2( $cod_detalle_concepto,$id_pdeclaracion,$WHERE, $start, $limit, $sidx, $sord);    
    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
    foreach ($lista as $rec) {
        $param = $rec["id_registro_por_concepto"];
        $_00 = $rec['id_trabajador'];
        $_01 = $rec['cod_tipo_documento'];
        $_02 = $rec['num_documento'];
        $_03 = $rec['apellido_paterno'];
        $_04 = $rec['apellido_materno'];
        $_05 = $rec['nombres'];
        
        // 0107 = Dia feriado entero.
        // 0115 = Dia feriado 1ero mayo.
        if($cod_detalle_concepto =='0107' || $cod_detalle_concepto =='0115' || $cod_detalle_concepto =='0705'){        
            $_06 = ($rec['valor']>=0) ? intval($rec['valor']) : $rec['valor'];     
        }else{
            $_06 = $rec['valor'];
        }
        
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $key,
            $param,
            $_00,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06
        );
        $i++;
    }
    return $response;
}

function editRPC() {
    $id = $_REQUEST['id'];
    $valor = $_REQUEST['valor'];

    $obj = new RegistroPorConcepto();
    $obj->setId_registro_por_concepto($id);
    $obj->setValor($valor);

    $dao = new RegistroPorConceptoDao();
    return $dao->edit($obj);
}

function delRPC() {
    $dao = new RegistroPorConceptoDao();     
    return $dao->del($_REQUEST['id']);
}

// funcion antes eliminada ahora utilizada etapapagagocontroller
// GRID personal por mes = similar a listar_15()
function listar_15_Mes() {

//========================================================================//

    $ID_DECLARACION = $_REQUEST['id_pdeclaracion'];
    $PERIODO = $_REQUEST['periodo'];
    // SOLO UTILIZADO EN RPC2 'registro por concepto'
    $tipo = $_REQUEST['tipo'];

    $FECHA = getFechasDePago($PERIODO);

//echoo($FECHA);
//$FECHA['first_day'];
//$FECHA['last_day'];

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

    $count = $dao_plame->listarTrabajadoresPorPeriodo_global_grid_Mes_Count(ID_EMPLEADOR_MAESTRO, $FECHA['first_day'], $FECHA['last_day'], $WHERE);

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
//valida
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
    $lista = $dao_plame->listarTrabajadoresPorPeriodo_global_grid_Mes(ID_EMPLEADOR_MAESTRO, $FECHA['first_day'], $FECHA['last_day'], $WHERE, $start, $limit, $sidx, $sord);

// ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return null;
    }

    foreach ($lista as $rec) {
        $param = $rec["id_trabajador"];
        $_01 = $rec["cod_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];
        $_06 = $rec["fecha_inicio"];
        $_07 = $rec["fecha_fin"];

//$js = "javascript:return_modal_anb_prestamo('" . $param . "','" . $_02 . "','" . $_03 . " " . $_04 . " " . $_05 . "')";

        $js = "javascript:agregarTrabajador_rpc('" . $param . "')";

        switch ($tipo) {
            case 2:
                $js = "javascript:agregarTrabajador_rpc2_phe('" . $param . "')";
                break;
            default:
                break;
        }





        $opciones = '<div class="red">
          <span  title="Editar" >
          <a href="' . $js . '">seleccionar</a>
          </span>
          </div>';

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
            $opciones
//utf8_encode($opciones),
//$_06
        );
        $i++;
    }
    return $response;
}



?>
