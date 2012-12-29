<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    //ESTRUTURA AFP
    require_once '../dao/EstructuraAfpDao.php';
    require_once '../dao/TrabajadorPdeclaracionDao.php';
    require_once '../dao/PlameDeclaracionDao.php';
    require_once '../dao/EstructuraLiquidacionDao.php';

    // Escribir Exel 2003
    require_once '../util/Spreadsheet/Excel/Writer.php';
    /* Establecer configuraci�n regional al holand�s */
    setlocale(LC_ALL, 'es_Es');
}


if ($op == "reporte_liquidacion") {
    generarLiquidacionAnual();
}

//echo (!empty($response)) ? json_encode($response) : '';
//Nombre descriptivo de del archivo  no vincula la funcion
//19-11-2012
/**
 * Reporte Anual:
 * - Afp 
 * - Onp
 * - Essalud
 */

function generarLiquidacionAnual() {
    //echoo($_REQUEST);
    $PERIODO = $_REQUEST['periodo'];

    $anio = getFechaPatron($PERIODO, "Y");


    $dao_pd = new PlameDeclaracionDao();
    $dao_el = new EstructuraLiquidacionDao();
    $dao_eafp = new EstructuraAfpDao();


    $data_base = $dao_el->dataBase($anio);
    $data_periodo = $dao_pd->dataPeriodos(ID_EMPLEADOR_MAESTRO, $anio);

    $mi_afp = afpArrayConstruido();
    $iden = count($mi_afp);
    $mi_afp[$iden]['codigo'] = 02;
    $mi_afp[$iden]['nombre'] = 'ONP';

    $mi_afp[($iden + 1)]['codigo'] = 00;
    $mi_afp[($iden + 1)]['nombre'] = 'ESSALUD';

//    $anio = 2012;
//    echoo($mi_afp);
    $arreglo = array();
    $cubodin = null;
    for ($i = 0; $i < count($mi_afp); $i++) {

        $cubodin = $mi_afp[$i]['codigo'];
        $data = array();
        $c = 0;
        for ($j = 0; $j < count($data_base); $j++):

            for ($k = 0; $k < count($data_periodo); $k++): // # MES A MES DE TRABAJADOR 1-12  Y regimen pensionario
                //echo "<br>periodo = ".$k;
                $data_rp = null;
                //----------------------------------------------------------------------------------------------------------------------
                // PASO 01 Lista de trabajadores con AFP y ONP 
                if ($cubodin != 00):
                    $data_tra = $dao_eafp->listarTrabajadoresAfp($data_periodo[$k]['id_pdeclaracion'], $cubodin);
                    $data_tra_id = arrayId($data_tra, 'id_persona');

                    if (in_array($data_base[$j]['id_persona'], $data_tra_id)):
                        if ($cubodin == 02):
                            $data_rp = $dao_el->data_ONP($data_periodo[$k]['id_pdeclaracion'], $data_base[$j]['id_persona']); //id_persona                              
                            $data[$c]['apellido_paterno'] = $data_base[$j]['apellido_paterno'];
                            $data[$c]['apellido_materno'] = $data_base[$j]['apellido_materno'];
                            $data[$c]['nombres'] = $data_base[$j]['nombres'];
                            $data[$c]['documento'] = $data_base[$j]['num_documento'];

                            $data[$c]['id_persona'] = $data_base[$j]['id_persona'];
                            $data[$c]['tipo'] = $mi_afp[$i]['nombre']; //'02';
                            $data[$c]['monto'][$k] = floatval($data_rp[0]['monto_pagado']);
                        else: // 21 -22-23-24                                


                            $data_rp = $dao_el->dataAFP($data_periodo[$k]['id_pdeclaracion'], $data_base[$j]['id_persona']); //id_persona                              
                            $data[$c]['apellido_paterno'] = $data_base[$j]['apellido_paterno'];
                            $data[$c]['apellido_materno'] = $data_base[$j]['apellido_materno'];
                            $data[$c]['nombres'] = $data_base[$j]['nombres'];

                            $data[$c]['documento'] = $data_rp[0]['cuspp'];
                            $data[$c]['id_persona'] = $data_base[$j]['id_persona'];
                            $data[$c]['tipo'] = $mi_afp[$i]['nombre'];
                            ;
                            $data[$c]['monto'][$k] = floatval($data_rp[0]['monto_pagado']);
                        endif;
                    endif;

                elseif ($cubodin == 00):  //ESSALUD   all los trabajadores
                    $data_rp = $dao_el->dataEssalud($data_periodo[$k]['id_pdeclaracion'], $data_base[$j]['id_persona']); //id_persona
                    $data[$c]['apellido_paterno'] = $data_base[$j]['apellido_paterno'];
                    $data[$c]['apellido_materno'] = $data_base[$j]['apellido_materno'];
                    $data[$c]['nombres'] = $data_base[$j]['nombres'];
                    $data[$c]['documento'] = $data_base[$j]['num_documento'];

                    $data[$c]['id_persona'] = $data_base[$j]['id_persona'];
                    $data[$c]['tipo'] = $mi_afp[$i]['nombre'];
                    ;
                    $data[$c]['monto'][$k] = floatval($data_rp[0]['monto_pagado']);
                endif;

            //----------------------------------------------------------------------------------------------------------------------


            endfor; //fin periodos
            $c++;
        endfor;

        $arreglo[$i] = array_values($data);
    }



    //echoo($arreglo);
// Creating a workbook
    $workbook = new Spreadsheet_Excel_Writer();
// sending HTTP headers
    $workbook->send(".liquidacion.xls");

//ESTILOS EXEL
    $negrita = & $workbook->addFormat();
    $negrita->setBold();
    //-- Colores RGB
    $workbook->setCustomColor(11, 0, 0, 150);
    $workbook->setCustomColor(12, 192, 192, 192);
    $workbook->setCustomColor(13, 221, 60, 16);
    $workbook->setCustomColor(14, 255, 255, 0);

//--- format_titulo
    $format_titulo = $workbook->addFormat();
    $format_titulo->setLocked();
    $format_titulo->setSize(14);
    $format_titulo->setColor("black");
    $format_titulo->setBold();

//--- format_titulo2
    $format_titulo2 = $workbook->addFormat();
    $format_titulo2->setSize(12);
    $format_titulo2->setColor("black");
    $format_titulo2->setBold();
    //$format_titulo2->setFgColor(14);
//-- Format_txt_Centrado
    $format_tabla_head_centrado = & $workbook->addFormat();
    $format_tabla_head_centrado->setBold();
    $format_tabla_head_centrado->setSize(10);
    $format_tabla_head_centrado->setTextWrap(1);
    $format_tabla_head_centrado->setBorder(1);
    $format_tabla_head_centrado->setBorder(3);
    $format_tabla_head_centrado->setVAlign('vjustify');
    //$format_tabla_head_centrado->setHAlign('justify');

    $format_normal = & $workbook->addFormat();
    $format_normal->setItalic();
    $moneda = ''; // '$'
//--format_decimal
    $format_decimal = & $workbook->addFormat();
    $format_decimal->setNumFormat($moneda . '#,##0.00;' . $moneda . '-#,##0.00');
//--format_decimal_amarillo
    $format_decimal_amarillo = & $workbook->addFormat();
    $format_decimal_amarillo->setNumFormat($moneda . '#,##0.00;' . $moneda . '-#,##0.00');
    $format_decimal_amarillo->setFgColor(14);

    //VARIABLES FOR MI EXEL
    ;
    $array = array(
        "No.",
        "CUISPP/DNI",
        "Ap. Paterno",
        "Ap. Materno",
        "1er. Nombre",
        "2do. Nombre",
        getNameMonth(1, 1),
        getNameMonth(2, 1),
        getNameMonth(3, 1),
        getNameMonth(4, 1),
        getNameMonth(5, 1),
        getNameMonth(6, 1),
        getNameMonth(7, 1),
        getNameMonth(8, 1),
        getNameMonth(9, 1),
        getNameMonth(10, 1),
        getNameMonth(11, 1),
        getNameMonth(12, 1),
        "TOTAL"
    );

    for ($i = 0; $i < count($mi_afp); $i++):

        $worksheet = & $workbook->addWorksheet($mi_afp[$i]['nombre']);


        $worksheet->write(1, 5, 'LIQUIDACION ANUAL DE APORTES Y RETENCIONES PREVISIONALES', $format_titulo);
        $worksheet->write(2, 9, '( Ley 27605 )', $format_titulo);
        $worksheet->write(3, 1, 'Empresa :', $format_titulo);
        $worksheet->write(3, 3, NAME_EMPRESA, $format_titulo);

        $worksheet->write(4, 1, 'RUC :', $format_titulo);
        $worksheet->write(4, 3, RUC, $format_titulo);
        $worksheet->writeNote(4, 3, NAME_COMERCIAL);

        $worksheet->write(5, 3, $mi_afp[$i]['nombre'], $format_titulo);
        $worksheet->write(6, 1, "Liquidación correspondiente a los afiliados de " . $mi_afp[$i]['nombre'], $format_normal);
        $worksheet->write(7, 1, "ANIO : " . $anio, $format_titulo);

        //$worksheet->hideGridLines();
        $worksheet->setColumn(0, 0, 1);
        $worksheet->setColumn(1, 1, 4);
        $worksheet->setColumn(2, 2, 16);
        $worksheet->setColumn(3, 3, 16);
        $worksheet->setColumn(4, 4, 16); //# p
        $worksheet->setColumn(5, 5, 13);
        $worksheet->setColumn(6, 6, 10);
        $worksheet->setColumn(7, 7, 9);
        $worksheet->setColumn(8, 8, 9);
        $worksheet->setColumn(9, 9, 9);
        $worksheet->setColumn(10, 10, 9);
        $worksheet->setColumn(11, 11, 9);
        $worksheet->setColumn(12, 12, 9);
        $worksheet->setColumn(13, 13, 9);
        $worksheet->setColumn(14, 14, 9);
        $worksheet->setColumn(15, 15, 9);
        $worksheet->setColumn(16, 16, 9);
        $worksheet->setColumn(17, 17, 9);
        $worksheet->setColumn(18, 18, 10);


        $worksheet->writeRow(9, 1, $array, $format_tabla_head_centrado);



        $contador = 0;
        $data_base = $arreglo[$i];
        //echoo($arreglo[$i]);

        for ($j = 0; $j < count($data_base); $j++):

            $fila = $contador + 10;
            $worksheet->write($fila, 0, $data_base[$j]['id_persona']);
            $worksheet->write($fila, 1, $contador+1);
            $worksheet->write($fila, 2, $data_base[$j]['documento']);
            $worksheet->write($fila, 3, $data_base[$j]['apellido_paterno']);
            $worksheet->write($fila, 4, $data_base[$j]['apellido_materno']);
            $worksheet->write($fila, 5, $data_base[$j]['nombres']);

            $total['horizontal'] = 0;
            for ($k = 0; $k < 12; $k++): //12 meses

                $worksheet->write($fila, (7 + $k), $data_base[$j]['monto'][$k], $format_decimal);
                $total['horizontal'] = $total['horizontal'] + $data_base[$j]['monto'][$k];

            endfor;

            //SUMA HORIZONTAL
            $data_base[$j]['monto'][12] = $total['horizontal'];
            $worksheet->write($fila, 19, $total['horizontal'], $format_decimal_amarillo);
            //$worksheet->write($fila, 8, $total['horizontal']); 
            $contador++;
        endfor;



        $total2 = $data_base;
        //echoo($total2);
        $suma = array();

        for ($mes = 0; $mes < 13; $mes++) {
            $suma[$mes] = 0;
            for ($a = 0; $a < count($total2); $a++):  //#personas      
                //recorremos id_pdeclaracion 
                //var_dump($total2[$a]['monto']);
                
                for ($b = 0; $b < 13; $b++): //#meses  + pago                      

                    if ($mes == $b):
                        $suma[$mes] = $suma[$mes] + $total2[$a]['monto'][$b];
                        break;
                    endif;


                endfor;

            endfor;
        }
    //echoo($suma);
    for($z=0;$z<count($suma);$z++):
        $worksheet->write( ($contador+10) , (7+$z), $suma[$z],$format_decimal_amarillo); 
    endfor;
     $worksheet->write( ($contador+10+4) , 2, 'Firma ............................................fecha '.date("d/m/Y"),$format_titulo2); 
     //$worksheet->write( ($contador+10+5) , 2, 'CANO MUENTE CARLOS ALBERTO',$format_titulo2); 
     //$worksheet->write( ($contador+10+6) , 2, 'GERENTE GENERAL',$format_titulo2);             



    endfor;


    $workbook->close();
}

