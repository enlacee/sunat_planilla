<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/Spreadsheet/Excel/Writer.php';
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    //CONTROLLER
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    //PAGO
    require_once '../dao/PagoDao.php';
    require_once '../model/Pago.php';


    //CALCULO DE DIAS controller
    require_once '../controller/PlameDiaNoSubsidiadoController.php';
    require_once '../controller/PlameDiaSubsidiadoController.php';

    //PLAME
    require_once '../dao/PlameDeclaracionDao.php';
}

$response = NULL;

if ($op == "registrar_etapa") {
    $response = registrarTrabajadoresPorEtapa();
} else if ($op == "cargar_tabla") {
    $response = cargartabla();
} else if ($op == "grid_lineal") {
    $response = cargar_tabla_grid_lineal();
} else if ($op == "edit") {

    $response = editarPago();
} else if ($op == "del") {
    $response = eliminarPago();
} else if ($op == "recibo15") {
    generarRecibo15();
}

echo (!empty($response)) ? json_encode($response) : '';

function cargartabla() {
    $ID_ETAPA_PAGO = $_REQUEST['id_etapa_pago'];

    $dao = new PagoDao();

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
    $lista = $dao->listar($ID_ETAPA_PAGO);

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

        $param = $rec["id_pago"];
        $_00 = $rec['id_trabajador'];
        $_01 = $rec['cod_tipo_documento'];
        $_02 = $rec['num_documento'];
        $_03 = $rec['apellido_paterno'];
        $_04 = $rec['apellido_materno'];
        $_05 = $rec['nombres'];
        $_06 = $rec['dia_total'];
        $_07 = $rec['sueldo_neto'];
        $_08 = $rec['ccosto']; //Ccosto
        $_09 = $rec['estado'];

        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/editar_trabajador.php?id_pago=" . $param . "&id_trabajador=" . $_00 . "','#detalle_declaracion_trabajador')";
        $js2 = "javascript:eliminarPago('" . $param . "')";


        // $js2 = "javascript:eliminarPersona('" . $param . "')";		
        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '" class="divEditar" ></a>
		</span>
                
		<span  title="Eliminar" >
		<a href="' . $js2 . '" class="divEliminar" ></a>
		</span>

		</div>';

        //hereee
        $response->rows[$i]['id'] = $_00; //$param;
        $response->rows[$i]['cell'] = array(
            $_00, //$param,
            $_01,
            $_02,
            $_03,
            $_04,
            $_05,
            $_06,
            $_07,
            $_08,
            $opciones
        );
        $i++;
    }

//echo "<pre>";
//print_r($response);
//echo "</pre>";
    return $response;
}

// VIEW EMPRESA
function buscarPagoPor_ID($id_pago) {

    $dao = new PagoDao;
    $data = $dao->buscar_ID($id_pago);

    $model = new Pago();
    if ($data) {
        $model->setId_pago($data['id_pago']);
        $model->setId_etapa_pago($data['id_etapa_pago']);
        $model->setId_trabajador($data['id_trabajador']);
        $model->setId_empresa_centro_costo($data['id_empresa_centro_costo']);
        $model->setSueldo_base($data['sueldo_base']);
        $model->setSueldo($data['sueldo']);
        $model->setSueldo_neto($data['sueldo_neto']);

        $model->setDescuento($data['descuento']);
        $model->setDescripcion($data['descripcion']);
        $model->setDia_total($data['dia_total']);
        $model->setDia_laborado($data['dia_laborado']);
        /* $model->setDia_nosubsidiado($data['dia_nosubsidiado']);
          $model->setDia_laborado($data['dia_laborado']); */
        $model->setOrdinario_hora($data['ordinario_hora']);
        $model->setOrdinario_min($data['ordinario_min']);
        $model->setSobretiempo_hora($data['sobretiempo_hora']);
        $model->setSobretiempo_min($data['sobretiempo_min']);
        $model->setEstado($data['estado']);
        $model->setId_empresa_centro_costo($data['id_empresa_centro_costo']);
        $model->setFecha_modificacion($data['fecha_modificacion']);
    }
    return $model;

    function CalcularSueldo($dia_total, $dia_falto) {
        // $dia = 
    }

}

