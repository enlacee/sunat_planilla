<?php

session_start();
//header("Content-Type: text/html; charset=utf-8");

$op = $_REQUEST["oper"];
if ($op) {
    //Empleador
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/EmpleadorDao.php';
    //
    require_once '../dao/TrabajadorDao.php';
    require_once '../dao/VacacionDao.php';
    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    //PLAME PERIODO
    require_once '../dao/PlameDeclaracionDao.php';

    // vacacion detalle
    require_once '../dao/VacacionDetalleDao.php';
    require_once '../model/VacacionDetalle.php';
}

$response = NULL;

if ($op == "cargar_tabla_trabajador") {
    $ESTADO = $_REQUEST['estado'];
    $response = cargar_tabla_trabajador($ESTADO);
} else if ($op == 'cargar_tabla') {
    $response = cargarTablaVacacion();
} else if ($op == "add") {
    $response = addVacacion();
} else if ($op == "del") {
    $response = delVacacion();
} else if($op == "validarFecha"){
    $response = validarFechaVacacion();
}

echo (!empty($response)) ? json_encode($response) : '';

// trabajadores registrados en (periodo)
function cargarTablaVacacion() {

    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $PERIODO = $_REQUEST['periodo'];

    //$dao_trabajador = new TrabajadorDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
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

    $daoVacacion = new VacacionDao();
    $count = $daoVacacion->listarCount(ID_EMPLEADOR_MAESTRO, $ID_PDECLARACION, $WHERE);

    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        //$total_pages = 0;
    }

    if ($page > $total_pages)
        $page = $total_pages;


    $start = $limit * $page - $limit;
    //valida
    if ($start < 0)
        $start = 0;

    //llena en al array

    $lista = $daoVacacion->listar(ID_EMPLEADOR_MAESTRO, $ID_PDECLARACION, $WHERE, $start, $limit, $sidx, $sord);

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
    //$lista = $lista[0];
    foreach ($lista as $rec) {
        $param = $rec["id_vacacion"];
        $_01 = $rec["id_trabajador"];
        $_02 = $rec["nombre_tipo_documento"];
        $_03 = $rec["num_documento"];
        $_04 = $rec["apellido_paterno"];
        $_05 = $rec["apellido_materno"];
        $_06 = $rec["nombres"];

        $js7 = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_vacacion_2.php?id_vacacion=" . $param . "&id_pdeclaracion=" . $ID_PDECLARACION . "&periodo=" . $PERIODO . "','#CapaContenedorFormulario')";
        $_07 = '<a href="' . $js7 . '" class="divEditar" ></a>';

        //hereee
        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_07
        );

        $i++;
    }

    return $response;
}

//DUPLICADO trabajador
function cargar_tabla_trabajador($ESTADO) {
    
    $dao_trabajador = new TrabajadorDao();
    $PERIODO = $_REQUEST['periodo'];
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];

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

    $count = $dao_trabajador->cantidadTrabajador(ID_EMPLEADOR_MAESTRO, $ESTADO, $WHERE);

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

    //llena en al array
    $lista = $dao_trabajador->listarTrabajador(ID_EMPLEADOR_MAESTRO, $ESTADO, $WHERE, $start, $limit, $sidx, $sord);

// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;  /* break; */
    }
    //$lista = $lista[0];
    foreach ($lista as $rec) {
        $param = $rec["id_trabajador"];
        //$array_fecha = getFechaVacacionCalc($param);        
        $_01 = $rec["nombre_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];
        //$_06 = $array_fecha['fecha_inicio'];
        //$_07 = $array_fecha['fecha_calc'];
        $name = "DNI : " . $rec["num_documento"] . " " . $rec["apellido_paterno"] . " " . $rec["apellido_materno"] . " " . $rec["nombres"];

        $js9 = "javascript:newVacacion('$ID_PDECLARACION','$PERIODO','$param','$name','$_07')";
        //$js9 = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_pvacaciones_all.php?id_pdeclaracion=" . $param . "&periodo=".$PERIODO."&name='".$name."'','#CapaContenedorFormulario')";
        $_09 = '<a href="' . $js9 . '" class="divEditar" ></a>';

        //hereee
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
            $_09,
        );

        $i++;
    }

    return $response;
}