function generarLiquidacionAnual2() {

    $PERIODO = $_REQUEST['periodo'];
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];

    $anio = getFechaPatron($PERIODO, "Y");
    $mes = getFechaPatron($PERIODO, "m");
    $nombre_mes = getNameMonth($mes);

//..............................................................................
// Inicio Exel
//..............................................................................
// Creating a workbook
    $workbook = new Spreadsheet_Excel_Writer();
// sending HTTP headers
    $workbook->send(".liquidacion.xls");

//ESTILOS EXEL
    $negrita = & $workbook->addFormat();
    $negrita->setBold();
    //-- Colores RGB
    $workbook->setCustomColor(11, 0, 0, 150);
    $workbook->setCustomColor(12, 192, 192, 192);
    $workbook->setCustomColor(13, 221, 60, 16);
    $workbook->setCustomColor(14, 255, 255, 0);

//--- format_titulo
    $format_titulo = $workbook->addFormat();
    $format_titulo->setLocked();
    $format_titulo->setSize(14);
    $format_titulo->setColor("black");
    $format_titulo->setBold();

//--- format_titulo2
    $format_titulo2 = $workbook->addFormat();
    $format_titulo2->setSize(12);
    $format_titulo2->setColor("black");
    $format_titulo2->setBold();
    //$format_titulo2->setFgColor(14);
