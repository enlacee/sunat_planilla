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
    require_once '../dao/DetallePeriodoLaboralDao.php';
    require_once '../dao/VacacionDao.php';
    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    require_once '../model/Vacacion.php';
}

$response = NULL;

if ($op == "cargar_tabla_trabajador") {
    $ESTADO = $_REQUEST['estado'];
    $response = cargar_tabla_trabajador($ESTADO);
} else if ($op == 'vacacion_periodo') {
    $response = vacacion_periodo();
} else if ($op == "add") {
    $response = addVacacion();
} else if ($op == "del") {
    $response = delVacacion();
}

echo (!empty($response)) ? json_encode($response) : '';

//DUPLICADO trabajador
function cargar_tabla_trabajador($ESTADO) {

    $dao_trabajador = new TrabajadorDao();

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
    $lista = array();

    //$dao_trabajador->actualizarStock();

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

        //--- ASK
        //__01__


        $array_fecha = getFechaVacacionCalc($param);
        //echo $fecha_vacacion;  fecha_calc
        //--- ASK!
        // $anio_futuro = date("Y");
        $_01 = $rec["nombre_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];
        //$_06 = $array_fecha['fecha_inicio'];
        $_07 = $array_fecha['fecha_calc'];
        $name = "DNI : " . $rec["num_documento"] . " " . $rec["apellido_paterno"] . " " . $rec["apellido_materno"] . " " . $rec["nombres"];
        $js = "javascript:verVacacion('" . $param . "','" . $name . "')";
        $opciones = '<div id="divEliminar_Editar">				
          <span  title="Editar"  >
          <a class="divEditar" href="' . $js . '"></a>
          </span>
          &nbsp;
          </div>';
        //$_06 = $rec["fecha_nacimiento"];        
        //$_07 = $rec["estado"];
        //$js = "javascript:cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona=" . $param . "','#CapaContenedorFormulario')";
        //$opciones_1 = '<a href="' . $js . '">Modificar</a>';
        //$opciones_2 = '<a href="' . $js2 . '">Eliminar</a>';
        //$opciones = $rec['reporte'];
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
            $opciones,
        );

        $i++;
    }

    return $response;  //RETORNO A intranet.js
}

// filtro de trabajadores en vacacion  en periodo
function vacacion_periodo(){    
    $periodo = $_REQUEST['periodo'];
    
    $dao_trabajador = new VacacionDao();

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

    $count = $dao_trabajador->vacacionPeriodoCount(ID_EMPLEADOR, $periodo,$WHERE);

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
    $lista = array();

    //$dao_trabajador->actualizarStock();

    $lista = $dao_trabajador->vacacionPeriodo(ID_EMPLEADOR, $periodo,$WHERE, $start, $limit, $sidx, $sord);

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

        //--- ASK
        //__01__
        //$array_fecha = getFechaVacacionCalc($param);
        //echo $fecha_vacacion;  fecha_calc
        //--- ASK!
        // $anio_futuro = date("Y");

        $_01 = $rec["num_documento"];
        $_02 = $rec["apellido_paterno"];
        $_03 = $rec["apellido_materno"];

        $_04 = $rec["nombres"];
        //$_06 = $array_fecha['fecha_inicio'];
        $_05 = $rec['fecha_programada']; //$array_fecha['fecha_calc'];
        
        if(!is_null($_05)):
            $estado_vaca=null;
            if(getFechaPatron($_05, "m") == getFechaPatron($periodo, "m") ):
                $estado_vaca = '<img src="images/check.gif"/>';
            endif;                    
            
        endif;


        $name = "DNI : " . $rec["num_documento"] . " " . $rec["apellido_paterno"] . " " . $rec["apellido_materno"] . " " . $rec["nombres"];
        $js = "javascript:verVacacion('" . $param . "','" . $name . "')";
        $opciones = '<div id="divEliminar_Editar">				
          <span  title="Editar"  >
          <a class="divEditar" href="' . $js . '"></a>
          </span>
          &nbsp;
          </div>';

        //$_06 = $rec["fecha_nacimiento"];        
        //$_07 = $rec["estado"];
        //$js = "javascript:cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona=" . $param . "','#CapaContenedorFormulario')";
        //$opciones_1 = '<a href="' . $js . '">Modificar</a>';
        //$opciones_2 = '<a href="' . $js2 . '">Eliminar</a>';
        //$opciones = $rec['reporte'];
        //hereee

        $response->rows[$i]['id'] = $param;
        $response->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $estado_vaca,
            $opciones,
        );

        $i++;
    }

    return $response;  //RETORNO A intranet.js
}

