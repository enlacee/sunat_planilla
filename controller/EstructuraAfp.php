<?php
//Nombre descriptivo de del archivo  no vincula la funcion

//---12/11/2012
function generarExelAfp($ID_PDECLARACION, $PERIODO) {

    //$id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $anio = getFechaPatron($PERIODO, "Y");
    $mes = getFechaPatron($PERIODO, "m");

    //01 Lista de trabajadores con AFP
    $dao_eafp = new EstructuraAfpDao();
    $data_tra = $dao_eafp->listarTrabajadoresAfp($ID_PDECLARACION);

    //02 CODIGOS de Movimientos
    // CODIGO = 1 = Inicio Relacion laboral    
    $data_tra_1 = $dao_eafp->codigoMovimiento_1($ID_PDECLARACION, $anio, $mes);
    
    $data_tra_1id = arrayId($data_tra_1, 'id_trabajador');


    // CODIGO = 2 = Término de relación laboral.   
    $data_tra_2 = $dao_eafp->codigoMovimiento_2($ID_PDECLARACION, $anio, $mes);
    $data_tra_2id = arrayId($data_tra_2, 'id_trabajador');

    //----------- SUBSIDIADO
    // CODIGO = 3 = Inicio de subsidio (por accidente de trabajo u otro). 
    // - lesion
    // - maternidad
    $data_tra_3 = $dao_eafp->codigoMovimiento_3($ID_PDECLARACION);
    $data_tra_3id = arrayId($data_tra_3, 'id_trabajador');

    //----------- NO SUBSIDIADO
    // CODIGO = 4 =  Inicio de licencia sin goce de haber   
    $data_tra_4 = $dao_eafp->codigoMovimiento_4($ID_PDECLARACION);
    $data_tra_4id = arrayId($data_tra_4, 'id_trabajador');
    // CODIGO = 5 =  Inicio de período vacacional    
    $data_tra_5 = $dao_eafp->codigoMovimiento_5($ID_PDECLARACION, $anio, $mes);
    $data_tra_5id = arrayId($data_tra_5, 'id_trabajador');
    // CODIGO = 6  = todos tienen este codigo xq es fecha final.....
//..............................................................................
// Inicio Exel
//..............................................................................
// Creating a workbook
    $workbook = new Spreadsheet_Excel_Writer();
// sending HTTP headers
    $workbook->send($mes . '.' . $anio . 'afpnet.xls');
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

    $format_normal = & $workbook->addFormat();
    //$format_normal->setMerge();
    //$format_normal->setAlign('left');
    //$format_normal->setHAlign('right');
// Creating a worksheet
    $worksheet = & $workbook->addWorksheet('hoja 01');

    if (is_array($data_tra)) {
        $id = 0;
        $numero = 0;
        $duplicado = array();
        for ($i = 0; $i < count($data_tra); $i++) {
            $numero = $numero + 1;

            //-- LOGIC CODIGO = 1
            if (in_array($data_tra[$i]['id_trabajador'], $data_tra_1id)) {
                //Encontro hace busqueda
                for ($a = 0; $a < count($data_tra_1); $a++):
                    if ($data_tra[$i]['id_trabajador'] == $data_tra_1[$a]['id_trabajador']):
                        $data_tra[$i]['codigo_movimiento'] = 1;
                        $data_tra[$i]['fecha_movimiento'] = $data_tra_1[$a]['fecha_inicio'];
                        break;
                    endif;
                endfor;
            }

            //-- LOGIC CODIGO = 2
            if (in_array($data_tra[$i]['id_trabajador'], $data_tra_2id)) {
                //Encontro hace busqueda
                for ($a = 0; $a < count($data_tra_2); $a++):
                    if ($data_tra[$i]['id_trabajador'] == $data_tra_2[$a]['id_trabajador']):
                        if ($data_tra[$i]['codigo_movimiento'] == 1) {
                            $duplicado['estado'] = true;
                            $duplicado['codigo_movimiento'] = 2;
                            $duplicado['fecha_movimiento'] = $data_tra_2[$a]['fecha_fin'];
                        } else {
                            $duplicado['estado'] = false;
                            $data_tra[$i]['codigo_movimiento'] = 2;
                            $data_tra[$i]['fecha_movimiento'] = $data_tra_2[$a]['fecha_fin'];
                        }
                        break;
                    endif;
                endfor;
            }

            //-- LOGIC CODIGO = 3
            if (in_array($data_tra[$i]['id_trabajador'], $data_tra_3id)) {
                //Encontro hace busqueda
                for ($a = 0; $a < count($data_tra_3); $a++):
                    if ($data_tra[$i]['id_trabajador'] == $data_tra_3[$a]['id_trabajador']):
                        if (!is_null($data_tra_3[$a]['fecha_fin'])) {
                            $duplicado['estado'] = true;
                            $duplicado['codigo_movimiento'] = 6;
                            $duplicado['fecha_movimiento'] = $data_tra_3[$a]['fecha_fin'];
                        }
                        $data_tra[$i]['codigo_movimiento'] = 3;
                        $data_tra[$i]['fecha_movimiento'] = $data_tra_3[$a]['fecha_inicio'];
                        break;
                    endif;
                endfor;
            }

            //-- LOGIC CODIGO = 4
            if (in_array($data_tra[$i]['id_trabajador'], $data_tra_4id)) {
                //Encontro hace busqueda
                for ($a = 0; $a < count($data_tra_4); $a++):
                    if ($data_tra[$i]['id_trabajador'] == $data_tra_4[$a]['id_trabajador']):
                        if (!is_null($data_tra_4[$a]['fecha_fin'])) {
                            $duplicado['estado'] = true;
                            $duplicado['codigo_movimiento'] = 6;
                            $duplicado['fecha_movimiento'] = $data_tra_4[$a]['fecha_fin'];
                        }
                        $data_tra[$i]['codigo_movimiento'] = 4;
                        $data_tra[$i]['fecha_movimiento'] = $data_tra_4[$a]['fecha_inicio'];
                        break;
                    endif;
                endfor;
            }


            //-- LOGIC CODIGO = 5
            if (in_array($data_tra[$i]['id_trabajador'], $data_tra_5id)) {
                //Encontro hace busqueda
                for ($a = 0; $a < count($data_tra_5); $a++):
                    if ($data_tra[$i]['id_trabajador'] == $data_tra_5[$a]['id_trabajador']):
                        $data_tra[$i]['codigo_movimiento'] = 5;
                        $data_tra[$i]['fecha_movimiento'] = $data_tra_5[$a]['fecha_inicio'];

                        if (!is_null($data_tra_5[$a]['fecha_fin'])) {
                            $duplicado['estado'] = true;
                            $duplicado['codigo_movimiento'] = 6;
                            $duplicado['fecha_movimiento'] = $data_tra_5[$a]['fecha_fin'];
                        }
                        break;
                    endif;
                endfor;
            }

            $worksheet->write($id, 0, ($numero));
            $worksheet->write($id, 1, $data_tra[$i]['cuspp'], $format_normal);
            $worksheet->write($id, 2, $data_tra[$i]['num_documento'], $format_normal);
            $worksheet->write($id, 3, $data_tra[$i]['apellido_paterno'], $format_normal);
            $worksheet->write($id, 4, $data_tra[$i]['apellido_materno'], $format_normal);
            $worksheet->write($id, 5, $data_tra[$i]['nombres'], $format_normal);

            $worksheet->write($id, 6, $data_tra[$i]['codigo_movimiento'], $format_normal);
            $worksheet->write($id, 7, getFechaPatron($data_tra[$i]['fecha_movimiento'], "d/m/Y"), $format_normal);
            //==================================================== 
            $all_ingreso = get_AFP_Ingresos($ID_PDECLARACION, $data_tra[$i]['id_trabajador']);
            //====================================================
            $worksheet->write($id, 8, $all_ingreso, $format_normal);
            $worksheet->write($id, 9, 0, $format_normal);
            $worksheet->write($id, 10, 0, $format_normal);
            $worksheet->write($id, 11, 0, $format_normal);

            $id++;
            //Duplicamos 
            if ($duplicado['estado']):
                $numero = $numero + 1;
                $worksheet->write($id, 0, ($numero));
                $worksheet->write($id, 1, $data_tra[$i]['cuspp'], $format_normal);
                $worksheet->write($id, 2, $data_tra[$i]['num_documento'], $format_normal);
                $worksheet->write($id, 3, $data_tra[$i]['apellido_paterno'], $format_normal);
                $worksheet->write($id, 4, $data_tra[$i]['apellido_materno'], $format_normal);
                $worksheet->write($id, 5, $data_tra[$i]['nombres'], $format_normal);

                $worksheet->write($id, 6, $duplicado['codigo_movimiento'], $format_normal);
                $worksheet->write($id, 7, getFechaPatron($duplicado['fecha_movimiento'], "d/m/Y"), $format_normal);
                $worksheet->write($id, 8, 0, $format_normal);
                $worksheet->write($id, 9, 0, $format_normal);
                $worksheet->write($id, 10, 0, $format_normal);
                $worksheet->write($id, 11, 0, $format_normal);
                $duplicado = null;
                $id++;
            endif;
        }//END FOR
    }//END IF
//..............................................................................
// Inicio Exel
//..............................................................................
    $workbook->close();
}