//-- Format_txt_Centrado
    $format_tabla_head_centrado = & $workbook->addFormat();
    $format_tabla_head_centrado->setBold();
    $format_tabla_head_centrado->setSize(10);
    $format_tabla_head_centrado->setTextWrap(1);
    $format_tabla_head_centrado->setBorder(1);
    $format_tabla_head_centrado->setBorder(3);
    $format_tabla_head_centrado->setVAlign('vjustify');
    //$format_tabla_head_centrado->setHAlign('justify');

    $format_normal = & $workbook->addFormat();
    $format_normal->setItalic();
    $moneda = '';// '$'
//--format_decimal
    $format_decimal = & $workbook->addFormat();    
    $format_decimal->setNumFormat($moneda . '#,##0.00;' . $moneda . '-#,##0.00');    
//--format_decimal_amarillo
    $format_decimal_amarillo = & $workbook->addFormat();
    $format_decimal_amarillo->setNumFormat($moneda . '#,##0.00;' . $moneda . '-#,##0.00');    
    $format_decimal_amarillo->setFgColor(14);
    //lista de afp
    $mi_afp = afpArrayConstruido();
    $iden = count($mi_afp);
    $mi_afp[$iden]['codigo'] = 02;
    $mi_afp[$iden]['nombre'] = 'ONP';

    $mi_afp[($iden+1)]['codigo'] = 00;
    $mi_afp[($iden+1)]['nombre'] = 'ESSALUD';


    //echoo($mi_afp);
    // PASO 01  = REGIMENES PENSIONARIOS
    if (is_array($mi_afp)) {

        //VARIABLES FOR MI EXEL
        $numero = 0;
        $array = array(
            "No.",
            "CUISPP/DNI",
            "Ap. Paterno",
            "Ap. Materno",
            "1er. Nombre",
            "2do. Nombre",
            getNameMonth(1, 1),
            getNameMonth(2, 1),
            getNameMonth(3, 1),
            getNameMonth(4, 1),
            getNameMonth(5, 1),
            getNameMonth(6, 1),
            getNameMonth(7, 1),
            getNameMonth(8, 1),
            getNameMonth(9, 1),
            getNameMonth(10, 1),
            getNameMonth(11, 1),
            getNameMonth(12, 1),
            "TOTAL"
        );

        for ($i = 0; $i < count($mi_afp); $i++):
            $id = 1;
            $numero = $numero + 1;
            $worksheet = & $workbook->addWorksheet($mi_afp[$i]['nombre']);

            //$worksheet->write($id, 0, "$i....$id".$mi_afp[$i]['nombre']);                       
            $id++;

            $worksheet->write(1, 5, 'LIQUIDACION ANUAL DE APORTES Y RETENCIONES PREVISIONALES', $format_titulo);
            $worksheet->write(2, 9, '( Ley 27605 )', $format_titulo);
            $worksheet->write(3, 1, 'Empresa :', $format_titulo);
            $worksheet->write(3, 3, NAME_EMPRESA, $format_titulo);

            $worksheet->write(4, 1, 'RUC :', $format_titulo);
            $worksheet->write(4, 3, RUC, $format_titulo);
            $worksheet->writeNote(4, 3, NAME_COMERCIAL);

            $worksheet->write(5, 3, $mi_afp[$i]['nombre'], $format_titulo);
            $worksheet->write(6, 1, "Liquidación correspondiente a los afiliados de " . $mi_afp[$i]['nombre'], $format_normal);
            $worksheet->write(7, 1, "ANIO : " . $anio, $format_titulo);

            //$worksheet->hideGridLines();
            $worksheet->setColumn(0, 0, 1);
            $worksheet->setColumn(1, 1, 4);
            $worksheet->setColumn(2, 2, 16);
            $worksheet->setColumn(3, 3, 16);
            $worksheet->setColumn(4, 4, 16); //# p
            $worksheet->setColumn(5, 5, 13);
            $worksheet->setColumn(6, 6, 10);
            $worksheet->setColumn(7, 7, 9);
            $worksheet->setColumn(8, 8, 9);
            $worksheet->setColumn(9, 9, 9);
            $worksheet->setColumn(10, 10, 9);
            $worksheet->setColumn(11, 11, 9);
            $worksheet->setColumn(12, 12, 9);
            $worksheet->setColumn(13, 13, 9);
            $worksheet->setColumn(14, 14, 9);
            $worksheet->setColumn(15, 15, 9);
            $worksheet->setColumn(16, 16, 9);
            $worksheet->setColumn(17, 17, 9);
            $worksheet->setColumn(18, 18, 10);

            $id++;
            $worksheet->writeRow(9, 1, $array, $format_tabla_head_centrado);
            $id++;

            //Dao
            $dao_eafp = new EstructuraAfpDao();
            $dao_pd = new PlameDeclaracionDao();

            $dao_el = new EstructuraLiquidacionDao();
            //data personas id
            $data_base = $dao_el->dataBase($anio);
            //$data_base_id = arrayId_Id($data_base, 'id_persona');
            //$array_data_base = arrayId($data_base, 'id_persona');
            // lista de periodos
            // 
            // lista de periodos....
            $data_periodo = $dao_pd->dataPeriodos(ID_EMPLEADOR_MAESTRO, $anio);
            $data_periodo_id = arrayId($data_periodo, 'id_pdeclaracion');
            //echoo($data_tra_id);


            $contador = 0;
            $total2 = array();
            
            for ($j = 0; $j < count($data_base); $j++):

                if (/*in_array($data_base[$j]['id_persona'], $data_tra_id)*/true):

                    $fila = $contador + 10;
                    $worksheet->write($fila, 0, $data_base[$j]['id_persona']);
                    $worksheet->write($fila, 1, $contador/* $j */);                    
                    $worksheet->write($fila, 3, $data_base[$j]['apellido_paterno']);
                    $worksheet->write($fila, 4, $data_base[$j]['apellido_materno']);
                    $worksheet->write($fila, 5, $data_base[$j]['nombres']);

                    $cups = null;
                    $total['horizontal']=0;
                    //$total2[$ji]['tipo'] = $mi_afp[$i]['codigo'];
                    //$total2[$j]['id_persona'] = $data_base[$j]['id_persona'];
                    for ($k = 0; $k < count($data_periodo); $k++): // # MES A MES DE TRABAJADOR 1-12  Y regimen pensionario
                        $data_rp = null;
                        //----------------------------------------------------------------------------------------------------------------------
                        // PASO 01 Lista de trabajadores con AFP y ONP 
                        if($mi_afp[$i]['codigo']!=00):                            
                            $data_tra = $dao_eafp->listarTrabajadoresAfp($data_periodo[$k]['id_pdeclaracion'], $mi_afp[$i]['codigo']);
                            $data_tra_id = arrayId($data_tra, 'id_persona'); 
                        endif;
                        
                        //----------------------------------------------------------------------------------------------------------------------
                        if ($mi_afp[$i]['codigo'] == 02):
                            if (in_array($data_base[$j]['id_persona'], $data_tra_id)):                             
                                $data_rp = $dao_el->data_ONP($data_periodo[$k]['id_pdeclaracion'], $data_base[$j]['id_persona']); //id_persona                            
                            endif;
                        elseif($mi_afp[$i]['codigo']==00):                           
                                // HERE BUSCA LOS 129 personas en cada declaracion.
                                $data_rp = $dao_el->dataEssalud($data_periodo[$k]['id_pdeclaracion'], $data_base[$j]['id_persona']); //id_persona                           
                        else:
                           if (in_array($data_base[$j]['id_persona'], $data_tra_id)):
                                $data_rp = $dao_el->dataAFP($data_periodo[$k]['id_pdeclaracion'], $data_base[$j]['id_persona']); //id_persona
                            endif;
                        endif;
                        //----------------------------------------------------------------------------------------------------------------------
                        
                        $worksheet->write($fila, (7 + $k), $data_rp[0]['monto_pagado'],$format_decimal); 
                        
                        $cups = ($cups == null) ? $data_rp[0]['cuspp'] : $cups;
                        $total['horizontal'] = $total['horizontal'] + $data_rp[0]['monto_pagado'];
                        
                        $total2[$contador]['id_persona'] = $data_base[$j]['id_persona'];                        
                        $total2[$contador]['monto'][$k] = floatval($data_rp[0]['monto_pagado']); 
                    endfor;
                    $total2[ $contador ]['monto'][] = $total['horizontal'];
                    
                    if ($cups == null):
                        $worksheet->write($fila, 2, $data_base[$j]['num_documento']);
                    else:
                        $worksheet->write($fila, 2, $cups);
                    endif;                    
                    
                    //SUMA HORIZONTAL
                    $worksheet->write($fila, 19, $total['horizontal'],$format_decimal_amarillo);                    
                    //$worksheet->write($fila, 8, $total['horizontal']); 
                    $contador++;
                endif;
                
           
            endfor;
            //echo "inicio";
            //echoo($total2); 
            //echo "acabo";
 // echoo($data_periodo_id);   
            $totall = array();
            for($mes =0; $mes<13;$mes++){
                $totall[$mes]=0;
            for($a=0; $a<count($total2); $a++):  //#personas      
                //recorremos id_pdeclaracion               
                for($b=0;$b<count($total2[$a]['monto']);$b++): //#meses  + pago                     
                    if( $b == $mes ):
                        $totall[$mes] = $totall[$mes] + $total2[$a]['monto'][$b];
                        break;
                    endif;                    
                endfor;                
            endfor;
            $worksheet->write( ($contador+10) , (7+$mes), $totall[$mes],$format_decimal_amarillo); 
            }
                    
            
            
            
           
        $worksheet->write( ($contador+10+4) , 2, 'Firma ............................................fecha '.date("d/m/Y"),$format_titulo2); 
        $worksheet->write( ($contador+10+5) , 2, 'CANO MUENTE CARLOS ALBERTO',$format_titulo2); 
        $worksheet->write( ($contador+10+6) , 2, 'GERENTE GENERAL',$format_titulo2);             



        endfor;

        //echoo($total2);
        //echo "\n\n";
       // echoo($totall);
    }//ENDIF
    // PASO 02  = ESSALUD




    $workbook->close();
}

?>