function addVacacion() {
    $id_trabajador = $_REQUEST['id_trabajador'];
    //varibales
    $id_vacacion = null;
    $response;

    $vdetalle = array();
    for ($i = 1; $i < 10; $i++) {
        if (!empty($_REQUEST["v_fechaInicio_$i"])) {
            $vdetalle[] = array(
                'fecha_inicio' => getFechaPatron($_REQUEST["v_fechaInicio_$i"], 'Y-m-d'),
                'dia' => $_REQUEST["v_numDia_$i"]
            );
        } else {            
            break;
        }
    }

    // buscar id_pdeclaracion
    if (count($vdetalle) >= 1) {//Pregunta SI Existe periodo registrado como vacacion Base
        $arreglo;
        $anio;
        $new_periodo;
        $data_pdeclaracion;

        $new_periodo = getMesInicioYfin($vdetalle[0]['fecha_inicio']);
        //Dao
        $dao_plame = new PlameDeclaracionDao();
        $data_pdeclaracion = $dao_plame->Buscar_IDPeriodo(ID_EMPLEADOR_MAESTRO, $new_periodo['mes_inicio']);
        // OJO fijar si existe periodo 'id_pdeclaracion' en base de datos
        if (is_array($data_pdeclaracion)) {
            $anio = getFechaPatron($data_pdeclaracion['periodo'], 'Y');
            $arreglo = existeTrabajadorEnVacacion($id_trabajador, $anio);
            if (is_array($arreglo)) {
                $id_vacacion = $arreglo['id_vacacion'];
            } else {
                //dao
                $daoVacacion = new VacacionDao();
                $id_vacacion = $daoVacacion->add($id_trabajador, $data_pdeclaracion['id_pdeclaracion']);
            }
        } else {
            $response->rpta = false;
            $response->mensaje = "El Perido no existe en la base de datos.";
        }
    }//End If  
//    echo "\nid_vacacion";
//    echoo($id_vacacion);
//    echo "\nvacacion detalle";
//    echoo($vdetalle);

    //REGISTRAR VACACION O VACACIONES
    if (!is_null($id_vacacion)) {
        $dao_vd = new VacacionDetalleDao();
        $contador = 0;
        for ($i = 0; $i < count($vdetalle); $i++) {
            $objvd = new VacacionDetalle();
            $objvd->setId_vacacion($id_vacacion);
            $objvd->setFecha_inicio($vdetalle[$i]['fecha_inicio']);
            $objvd->setFecha_fin(crearFecha($vdetalle[$i]['fecha_inicio'], ($vdetalle[$i]['dia']-1))); //$fecha_fin
            $objvd->setDia($vdetalle[$i]['dia']);
            $objvd->setFecha_creacion(date("Y-m-d"));
            $dao_vd->add($objvd);
            $contador++;
        }
        $response->rpta = true;
        $response->mensaje = "Se registraron correctamente.\nNum de reg. [$contador].";        
    }

    return $response;
}

function existeTrabajadorEnVacacion($id_trabajador, $anio) {
    // 01 = 
    $daoVacacion = new VacacionDao();
    $data = $daoVacacion->searchTrabajadorPorAnio($id_trabajador, $anio);
    return $data;
}

// Validar Fechas de vacaciones a registrar
function validarFechaVacacion(){
    //echoo($_REQUEST);
    //$ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $PERIODO = $_REQUEST['periodo'];
    $id_trabajador = $_REQUEST['id_trabajador'];
    $anio = getFechaPatron($PERIODO, 'Y');
    
    $inicio = getFechaPatron($_REQUEST['fecha_inicio'], "Y-m-d") ;
    $fin = getFechaPatron($_REQUEST['fecha_fin'], "Y-m-d");    
    $rangoDeFecha =rangoDeFechas($inicio, $fin, "Y-m-d");
    
    // buscar id_pdeclaracion UNICO POR ANIO.
    $daov = new VacacionDao();
    $id_pdeclaracion_vacacion_base = $daov->getPdeclaracionBase($id_trabajador,$anio);    
    
    $data = $daov->fechasDevacacionesTrabajador($id_trabajador, $id_pdeclaracion_vacacion_base/*$ID_PDECLARACION*/);    
    $flag = false;
    
    
    for($i=0;$i<count($data);$i++){
        $rangoFechaVd = rangoDeFechas($data[$i]['fecha_inicio'], $data[$i]['fecha_fin'], "Y-m-d");
        $counter_rangoDeFecha = count($rangoDeFecha);
        
        $diferencia = array_diff($rangoDeFecha, $rangoFechaVd);
        $counter_diferencia = count($diferencia);
          
        if($counter_rangoDeFecha != $counter_diferencia){
            $flag = true;
            break;
        }            

    }
    
    
    if($flag){
        $response->rpta = false;
        $response->mensaje = "El rango de las fechas enviadas.\ncoincide con una fecha de vacacion ya registrada.\ncorrija los datos";       

    }else{
        $response->rpta = true;
        $response->mensaje ="no existen";
    }
    
    return $response;    
}


// Eliminar funcion !delete
function delVacacion() {
    if ($_REQUEST['id_vacacion']) {
        $dao = new VacacionDao();
        $dao->del($_REQUEST['id_vacacion']);
        return true;
    } else {
        return false;
    }
}







?>