//Generar Rporte
function generarReporteAfp($ID_PDECLARACION, $PERIODO) {

    //$id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $anio = getFechaPatron($PERIODO, "Y");
    $mes = getFechaPatron($PERIODO, "m");
    $nombre_mes = getNameMonth($mes);

    $mi_afp = afpArrayConstruido();
    //variables file
    $BREAK = chr(13) . chr(10);
    $BR = ' ';
    $cab_comprimido = chr(27) . M . chr(15);
    $PUNTO = '|';

    //---- file
    $filee = array();
    $all_ingreso = 0;

    if (is_array($mi_afp)) {

        //Dao
        $dao_eafp = new EstructuraAfpDao();
        $dao_afp = new ConfAfpDao();
        $dao_tafp = new ConfAfpTopeDao();
        

        for ($i = 0; $i < count($mi_afp); $i++) {            
            
            $numero = 0;
            $contador_break = 0;
            
            $total = array();
            $total['ingresos'] = 0;
            $total['aporte_obligatorio'] = 0;
            $total['fondo_pension'] = 0;
            $total['seguro'] = 0;
            $total['comision'] = 0;
            $total['retencion_redistribucion'] = 0;
            $total['total'] = 0;            
            

            // file head
            $filee[$i]['nombre'] = $mi_afp[$i]['nombre'] . '.txt';
            $filee[$i]['fp'] = fopen($filee[$i]['nombre'], 'w');
            $fp = $filee[$i]['fp'];

            fwrite($fp, $cab_comprimido);

            $fp = cabecera_afp($fp, $mi_afp[$i]['nombre'], $nombre_mes, $anio, $BREAK, $PUNTO);

            //01 Lista de trabajadores con AFP        
            $data_tra = $dao_eafp->listarTrabajadoresAfp($ID_PDECLARACION, $mi_afp[$i]['codigo']);
            //echo "id_pdeclaracion".$ID_PDECLARACION;
            //echoo($mi_afp[$i]['codigo']);
            //echoo($data_tra);
            // CODIGO = 1 = Inicio Relacion laboral    
            $data_tra_1 = $dao_eafp->codigoMovimiento_1($ID_PDECLARACION, $anio, $mes, $mi_afp[$i]['codigo']);
            $data_tra_1id = arrayId($data_tra_1, 'id_trabajador');

            // CODIGO = 5 =  Inicio de período vacacional    
            $data_tra_5 = $dao_eafp->codigoMovimiento_5($ID_PDECLARACION,$mi_afp[$i]['codigo']);
            $data_tra_5id = arrayId($data_tra_5, 'id_trabajador');


            //DAO vigente AFP
            $afp = $dao_afp->vigenteAfp($mi_afp[$i]['codigo'], $PERIODO);

            // Configuracion de Tope Afp 02/10/2012            
            $monto_tope = $dao_tafp->vigenteAux($PERIODO);

            //echo "***********************************************************\n";

            $A_OBLIGATORIO = floatval($afp['aporte_obligatorio']);
            $COMISION = floatval($afp['comision']);
            $PRIMA_SEGURO = floatval($afp['prima_seguro']);


            for ($j = 0; $j < count($data_tra); $j++):
                $numero = $numero + 1;
                //++
                $contador_break = $contador_break + 1;
                if ($contador_break == 47) {
                    fwrite($fp, chr(12));
                    $fp = cabecera_afp($fp, $mi_afp[$i]['nombre'], $nombre_mes, $anio, $BREAK, $PUNTO);
                    //fwrite($fp,"-ANB-");
                    $contador_break = 0;
                }
                //++  
                                  
                //-- LOGIC CODIGO = 1
                if (in_array($data_tra[$j]['id_trabajador'], $data_tra_1id)) {
                    //Encontro hace busqueda
                    for ($a = 0; $a < count($data_tra_1); $a++):
                        if ($data_tra[$j]['id_trabajador'] == $data_tra_1[$a]['id_trabajador']):
                            $data_tra[$j]['codigo_movimiento'] = 1;
                            $data_tra[$j]['fecha_movimiento'] = $data_tra_1[$a]['fecha_inicio'];
                            break;
                        endif;
                    endfor;
                }

                //-- LOGIC CODIGO = 5
                if (in_array($data_tra[$i]['id_trabajador'], $data_tra_5id)) {
                    //Encontro hace busqueda
                    for ($a = 0; $a < count($data_tra_5); $a++):
                        if ($data_tra[$j]['id_trabajador'] == $data_tra_5[$a]['id_trabajador']):
                            $data_tra[$j]['codigo_movimiento'] = 5;
                            $data_tra[$j]['fecha_movimiento'] = 'fecha inicio vac.';//$data_tra_5[$a]['fecha_inicio'];
                            break;
                        endif;
                    endfor;
                }
                
                
                
                //get_AFP_Ingresos2(DATA_CONCEPTOS);  ANB
                //================================================================================== 
                $all_ingreso = number_format_2( get_AFP_Ingresos($ID_PDECLARACION, $data_tra[$j]['id_trabajador']) );
                //==================================================================================    
                // UNO = comision porcentual
                $_601 = number_format_2( (floatval($all_ingreso)) * ($COMISION / 100) );

                // DOS prima de seguro
                $_606 = number_format_2( (floatval($all_ingreso)) * ($PRIMA_SEGURO / 100) );

                // TRES = aporte obligatorio
                $_608 = number_format_2( (floatval($all_ingreso)) * ($A_OBLIGATORIO / 100) );

                /*
                 *  Conficion Parametro Tope. Monto maximo a pagar por all las
                 *  afp segun el periodo  d/m/Y
                 */
                $_608 = ($_608 > $monto_tope) ? $monto_tope : $_608;

                // file body                    
                fwrite($fp, $PUNTO);
                fwrite($fp, str_pad($numero, 3, " ", STR_PAD_LEFT));
                fwrite($fp, $PUNTO);
                fwrite($fp, str_pad($data_tra[$j]['cuspp'], 18, " ", STR_PAD_BOTH));
                fwrite($fp, $PUNTO);
                //fwrite($fp, str_pad(' ',50," ",STR_PAD_BOTH));    
                fwrite($fp, str_pad($BR . limpiar_caracteres_especiales_plame($data_tra[$j]['apellido_paterno']), 15, " ", STR_PAD_RIGHT));
                fwrite($fp, str_pad(limpiar_caracteres_especiales_plame($data_tra[$j]['apellido_materno']), 15, " ", STR_PAD_RIGHT));
                fwrite($fp, str_pad(limpiar_caracteres_especiales_plame($data_tra[$j]['nombres']) . $BR, 20, " ", STR_PAD_RIGHT));
                fwrite($fp, $PUNTO);

                fwrite($fp, str_pad($data_tra[$j]['codigo_movimiento'], 6, " ", STR_PAD_BOTH));    //codigo movimiento
                fwrite($fp, $PUNTO);
                fwrite($fp, str_pad(getFechaPatron($data_tra[$j]['fecha_movimiento'], 'd/m/Y') . $BR, 13, " ", STR_PAD_LEFT));  //fecha   
                fwrite($fp, $PUNTO);
                fwrite($fp, str_pad( number_format_var($all_ingreso) . $BR, 12, " ", STR_PAD_LEFT));
                $total['ingresos'] = $total['ingresos'] + $all_ingreso;

                fwrite($fp, $PUNTO);
                fwrite($fp, str_pad(number_format_var($_608) . $BR, 13, " ", STR_PAD_LEFT));
                $total['aporte_obligatorio'] = $total['aporte_obligatorio'] + $_608;

                fwrite($fp, $PUNTO);
                fwrite($fp, str_pad('--', 12, " ", STR_PAD_BOTH));
                fwrite($fp, $PUNTO);
                fwrite($fp, str_pad('--', 13, " ", STR_PAD_BOTH));
                fwrite($fp, $PUNTO);
                fwrite($fp, str_pad('--', 11, " ", STR_PAD_BOTH));
                fwrite($fp, $PUNTO);
                fwrite($fp, str_pad(number_format_var($_608) . $BR, 10, " ", STR_PAD_LEFT));
                $total['fondo_pension'] = $total['fondo_pension'] + $_608;


                fwrite($fp, $PUNTO);
                fwrite($fp, str_pad(number_format_var($_606) . $BR, 12, " ", STR_PAD_LEFT));   //seguros 
                $total['seguro'] = $total['seguro'] + $_606;

                fwrite($fp, $PUNTO);
                fwrite($fp, str_pad(number_format_var($_601) . $BR, 12, " ", STR_PAD_LEFT)); //comision 
                $total['comision'] = $total['comision'] + $_601;

                fwrite($fp, $PUNTO);
                $total_redistri = ($_606 + $_601);
                fwrite($fp, str_pad(number_format_var($total_redistri) . $BR, 15, " ", STR_PAD_LEFT)); //total retribuciones 
                $total['retencion_redistribucion'] = $total['retencion_redistribucion'] + $total_redistri;

                fwrite($fp, $PUNTO);
                $total_total = ($_608 + $_606 + $_601);
                fwrite($fp, str_pad(number_format_var($total_total) . $BR, 14, " ", STR_PAD_LEFT));
                $total['total'] = $total['total'] + $total_total;
                //fwrite($fp, $PUNTO); 
                fwrite($fp, $BREAK);

            endfor;
            
        $fp = pie_afp($fp, $total, $BREAK, $PUNTO);        
        fclose($fp);            
        }//ENDFOR    

    }//ENDIF
    
    
    // ..............
    $file = array();
    $file = $filee;

    $zipfile = new zipfile();
    $carpeta = "file-" . date("d-m-Y") . "/";
    $zipfile->add_dir($carpeta);

    for ($i = 0; $i < count($file); $i++):
        $zipfile->add_file(implode("", file($file[$i]['nombre'])), $carpeta . $file[$i]['nombre']);
    endfor;

    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment; filename=zipfile.zip");
    echo $zipfile->file();
}




