<?php
//Nombre descriptivo de del archivo  no vincula la funcion

//19-11-2012
/**
 * Reporte Anual:
 * - Afp 
 * - Onp
 * - Essalud
 */
function generarLiquidacionAnual($ID_PDECLARACION, $PERIODO) {
    
    $anio = getFechaPatron($PERIODO, "Y");
    $mes = getFechaPatron($PERIODO, "m");
    $nombre_mes = getNameMonth($mes);
    
//..............................................................................
// Inicio Exel
//..............................................................................
// Creating a workbook
    $workbook = new Spreadsheet_Excel_Writer();
// sending HTTP headers
    $workbook->send($anio . '.liquidacion.xls');
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
    //----------------------------------------------------------------------
    //----------------------- HOJA 2 ---------------------------------------
    //----------------------------------------------------------------------    
    $worksheet = & $workbook->addWorksheet('hoja 02');
    
    
     
    $mi_afp = afpArrayConstruido();
    //---- file
    $filee = array();
    $all_ingreso = 0;

    if (is_array($mi_afp)){

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
            $data_tra_5 = $dao_eafp->codigoMovimiento_5($ID_PDECLARACION, $anio, $mes, $mi_afp[$i]['codigo']);
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
                            $data_tra[$j]['fecha_movimiento'] = $data_tra_5[$a]['fecha_inicio'];
                            break;
                        endif;
                    endfor;
                }

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
    
    
    
    
    
    
}



?>