// GRID SIN PIE 
function cargar_tabla_grid_lineal() {
    $ID_PAGO = $_REQUEST['id_pago'];

    $dao = new PagoDao();

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
    $lista = $dao->buscar_ID_GRID_LINEAL($ID_PAGO);

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
        $param = $rec["id_pago"];
        $dia_total = $rec['dia_total'];



        $dao1 = new PdiaSubsidiadoDao();
        $dia_subsidiado = $dao1->busacar_IdPago($param, "SUMA");

        $dao2 = new PdiaNoSubsidiadoDao();
        $dia_NOsubsidiado = $dao2->buscar_IdPago($param, "SUMA");


        $dia_laborado_calc = $dia_total - ($dia_subsidiado + $dia_NOsubsidiado);
        //$_00 = $rec['id_trabajador'];
        $_01 = $rec['cod_tipo_documento'];
        $_02 = $rec['num_documento'];
        $_03 = $rec['apellido_paterno'];
        $_04 = $rec['apellido_materno'];
        $_05 = $rec['nombres'];
        $_06 = $dia_laborado_calc;
        $_07 = $rec['sueldo']; //INGRESOS
        $_08 = $rec['descuento']; //$rec['descuento']; 
        $_09 = $rec['sueldo_neto']; //$rec['valor_neto'];
        $_10 = $rec['estado'];

        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/editar_trabajador.php?id_pago=" . $param . "&id_trabajador=" . $_00 . "','#detalle_declaracion_trabajador')";

        // $js2 = "javascript:eliminarPersona('" . $param . "')";		
        $opciones = '<div id="divEliminar_Editar">				
		<span  title="Editar" >
		<a href="' . $js . '"><img src="images/edit.png"/></a>
		</span>
		</div>';

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
            $_08,
            $_09,
            $_10
                //$opciones*/
        );
        $i++;
    }

//echo "<pre>";
//print_r($response);
//echo "</pre>";
    return $response;
}

//editar

function editarPago() {
    echo "<pre>";
    print_r($_REQUEST);
    echo "</pre>";
    $ID_PAGO = $_REQUEST['id_pago'];

    $model = new Pago();
    $model->setId_pago($ID_PAGO);
    //$model->setId_trabajador($data[]);
    //$model->setValor($valor) //NO CAMBIA
    $model->setDescuento($_REQUEST['descuento']);
    $model->setSueldo_neto($_REQUEST['total_ingreso']);
    $model->setDescripcion($_REQUEST['descripcion']);
    //$model->setDia_laborado($_REQUEST['dia_laborado']);
    $model->setOrdinario_hora($_REQUEST['hora_ordinaria_hh']);
    $model->setOrdinario_min($_REQUEST['hora_ordinaria_mm']);
    $model->setSobretiempo_hora($_REQUEST['hora_sobretiempo_hh']);
    $model->setSobretiempo_min($_REQUEST['hora_sobretiempo_mm']);
    $model->setFecha_modificacion(date("Y-m-d H:i:s"));
    //$model->set

    echo "<pre>MODEL";
    print_r($model);
    echo "</pre>";

    $dao = new PagoDao();
    return $dao->actualizar($model);
}

function eliminarPago() {
    $dao = new PagoDao();
    return $dao->del($_REQUEST['id_pago']);
}

//---30/08/2012
function generarRecibo15() {
    // id de trabajador a generar recibo x quincena.
    //null
    $ids = $_REQUEST['ids'];
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $id_etapa_pago = $_REQUEST['id_etapa_pago'];


    $dao = new PagoDao();
    $data_tra = $dao->listar($id_etapa_pago);

    if (isset($ids)) {
        /*         * ** filtro **** */
        $ids_tra = array();
        for ($i = 0; $i < count($ids); $i++) {
            for ($j = 0; $j < count($data_tra); $j++) {
                if ($ids[$i] == $data_tra[$j]['id_trabajador']) {

                    $ids_tra[] = $data_tra[$j];
                    break;
                }
            }
        }
        $data_tra = null;
        $data_tra = $ids_tra; //array_values($data_traa);  
    }


    generarRecibo15Exel($id_pdeclaracion, $data_tra);
}

