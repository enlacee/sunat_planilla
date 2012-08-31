<?php

function generarRecibo15Exel($id_pdeclaracion,$dataa) {

    $data = $dataa[0];

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

// tabla Principall Lote
// -- Estableciendo formato en Columnas
    $worksheet->setColumn(0, 0, 1);
    $worksheet->setColumn(1, 1, 15);
    $worksheet->setColumn(2, 2, 14);
//-- Estableciendo formato fila 
    $worksheet->setRow(0, 22);
//$worksheet->setRow ( integer $row , integer $height , mixed $format=0 )
    $array = array(
        "01",
        "02",
        "03",
        "04",
        "05"       
    );
    $fila_exel++;
    $worksheet->writeRow(0, 0, $array, $format_tabla_head_centrado);
    $fila_exel++;
    //--------------------------------------------------------------------------
// The actual data

    //$worksheet->write(0, 1, 'CAMUENTE S.A', $negrita);
    $worksheet->write(2, 3, 'RECIBO', $negrita);


    $descripcion1 = "ADELANTO DE QUINCENA CORRESPONDIENTE";
    $descripcion2 = "Al MES DE " . strtoupper($nombre_mes) . " DEL " . $anio;
    $worksheet->write(4, 1, $descripcion1);
    $worksheet->write(5, 1, $descripcion2);

    $_NOMBRE = "NOMBRES: ";
    $_NOMBRE_ = $data['apellido_paterno'] . " " . $data['apellido_materno'] . " " . $data['nombres'];
    $worksheet->write(7, 1, $_NOMBRE);
    $worksheet->write(7, 2, strtoupper($_NOMBRE_));

    $_CANTIDAD = "SUELDO: ";
    $_CANTIDAD_ = $data['sueldo'];
    $worksheet->write(8, 1, $_CANTIDAD);
    $worksheet->write(8, 2, $_CANTIDAD_, $format_decimal_total_azul);

    $_N_CUENTA = "N. CTA:";
    $_N_CUENTA_ = " -  -";
    $worksheet->write(9, 1, $_N_CUENTA);
    $worksheet->write(9, 2, $_N_CUENTA_);

    $_FECHA_CREACION = "FECHA: ";
    $_FECHA_CREACION_ = getFechaPatron($data['fecha_creacion'], "d/m/Y");
    $worksheet->write(10, 1, $_FECHA_CREACION);
    $worksheet->write(10, 2, $_FECHA_CREACION_);


    $worksheet->write(14, 2, "_______________");
    $worksheet->write(15, 2, "      Vo.Bo.        ");


    $worksheet->write(14, 3, "__________________");
    $worksheet->write(15, 3, "RECIBI CONFORME");
    $worksheet->write(16, 3, "DNI. " . $data['num_documento']);



//$workbook->setVersion(8);
// Let's send the file
    $workbook->close();
}

?>
