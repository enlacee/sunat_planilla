<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    require_once '../model/Prestamo.php';
    require_once '../model/PrestamoCuota.php';
    require_once '../dao/PrestamoDao.php';
    require_once '../dao/PrestamoCuotaDao.php';
}

$response = NULL;
if ($op == 'cargar_tabla_trabajador') {
    $response = listarTrabajadores();
} else if ($op == 'cargar_tabla') {
    //$response = listarPrestamos();
   $response = listarPrestamoPeriodo(); 
    
} else if ($op == "add") {
    $response = addPrestamos();
} else if ($op == "edit") {
    $response = editPrestamos();
}

echo (!empty($response)) ? json_encode($response) : '';


//lisat de trabajadores con prestamos. PRIMERO
function listarPrestamos() {
        
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $periodo = $_REQUEST['periodo'];    

    $dao = new PrestamoDao();
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];

    $WHERE = '';
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

    $count = $dao->listarCount(ID_EMPLEADOR, $WHERE);
    
   
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

    $lista = array();
    $lista = $dao->listar(ID_EMPLEADOR, $WHERE, $start, $limit, $sidx, $sord);

    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $responce;
    }
    
    $parametro = "id_declaracion=$id_pdeclaracion&periodo=$periodo";
    
    foreach ($lista as $rec) {
        $param = $rec["id_prestamo"];
        $_00 = $rec["id_trabajador"];
        //$_01 = $rec["cod_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];
        $_06 = $rec["fecha_inicio"];
        $_07 = ($rec["estado"] == 1) ? 'ACTIVO' : 'INACTIVO';
        

        //$js = "javascript:return_modal_anb_prestamo('".$param ."','".$_02."','".$_03." ".$_04." ".$_05."')";
        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_cprestamo.php?id_prestamo=" . $param . "&$parametro','#CapaContenedorFormulario')";
        $opciones = '<div id="">
          <span  title="Editar" >
          <a class = "divEditar" href="' . $js . '"></a>
          </span>          
          </div>'
        ;

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            //$_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_07,
            
            $opciones
        );
        $i++;
    }

    return $responce;
}

// lista a los trabajadores x periodo
function listarPrestamoPeriodo(){
    
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $periodo = $_REQUEST['periodo'];
    
    $dao = new PrestamoDao();
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];

    $WHERE = '';
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

    $count = $dao->listarPrestamoPeriodoCount(ID_EMPLEADOR,$periodo, $WHERE);
    
   
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

    //$lista = array();
    $lista = $dao->listarPrestamoPeriodo(ID_EMPLEADOR,$periodo, $WHERE, $start, $limit, $sidx, $sord);

    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $responce;
    }
    
    $parametro = "id_declaracion=$id_pdeclaracion&periodo=$periodo";
    
    foreach ($lista as $rec) {
        $param = $rec["id_prestamo"];
        $_00 = $rec["id_trabajador"];
        //$_01 = $rec["cod_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];
        $_06 = $rec["fecha_inicio"];
        $_07 = $rec["valor"];//($rec["estado"] == 1) ? 'ACTIVO' : 'INACTIVO';
        

        //$js = "javascript:return_modal_anb_prestamo('".$param ."','".$_02."','".$_03." ".$_04." ".$_05."')";
        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_cprestamo.php?id_prestamo=" . $param . "&$parametro','#CapaContenedorFormulario')";
        $opciones = '<div id="">
          <span  title="Editar" >
          <a class = "divEditar" href="' . $js . '"></a>
          </span>          
          </div>'
        ;

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            //$_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_07,
            
            $opciones
        );
        $i++;
    }

    return $responce;
    
}




function listarTrabajadores() {

    $dao = new PrestamoDao();
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];

    $WHERE = '';
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

    $count = $dao->listarTrabajadorCount(ID_EMPLEADOR_MAESTRO, $WHERE);

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
    //88888888888888888888888888888888888888888888888888888888888888888888888888
    // podria usarse categoria trabajaador para cargar los mismos trabajadores...
    $lista = array();
    $lista = $dao->listarTrabajador(ID_EMPLEADOR_MAESTRO, $WHERE, $start, $limit, $sidx, $sord);

    //listar de trabajadores que tienen un prestamo vigente

    $press = $dao->listarTrabajador_PrestamoActivo(ID_EMPLEADOR);



    //88888888888888888888888888888888888888888888888888888888888888888888888888
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $responce;
    }

    foreach ($lista as $rec) {
        $param = $rec["id_trabajador"];
        $_01 = $rec["nombre_tipo_documento"];
        $_02 = $rec["num_documento"];
        $_03 = $rec["apellido_paterno"];
        $_04 = $rec["apellido_materno"];
        $_05 = $rec["nombres"];


         $num_prestamos = 0;
        //$cadena_prestamo = '';
        for ($j = 0; $j < count($press); $j++) {
            if ($param == $press[$j]['id_trabajador']) {                
                $num_prestamos++;
                //break;
            }
        }        
        
       /* if ($num_prestamos > 0){            
          $opciones = '<div class="red">
          <span  title="Editar" >
          <a href="#">P. Pendiente</a>
          </span>
          &nbsp;
          </div>';  */          
       // }else{            
          $js = "javascript:return_modal_anb_prestamo('" . $param . "','" . $_02 . "','" . $_03 . " " . $_04 . " " . $_05 . "')";
          $opciones = '<div class="red">
          <span  title="Editar" >
          <a href="' . $js . '">seleccionar</a>
          </span>
          &nbsp;
          </div>';             
       // }




        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $num_prestamos,
            $opciones
        );
        $i++;
    }

    return $responce;
}




