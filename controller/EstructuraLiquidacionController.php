<?php

$op = $_REQUEST["oper"];
if (true) {
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

$response = NULL;
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
    // Creating a worksheet
    //$worksheet = & $workbook->addWorksheet('hoja 01'); 
    //----------------------------------------------------------------------
    //----------------------- HOJA 2 ---------------------------------------
    //----------------------------------------------------------------------
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
            $worksheet->setColumn(6, 6, 12);
            $worksheet->setColumn(7, 7, 8);
            $worksheet->setColumn(8, 8, 8);
            $worksheet->setColumn(9, 9, 8);
            $worksheet->setColumn(10, 10, 8);
            $worksheet->setColumn(11, 11, 8);
            $worksheet->setColumn(12, 12, 8);
            $worksheet->setColumn(13, 13, 8);
            $worksheet->setColumn(14, 14, 8);
            $worksheet->setColumn(15, 15, 8);
            $worksheet->setColumn(16, 16, 8);
            $worksheet->setColumn(17, 17, 8);
            $worksheet->setColumn(18, 18, 8);

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
            // PASO 01 Lista de trabajadores con AFP        
            $data_tra = $dao_eafp->listarTrabajadoresAfp($ID_PDECLARACION, $mi_afp[$i]['codigo']);

            $data_tra_id = arrayId($data_tra, 'id_persona');
            // lista de periodos....
            $data_periodo = $dao_pd->dataPeriodos(ID_EMPLEADOR_MAESTRO, $anio);
            $data_periodo_id = arrayId($data_periodo, 'id_pdeclaracion');
            //echoo($data_tra_id);


            $contador = 0;
            $total2 = array();
            
            for ($j = 0; $j < count($data_base); $j++):

                if (in_array($data_base[$j]['id_persona'], $data_tra_id)):

                    $fila = $contador + 10;                    
                    $ji = $contador;
                    $worksheet->write($fila, 0, $data_base[$j]['id_persona']);
                    $worksheet->write($fila, 1, $contador/* $j */);

                    //$worksheet->write($fila, 2, $data_base[$j]['cuspp']);
                    $worksheet->write($fila, 3, $data_base[$j]['apellido_paterno']);
                    $worksheet->write($fila, 4, $data_base[$j]['apellido_materno']);
                    $worksheet->write($fila, 5, $data_base[$j]['nombres']);

                    $cups = null;
                    $total['horizontal']=0;
                    //$total2[$ji]['tipo'] = $mi_afp[$i]['codigo'];
                    //$total2[$j]['id_persona'] = $data_base[$j]['id_persona'];
                    for ($k = 0; $k < count($data_periodo); $k++): // # MES A MES DE TRABAJADOR 1-12 
                        $data_rp = null;
                        if ($mi_afp[$i]['codigo'] == 02):
                            $data_rp = $dao_el->data_ONP($data_periodo[$k]['id_pdeclaracion'], $data_base[$j]['id_persona']); //id_persona
                            //$total2[$ji][$k]['monto_pagado_onp'] = $data_rp[0]['monto_pagado'];
                        elseif($mi_afp[$i]['codigo']==00):
                            $data_rp = $dao_el->dataEssalud($data_periodo[$k]['id_pdeclaracion'],$data_base[$j]['id_persona']);
                        else:
                            $data_rp = $dao_el->dataAFP($data_periodo[$k]['id_pdeclaracion'], $data_base[$j]['id_persona']);
                            //$total2[$ji][$k]['monto_pagado_afp'] = $data_rp[0]['monto_pagado'];
                        endif;

                        $cups = ($cups == null) ? $data_rp[0]['cuspp'] : $cups;
                        
                        $total2[$ji][$k]['id_persona'] = $data_base[$j]['id_persona'];
                        $total2[$ji][$k]['id_pdeclaracion'] = $data_periodo[$k]['id_pdeclaracion'];
                        $total2[$ji][$k]['monto_pagado'] = $data_rp[0]['monto_pagado'];
                                                
                        
                        $total['horizontal'] = $total['horizontal'] + $data_rp[0]['monto_pagado'];
                        $worksheet->write($fila, (7 + $k), $data_rp[0]['monto_pagado']);

                    endfor;

                    if ($cups == null) {
                        $worksheet->write($fila, 2, $data_base[$j]['num_documento']);
                    } else {
                        $worksheet->write($fila, 2, $cups);
                    }
                    
                    //SUMA HORIZONTAL
                    $worksheet->write($fila, 19, $total['horizontal']);                    
                    //$worksheet->write($fila, 8, $total['horizontal']); 
                    $contador++;
                endif;
                
           
            endfor;
            //echo "inicio";
            //echoo($total2); 
            //echo "acabo";
 // echoo($data_periodo_id);          
      /*      
            for($a=0; $a<count($total2); $a++):                
                //recorremos id_pdeclaracion               
                for($z=0;$z<count($data_periodo_id);$z++):
                    $totall = 0;
                    //echo "inicio z = $z  : a=$a : ";
                    //echo ">>>>>>>>> ".$data_periodo_id[$z];
                    //echo "<br>\n\n";
                    //echoo($total2);
                    //break;
                    for($b=0;$b<count($total2[$a]);$b++):
                        //echo " b = ".$b;
                        //echo "id_pdeclaracion " .$total2[$a][$b]['id_pdeclaracion'];
                        //echo "id_persona " .$total2[$a][$b]['id_persona'];
                        if( $total2[$a][$b]['id_pdeclaracion'] == $data_periodo_id[$z] ):
                            //$totall = $totall + $total2[$a][$b]['monto_pagado'];
                            //echo "\nmonto = ".$total2[$a][$b]['monto_pagado'];
                            //echo "\id_pdeclaracion =".$data_periodo_id[$z];
                            //echo "\n id_persona = ".$total2[$a][$b]['id_persona'];
                            break;
                        endif;
                        
                    endfor;
                    //pintar
                    $worksheet->write( 0 , (8+$z), $totall); 
                endfor;                
            endfor;
*/
           
        $worksheet->write( ($contador+10+1) , 2, 'Firma ............................................fecha '.date("d/m/Y"),$format_titulo2); 
        $worksheet->write( ($contador+10+2) , 2, 'CANO MUENTE CARLOS ALBERTO',$format_titulo2); 
        $worksheet->write( ($contador+10+3) , 2, 'GERENTE GENERAL',$format_titulo2);             



        endfor;

        
        
    }//ENDIF
    // PASO 02  = ESSALUD




    $workbook->close();
}

?>
