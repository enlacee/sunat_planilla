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
    require_once '../dao/EtapaPagoDao.php';


    //CALCULO DE DIAS controller
    require_once '../controller/PlameDiaNoSubsidiadoController.php';
    require_once '../controller/PlameDiaSubsidiadoController.php';

    //PLAME
    require_once '../dao/PlameDeclaracionDao.php';


    //NEW 06/08/2012 Utilizando recursos de t-registro
    require_once '../dao/EstablecimientoDao.php';
    require_once '../dao/EmpresaCentroCostoDao.php';
    require_once '../dao/EstablecimientoDireccionDao.php';
    //require_once '../controller/EstablecimientoDireccionController.php';
    //ZIP
    require_once '../util/zip/zipfile.inc.php';
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
    //generarRecibo15();
    generarRecibo15_txt();
} else if ($op == "recibo15_txt") {
    echo "<pre>";
    print_r($_REQUEST);
    echo "</pre>";
    //generarRecibo15_txt();
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



    $count = $dao->listarCount($ID_ETAPA_PAGO, $WHERE);

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
    
    $lista = array();
    $lista = $dao->listar($ID_ETAPA_PAGO,$WHERE, $start, $limit, $sidx, $sord);
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
        //$_09 = $rec['estado'];

        $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/editar_trabajador.php?id_pago=" . $param . "&id_trabajador=" . $_00 . "','#detalle_declaracion_trabajador')";
        $js2 = "javascript:eliminarPago('" . $param . "')";

        
          // $js2 = "javascript:eliminarPersona('" . $param . "')";
        
        //<span  title="Editar" >
        //<a href="' . $js . '" class="divEditar" ></a>
        //</span>
          $opciones = '<div id="divEliminar_Editar">          
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

    //
    $dao = new PlameDeclaracionDao();
    $data_pd = $dao->buscar_ID($id_pdeclaracion);
    $fecha = $data_pd['periodo'];

    $nombre_mes = getNameMonth(getFechaPatron($fecha, "m"));
    $anio = getFechaPatron($fecha, "Y");



//..............................................................................
// Inicio Exel
//..............................................................................
// Creating a workbook
    $workbook = new Spreadsheet_Excel_Writer();

// sending HTTP headers
    $workbook->send(NAME_COMERCIAL . '-1RA QUINCENA.xls');
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
    $format_decimal_total_azul->setNumFormat($moneda . '[$S/.-280A] #.##0,0');
    $format_decimal_total_azul->setColor(11);
    $format_decimal_total_azul->setBold();
    $format_decimal_total_azul->setAlign("left");
    //$format_decimal_total_azul->setHAlign("center");
    $format_decimal_total_azul->setSize(8);
    $format_decimal_total_azul->setBorderColor('black');
//--format_simple_amarillo
    $format_simple_amarillo = $workbook->addFormat();
    $format_simple_amarillo->setSize(8);
    $format_simple_amarillo->setColor("black");
    $format_simple_amarillo->setFgColor(14);
    $format_simple_amarillo->setBold();

// Creating a worksheet
    $worksheet = & $workbook->addWorksheet('hoja 01');





    // paso 01 Listar ESTABLECIMIENTOS del Emplearo 'Empresa'
    $dao_est = new EstablecimientoDao();
    $est = array();
    $est = $dao_est->listar_Ids_Establecimientos(ID_EMPLEADOR);



    //$worksheet->write(0, 0, "00000");
    // paso 02 listar CENTROS DE COSTO del establecimento.    
    if (is_array($est) && count($est) > 0) {
        //DAO
        $dao_cc = new EmpresaCentroCostoDao();
        $dao_pago = new PagoDao();
        $dao_estd = new EstablecimientoDireccionDao();

        // -------- Variables globales --------//
        $SUM_STOTAL_CC = 0;
        $SUM_TOTAL_CC = array();
        $SUM_TOTAL_EST = array();

        $row_a = 0;
        $col_a = 0;

        for ($i = 0; $i < count($est); $i++) {

            //Establecimiento direccion Reniec
            $data_est_direc = $dao_estd->buscarEstablecimientoDireccionReniec($id_establecimiento);

            $ecc = array();
            $ecc = $dao_cc->listar_Ids_EmpresaCentroCosto($est[$i]['id_establecimiento']);
            // paso 03 listamos los trabajadores por Centro de costo 


            $row = 0 + $row_a;
            $col = 0 + $col_a;

            for ($j = 0; $j < /* 1 */ count($ecc); $j++) {

                $SUM_TOTAL_CC[$j]['centro_costo'] = $ecc[$j]['descripcion'];
                $SUM_TOTAL_CC[$j]['monto'] = 0;

                //var_dump($ecc);
                $daoed = new EstablecimientoDireccionDao();
                $data_est_direc = array();
                $data_est_direc = $daoed->buscarEstablecimientoDireccionReniec($est[$i]['id_establecimiento']);

                // titulo
                if ($row == 0) {
                    $worksheet->setColumn($row, $col, 1);
                    $worksheet->setColumn(($row + 1), ($col + 1), 4);
                    $worksheet->setColumn(($row + 2), ($col + 2), 15);
                    $worksheet->setColumn(($row + 3), ($col + 3), 40);
                    $worksheet->setColumn(($row + 4), ($col + 4), 15);
                    $worksheet->setColumn(($row + 5), ($col + 5), 21);
                    $worksheet->setColumn(($row + 6), ($col + 6), 10);
                    $worksheet->setColumn(($row + 7), ($col + 7), 10);
                    $array = array(
                        "01",
                        "02",
                        "03",
                        "04",
                        "05",
                        "06",
                        "07",
                        "08"
                    );
                    $worksheet->writeRow($row, $col, $array, $format_tabla_head_centrado);
                }



                $worksheet->write(($row + 1), ($col + 1), NAME_EMPRESA);

                $descripcion1 = date("d/m/Y", strtotime($data_pd['fecha_modificacion']));
                $descripcion2 = " - -";
                $worksheet->write(($row + 1), ($col + 4), "FECHA : ");
                $worksheet->write(($row + 1), ($col + 5), $descripcion1);

                $worksheet->write(($row + 2), ($col + 4), "PAGINA :");
                $worksheet->write(($row + 2), ($col + 5), $descripcion2);

                $worksheet->write(($row + 4), ($col + 2), "1RA QUINCENA");

                $worksheet->write(($row + 5), ($col + 2), "LOCALIDAD : " . $data_est_direc['ubigeo_distrito']);

                $worksheet->write(($row + 6), ($col + 2), "CENTRO DE COSTO : " . strtoupper($ecc[$j]['descripcion']), $format_simple_amarillo);

                $row = $row + 8;
                //$worksheet->write($row, $col, "##################################################");
                $array = array(
                    "01",
                    "N",
                    "DNI",
                    "APELLIDOS Y NOMBRES",
                    "IMPORTE",
                    "FIRMA",
                    "07",
                    "08"
                );
                $worksheet->writeRow($row, $col, $array, $format_tabla_head_centrado);

                // LISTA DE TRABAJADORES
                $data_tra = $dao_pago->listar_2($id_etapa_pago, $est[$i]['id_establecimiento'], $ecc[$j]['id_empresa_centro_costo']);

                for ($k = 0; $k < count($data_tra); $k++) {
                    $row = $row + 1;
                    $worksheet->write(($row), ($col + 1), $k);
                    $worksheet->write(($row), ($col + 2), $data_tra[$k]['cod_tipo_documento'] . "-" . $data_tra[$k]['num_documento']);
                    $worksheet->write(($row), ($col + 3), $data_tra[$k]['apellido_paterno'] . " " . $data_tra[$k]['apellido_materno'] . " " . $data_tra[$k]['nombres']);
                    $worksheet->write(($row), ($col + 4), $data_tra[$k]['sueldo'], $format_decimal_total_azul);
                    $worksheet->write(($row), ($col + 5), "_____________________");

                    $SUM_TOTAL_CC[$j]['monto'] = $SUM_TOTAL_CC[$j]['monto'] + $data_tra[$k]['sueldo'];
                }

                //--- LINE
                $row++;
                $worksheet->write(($row), ($col + 0), "", $format_line_separador);
                $worksheet->write(($row), ($col + 1), "", $format_line_separador);
                $worksheet->write(($row), ($col + 2), "", $format_line_separador);
                $worksheet->write(($row), ($col + 3), "", $format_line_separador);
                $worksheet->write(($row), ($col + 4), "", $format_line_separador);
                $worksheet->write(($row), ($col + 5), "", $format_line_separador);
                $worksheet->write(($row), ($col + 6), "", $format_line_separador);
                $worksheet->write(($row), ($col + 7), "", $format_line_separador);

                $row++;
                $worksheet->write(($row), ($col + 3), "TOTAL EN :" . $SUM_TOTAL_CC[$j]['centro_costo']);
                $worksheet->write(($row), ($col + 4), $SUM_TOTAL_CC[$j]['monto'], $format_decimal_total_azul);
                $row = $row + 4;






                //$row_a = $row_a + 5;
            }
        }//END FOR
    }//END IF
//..............................................................................
// Inicio Exel
//..............................................................................
    //|---------------------------------------------------------------------------
    //| Calculos Finales
    //|
    //|---------------------------------------------------------------------------
    //$worksheet->write(($row+4), ($col + 1), ".::RESUMEN DE PAGOS::.");    
    for ($z = 0; $z < count($SUM_TOTAL_CC); $z++) {
        $row = $row + 1;
        $worksheet->write(($row), ($col + 2), $SUM_TOTAL_CC[$z]['centro_costo']);
        $worksheet->write(($row), ($col + 4), $SUM_TOTAL_CC[$z]['monto'], $format_decimal_total_azul);

        $SUM_STOTAL_CC = $SUM_STOTAL_CC + $SUM_TOTAL_CC[$z]['monto'];
    }


    //
    $row = $row + 3;
    $worksheet->write(($row), ($col + 2), "T O T A L   G E N E R A L");
    $worksheet->write(($row), ($col + 4), $SUM_STOTAL_CC, $format_decimal_total_azul);




    $workbook->close();
    //generarRecibo15Exel($id_pdeclaracion, $data_tra);
//------------------------------------------------------------------------------    
    /*    //$dao = new PagoDao();
      //$data_tra = $dao->listar($id_etapa_pago);
      // INDIVIDUAL DESABILITADO !!!!!
      if (isset($idsXXX)) {
      //-------- filtro-------//
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
     */
//------------------------------------------------------------------------------        
    //generarRecibo15Exel($id_pdeclaracion, $data_tra);
}