function addPrestamos() {
    //echoo($_REQUEST);
    /*
      $_REQUEST['id_trabajador'];
      $_REQUEST['valor'];
      $_REQUEST['fecha_inicio'];
      $_REQUEST['num_cuota'];
     */
    $fecha_inicio = "01/" . $_REQUEST['fecha_inicio'];

    //echoo($fecha_inicio);
    $obj = new Prestamo();
    $obj->setId_empleador(ID_EMPLEADOR);
    $obj->setId_trabajador($_REQUEST['id_trabajador']);
    $obj->setValor($_REQUEST['valor']);
    $obj->setNum_cuota($_REQUEST['num_cuota']);
    $obj->setFecha_inicio(getFechaPatron($fecha_inicio, "Y-m-d"));
    $obj->setEstado(1); //ACTIVO
    $obj->setFecha_creacion(date("Y-m-d"));
    
   //REGISTRAR PRESTAMO
    $dao = new PrestamoDao();    
    $id_prestamo = $dao->add($obj);    
    
    // variables 01
    $tope_sgte_mes = 20;    
    $cuota_arreglo = calcPrestamo($obj->getValor(), $obj->getNum_cuota(), $tope_sgte_mes);
       
    // REGISTRAR PRESTAMO CUOTA
    $dao_cprestamo = new PrestamoCuotaDao();
    for($i = 0; $i<count($cuota_arreglo); $i++):
        $fecha_contruida = crearFecha($obj->getFecha_inicio(), $day = 0, $month = (0+$i), $year = 0);
        
        $obj_cp = new PrestamoCuota();
        $obj_cp->setId_prestamo($id_prestamo);
        $obj_cp->setMonto($cuota_arreglo[$i]['monto']);        
        $obj_cp->setFecha_calc($fecha_contruida);
        $obj_cp->setFecha_pago(null);
        $obj_cp->setEstado(0);
        $obj_cp->setDescripcion(null);
        //DAO
        $dao_cprestamo->add($obj_cp);
    endfor;    
            
    return true;
}

/**
 *  Funcion de Super Ayuda para generar prestamo.
 */
function calcPrestamo($monto, $num_cuota, $tope_sgte_mes=20){
    
    // variables 02
    $multiplo = floor($monto/$num_cuota); //ok
    $valor_multiplo = $multiplo * $num_cuota;
    $residuo = 0;
    
    //codicion de residuo
    if($monto > $valor_multiplo):
        $residuo = $monto - $valor_multiplo;
    endif;
    
    $bandera_sgte_mes = false;
    if($residuo > $tope_sgte_mes): //$residuo es mayor a 20
        $bandera_sgte_mes = true;
    else:
        $bandera_sgte_mes = false;
    endif;
    
    
    //DATOS A GUARDAR BD
    $cuota_arreglo = array();
    for($i=0; $i < $num_cuota;$i++ ):
        $cuota_arreglo[$i]['monto'] = ($valor_multiplo/$num_cuota);        
        if( ($num_cuota-1) == $i ): // ultima cuaota de pago
            $cuota_arreglo[$i]['monto'] = ($valor_multiplo/$num_cuota) + $residuo;
        endif;
    endfor;
    
    
    if($bandera_sgte_mes == true ): //SIGUIENTE MES CASO EXEPCION
        $i_count = count($cuota_arreglo);
        $cuota_arreglo[$i_count]['monto'] = $residuo;        
    endif;
     
    return $cuota_arreglo;    
}

//$a = calcPrestamo(285, 4);
//$b = calcPrestamo((285-71), 3);
//require_once '../util/funciones.php';
//echoo($a);
//echoo($b);
//-----------------------------------------------
//-----------------------------------------------
// view

function buscar_IdPrestamo($id_prestamo){
    
    $dao = new PrestamoDao();
    $data = $dao->buscar_data_id($id_prestamo);    
    
    return $data;
    
     
    
}



?>