// Recursivo.
function getFechaVacacionCalc($id_trabajador) { //id por defautl is Activo
    // ALERT!!!
    // ALERT!!!
    // CALCULO DE VACACIONES importante la fecha del Servidor !   
    //__00 _buscar
    $dao = new DetallePeriodoLaboralDao();
    //echo "id_trabajador = ".$id_trabajador."<br>";
    $data = $dao->buscarDetallePeriodoLaboral($id_trabajador);
    //echo "entrroorror\n";    
    /*
      echo "<pre> buscarDetallePeriodoLaboral";
      echo print_r($data);
      echo "<pre>";
     */
    //__01__
    $daov = new VacacionDao();
    //NO todos han sido asignado a Vacacion
    $data_v = $daov->listarUltimaFechaVacacion($id_trabajador);
    /*    echo "\n\n**************\n\n";

      echo "Ultima fecha vacacionen Seteada";
      echo "<pre>";
      print_r($data_v);
      echo "</pre>";
      echo "\n\n**************\n\n\n";
      var_dump($data_v['fecha']);
     */
//variables
    $fecha_i = null;



    /**
     * SI NO HA SIDO REGISTRADO CON VACACIONES!
     * se realiza dentro el calculo. para una fecha año atras del actual
     * esto ocurre solo 1 vez xq el trabajador Nunca tubo vacaciones. 
     */
    if ($data_v['fecha'] == null) {
        // AUN NO SE HA REGISTRADO NINGUNO , establece trabajador
        // segun la fecha de ingreso.
        //DATA = trabajador registrado      
        // - 2000
        // - 2012

        if (getFechaPatron($data['fecha_inicio'], "Y") < date("Y")) { //fecha es MENOR a 2012
            $dia_past = getFechaPatron($data['fecha_inicio'], "d");
            $mes_past = getFechaPatron($data['fecha_inicio'], "m");
            //$anio_past = getFechaPatron($data['fecha_inicio'], "Y");


            $anio_now = date("Y");
            //$resta = ($anio_now - $anio_past) - 1;
            $fecha_i = ($anio_now - 1) . "-" . $mes_past . "-" . $dia_past;
            //echo "<br>es fecha inicio = ".$fecha_i;


            $biciesto_0 = date("L", strtotime($fecha_i));
            $num_0 = ($biciesto_0 == 1) ? 1 : 0;
            /* echo "biciento dentro = ".$data['fecha_inicio'];
              echo "\n\n\n";
              echo $num_0;
              echo "\n\n\n";
             */
        } else { // -->> $data['fecha_inicio'] FECHA ES MAYOR al 2012.
            //ES MAYOR !  eh igual.
            $fecha_i = $data['fecha_inicio']; //"FECHA ES MAYOR al 2012 ";
        }



        //$fecha_i = $data['fecha_inicio'];
    } else {
        $fecha_i = $data_v['fecha']; //fecha Calculada.. before insert.
        //echo "\n\n\n<br>fecha calculada es entonses<br>";
        //echo "\n\n ===".$fecha_i;
    }

    //echo " \nfuera IF\nfecha_i = ".$fecha_i;
    //echo "\n\n\n";
    $biciesto = date("L", strtotime(date("Y-m-d")));
    $num = ($biciesto == 1) ? 1 : 0;
    $num_global = $num;
    //echo "\nnum global = ".$num_global;
    // SE SUMA UN 1 dia // CAda 4 años = 366
    $rpta = array();
    $rpta['fecha_inicio'] = $data['fecha_inicio'];
    $rpta['fecha_calc'] = crearFecha($fecha_i, (365 + $num_global), 0, 0);
    //echo " \nRETURN  == ".$rpta;
    //echo "\n";
    //var_dump($rpta);


    return $rpta;
}

function addVacacion() {
    echoo($_REQUEST);

    $f_calculado = getFechaPatron($_REQUEST['fv_calculado'], "Y-m-d");

    $f_programado = getFechaPatron($_REQUEST['fv_programado'], "Y-m-d");

    $data = tipoFechaVacacionMasDias($f_programado, $_REQUEST['tipo_vacacion']);
    echoo($data);
    $f_programado_fin = $data['fecha_fin'];

    $obj = new Vacacion();
    $obj->setId_trabajador($_REQUEST['id_trabajador']);
    $obj->setFecha($f_calculado);
    $obj->setFecha_programada($f_programado);
    $obj->setFecha_prograda_fin($f_programado_fin);
    $obj->setTipo_vacacion($_REQUEST['tipo_vacacion']);
    $obj->setId_pdeclaracion($_REQUEST['id_pdeclaracion']);

    $dao = new VacacionDao();
    $dao->add($obj);

//..............................................................................
// this no pasa xq javacript lo tiene controlado    
    //fecha limite de vacacion es : 11 meses
    /* $fecha_limite = crearFecha($f_calculado, 0, 11, 0);        
      if ($f_programado > $fecha_limite) {
      $f_programado = $f_calculado;
      } */
//..............................................................................

    return true;
}

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