function generarRecibo15_txt() {
    // id de trabajador a generar recibo x quincena.
    //null
    //echoo($_REQUEST);
    
    $ids = $_REQUEST['ids'];
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $id_etapa_pago = $_REQUEST['id_etapa_pago'];
//---------------------------------------------------
// Variables secundarios para generar Reporte en txt
    $master_est = null; //2;
    $master_cc = null; //2;

    if ($_REQUEST['todo'] == "todo") {
        $cubo_est = "todo";
        $cubo_cc = "todo";
    }

    $id_est = $_REQUEST['id_establecimientos'];
    $id_cc = $_REQUEST['cboCentroCosto'];

    if (!is_null($id_est)) {
        $master_est = $id_est;
    } else {
        //$cubo_est = "todo";
    }

    if (!is_null($id_cc)) {
        $master_cc = $id_cc;
    } else {
        //$cubo_cc = "todo";
    }
    //
    $dao = new PlameDeclaracionDao();
    $data_pd = $dao->buscar_ID($id_pdeclaracion);
    $fecha = $data_pd['periodo'];

    //---
    $dao_ep = new EtapaPagoDao();
    $data_ep = $dao_ep->buscar_ID($id_etapa_pago);
    ;

    $_name_15 = "error";
    if ($data_ep['tipo'] == 1):
        $_name_15 = "1RA QUINCENA";
    elseif ($data_ep['tipo'] == 2):
        $_name_15 = "2DA QUINCENA";
    endif;


    $nombre_mes = getNameMonth(getFechaPatron($fecha, "m"));
    $anio = getFechaPatron($fecha, "Y");


    $file_name = NAME_COMERCIAL . '-' . $_name_15 . '.txt';
    $file_name2 = NAME_COMERCIAL . '-BOLETA QUINCENA.txt';
    $fpx = fopen($file_name2, 'w');


    $BREAK = chr(13) . chr(10);
    //$BREAK = chr(14) . chr(10);
    //chr(27). chr(100). chr(0)
    $LINEA = str_repeat('-', 80);
//..............................................................................
// Inicio Exel
//..............................................................................
    $fp = fopen($file_name, 'w');


    // paso 01 Listar ESTABLECIMIENTOS del Emplearo 'Empresa'
    $dao_est = new EstablecimientoDao();
    $est = array();
    $est = $dao_est->listar_Ids_Establecimientos(ID_EMPLEADOR);
    $contador_break = 0;

    // paso 02 listar CENTROS DE COSTO del establecimento.    
    if (is_array($est) && count($est) > 0) {
        //DAO
        $dao_cc = new EmpresaCentroCostoDao();
        $dao_pago = new PagoDao();
        $dao_estd = new EstablecimientoDireccionDao();

        // -------- Variables globales --------//        
        $SUM_TOTAL_CC = array();
        $SUM_TOTAL_EST = array();



        for ($i = 0; $i < count($est); $i++) { // ESTABLECIMIENTO
            //echo " i = $i establecimiento    ID=".$est[$i]['id_establecimiento'];
            //echo "<br>";
            //$SUM_TOTAL_EST[$i]['establecimiento'] = strtoupper("Establecimiento X ==" . $est[$i]['id_establecimiento']);
            $bandera_1 = false;
            if ($est[$i]['id_establecimiento'] == $master_est) {
                $bandera_1 = true;
            } else if ($cubo_est == "todo") {
                $bandera_1 = true;
            }

            if ($bandera_1/* $est[$i]['id_establecimiento'] == $master_est  || $cubo_est == "todo" */) {
                
                $contador_break = $contador_break + 1;
                
                $SUM_TOTAL_EST[$i]['monto'] = 0;
                //Establecimiento direccion Reniec
                $data_est_direc = $dao_estd->buscarEstablecimientoDireccionReniec($est[$i]['id_establecimiento']/* $id_establecimiento */);

                $SUM_TOTAL_EST[$i]['establecimiento'] = $data_est_direc['ubigeo_distrito'];

                $ecc = array();
                $ecc = $dao_cc->listar_Ids_EmpresaCentroCosto($est[$i]['id_establecimiento']);
                // paso 03 listamos los trabajadores por Centro de costo 

                for ($j = 0; $j < count($ecc); $j++) {

                    $bandera_2 = false;
                    if ($ecc[$j]['id_empresa_centro_costo'] == $master_cc) {
                        $bandera_2 = true;
                    } else if ($cubo_est == "todo") {
                        $bandera_2 = true;
                    }

                    if ($bandera_2) {
                        //$contador_break = $contador_break + 1;
                        // LISTA DE TRABAJADORES
                        $data_tra = array();
                        $data_tra = $dao_pago->listar_2($id_etapa_pago, $est[$i]['id_establecimiento'], $ecc[$j]['id_empresa_centro_costo']);

                        if (count($data_tra)>0) {
                            
                            $SUM_TOTAL_CC[$i][$j]['establecimiento'] = $data_est_direc['ubigeo_distrito'];
                            $SUM_TOTAL_CC[$i][$j]['centro_costo'] = strtoupper($ecc[$j]['descripcion']);
                            $SUM_TOTAL_CC[$i][$j]['monto'] = 0;


                            //fwrite($fp, $LINEA);
                            fwrite($fp, $BREAK);
                            fwrite($fp, NAME_EMPRESA);
                            //$worksheet->write(($row + 1), ($col + 1), NAME_EMPRESA);

                            $descripcion1 = date("d/m/Y", strtotime($data_pd['fecha_modificacion']));
                            
                            fwrite($fp, str_pad("FECHA : ", 56, " ", STR_PAD_LEFT));
                            fwrite($fp, str_pad($descripcion1, 11, " ", STR_PAD_LEFT));
                            fwrite($fp, $BREAK);

                            fwrite($fp, str_pad("PAGINA :", 69, " ", STR_PAD_LEFT));
                            fwrite($fp, str_pad($contador_break, 11, " ", STR_PAD_LEFT));
                            fwrite($fp, $BREAK);

                            fwrite($fp, str_pad($_name_15/*"1RA QUINCENA"*/, 80, " ", STR_PAD_BOTH));
                            fwrite($fp, $BREAK);

                            fwrite($fp, str_pad("PLANILLA DEL MES DE " . strtoupper($nombre_mes) . " DEL " . $anio, 80, " ", STR_PAD_BOTH));
                            fwrite($fp, $BREAK);
                            fwrite($fp, $BREAK);

                            fwrite($fp, "LOCALIDAD : " . $data_est_direc['ubigeo_distrito']);
                            fwrite($fp, $BREAK);
                            fwrite($fp, $BREAK);

                            fwrite($fp, "CENTRO DE COSTO : " . strtoupper($ecc[$j]['descripcion']));
                            fwrite($fp, $BREAK);
                            fwrite($fp, $BREAK);
                            //$worksheet->write($row, $col, "##################################################");
                            
                            fwrite($fp, $LINEA);
                            fwrite($fp, $BREAK);
                            fwrite($fp, str_pad("N ", 4, " ", STR_PAD_LEFT));
                            fwrite($fp, str_pad("DNI", 12, " ", STR_PAD_RIGHT));
                            fwrite($fp, str_pad("APELLIDOS Y NOMBRES", 40, " ", STR_PAD_RIGHT));
                            fwrite($fp, str_pad("IMPORTE", 9, " ", STR_PAD_RIGHT));
                            fwrite($fp, str_pad("FIRMA", 15, " ", STR_PAD_RIGHT));
                            fwrite($fp, $BREAK);
                            fwrite($fp, $LINEA);
                            fwrite($fp, $BREAK);

                            for ($k = 0; $k < count($data_tra); $k++) {

                                $data = array();
                                $data = $data_tra[$k];

                                // Inicio de Boleta                            
                                generarRecibo15_txt2($fpx, $data, $nombre_mes, $anio);
                                // Final de Boleta
                                $texto_3 = $data_tra[$k]['apellido_paterno'] . " " . $data_tra[$k]['apellido_materno'] . " " . $data_tra[$k]['nombres'];                                
                                fwrite($fp, $BREAK);
                                fwrite($fp, str_pad(($k + 1) . " ", 4, " ", STR_PAD_LEFT));
                                fwrite($fp, str_pad($data_tra[$k]['num_documento'], 12, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad(strtoupper($texto_3), 40, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad($data_tra[$k]['sueldo'], 9, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("_______________", 15, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);
                                
                                // por persona
                                $SUM_TOTAL_CC[$i][$j]['monto'] = $SUM_TOTAL_CC[$i][$j]['monto'] + $data_tra[$k]['sueldo'];
                            }




                            $SUM_TOTAL_EST[$i]['monto'] = $SUM_TOTAL_EST[$i]['monto'] + $SUM_TOTAL_CC[$i][$j]['monto'];

                            //--- LINE
                            fwrite($fp, $BREAK);
                            //fwrite($fp, $LINEA);
                            fwrite($fp, $LINEA);
                            fwrite($fp, $BREAK);
                            fwrite($fp, str_pad("TOTAL " . $SUM_TOTAL_CC[$i][$j]['centro_costo'] . " " . $SUM_TOTAL_EST[$i]['establecimiento'], 56, " ", STR_PAD_RIGHT));
                            fwrite($fp, number_format($SUM_TOTAL_CC[$i][$j]['monto'], 2));
                            fwrite($fp, $BREAK);
                            fwrite($fp, $LINEA);
                            fwrite($fp, $BREAK);

                            fwrite($fp,chr(12));
                            //fwrite($fp, $BREAK . $BREAK . $BREAK . $BREAK);
                            //$row_a = $row_a + 5;
                        }//End Trabajadores
                    }//End Bandera.
                }//END FOR CCosto


                // CALCULO POR ESTABLECIMIENTOS
                 /* $SUM = 0.00;
                  for ($z = 0; $z < count($SUM_TOTAL_CC[$i]); $z++) {

                  fwrite($fp, str_pad($SUM_TOTAL_CC[$i][$z]['centro_costo'], 59, " ", STR_PAD_RIGHT));
                  fwrite($fp, number_format($SUM_TOTAL_CC[$i][$z]['monto'], 2));
                  fwrite($fp, $BREAK);


                  $SUM = $SUM + $SUM_TOTAL_CC[$i][$z]['monto'];
                  }
                  fwrite($fp, str_pad("T O T A L   G E N E R A L  --->>>", 59, " ", STR_PAD_RIGHT));
                  fwrite($fp, number_format($SUM, 2));
                 */

                fwrite($fp, $BREAK . $BREAK);
                
            }
        }//END FOR Est

        /*
          fwrite($fp, str_repeat('*', 85));
          fwrite($fp, $BREAK);
          fwrite($fp, "CALCULO FINAL ESTABLECIMIENTOS ");
          fwrite($fp, $BREAK);

          //$worksheet->write(($row+4), ($col + 1), ".::RESUMEN DE PAGOS::.");
          $SUM = 0;
          for ($z = 0; $z < count($SUM_TOTAL_EST); $z++) {

          fwrite($fp, str_pad($SUM_TOTAL_EST[$z]['establecimiento'], 59, " ", STR_PAD_RIGHT));
          fwrite($fp, number_format($SUM_TOTAL_EST[$z]['monto'], 2));
          fwrite($fp, $BREAK);


          $SUM = $SUM + $SUM_TOTAL_EST[$z]['monto'];
          }
          fwrite($fp, str_pad("T O T A L   G E N E R A L  --->>>", 59, " ", STR_PAD_RIGHT));
          fwrite($fp, number_format($SUM, 2));
          fwrite($fp, $BREAK);
          fwrite($fp, $BREAK);
         */
    }//END IF
//..............................................................................
// Inicio Exel
//..............................................................................
    //|---------------------------------------------------------------------------
    //| Calculos Finales
    //|
    //|---------------------------------------------------------------------------
    //
    //fwrite($fp, $BREAK . $BREAK . $BREAK . $BREAK . $BREAK . $BREAK . $BREAK . $BREAK . $BREAK);
    //fwrite($fp, $BREAK . $BREAK . $BREAK . $BREAK . $BREAK . $BREAK . $BREAK . $BREAK . $BREAK);


    fclose($fp);
    fclose($fpx);
    // $workbook->close();
    // .........................................................................
    // SEGUNDO ARCHIVO
    //..........................................................................










    $file = array();
    $file[] = $file_name;
    $file[] = ($file_name2);
    ////generarRecibo15_txt2($id_pdeclaracion, $id_etapa_pago);


    $zipfile = new zipfile();
    $carpeta = "file-" . date("d-m-Y") . "/";
    $zipfile->add_dir($carpeta);

    for ($i = 0; $i < count($file); $i++) {
        $zipfile->add_file(implode("", file($file[$i])), $carpeta . $file[$i]);
        //$zipfile->add_file( file_get_contents($file[$i]),$carpeta.$file[$i]);
    }

    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment; filename=zipfile.zip");

    echo $zipfile->file();
}

function generarRecibo15_txt2($fpx, $data, $nombre_mes, $anio) {

    //$file_name2 = NAME_COMERCIAL . '-BOLETA QUINCENA.txt';
    $BREAK = chr(13) . chr(10);
//..............................................................................
// Inicio Exel
//..............................................................................
    fwrite($fpx,chr(18));
    fwrite($fpx,chr(27));
    
    fwrite($fpx, $BREAK);    
    fwrite($fpx, str_pad(NAME_EMPRESA, 0, " ", STR_PAD_LEFT));
    fwrite($fpx, str_pad(NAME_EMPRESA, 45, " ", STR_PAD_LEFT));
    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK);

    fwrite($fpx, str_pad('R E C I B O', 20, " ", STR_PAD_LEFT));
    fwrite($fpx, str_pad('R E C I B O', 45, " ", STR_PAD_LEFT));
    fwrite($fpx, $BREAK);
    fwrite($fpx, str_pad('***********', 20, " ", STR_PAD_LEFT));
    fwrite($fpx, str_pad('***********', 45, " ", STR_PAD_LEFT));

    fwrite($fpx, $BREAK.$BREAK); 
    fwrite($fpx, $BREAK); 

    fwrite($fpx, str_pad('ADELANTO DE QUINCENA CORRESPONDIENTE', 20, " ", STR_PAD_LEFT));
    fwrite($fpx, str_pad('ADELANTO DE QUINCENA CORRESPONDIENTE', 45, " ", STR_PAD_LEFT));
    fwrite($fpx, $BREAK);
    fwrite($fpx, str_pad('Al MES DE ' . strtoupper($nombre_mes) . " DEL " . $anio, 20, " ", STR_PAD_LEFT));
    fwrite($fpx, str_pad('Al MES DE ' . strtoupper($nombre_mes) . " DEL " . $anio, 45, " ", STR_PAD_LEFT));

    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK);
    $_NOMBRE_ = $data['apellido_paterno'] . " " . $data['apellido_materno'] . " " . $data['nombres'];
    fwrite($fpx, str_pad('NOMBRES', 9, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad(textoaMedida(28, ": ".$_NOMBRE_), 36, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad('NOMBRES', 9, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad(textoaMedida(28, ": ".$_NOMBRE_), 27, " ", STR_PAD_RIGHT));
//fwrite($fpx, $BREAK);

    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK);
    fwrite($fpx, str_pad('CANTIDAD', 9, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad(': S/', 6, " ", STR_PAD_RIGHT));
    
    fwrite($fpx, str_pad($data['sueldo'], 30, " ", STR_PAD_RIGHT));
    
    fwrite($fpx, str_pad('CANTIDAD', 9, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad(': S/', 6, " ", STR_PAD_RIGHT));
    
    fwrite($fpx, str_pad($data['sueldo'], 8, " ", STR_PAD_LEFT));
    fwrite($fpx, $BREAK);

    fwrite($fpx, $BREAK);
    fwrite($fpx, str_pad('N. CTA', 9, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad(': -  -', 36, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad('N. CTA', 9, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad(': -  -', 27, " ", STR_PAD_RIGHT));
    fwrite($fpx, $BREAK);

    fwrite($fpx, $BREAK);
    $_FECHA_CREACION_ = getFechaPatron($data['fecha_creacion'], "d/m/Y");
    fwrite($fpx, str_pad('FECHA', 9, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad(": ".$_FECHA_CREACION_, 36, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad('FECHA', 9, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad(": ".$_FECHA_CREACION_, 27, " ", STR_PAD_RIGHT));

    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK);

    fwrite($fpx, $BREAK);
    fwrite($fpx, str_pad('_______________', 0, " ", STR_PAD_LEFT)); //VO
    fwrite($fpx, str_pad('_______________', 20, " ", STR_PAD_LEFT));

    fwrite($fpx, str_pad('_______________', 24, " ", STR_PAD_LEFT));   //VO           
    fwrite($fpx, str_pad('_______________', 20, " ", STR_PAD_LEFT));

    fwrite($fpx, $BREAK);
    fwrite($fpx, str_pad('     Vo.Bo.', 20, " ", STR_PAD_RIGHT)); //VO
    fwrite($fpx, str_pad('RECIBI CONFORME', 23, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad('     Vo.Bo.', 10, " ", STR_PAD_RIGHT));  //VO
    fwrite($fpx, str_pad('RECIBI CONFORME', 25, " ", STR_PAD_LEFT));

//fwrite($fpx, str_pad('RECIBI CONFORME', 0, " ", STR_PAD_LEFT));   
    fwrite($fpx, $BREAK);
    fwrite($fpx, str_pad('DNI. '.$data['num_documento'], 33, " ", STR_PAD_LEFT));
    fwrite($fpx, str_pad('DNI. '.$data['num_documento'], 44, " ", STR_PAD_LEFT));

    //fclose($fpx);
    // return $file_name2;
    fwrite($fpx,chr(12));
    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK.$BREAK);
}

function generarRecibo15Exel($id_pdeclaracion, $dataa) {

    //$data = $dataa[0];

    $dao = new PlameDeclaracionDao();
    $data_pd = $dao->buscar_ID($id_pdeclaracion);
    $fecha = $data_pd['periodo'];

    $nombre_mes = getNameMonth(getFechaPatron($fecha, "m"));
    $anio = getFechaPatron($fecha, "Y");

// Creating a workbook
    $workbook = new Spreadsheet_Excel_Writer();

// sending HTTP headers
    $workbook->send(NAME_COMERCIAL . '-1RA QUINCENA.xls');
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
    $worksheet = & $workbook->addWorksheet('hoja 01');


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
            $worksheet->write($row, ($col + 1), NAME_EMPRESA, $negrita);
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




    //------------------------------------------------------------------------
    //----------------------- HOJA 2 ---------------------------------------
    //------------------------------------------------------------------------  
    $worksheet = & $workbook->addWorksheet('hoja 02');




    //--------------------------------------------------------------------------
// The actual data
//$workbook->setVersion(8);
// Let's send the file
    $workbook->close();
}
?>