function cabecera_afp($fp, $nombre_afp, $nombre_mes, $anio, $BREAK, $PUNTO) {

    //$PUNTO = '³';
    $BR = ' ';
    $linea_caja = str_repeat('-', 230);

    fwrite($fp, str_pad('PRESENTACION PAGO', 50, " ", STR_PAD_RIGHT));
    fwrite($fp, $BREAK);
    fwrite($fp, str_pad('PLANILLA DEL MES ' . $nombre_mes . ' - ' . $anio, 85, " ", STR_PAD_RIGHT));
    //fwrite($fp, $BREAK);

    fwrite($fp, str_pad("DETALLE ADICIONAL DE LA PLANILLA DE APORTES PREVICIONALES", 100, " ", STR_PAD_RIGHT));
    fwrite($fp, $BREAK);
    fwrite($fp, $BREAK);
    fwrite($fp, str_pad($nombre_afp, 57, " ", STR_PAD_RIGHT));
    fwrite($fp, $BREAK);
    fwrite($fp, $BREAK);
    fwrite($fp, str_pad('MES : ' . $nombre_mes . '/' . $anio, 57, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad(' ', 57, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad(' ', 57, " ", STR_PAD_RIGHT));

    fwrite($fp, $BREAK);


    fwrite($fp, 'I.-DATOS BASICOS DEL EMPLEADOR');
    fwrite($fp, $BREAK);
    fwrite($fp, 'NOMBRE O RAZON SOCIAL : '.NAME_EMPRESA);
    fwrite($fp, $BREAK);
    fwrite($fp, 'RUC : '.RUC);
    fwrite($fp, $BREAK);    
    
    
    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('IDENTIFICACION DEL AFILIADO', 73, " ", STR_PAD_RIGHT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('MOV. DE PERSONAL', 20, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 12, " ", STR_PAD_RIGHT));
    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('FONDO DE PENSIONES', 63, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('RETENCIONES Y RETRIBUCIONES', 41, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 14, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);

    fwrite($fp, $PUNTO);
    fwrite($fp, str_repeat('-', 73));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_repeat('-', 20));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Remunerac.', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);

    fwrite($fp, str_repeat('-', 63));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_repeat('-', 41));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 14, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);


    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 3, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 18, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 50, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 6, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 13, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 13, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Aportes Voluntario', 26, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 11, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 10, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 15, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('T O T A L', 14, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);



    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 3, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 18, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('A P E L L I D O S  Y  N O M B R E S', 50, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Tipo', 6, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Fecha', 13, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Asegurable', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Aporte', 13, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_repeat('-', 26));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Aporte', 11, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Total', 10, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Seguros', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Comision %', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Total', 15, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 14, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);



    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('N', 3, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('CUSPP', 18, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 50, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 6, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('dd/mm/aaaa', 13, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Obligatorio', 13, " ", STR_PAD_BOTH));

    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Con fin', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Sin fin', 13, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Empleador', 11, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Fondo de', 10, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Sobre', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Retenciones y', 15, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 14, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);


    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 3, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 18, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    //fwrite($fp, str_pad(' ',50," ",STR_PAD_BOTH));    
    fwrite($fp, str_pad('PATERNO', 15, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad('MATERNO', 15, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad('NOMBRES', 20, " ", STR_PAD_RIGHT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 6, " ", STR_PAD_BOTH));    //codigo movimiento
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 13, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 13, " ", STR_PAD_BOTH));

    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Previsional', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Previsional', 13, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 11, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Pensiones', 10, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('R.A.', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('Retribuciones', 15, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 14, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);

    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);


/*
    //data
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(88, 3, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('xdc25xc51x41x1s', 18, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    //fwrite($fp, str_pad(' ',50," ",STR_PAD_BOTH));    
    fwrite($fp, str_pad($BR . 'COPITAN', 15, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad('NORABUENA', 15, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad('VICTOR ANIBAL' . $BR, 20, " ", STR_PAD_RIGHT));
    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad(5, 6, " ", STR_PAD_BOTH));    //codigo movimiento
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' 01/06/2012' . $BR, 13, " ", STR_PAD_LEFT));  //fecha   
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(3985.00 . $BR, 12, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(398.00 . $BR, 13, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('--', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('--', 13, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('--', 11, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(398.50 . $BR, 10, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(51.41 . $BR, 12, " ", STR_PAD_LEFT));   //seguros    
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(69.74 . $BR, 12, " ", STR_PAD_LEFT)); //comision     
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(121.15 . $BR, 15, " ", STR_PAD_LEFT)); //total retribuciones 
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(519.65 . $BR, 14, " ", STR_PAD_LEFT));
    //fwrite($fp, $PUNTO);     
    fwrite($fp, $BREAK);
*/
    return $fp;
}

function pie_afp($fp, $total, $BREAK, $PUNTO) {
    /* $total = array();
      $total['ingresos'] = 0;
      $total['aporte_obligatorio'] = 0;
      $total['fondo_pension'] = 0;
      $total['seguro'] = 0;
      $total['comision'] = 0;
      $total['retencion_redistribucion'] = 0;
      $total['total'] = 0; */
    //data
    $BR = ' ';
    $linea_caja = str_repeat('-', 230);
    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);
    
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 3, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 18, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    //fwrite($fp, str_pad(' ',50," ",STR_PAD_BOTH));    
    fwrite($fp, str_pad(' ', 15, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad(' ', 15, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad(' ', 20, " ", STR_PAD_RIGHT));
    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad(' ', 6, " ", STR_PAD_BOTH));    //codigo movimiento
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(' ', 13, " ", STR_PAD_LEFT));  //fecha   
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(number_format_var($total['ingresos']).$BR, 12, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(number_format_var($total['aporte_obligatorio']). $BR, 13, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('--', 12, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('--', 13, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('--', 11, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(number_format_var($total['fondo_pension']). $BR, 10, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(number_format_var($total['seguro']) . $BR, 12, " ", STR_PAD_LEFT));   //seguros    
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(number_format_var($total['comision']) . $BR, 12, " ", STR_PAD_LEFT)); //comision     
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(number_format_var($total['retencion_redistribucion']) . $BR, 15, " ", STR_PAD_LEFT)); //total retribuciones 
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad(number_format_var($total['total']) . $BR, 14, " ", STR_PAD_LEFT));
    //fwrite($fp, $PUNTO); 
    fwrite($fp, $BREAK);
    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);

    return $fp;
}

?>