function generarRecibo15Exel($id_pdeclaracion, $dataa) {

    //$data = $dataa[0];

    $dao = new PlameDeclaracionDao();
    $data_pd = $dao->buscar_ID($id_pdeclaracion);
    $fecha = $data_pd['periodo'];

    $nombre_mes = getNameMonth(getFechaPatron($fecha, "m"));
    $anio = getFechaPatron($fecha, "Y");
    getFechaPatron($fecha_es_us, $patron_date);

// Creating a workbook
    $workbook = new Spreadsheet_Excel_Writer();

// sending HTTP headers
    $workbook->send('Anibal-exel.xls');
//OPCIONAL
// $workbook->setTempDir('/home/xnoguer/temp');
//ESTILOS EXEL
    $negrita = & $workbook->addFormat();
    $negrita->setBold();
    //-- Colores RGB
    $workbook->setCustomColor(11, 0, 0, 150);
    $workbook->setCustomColor(12, 192, 192, 192);
    $workbook->setCustomColor(13, 221, 60, 16);
    $workbook->setCustomColor(14, 255, 255, 0);
    //-- Format_txt_Centrado
    $format_tabla_head_centrado = & $workbook->addFormat();
    $format_tabla_head_centrado->setBold();
    $format_tabla_head_centrado->setSize(8);
    $format_tabla_head_centrado->setTextWrap(1);
    $format_tabla_head_centrado->setBorder(1);
    $format_tabla_head_centrado->setColor(11);
    $format_tabla_head_centrado->setFgColor(12);
    $format_tabla_head_centrado->setBgColor(12);
    $format_tabla_head_centrado->setVAlign('vequal_space');
//$format_tabla_head_centrado->setHAlign('equal_space');
    $format_tabla_head_centrado->setAlign('center');

//--format_line_separador
    $format_line_separador = & $workbook->addFormat();
    $format_line_separador->setBottom(1);
    $format_line_separador->setBorderColor(11);
    //--format_decimal_total_azul
    $format_decimal_total_azul = & $workbook->addFormat();
    $format_decimal_total_azul->setNumFormat($moneda . '[$S/.-280A] #.##0,00');
    $format_decimal_total_azul->setColor(11);
    $format_decimal_total_azul->setBold();
    $format_decimal_total_azul->setAlign("left");
    //$format_decimal_total_azul->setHAlign("center");
    $format_decimal_total_azul->setSize(8);
    $format_decimal_total_azul->setBorderColor('black');


// Creating a worksheet
    $worksheet = & $workbook->addWorksheet('My first worksheet');


    //--------------------------------------------------------------------------
    $row_a = 0;
    $col_a = 0;

    for ($a = 0; $a < count($dataa); $a++) {

        $data = array();
        $data = $dataa[$a];


        $row = 0 + $row_a;
        $col = 0 + $col_a;
        for ($i = 0; $i < 2; $i++) { // Repetir 2 VECES
            if ($i == 1) {
                $col = $col + 5;
            }
            // tabla Principall Lote
            //$worksheet->setRow($row, 22);
            $worksheet->setColumn($row, $col, 1);
            $worksheet->setColumn(($row + 1), ($col + 1), 15);
            $worksheet->setColumn(($row + 2), ($col + 2), 14);
            $worksheet->setColumn(($row + 3), ($col + 3), 10);
            $worksheet->setColumn(($row + 4), ($col + 4), 10);

//$worksheet->write(7, (2+$i), "fila $row  Y COL = $col");
//$worksheet->setRow ( integer $row , integer $height , mixed $format=0 )
            $array = array(
                "01",
                "02",
                "03",
                "04",
                "05"
            );

            //$worksheet->writeRow($row, $col, $array, $format_tabla_head_centrado);
            $worksheet->write($row, ($col + 1), 'CAMUENTE S.A', $negrita);
            $worksheet->write(($row + 2), ($col + 3), 'RECIBO', $negrita);


            $descripcion1 = "ADELANTO DE QUINCENA CORRESPONDIENTE";
            $descripcion2 = "Al MES DE " . strtoupper($nombre_mes) . " DEL " . $anio;
            $worksheet->write(($row + 4), ($col + 1), $descripcion1);
            $worksheet->write(($row + 5), ($col + 1), $descripcion2);

            $_NOMBRE = "NOMBRES: ";
            $_NOMBRE_ = $data['apellido_paterno'] . " " . $data['apellido_materno'] . " " . $data['nombres'];
            $worksheet->write(($row + 7), ($col + 1), $_NOMBRE);
            $worksheet->write(($row + 7), ($col + 2), strtoupper($_NOMBRE_));

            $_CANTIDAD = "SUELDO: ";
            $_CANTIDAD_ = $data['sueldo'];
            $worksheet->write(($row + 8), ($col + 1), $_CANTIDAD);
            $worksheet->write(($row + 8), ($col + 2), $_CANTIDAD_, $format_decimal_total_azul);

            $_N_CUENTA = "N. CTA:";
            $_N_CUENTA_ = " -  -";
            $worksheet->write(($row + 9), ($col + 1), $_N_CUENTA);
            $worksheet->write(($row + 9), ($col + 2), $_N_CUENTA_);

            $_FECHA_CREACION = "FECHA: ";
            $_FECHA_CREACION_ = getFechaPatron($data['fecha_creacion'], "d/m/Y");
            $worksheet->write(($row + 10), ($col + 1), $_FECHA_CREACION);
            $worksheet->write(($row + 10), ($col + 2), $_FECHA_CREACION_);


            $worksheet->write(($row + 14), ($col + 2), "_______________");
            $worksheet->write(($row + 15), ($col + 2), "      Vo.Bo.        ");


            $worksheet->write(($row + 14), ($col + 3), "__________________");
            $worksheet->write(($row + 15), ($col + 3), "RECIBI CONFORME");
            $worksheet->write(($row + 16), ($col + 3), "DNI. " . $data['num_documento']);
            
            //--- LINE
            $worksheet->write(($row + 17), ($col + 0), "", $format_line_separador);
            $worksheet->write(($row + 17), ($col + 1), "", $format_line_separador);
            $worksheet->write(($row + 17), ($col + 2), "", $format_line_separador);
            $worksheet->write(($row + 17), ($col + 3), "", $format_line_separador);
            $worksheet->write(($row + 17), ($col + 4), "", $format_line_separador);

            
        }

        $row_a = $row_a + 19;
        //$col_a = $col_a + 19;
    }




    //--------------------------------------------------------------------------
// The actual data
//$workbook->setVersion(8);
// Let's send the file
    $workbook->close();
}
?>



