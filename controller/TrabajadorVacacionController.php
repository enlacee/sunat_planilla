<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    require_once '../controller/ConfConceptosController.php';

    // IDE CONFIGURACION  -- generarConfiguracion(periodo);    
    require_once '../dao/ConfAsignacionFamiliarDao.php';
    require_once '../dao/ConfSueldoBasicoDao.php';
    require_once '../dao/ConfEssaludDao.php';
    require_once '../dao/ConfOnpDao.php';
    require_once '../dao/ConfUitDao.php';

    // Adicional onp
    require_once '../dao/ConfAfpDao.php';
    require_once '../dao/ConfAfpTopeDao.php';
    // Conceptos estan afectos
    require_once '../dao/PlameDetalleConceptoAfectacionDao.php';

    // CONTROLLER CONFIGURACION
    require_once '../controller/ConfController.php';
    require_once '../dao/PlameAfectacionDao.php';

    //DATA  fuck
    require_once '../dao/TrabajadorPdeclaracionDao.php';


    //vacacion
    require_once '../dao/VacacionDao.php';
    require_once '../model/TrabajadorVacacion.php';
    require_once '../dao/TrabajadorVacacionDao.php';
    require_once '../dao/VacacionDetalleDao.php';
    // daos adicionales planilla
    require_once '../dao/TrabajadorDao.php';
    // reporte txt
    require_once '../dao/EstablecimientoDao.php';
    require_once '../dao/EmpresaCentroCostoDao.php';
    require_once '../dao/EstablecimientoDireccionDao.php';
    require_once '../dao/PersonaDireccionDao.php';
    require_once '../dao/DetalleRegimenPensionarioDao.php';
    require_once '../dao/DetallePeriodoLaboralDao.php';
    //reporte tabla
    //RENTA DE QUINTA
    require_once '../controller/IR5Controller.php';


    require_once '../dao/DeclaracionDConceptoVacacionDao.php';

    require_once '../model/TrabajadorVacacion.php';
    require_once '../model/DeclaracionDConceptoVacacion.php';


    //registro por concepto PREGUNTAR
    require_once '../dao/RegistroPorConceptoDao.php';
    //ZIP
    require_once '../util/zip/zipfile.inc.php';

    require_once '../controller/funcionesAyuda.php';

    //libreria FUNCIONES_AYUDA
    require_once '../dao/PrestamoDao.php';
    require_once '../dao/ParatiFamiliaDao.php';
    require_once '../model/PrestamoCuota.php';
    require_once '../model/PtfPago.php';

    // planilla de vacacion
    require_once '../dao/PlameDao.php';
    require_once '../dao/PlameDeclaracionDao.php';
    require_once '../dao/PlameDetalleConceptoEmpleadorMaestroDao.php';
}

if ($op == "generar") {
    $response = planillaVacacion();
} else if ($op == "boletaVacacion") {//recibo30
    boletaVacacacion();
} else if ($op == 'cargar_tabla') {
    $response = cargarTablaTrabajdorVacacion();
} else if ($op == "del") {
    $response = eliminarTVacacion();
} else if ($op == "delAll") {
    $response = eliminarAll();
} else if ($op == "planilla") {
    planilla();
} else {
    echo "ERRROR OPER";
}


echo (!empty($response)) ? json_encode($response) : '';

function planilla() {
    
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $PERIODO = $_REQUEST['periodo'];
//---------------------------------------------------

    $num_mes = getFechaPatron($PERIODO, "m");
    $nombre_mes = getNameMonth($num_mes);
    $anio = getFechaPatron($PERIODO, "Y");

    $file_name = 'planillav.txt';

    $BREAK = chr(13) . chr(10);
    $BREAK2 = chr(13) . chr(10) . chr(13) . chr(10);
    $PUNTO = '|';
    //$LINEA = str_repeat('-', 80);
    $fp = fopen($file_name, 'w');
    $cab_comprimido = chr(27) . M . chr(15);
    fwrite($fp, $cab_comprimido);

    //DAO
    $dao_pago = new TrabajadorPdeclaracionDao(); //[OK]
    $dao_tpdv = new TrabajadorVacacionDao();
    $dao_ddc = new DeclaracionDconceptoDao();
    
    $fp = helper_cabecera($fp, $nombre_mes, $anio, $BREAK, $BREAK2, $PUNTO);


    if (true) {
        // LISTA DE TRABAJADORES
                
        $total = array();
        $total['ingresos_h_v'] = 0.00;
        $total['ingresos_h_b'] = 0.00;
        $total['ingresos_a_f'] = 0.00;
        $total['ingresos_r_c'] = 0.00;
        $total['ingresos_a_t'] = 0.00;
        $total['ingresos_t_f'] = 0.00;
        $total['ingresos_h_e'] = 0.00;
        $total['ingresos_total'] = 0.00;

        $total['descuentos_snp'] = 0.00;
        $total['descuentos_5ta'] = 0.00;
        $total['descuentos_afp'] = 0.00;
        $total['descuentos_a_q'] = 0.00;
        $total['descuentos_d_p'] = 0.00;
        $total['descuentos_d_ptf'] = 0.00;
        $total['descuentos_jdl'] = 0.00;
        $total['descuentos_otros'] = 0.00;
        $total['descuentos_total'] = 0.00;

        $total['aportes_essalud'] = 0.00;
        //$total['aportes_otros'] = 0.00;
        $total['aportes_total'] = 0.00;

        $total['total_rnd'] = 0.00;
        $total['total_total'] = 0.00;
        $contador_break = 0;
        //
        //$data_tra = $dao_pago->listar_3($ID_PDECLARACION);
        $data_tra = $dao_tpdv->listarPlanilla($ID_PDECLARACION);
        $dao_ddcv = new DeclaracionDConceptoVacacionDao();
        //                
        for ($k = 0; $k < count($data_tra); $k++) {

            //++
            $contador_break = $contador_break + 1;
            if ($contador_break == 47) {
                fwrite($fp, chr(12));
                $fp = helper_cabecera($fp, $nombre_mes, $anio, $BREAK, $BREAK2, $PUNTO);
                //fwrite($fp,"-ANB-");
                $contador_break = 0;
            }
            //++
            // LISTA DE TRABAJADORES VACACION
            //$data_vaca = PagoDao::anb_dosQuincenasEnBoleta($ID_PDECLARACION, $data_tra[$k]['id_trabajador']);

            //..............................................................................
            $calc = array();
            //..............................................................................            
            //conceptos calculados base         
            
            
            $calc = $dao_ddcv->buscar_ID_TrabajadorVacacionPorConceptosResumido($data_tra[$k]['id_trabajador_vacacion']);
            

            //-------------------------------- I PINTANDO LINEA --------------------------------//        
            //--
            fwrite($fp, str_pad($k + 1, 4, " ", STR_PAD_RIGHT));

            $name_tra = $data_tra[$k]['apellido_paterno'] . ' ' . $data_tra[$k]['apellido_materno'] . ' ' . $data_tra[$k]['nombres'];
            fwrite($fp, str_pad(textoaMedida(29, $name_tra), 31, " ", STR_PAD_RIGHT));

            fwrite($fp, str_pad($data_tra[$k]['num_documento'], 12, " ", STR_PAD_RIGHT));

            fwrite($fp, str_pad($data_tra[$k]['dia'], 15, " ", STR_PAD_RIGHT)); //DIA Vacacion

            //fwrite($fp, str_pad($data_tra[$k]['dia_laborado'], 5, " ", STR_PAD_RIGHT));
            //fwrite($fp, str_pad($data_tra[$k]['ordinario_hora'], 5, " ", STR_PAD_RIGHT));

           
            //fwrite($fp, str_pad("00.00", 9, " ", STR_PAD_LEFT));           

            $_01 = buscar_buscar_concepto($calc, C118); //Vacacion
            fwrite($fp, str_pad($_01, 9, " ", STR_PAD_LEFT));
            $total['ingresos_h_v'] = $total['ingresos_h_v'] + $_01;
            
            fwrite($fp, str_pad("*", 9, " ", STR_PAD_LEFT)); 

            $_02 = buscar_buscar_concepto($calc, C201);
            fwrite($fp, str_pad($_02, 8, " ", STR_PAD_LEFT));
            $total['ingresos_a_f'] = $total['ingresos_a_f'] + $_02;

            $_03 = buscar_buscar_concepto($calc, C304);
            fwrite($fp, str_pad($_03/* CAJA */, 8, " ", STR_PAD_LEFT));
            $total['ingresos_r_c'] = $total['ingresos_r_c'] + $_03;

            $_04 = buscar_buscar_concepto($calc, C909);
            fwrite($fp, str_pad($_04/* TRANSP */, 8, " ", STR_PAD_LEFT));
            $total['ingresos_a_t'] = $total['ingresos_a_t'] + $_04;

            $_05 = "*";
            fwrite($fp, str_pad($_05/* TRAB FERIDO 0107 */, 8, " ", STR_PAD_LEFT));
            $total['ingresos_t_f'] = 0;

            // CALCULADO!
            $_06 = "*";
            fwrite($fp, str_pad($_06/* EXTRAS */, 8, " ", STR_PAD_LEFT));
            $total['ingresos_h_e'] = 0;

            //..................................................................            
            $total_ingresos = ($_01 + $_02 + $_03 + $_04);
            fwrite($fp, str_pad(number_format_var($total_ingresos)/* TOTAL */, 9, " ", STR_PAD_LEFT));
            $total['ingresos_total'] = $total['ingresos_total'] + $total_ingresos;
            //..................................................................

            fwrite($fp, $PUNTO);

            $_07 = buscar_buscar_concepto($calc, C607);
            fwrite($fp, str_pad($_07/* SNP */, 8, " ", STR_PAD_LEFT));
            $total['descuentos_snp'] = $total['descuentos_snp'] + $_07;

            $_08 = buscar_buscar_concepto($calc, C605); //5ta 
            fwrite($fp, str_pad($_08, 8, " ", STR_PAD_LEFT));
            $total['descuentos_5ta'] = $total['descuentos_5ta'] + $_08;

            // CALCULADO!
            $_09_1 = buscar_buscar_concepto($calc, C601);
            $_09_2 = buscar_buscar_concepto($calc, C606);
            $_09_3 = buscar_buscar_concepto($calc, C608);

            $_09 = ($_09_1 + $_09_2 + $_09_3);
            fwrite($fp, str_pad($_09/* A.F.P. */, 8, " ", STR_PAD_LEFT));
            $total['descuentos_afp'] = $total['descuentos_afp'] + $_09;

            $_10 = "*";//buscar_buscar_concepto($calc, C701); // QUINCENA 
            fwrite($fp, str_pad($_10, 9, " ", STR_PAD_LEFT));
            $total['descuentos_a_q'] = 0;//$total['descuentos_a_q'] + $_10;

            //======sub 01 ParaTiFamilia=============================
            $_11 = buscar_buscar_concepto($calc, C1001); 
            fwrite($fp, str_pad($_11, 8, " ", STR_PAD_LEFT));
            $total['descuentos_d_ptf'] = $total['descuentos_d_ptf'] + $_11;            

            //======sub 02 Prestamo=============================
            $_12 = buscar_buscar_concepto($calc, C1002);
            fwrite($fp, str_pad($_12, 8, " ", STR_PAD_LEFT));
            $total['descuentos_d_p'] = $total['descuentos_d_p'] + $_12;
            

            $_13 = buscar_buscar_concepto($calc, C703); // Dscto judicial
            fwrite($fp, str_pad($_13, 8, " ", STR_PAD_LEFT));
            $total['descuentos_jdl'] = $total['descuentos_jdl'] + $_13;

         
            $_14 = "*";
            fwrite($fp, str_pad($_14/* OTROSDESC. */, 8, " ", STR_PAD_LEFT));
            $total['descuentos_otros'] = 0;//$total['descuentos_otros'] + $_14;

//=======================================================================================
            $descuentos = ($_07 + $_08 + $_09 + $_11 + $_12+$_13);

            $_15 = $total_ingresos - $descuentos;
            $_15_round = roundFaborContra($_15);
//=======================================================================================            

            fwrite($fp, str_pad($descuentos/* 'TOTAL.' */, 9, " ", STR_PAD_LEFT));
            $total['descuentos_total'] = $total['descuentos_total'] + $descuentos;
            // number_format_var($number);
            fwrite($fp, $PUNTO);

            $_16 = buscar_buscar_concepto($calc, C804);
            fwrite($fp, str_pad(number_format_var($_16)/* ESSALUD */, 8, " ", STR_PAD_LEFT));
            $total['aportes_essalud'] = $total['aportes_essalud'] + $_16;

            //$_17 = 0.00;
            fwrite($fp, str_pad('-', 6, " ", STR_PAD_LEFT));

            $_18 = $_16;
            fwrite($fp, str_pad(number_format_var($_18)/* TOTAL. */, 9, " ", STR_PAD_LEFT));
            $total['aportes_total'] = $total['aportes_total'] + $_18;



            fwrite($fp, $PUNTO);

            fwrite($fp, str_pad($_01, 9, " ", STR_PAD_LEFT)); //MONTO VACACION



            fwrite($fp, str_pad($_15_round['decimal']/* RND */, 6, " ", STR_PAD_LEFT));
            $total['total_rnd'] = $total['total_rnd'] + ( $_15_round['decimal'] );

            fwrite($fp, str_pad(number_format_var($_15_round['numero'])/* A. */, 10, " ", STR_PAD_LEFT));
            $total['total_total'] = $total['total_total'] + ( $_15_round['numero'] );


            //-------------------------------- I PINTANDO LINEA --------------------------------//        

            fwrite($fp, $BREAK);
        }//enfFor $k 
        // imprimir :PIE:
        $fp = helper_pie($fp, $total, $BREAK, $PUNTO);
    }

    fclose($fp);

    $file = array();
    $file[] = $file_name;
    //$file[] = ($file_name2);
    //generarRecibo15_txt2($ID_PDECLARACION, $id_etapa_pago);


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




function helper_cabecera($fp, $nombre_mes, $anio, $BREAK, $BREAK2, $PUNTO) {

    //$PUNTO = '³';
    $linea_caja = str_repeat('-', 254);

    fwrite($fp, str_pad(NAME_EMPRESA, 50, " ", STR_PAD_RIGHT));
    fwrite($fp, $BREAK);
    fwrite($fp, str_pad('DIRECCION', 105, " ", STR_PAD_RIGHT));
    //fwrite($fp, $BREAK);

    fwrite($fp, str_pad("PLANILLA UNICA DE PAGOS - TRABAJADORES", 50, " ", STR_PAD_RIGHT));


    fwrite($fp, $BREAK);
    fwrite($fp, str_pad('', 105, " ", STR_PAD_BOTH));
    fwrite($fp, str_pad($nombre_mes . ' - ' . $anio, 38, " ", STR_PAD_BOTH));
    fwrite($fp, $BREAK2);



    //cabecera    
    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);
    fwrite($fp, str_pad('', 65, " ", STR_PAD_BOTH));
    fwrite($fp, str_pad('R E M U N E R A C I O N E S', 64, " ", STR_PAD_RIGHT));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('D E S C U E N T O S', 74, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('A P O R T A C I O N E S', 23, " ", STR_PAD_BOTH));
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('NETO', 25, " ", STR_PAD_LEFT));
    fwrite($fp, $BREAK);


    //cabecera 2
    fwrite($fp, str_pad('#', 4, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad('COD APELLIDOS Y NOMBRE', 31, " ", STR_PAD_RIGHT)); //38

    fwrite($fp, str_pad('DNI', 12, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('DIAS', 5, " ", STR_PAD_BOTH)); //new 4

    fwrite($fp, str_pad('*', 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('*', 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('HABER'/* BASICO */, 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('*'/* VACACION */, 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('ASIG.'/* FAMIL */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('RIESGO.'/* CAJA */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('ASIG.'/* TRANSP */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('*'/* FERIADO */, 8, " ", STR_PAD_LEFT)); //-------

    fwrite($fp, str_pad('*'/* EXTRAS */, 8, " ", STR_PAD_LEFT)); //-------


    fwrite($fp, str_pad('TOTAL'/**/, 9, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('S.N.P.'/**/, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('5TA'/**/, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('A.F.P.'/**/, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('*'/* QUINCENA */, 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('DESC.'/* P.T.FAML */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('DESC.'/* PRESTAMO */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('DESC.'/* JUDIC. */, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('*'/* DESC. */, 8, " ", STR_PAD_LEFT));

//    fwrite($fp, str_pad('RND.'/**/, 8, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('TOTAL.'/**/, 9, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('ESSALUD'/**/, 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('OTRO'/* DESC. */, 6, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('TOTAL.'/* DESC. */, 9, " ", STR_PAD_LEFT));

    //fwrite($fp, str_pad('', 12, " ", STR_PAD_BOTH));


    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('NETO.', 9, " ", STR_PAD_LEFT)); //neto vacaciones

    fwrite($fp, str_pad('RND.', 6, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('A.', 10, " ", STR_PAD_LEFT));

    fwrite($fp, $BREAK);

    //--
    fwrite($fp, str_pad('', 4, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad('', 31, " ", STR_PAD_RIGHT)); //8


    fwrite($fp, str_pad('', 12, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('VAC.', 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('*', 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('*', 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad('VAC.', 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('*'/*BASICO*/, 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('FAMIL', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('CAJA', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('TRANSP', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('*', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('*', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('TOTAL', 9, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('CATEG', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('*', 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('PTF.', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('PREST.', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('JUDIC.', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('*', 8, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad('TOTAL.', 9, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('', 8, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('DSCT', 6, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('', 9, " ", STR_PAD_LEFT));


    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('VAC.', 9, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('', 6, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('PAGAR', 10, " ", STR_PAD_LEFT));

    fwrite($fp, $BREAK);

    fwrite($fp, $linea_caja);

    fwrite($fp, $BREAK);

    return $fp;
}

function helper_pie($fp, $total, $BREAK, $PUNTO) {
    $PUNTO = " ";
    $linea_caja = str_repeat('-', 254);
    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);

    //cabecera 2
    fwrite($fp, str_pad('#', 4, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad(' '/* 'COD APELLIDOS Y NOMBRE' */, 31, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad(' '/* 'DNI'12 */, 11, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad(' '/* 'DIAS' */, 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad(' '/* 'DIAS' */, 5, " ", STR_PAD_BOTH));

    //fwrite($fp, str_pad(' '/*'HORAS'*/, 8, " ", STR_PAD_BOTH));


    fwrite($fp, str_pad(number_format_var($total['ingresos_h_v'])/* BASICO */, 15, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['ingresos_a_f'])/* 'ASIG.' FAMIL */, 17, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['ingresos_a_t'])/* ASIG.TRANSP */, 16, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad(number_format_var($total['ingresos_h_e'])/* HORAS EXTRAS */, 16, " ", STR_PAD_LEFT)); //-------
    //

    fwrite($fp, $PUNTO);


    fwrite($fp, str_pad(number_format_var($total['descuentos_snp'])/* 'S.N.P.' */, 17, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad(number_format_var($total['descuentos_afp'])/* 'A.F.P.' */, 16, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad(number_format_var($total['descuentos_d_p'])/* DESC. PRESTAMO */, 17, " ", STR_PAD_LEFT));



    fwrite($fp, str_pad(number_format_var($total['descuentos_jdl'])/* DESC. JUDIC. */, 16, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad(number_format_var($total['descuentos_total'])/* TOTAL. */, 17, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad(number_format_var($total['aportes_total'])/* TOTAL. DESC. */, 23, " ", STR_PAD_LEFT));

    //?
    //fwrite($fp, str_pad('', 9, " ", STR_PAD_LEFT)); //sobra

    fwrite($fp, $PUNTO);

    //fwrite($fp, str_pad(number_format_var($total['total_rnd'])/*RND.*/, 8, " ", STR_PAD_LEFT));
    fwrite($fp, str_pad(number_format_var($total['total_rnd'])/* RND. */, 15/* 18 */, " ", STR_PAD_LEFT));


    fwrite($fp, $BREAK);


    //==========================================================================Ç
    // segunda parte
    //==========================================================================Ç


    fwrite($fp, str_pad('#', 4, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad(' '/* 'COD APELLIDOS Y NOMBRE' */, 38, " ", STR_PAD_RIGHT));

    fwrite($fp, str_pad(' '/* 'DNI' */, 12, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad(' '/* 'DIAS' */, 5, " ", STR_PAD_BOTH));

    fwrite($fp, str_pad(' '/* 'HORAS' */, 5, " ", STR_PAD_BOTH));


    fwrite($fp, str_pad(number_format_var($total['ingresos_h_b'])/* BASICO */, 16, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['ingresos_r_c'])/* RIESGO. CAJA */, 16, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['ingresos_t_f'])/* TRAB FERIADO */, 16, " ", STR_PAD_LEFT)); //-------

    fwrite($fp, str_pad(number_format_var($total['ingresos_total'])/* TOTAL */, 17, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad(number_format_var($total['descuentos_5ta'])/* '5TA */, 16, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['descuentos_a_q'])/* ADEL QUINCENA */, 17, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['descuentos_d_ptf'])/* DESC. P.T.FAML */, 16, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad(number_format_var($total['descuentos_otros'])/* OTROS.DESC. */, 16, " ", STR_PAD_LEFT));
//    fwrite($fp, str_pad('RND.'/**/, 8, " ", STR_PAD_BOTH));


    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad(number_format_var($total['aportes_essalud'])/* 'ESSALUD.' */, 17, " ", STR_PAD_LEFT));

    fwrite($fp, str_pad('-'/* OTROS. DESC. */, 15, " ", STR_PAD_LEFT));


    //fwrite($fp, str_pad('', 12, " ", STR_PAD_LEFT));

    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad(number_format_var($total['ingresos_h_v']), 9, " ", STR_PAD_LEFT));


    fwrite($fp, str_pad(number_format_var($total['total_total'])/* A. */, 16, " ", STR_PAD_LEFT));


    //fwrite($fp, str_pad(number_format_var($total['total_total'])/*A.*/, 10, " ", STR_PAD_LEFT));


    fwrite($fp, $BREAK);
    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);

    return $fp;
}



function planillaVacacion() {

    //$ids = $_REQUEST['ids'];
    $PERIODO = $_REQUEST['periodo'];
    $fecha = getFechasDePago($PERIODO);
    $anio = getFechaPatron($PERIODO, "Y");
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $robot;
    generarConfiguracion($PERIODO);
    generarConfiguracion2($PERIODO);
    //echoo($_REQUEST);
    //echoo($_SESSION);
    // 01 consultar trabajadores con vacacion
    //DAO
    $daov = new VacacionDao();
    $daotv = new TrabajadorVacacionDao();
    $daovd = new VacacionDetalleDao();
    $dao_tra = new TrabajadorDao();
    $dao_plame = new PlameDao();

    //model
    $model_tpd = new TrabajadorVacacion();

    // Operacion (01)
    // listar trabajadores.
    //$data_trav = $dao_plame->listarTrabajadoresPorPeriodo_global(ID_EMPLEADOR_MAESTRO, $fecha['first_day'], $fecha['last_day']);
    $data_tra = $dao_plame->listarTrabajadoresPorPeriodo(ID_EMPLEADOR, $fecha['first_day'], $fecha['last_day']);


    // Operacion (02)
    // listar trabajador con vacacion
    $countDataTra = count($data_tra);
    $counterVacacion = 0;
    if ($countDataTra > 0) {
        for ($i = 0; $i < $countDataTra; $i++) {
            $data_trav = array();
            $data_ask = array();

            $data_trav = $daov->trabajadorVacacion($data_tra[$i]['id_trabajador'], $anio);

            if (count($data_trav) > 0) {
                $data_ask = leerVacacionDetalle($data_trav, $PERIODO, $fecha['first_day'], $fecha['last_day']);
                //REALMENTE TIENE VACACION EN ESTE MES! .
                if ($data_ask['dia'] > 0) {
                    $SUELDO_CAL = 0;
                    $proceso_porcentaje = 0;
                    $dia_vacacion = 0;

                    // Inicio proceso
                    $counterVacacion++;
                    $dia_vacacion = $dia_vacacion + ($data_ask['dia']);
                    //echo "\ndia_vacacion antes = $dia_vacacion";                    

                    if ($dia_vacacion == 30) { // dias completos del mes trabajados             
                        $SUELDO_CAL = $data_tra[$i]['monto_remuneracion'];
                        $proceso_porcentaje = 100;
                    } else if ($dia_vacacion < 30) {
                        $smpd = sueldoMensualXDia($data_tra[$i]['monto_remuneracion']);
                        $SUELDO_CAL = $smpd * $dia_vacacion;
                        //--------
                        $calc_porcentaje = 100 * ($dia_vacacion / 30);
                        $arregloNum = getRendondeoEnSoles($calc_porcentaje);
                        $proceso_porcentaje = $arregloNum['numero'];
                    }

                    // -------------- obj Trabajador Pdeclaracion --------------
                    $model_tpd->setId_pdeclaracion($ID_PDECLARACION);
                    $model_tpd->setId_trabajador($data_tra[$i]['id_trabajador']);
                    $model_tpd->setFecha_lineal($data_ask['fecha_lineal']);
                    $model_tpd->setDia($dia_vacacion);
                    $model_tpd->setSueldo($SUELDO_CAL);
                    $model_tpd->setSueldo_base($data_tra[$i]['monto_remuneracion']);
                    $model_tpd->setProceso_porcentaje($proceso_porcentaje);
                    $model_tpd->setFecha_creacion(date("Y-m-d"));

                    // Registrar datos adicionales del Trabajador  
                    $data_trav_adit = $dao_tra->buscarDataForPlanilla($data_tra[$i]['id_trabajador']);
                    $model_tpd->setId_empresa_centro_costo($data_trav_adit['id_empresa_centro_costo']);
                    $model_tpd->setId_establecimiento($data_trav_adit['id_establecimiento']);
                    $model_tpd->setCod_regimen_pensionario($data_trav_adit['cod_regimen_pensionario']);
                    $model_tpd->setCod_regimen_aseguramiento_salud($data_trav_adit['cod_regimen_aseguramiento_salud']);
                    $model_tpd->setCod_ocupacion_p($data_trav_adit['cod_ocupacion_p']);
                    //echoo($model_tpd);
                    conceptoPorConceptoXDVacacion($model_tpd, $robot, $ID_PDECLARACION, $PERIODO);
                }//END IF  
            }//end trabajador con dias de vacacion
        }
    }
    $response->registrosActualizados = "";
    $response->rpta = true;
    $response->mensaje = "Num de trabajadores Procesados [$counterVacacion]";
    return $response;
}

//END-FUNCTION

function conceptoPorConceptoXDVacacion($obj, $robot, $ID_PDECLARACION, $PERIODO) {
    //$obj= new TrabajadorVacacion();    
    $ID_PDECLARACION = $obj->getId_pdeclaracion();

    //DAO    
    $dao_rpc = new RegistroPorConceptoDao();  // a calcular data 50/50 si<30 dias.
    // Arreglo data_rpc = lista de conceptos del trabajador.    
    $datarpc = $dao_rpc->buscar_RPC_PorTrabajador2($ID_PDECLARACION, $obj->getId_trabajador());
    $pporcentaje = $obj->getProceso_porcentaje() / 100;
    // echo "\nPORCENTAJE = " . $pporcentaje;
    //Variables locales
    $_arregloAfps = array(21, 22, 23, 24);
    $_asigFamiliar = 0;
    $_prestamo = 0;
    $_ptf = 0;
    $_sueldoBasico = 0;

    //$_r5ta = 0;
    $_essaludMasVida = 0;
    $_aseguraPensionMas = 0;
    $_bonifRiesgoCaja = 0;
    $_dsctoMandatoJudicial = 0;
    $_movilidad = 0;
    $_onp = 0;
    $_essalud = 0;  // concepto_0804();
    // CONCEPTO : ASIGNACION FAMILIAR
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C201);
    if (is_array($datarpc_val)) {
        if (intval($datarpc_val['valor']) == 1) {
            $_asigFamiliar = concepto_0201() * $pporcentaje;
        }
    }
    unset($datarpc_val);


    if ($obj->getSueldo() > 0) {
        // CONCEPTO : SUELDO BASICO             
        $_sueldoBasico = concepto_0121($obj->getSueldo()); //ok
        unset($datarpc_val);
    }


    //  ECHO " <<<< 0706 == CONCEPTO EXCLUSIVO DE EMPRESA  PRESTAMO >>> ";    
    //$arreglo_0706 = concepto_0706($obj->getId_trabajador(), $ID_PDECLARACION, $PERIODO);

    // Sub-funcion 01
    $_ptf = concepto_1001($obj->getId_trabajador(), $PERIODO);
    $_ptf = $_ptf*$pporcentaje;

    // Sub-funcion 02
    $_prestamo = concepto_1002($obj->getId_trabajador(), $PERIODO);
    $_prestamo = $_prestamo*$pporcentaje;       

    //----------------- fijo no mover varia el calculo-------------------
    // CONCEPTO : ESSALUD_MAS VIDA    
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C604);
    if (intval($datarpc_val['valor']) == 1) {
        $_essaludMasVida = concepto_0604() * $pporcentaje;
    }
    unset($datarpc_val);

    // CONCEPTO : ASEGURA PENSION_MAS     
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C612);
    if (intval($datarpc_val['valor']) == 1) {
        $_aseguraPensionMas = concepto_0612() * $pporcentaje;
    }
    unset($datarpc_val);


    // CONCEPTO : BONIFICACIÓN POR RIESGO DE CAJA
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C304);
    if (floatval($datarpc_val['valor']) > 0) {
        $_bonifRiesgoCaja = concepto_0304($datarpc_val['valor']) * $pporcentaje;
    }
    unset($datarpc_val);


    // CONCEPTO : DESCUENTO AUTORIZADO U ORDENADO POR MANDATO JUDICIAL    *****************************************************************    
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C703);
    if (floatval($datarpc_val['valor']) > 0) {
        $_dsctoMandatoJudicial = concepto_0703($datarpc_val['valor']) * $pporcentaje;
    }
    unset($datarpc_val);

    // CONCEPTO : MOVILIDAD SUPEDITADA A ASISTENCIA Y QUE CUBRE SÓLO EL TRASLADO        
    $datarpc_val = buscarConceptoPorConceptoXD($datarpc, C909); //ok
    if (floatval($datarpc_val['valor']) > 0) {
        $_movilidad = concepto_0909($datarpc_val['valor']) * $pporcentaje;
    }
    unset($datarpc_val);


    // CONCEPTO : RENTA DE QUINTA CATEGORIA 0605
    // USAR RENTA DE 5TA DESPUES  DE OPERACION !!!! es MAS EFICIENTE!!!
    // xq ka base de datos tiene que estar llena.
    //$_r5ta = calcular_IR5_concepto_0605($ID_PDECLARACION, $obj->getId_trabajador(),$PERIODO);
    //|##############################################################################
    //|                            Init Cargar Conceptos
    $conceptos = array(
        array(
            'cod_detalle_concepto' => C201,
            'monto_pagado' => $_asigFamiliar,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C118, /* C121 */ // Cambiado Concepto a vacacion, OJO!.
            'monto_pagado' => $_sueldoBasico,
            'monto_devengado' => 0
        ),
        //array(
        //    'cod_detalle_concepto' => C706,
        //    'monto_pagado' => $arreglo_0706['concepto'] * $pporcentaje, // OK FULL PRESTAMO+PTF EMPRESA
        //    'monto_devengado' => 0
        //),
        array(
            'cod_detalle_concepto' => C1001,
            'monto_pagado' => $_ptf,
            'monto_devengado' => 0            
        ),
        array(
            'cod_detalle_concepto' => C1002,
            'monto_pagado' => $_prestamo,
            'monto_devengado' => 0            
        ),          
        array(
            'cod_detalle_concepto' => C604,
            'monto_pagado' => $_essaludMasVida, //no usado
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C612,
            'monto_pagado' => $_aseguraPensionMas, //no usado
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C304,
            'monto_pagado' => $_bonifRiesgoCaja,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C703,
            'monto_pagado' => $_dsctoMandatoJudicial,
            'monto_devengado' => 0
        ),
        array(
            'cod_detalle_concepto' => C909,
            'monto_pagado' => $_movilidad,
            'monto_devengado' => 0
        ),
    );

    //|                            End Cargar Conceptos
    //|##############################################################################
    // CONCEPTO : RENTA DE QUINTA 
    $ingresos5ta = get_IR5_Ingresos($ID_PDECLARACION, $obj->getId_trabajador(), $conceptos);
    if ($ingresos5ta > 1400) { // 2012-2013  promedio para optimizar        
        $_r5ta = calcular_IR5_concepto_0605($ID_PDECLARACION, $obj->getId_trabajador(), $PERIODO, $conceptos);
        //$_r5ta = $_r5ta;
        $conceptos[] = array('cod_detalle_concepto' => C605, 'monto_pagado' => ($_r5ta), 'monto_devengado' => 0);
    }

    // NOTA:
    // No es necesario sacar Porcentaje xq solo  el desvuento es en base a los conceptos Es proporcional el descuento que
    // se realiza en estas Operacions.
    // CONCEPTO : ONP , AFP  
    if ($obj->getCod_regimen_pensionario() == '02') { //ONP        
        $_onp = concepto_0607($conceptos);
        $conceptos[] = array('cod_detalle_concepto' => C607, 'monto_pagado' => $_onp, 'monto_devengado' => 0);
    } else if (in_array($obj->getCod_regimen_pensionario(), $_arregloAfps)) {
        $arreglo_afp = concepto_AFP($obj->getCod_regimen_pensionario(), $conceptos); // 3 CONCEPTOS        
        //$conceptos = array_merge($conceptos, $arreglo_afp); 
        $_601 = $arreglo_afp['0601'];
        $_606 = $arreglo_afp['0606'];
        $_608 = $arreglo_afp['0608'];
        $conceptos[] = array('cod_detalle_concepto' => C601, 'monto_pagado' => $_601, 'monto_devengado' => 0);
        $conceptos[] = array('cod_detalle_concepto' => C606, 'monto_pagado' => $_606, 'monto_devengado' => 0);
        $conceptos[] = array('cod_detalle_concepto' => C608, 'monto_pagado' => $_608, 'monto_devengado' => 0);
    }

    // CONCEPTO : ESSALUD
    if ($obj->getCod_regimen_aseguramiento_salud() == '00') {

        $_essalud = concepto_0804($conceptos);
        // ECHO "\nPORCENTAJE ES EN ESSSALUDDDD = PORCENTAJE = $pporcentaje";
        // ECHO "\nESSALUD eS = _essalud = " . $_essalud;
        $conceptos[] = array('cod_detalle_concepto' => C804, 'monto_pagado' => $_essalud, 'monto_devengado' => 0);
    }

    //==========================================================================
    // --------------------- Registrar Data -------------------------
    //==========================================================================

    $dao_tv = new TrabajadorVacacionDao();
    $dao_ddc = new DeclaracionDConceptoVacacionDao();

    // Registrar o Actualizar
    $data_id = $dao_tv->existe($ID_PDECLARACION, $obj->getId_trabajador());
    if (is_null($data_id)) {
        $id = $dao_tv->add($obj);
    } else {
        // echo "\nACTULIZACION!!!!!!!!!!!!!!!!!!!!!!!";
        $id = $data_id;
        $obj->setId_trabajador_vacacion($id);
        $obj->setFecha_actualizacion(date("Y-m-d"));
        $dao_tv->update($obj);
        // limpiar
        $dao_ddc->limpiar($id);
    }
    

    //registrar declaraciones de conceptos.
    $concepto_redondeo = array();
    for ($i = 0; $i < count($conceptos); $i++) {
        if ($conceptos[$i]['monto_pagado'] > 0) {

            $soles = getRendondeoEnSoles($conceptos[$i]['monto_pagado']);
            $monto_pagado = $soles['numero'];
            $monto_devengado = $soles['decimal'];
            // -test
            $concepto_redondeo[] = array(
                'cod_detalle_concepto' => $conceptos[$i]['cod_detalle_concepto'],
                'monto_pagado' => $monto_pagado,
                'monto_devengado' => $monto_devengado
            );

            $obj_ddc = new DeclaracionDConceptoVacacion();
            $obj_ddc->setId_trabajador_vacacion($id);
            $obj_ddc->setCod_detalle_concepto($conceptos[$i]['cod_detalle_concepto']);
            $obj_ddc->setMonto_pagado($monto_pagado);
            $obj_ddc->setMonto_devengado($monto_devengado);

            $dao_ddc->add($obj_ddc);
        }
    }
    // echoo($concepto_redondeo);
    
}

//-----------------------------------------------------------------------------//
//.............................................................................//
//-----------------------------------------------------------------------------//
function cargarTablaTrabajdorVacacion() {

    $dao_tv = new TrabajadorVacacionDao();
    $PERIODO = $_REQUEST['periodo'];
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
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

    $count = $dao_tv->listarCount($ID_PDECLARACION, $WHERE);
    //var_dump($count);
    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        //$total_pages = 0;
    }

    if ($page > $total_pages)
        $page = $total_pages;

    // var_dump($page);
    $start = $limit * $page - $limit;
    //valida
    if ($start < 0)
        $start = 0;

    //llena en al array
    $lista = $dao_tv->listar($ID_PDECLARACION, $WHERE, $start, $limit, $sidx, $sord);
    //$lista = $daoVacacion->listar(ID_EMPLEADOR_MAESTRO, $ID_PDECLARACION, $WHERE, $start, $limit, $sidx, $sord);
// CONTRUYENDO un JSON
    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    //echoo($response);
    $i = 0;
    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
    //$lista = $lista[0];
    foreach ($lista as $rec) {
        $param = $rec["id_trabajador_vacacion"];
        $_01 = $rec["id_trabajador"];
        $_02 = $rec["nombre_tipo_documento"];
        $_03 = $rec["num_documento"];
        $_04 = $rec["apellido_paterno"];
        $_05 = $rec["apellido_materno"];
        $_06 = $rec["nombres"];
        //$js7 = "javascript:cargar_pagina('sunat_planilla/view-empresa/edit_vacacion_2.php?id_vacacion=" . $param . "&id_pdeclaracion=" . $ID_PDECLARACION . "&periodo=" . $PERIODO . "','#CapaContenedorFormulario')";
        //$_07 = '<a href="' . $js7 . '" class="divEditar" ></a>';
        $js8 = "javascript:eliminarTrabajadorVacacion('$param',$_01)";
        $_08 = '<a href="' . $js8 . '" class="divEliminar" ></a>';
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
            $_08
        );
        $i++;
    }

    return $response;
}

function eliminarTVacacion() {   //OK 
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $id_trabajador = $_REQUEST['id_trabajador'];
    $id = $_REQUEST['id'];

    //paso 01 Elimar trabajador_vacacion
    $dao_tv = new TrabajadorVacacionDao();
    $rpta = $dao_tv->eliminar($id);
    return $rpta;
}

function eliminarAll() { //OK
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $dao_tv = new TrabajadorVacacionDao();
    $rpta = $dao_tv->eliminarAll($id_pdeclaracion);
    return $rpta;
}

//---- end vacacion



function boletaVacacacion() {

    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $PERIODO = $_REQUEST['periodo'];
    //generarConfiguracion($PERIODO);
//---------------------------------------------------
// Variables secundarios para generar Reporte en txt
    $nombre_mes = getNameMonth(getFechaPatron($PERIODO, "m"));
    $anio = getFechaPatron($PERIODO, "Y");
    $master_est = null;
    $master_cc = null;
//--
    $master_est = ($_REQUEST['id_establecimientos']) ? $_REQUEST['id_establecimientos'] : '';
    $master_cc = ($_REQUEST['cboCentroCosto']) ? $_REQUEST['cboCentroCosto'] : ''; // OJOO SI NO SE HA SELECCIONADO C.C.

    if ($_REQUEST['todo'] == "todo") {
        $cubo_est = "todo";
    }

    $file_name = '01.txt'; //NAME_COMERCIAL . '-BOLETA PAGO.txt';
    $file_name2 = '02.txt'; //NAME_COMERCIAL . '-MENSUAL.txt';

    $BREAK = chr(13) . chr(10);
    $BREAK2 = chr(13) . chr(10) . chr(13) . chr(10);
    $LINEA = str_repeat('-', 80);
    $FORMATO_0 = chr(27) . '@' . chr(27) . 'C!';
    $FORMATO = chr(18) . chr(27) . "P";
//..............................................................................
// Inicio Exel
//..............................................................................
    $fpx = fopen($file_name2, 'w');
    $fp = fopen($file_name, 'w');

    //fwrite($fp, $FORMATO_0);
    fwrite($fpx, $FORMATO);
    //fwrite($fpx, $BREAK);
    // paso 01 Listar ESTABLECIMIENTOS del Emplearo 'Empresa'
    $dao_est = new EstablecimientoDao(); //?????????????
    $est = array();
    $est = $dao_est->listar_Ids_Establecimientos(ID_EMPLEADOR);
    $contador_break = 0;

    // paso 02 listar CENTROS DE COSTO del establecimento.    
    if (count($est) > 0) {

        //DAO
        $dao_cc = new EmpresaCentroCostoDao();
        $dao_pago = new TrabajadorPdeclaracionDao(); //[OK]
        //new
        $dao_trav = new TrabajadorVacacionDao();


        $dao_estd = new EstablecimientoDireccionDao();
        $dao_rp = new DetalleRegimenPensionarioDao(); //[OK]
        $dao_pdireccion = new PersonaDireccionDao(); //[OK]
        $dao_dpl = new DetallePeriodoLaboralDao(); //[OK]
        // -------- Variables globales --------//        
        $SUM_TOTAL_CC = array();
        $SUM_TOTAL_EST = array();

        for ($i = 0; $i < count($est); $i++) { // ESTABLECIMIENTO
            //fwrite($fp, $BREAK2);
            //fwrite($fp, "Conteo de eSTABLECIMNETO = i = $i");
            //fwrite($fp, $BREAK2);
            $bandera_1 = false;
            if ($est[$i]['id_establecimiento'] == $master_est) {
                $bandera_1 = true;
            } else if ($cubo_est == "todo") {
                $bandera_1 = true;
            }

            if ($bandera_1) {
                //$contador_break = $contador_break + 1;
                // paso 02 Establecimiento direccion Reniec
                $data_est_direc = $dao_estd->buscarEstablecimientoDireccionReniec($est[$i]['id_establecimiento']);


                // paso 03 Centro de costo ' lista los trabajadores por' 
                $ecc = $dao_cc->listar_Ids_EmpresaCentroCosto($est[$i]['id_establecimiento']);

                //fwrite($fp, $BREAK2);
                //fwrite($fp, "NUM DE establecimiento y cuantos CENTROS  COSTOS TIENEN   =".count($ecc));                 
                //fwrite($fp, $BREAK2);

                for ($j = 0; $j < count($ecc); $j++) {

                    //fwrite($fp, $BREAK2);
                    //fwrite($fp, "entra a for j = $j ");
                    $bandera_2 = false;
                    if ($ecc[$j]['id_empresa_centro_costo'] == $master_cc) {
                        $bandera_2 = true;
                    } else if ($cubo_est == "todo") {
                        $bandera_2 = true;
                    } else if ($master_cc == 0) {
                        $bandera_2 = true;
                    }

                    if ($bandera_2) {
                        fwrite($fp, $FORMATO_0);
                        // LISTA DE TRABAJADORES
                        $data_tra = array();
                        //$data_tra = $dao_pago->listar_2($ID_PDECLARACION, $est[$i]['id_establecimiento'], $ecc[$j]['id_empresa_centro_costo']);
                        $data_tra = $dao_trav->listarReporteVacacion($ID_PDECLARACION, $est[$i]['id_establecimiento'], $ecc[$j]['id_empresa_centro_costo']);


                        if (count($data_tra) > 0) {

                            $SUM_TOTAL_CC[$i][$j]['establecimiento'] = $data_est_direc['ubigeo_distrito'];
                            $SUM_TOTAL_CC[$i][$j]['centro_costo'] = strtoupper($ecc[$j]['descripcion']);
                            $SUM_TOTAL_CC[$i][$j]['monto'] = 0;

                            // .......................Inicio Cabecera  $fpx ........ 
                            fwrite($fpx, NAME_EMPRESA);

                            fwrite($fpx, str_pad("FECHA : ", 56, " ", STR_PAD_LEFT));
                            fwrite($fpx, str_pad(date("d/m/Y"), 11, " ", STR_PAD_LEFT));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("PAGINA :", 69, " ", STR_PAD_LEFT));
                            $contador_break++;
                            fwrite($fpx, str_pad($contador_break, 11, " ", STR_PAD_LEFT));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("MENSUAL", 80, " ", STR_PAD_BOTH));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("PLANILLA DE VACACION DE " . strtoupper($nombre_mes) . " DEL " . $anio, 80, " ", STR_PAD_BOTH));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, "LOCALIDAD : " . $data_est_direc['ubigeo_distrito']);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, "CENTRO DE COSTO : " . strtoupper($ecc[$j]['descripcion']));
                            fwrite($fpx, $BREAK);
                            //$worksheet->write($row, $col, "##################################################");
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, $LINEA);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("N ", 4, " ", STR_PAD_LEFT));
                            fwrite($fpx, str_pad("DNI", 12, " ", STR_PAD_RIGHT));
                            fwrite($fpx, str_pad("APELLIDOS Y NOMBRES", 40, " ", STR_PAD_RIGHT));
                            fwrite($fpx, str_pad("IMPORTE", 9, " ", STR_PAD_RIGHT));
                            fwrite($fpx, str_pad("FIRMA", 15, " ", STR_PAD_RIGHT));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, $LINEA);
                            fwrite($fpx, $BREAK);
                            // .......................final Cabecera  $fpx ........                        
                            //$contador_break = 0;
                            $num_trabajador = 0;
                            for ($k = 0; $k < count($data_tra); $k++) {
                                //fwrite($fp,$FORMATO);
                                fwrite($fp, str_pad("BOLETA DE PAGO VAC.", 136, " ", STR_PAD_BOTH));
                                fwrite($fp, $BREAK);
                                fwrite($fp, str_pad("D.S. 020-2008-TR DEL 17-01-2008", 136, " ", STR_PAD_BOTH));
                                fwrite($fp, $BREAK);

                                fwrite($fp, NAME_EMPRESA);
                                fwrite($fp, $BREAK);


                                $direccion = $data_est_direc['ubigeo_nombre_via'] . " " . $data_est_direc['nombre_via'];
                                $direccion .=" " . $data_est_direc['numero_via'] . " - " . $data_est_direc['ubigeo_distrito'];

                                fwrite($fp, str_pad($direccion, 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("CODIGO: " . $data_tra[$k]['num_documento'], 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("DNI: " . $data_tra[$k]['num_documento'], 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);

                                $nombre_c = $data_tra[$k]['apellido_paterno'] . " " . $data_tra[$k]['apellido_materno'] . " " . $data_tra[$k]['nombres'];
                                fwrite($fp, str_pad("R.U.C. " . RUC, 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("NOMBRE Y APELLIDOS: " . $nombre_c, 88, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);

                                // Buscar fecha inicio
                                $data_fech_inicio = $dao_dpl->buscarDetallePeriodoLaboral_2($data_tra[$k]['id_trabajador']);

                                fwrite($fp, str_pad("Reo.Pat. 2010033861100000", 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("CARNET DE ESSALUD : -", 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("FECHA INGRESO : " . getFechaPatron($data_fech_inicio, "d/m/Y"), 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);

                                //......................................................                                                
                                $afp_carnet_value = null;
                                $afp_nombre_value = null;

                                if ($data_tra[$k]['cod_regimen_pensionario'] == '02') { //ONP                            
                                    $afp_nombre_value = "R.P. : " . $data_tra[$k]['nombre_afp'];
                                } else { //AFP                            
                                    //dao                            
                                    $arreglo_data_rp = $dao_rp->buscarDetalleRegimenPensionario($data_tra[$k]['id_trabajador']);
                                    $afp_nombre_value = "A.F.P. : " . $data_tra[$k]['nombre_afp'];
                                    $afp_carnet_value = "NRO.CARNET AFP : " . $arreglo_data_rp['cuspp'];
                                }

                                //......................................................


                                fwrite($fp, str_pad("FECHA VAC. " . $data_tra[$k]['fecha_lineal'], 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad($afp_carnet_value, 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad($afp_nombre_value, 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);


                                $num_mes = intval(getFechaPatron($PERIODO, "m"));
                                $fecha_0 = getNameMonth($num_mes);
                                $fecha_1 = getFechaPatron($PERIODO, "d.Y");
                                $cadena_fecha = "MES : " . $fecha_0 . " DE : " . $fecha_1;
                                fwrite($fp, str_pad($cadena_fecha, 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("CARGO : " . $data_tra[$k]['nombre_ocupacion'], 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("SECCION : " . $data_tra[$k]['nombre_centro_costo'], 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);


                                //......................................................
                                $data_direccion = array();
                                $data_direccion = $dao_pdireccion->listarPersonaDirecciones($data_tra[$k]['id_persona']);
                                //foreach ($lista as $rec) {
                                $cadena = '';
                                for ($a = 0; $a < 1/* count($lista) */; $a++) {
                                    $rec = $data_direccion[$a];

                                    //$id_persona = $rec['id_persona'];                                    
                                    $ubigeo_nombre_via = $rec["ubigeo_nombre_via"];
                                    $nombre_via = $rec['nombre_via'];
                                    $numero_via = $rec['numero_via'];

                                    $ubigeo_nombre_zona = $rec['ubigeo_nombre_zona'];
                                    $nombre_zona = $rec['nombre_zona'];
                                    $etapa = $rec['etapa'];
                                    $manzana = $rec['manzana'];
                                    $blok = $rec['blok'];
                                    $lote = $rec['lote'];

                                    $departamento = $rec['departamento'];
                                    $interior = $rec['interior'];

                                    $kilometro = $rec['kilometro'];

                                    $ubigeo_departamento = str_replace('DEPARTAMENTO', '', $rec['ubigeo_departamento']);
                                    $ubigeo_provincia = $rec['ubigeo_provincia'];
                                    $ubigeo_distrito = $rec['ubigeo_distrito'];


                                    $cadena .= ($ubigeo_nombre_via != "-") ? " " . $ubigeo_nombre_via : '';
                                    $cadena .= (!empty($nombre_via)) ? " " . $nombre_via : '';
                                    $cadena .= (!empty($numero_via)) ? " " . $numero_via : '';

                                    $cadena .= ($ubigeo_nombre_zona != "-") ? $ubigeo_nombre_zona : '';
                                    $cadena .= (!empty($nombre_zona)) ? " " . $nombre_zona : '';
                                    $cadena .= (!empty($etapa)) ? " " . $etapa : '';

                                    $cadena .= (!empty($manzana)) ? ' MZA. ' . $manzana : '';
                                    $cadena .= (!empty($blok)) ? " " . $blok : '';
                                    $cadena .= (!empty($etapa)) ? " " . $etapa : '';
                                    $cadena .= (!empty($lote)) ? ' LOTE. ' . $lote : '';

                                    $cadena .= (!empty($departamento)) ? " " . $departamento : '';
                                    $cadena .= (!empty($interior)) ? " " . $interior : '';
                                    $cadena .= (!empty($kilometro)) ? " " . $kilometro : '';

                                    $cadena .= ($ubigeo_departamento != "-") ? $ubigeo_departamento . "-" : '';
                                    $cadena .= ($ubigeo_provincia != "-") ? $ubigeo_provincia . "-" : '';
                                    $cadena .= ($ubigeo_distrito != "-") ? $ubigeo_distrito : '';

                                    $cadena = strtoupper($cadena);
                                }

                                //......................................................

                                $cadena_dialab = $data_tra[$k]['dia'] . " DIAS DE VAC. " . ($data_tra[$k]['dia'] * HORA_BASE) . " HORAS.";

                                fwrite($fp, str_pad($cadena_dialab, 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("DIRECCION : " . $cadena, 88, " ", STR_PAD_RIGHT));

                                fwrite($fp, $BREAK);


                                $array_mixto = generarBoletaVacacionTabla($fp, $data_tra[$k]['id_trabajador_vacacion'], $data_tra[$k]['cod_regimen_pensionario'], $PERIODO, $ID_PDECLARACION, $data_tra[$k]['id_trabajador'], $BREAK, $BREAK2);

                                $neto_pagar = $array_mixto['numero'];

                                if ($array_mixto['centinela'] == true) {
                                    //SALIO DE MARCO SGTE PAGINA!
                                    fwrite($fp, chr(12));
                                } else {
                                    fwrite($fp, chr(12));
                                }

                                //++                            
                                // Generar Boleta....................................
                                /* $fpx = */  //generarBoletaLineal($fpx, $data,$neto_pagar,$k,$BREAK);

                                $texto_3 = $data_tra[$k]['apellido_paterno'] . " " . $data_tra[$k]['apellido_materno'] . " " . $data_tra[$k]['nombres'];
                                fwrite($fpx, $BREAK);
                                fwrite($fpx, str_pad(($k + 1) . " ", 4, " ", STR_PAD_LEFT));
                                fwrite($fpx, str_pad($data_tra[$k]['num_documento'], 12, " ", STR_PAD_RIGHT));
                                fwrite($fpx, str_pad(strtoupper($texto_3), 40, " ", STR_PAD_RIGHT));
                                fwrite($fpx, str_pad(number_format($neto_pagar, 2), 9, " ", STR_PAD_RIGHT));
                                fwrite($fpx, str_pad("_______________", 15, " ", STR_PAD_RIGHT));
                                fwrite($fpx, $BREAK);

                                $num_trabajador++;
                                if ($num_trabajador >= 23):
                                    fwrite($fpx, chr(12));
                                    $num_trabajador = 0;
                                endif;

                                // por persona
                                $SUM_TOTAL_CC[$i][$j]['monto'] = $SUM_TOTAL_CC[$i][$j]['monto'] + $neto_pagar;
                            }//enfFor $k 
                            //$SUM_TOTAL_EST[$i]['monto'] = $SUM_TOTAL_EST[$i]['monto'] + $SUM_TOTAL_CC[$i][$j]['monto'];
                            //--- LINE
                            fwrite($fpx, $BREAK);
                            //fwrite($fp, $LINEA);
                            fwrite($fpx, $LINEA);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("TOTAL " . $SUM_TOTAL_CC[$i][$j]['centro_costo'] . " " . $SUM_TOTAL_EST[$i]['establecimiento'], 56, " ", STR_PAD_RIGHT));
                            fwrite($fpx, number_format($SUM_TOTAL_CC[$i][$j]['monto'], 2));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, $LINEA);
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, chr(12));
                        }//EXISTEN TRABAJADORES!
                    }
                }//END FOR CCosto

                fwrite($fp, $BREAK);
            }
        }//END FOR Est
    }//END IF


    fclose($fp);
    fclose($fpx);


    $file = array();
    $file[] = $file_name;
    $file[] = $file_name2;

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

// boleta tabla vacacion

function generarBoletaVacacionTabla($fp, $id_trabajador_vacacion, $cod_regimen_pensionario, $periodo, $id_pdeclaracion, $id_trabajador, $BREAK, $BREAK2) {
    $array_mixto = array();
    //..............................................................................
    $cod_conceptos_ingresos = array('100', '200', '300', '400', '500', '900');

    $cod_conceptos_descuentos = array('600', '700','1000');

    $cod_conceptos_aportes = array(/* '600', */ '800');
    //..............................................................................

    $dao_ddc = new DeclaracionDconceptoDao();
    $dao_ddcv = new DeclaracionDConceptoVacacionDao();
    $dao_pdcem = new PlameDetalleConceptoEmpleadorMaestroDao();


    $LINEA = str_repeat('-', 135);
    $VACIO = str_repeat(' ', 135);
    $PUNTO = "|";
    $BORDE_R = str_pad('', 3, " ", STR_PAD_RIGHT); // $BORDER
    $BORDE_L = str_pad('', 3, " ", STR_PAD_LEFT); // $BORDER
    fwrite($fp, $LINEA);
    fwrite($fp, $BREAK);

    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("R E M U N E R A C I O N E S", 38, " ", STR_PAD_BOTH));
    fwrite($fp, $BORDE_L);

    fwrite($fp, $PUNTO);
    //fwrite($fp, $BORDE_R);   
    fwrite($fp, str_pad("R E T E N C I O N E S / D E S C U E N T O S", 44, " ", STR_PAD_BOTH));
    //fwrite($fp, $BORDE_L);

    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("A P O R T A C I O N E S", 37, " ", STR_PAD_BOTH));
    fwrite($fp, $BORDE_L);
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);

    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("DESCRIPCION", 19, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad("IMPORTE", 19, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);

    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("DESCRIPCION", 19, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad("IMPORTE", 19, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);
    fwrite($fp, $PUNTO);

    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("DESCRIPCION", 19, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad("IMPORTE", 18, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);
    fwrite($fp, $PUNTO);

    fwrite($fp, $BREAK);
    fwrite($fp, $LINEA);
    fwrite($fp, $BREAK);

    // ----- INICIO CUERPO 
    //conceptos calculados base
    $calc = array();
    $calc = $dao_ddcv->buscar_ID_TrabajadorVacacionPorConceptos($id_trabajador_vacacion);

    // INGRESOS
    // 01 lista de todos conceptos Ingresos
    $c_pingreso = array();
    $c_pingreso = $dao_pdcem->view_listarConceptoReducido(ID_EMPLEADOR_MAESTRO, $cod_conceptos_ingresos, 1);

    // armado de array
    $array_ingreso = array();
    $array_ingreso = arrayId($c_pingreso, "cod_detalle_concepto");

    $ingresos = array();
    $x = 0;
    $sum_i = 0.00;
    for ($o = 0; $o < count($calc); $o++):
        if (in_array($calc[$o]['cod_detalle_concepto'], $array_ingreso)):
            $ingresos[$x]['descripcion'] = $calc[$o]['descripcion'];
            $ingresos[$x]['descripcion_abreviada'] = $calc[$o]['descripcion_abreviada'];
            //$ingresos[$x]['cod_detalle_concepto'] = $calc[$o]['cod_detalle_concepto'];
            $mPagado = ($calc[$o]['monto_pagado'] > 0) ? $calc[$o]['monto_pagado'] : 0;
            $mDevengado = ($calc[$o]['monto_devengado'] > 0) ? $calc[$o]['monto_devengado'] : 0;
            $mTotal = $mPagado + $mDevengado;
            $ingresos[$x]['monto_pagado'] = $mTotal;
            $sum_i = $sum_i + $mTotal;
            $x++;
        //}
        endif;
    endfor;

//echoo($ingresos);
//------------------------------------------------------------------------------
// DESCUENTOS
// 01 lista de todos conceptos 
    $c_pdescuento = array();
    $c_pdescuento = $dao_pdcem->view_listarConceptoReducido(ID_EMPLEADOR_MAESTRO, $cod_conceptos_descuentos, $seleccionado = array(0, 1));

    // armado de array
    $array_descuento = array();
    $array_descuento = arrayId($c_pdescuento, "cod_detalle_concepto");
    $descuentos = array();
    $x = 0;
    $sum_d = 0.00;
    for ($o = 0; $o < count($calc); $o++):
        //if ($calc[$o]['cod_detalle_concepto'] == '0703') {
        if (in_array($calc[$o]['cod_detalle_concepto'], $array_descuento)):
            $descuentos[$x]['descripcion'] = $calc[$o]['descripcion'];
            $descuentos[$x]['descripcion_abreviada'] = $calc[$o]['descripcion_abreviada'];
            $mPagado = ($calc[$o]['monto_pagado'] > 0) ? $calc[$o]['monto_pagado'] : 0;
            $mDevengado = ($calc[$o]['monto_devengado'] > 0) ? $calc[$o]['monto_devengado'] : 0;
            $mTotal = $mPagado + $mDevengado;
            $descuentos[$x]['cod_detalle_concepto'] = $calc[$o]['cod_detalle_concepto'];
            $descuentos[$x]['monto_pagado'] = $mTotal;
            $sum_d = $sum_d + $mTotal;
            $x++;
        endif;
    //}
    endfor;


    //------------------------------------------------------------------------------
    // 01 lista de todos conceptos
    $c_paporte = array();
    $c_paporte = $dao_pdcem->view_listarConceptoReducido(ID_EMPLEADOR_MAESTRO, $cod_conceptos_aportes, 0);

    // armado de array
    $array_aporte = array();
    $array_aporte = arrayId($c_paporte, "cod_detalle_concepto");
    $aportes = array();
    $x = 0;
    $sum_a = 0.00;
    for ($o = 0; $o < count($calc); $o++):
        if (in_array($calc[$o]['cod_detalle_concepto'], $array_aporte)):
            $aportes[$x]['descripcion'] = $calc[$o]['descripcion'];
            $aportes[$x]['descripcion_abreviada'] = $calc[$o]['descripcion_abreviada'];
            $mPagado = ($calc[$o]['monto_pagado'] > 0) ? $calc[$o]['monto_pagado'] : 0;
            $mDevengado = ($calc[$o]['monto_devengado'] > 0) ? $calc[$o]['monto_devengado'] : 0;
            $mTotal = $mPagado + $mDevengado;
            $aportes[$x]['monto_pagado'] = $mTotal;
            $sum_a = $sum_a + $mTotal;
            $x++;
        endif;
    endfor;


//----------------------------PINTAR EN TABLA-----------------------------------
    $cnt_ingreso = count($ingresos);
    $cnt_descuento = count($descuentos);
    $cnt_aporte = count($aportes);


    $numeros = array($cnt_ingreso, $cnt_descuento, $cnt_aporte);

    $mayor = getNumeroMayor($numeros) + 1;
    //$mayor = $mayor + 1;
    //array mixto
    if ($mayor > 7) {
        $array_mixto['centinela'] = true;
    } else {
        $array_mixto['centinela'] = false;
    }


    for ($i = 0; $i < $mayor; $i++): // [7] limite maX por hoja

        fwrite($fp, $PUNTO);
        fwrite($fp, $BORDE_R);
        $descripcion_1 = ($ingresos[$i]['descripcion_abreviada'] == "") ? $ingresos[$i]['descripcion'] : $ingresos[$i]['descripcion_abreviada'];

        if (strlen($descripcion_1) > 29):
            $descripcion_1 = substr($descripcion_1, 0, 26);
            $descripcion_1.= "...";
        endif;

        fwrite($fp, str_pad($descripcion_1, 29, " ", STR_PAD_RIGHT));
        $ingreso_boo = ($ingresos[$i]['monto_pagado']) ? number_format_var($ingresos[$i]['monto_pagado']) : '';
        fwrite($fp, str_pad($ingreso_boo, 9, " ", STR_PAD_LEFT));
        fwrite($fp, $BORDE_L);
        fwrite($fp, $PUNTO);

        fwrite($fp, $BORDE_R);
        $descripcion_2 = ($descuentos[$i]['descripcion_abreviada'] == "") ? $descuentos[$i]['descripcion'] : $descuentos[$i]['descripcion_abreviada'] . " ";

        //,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,
        $dao_afp = new ConfAfpDao();
        $afp = $dao_afp->vigenteAfp($cod_regimen_pensionario, $periodo);

        if ($cod_regimen_pensionario == '02') { //ONP
        } else { //AF --Q ESTA AFILIADO
            if ($descuentos[$i]['cod_detalle_concepto'] == '0601') {
                $descripcion_2 .= $afp['comision'] . "%";
            } else if ($descuentos[$i]['cod_detalle_concepto'] == '0606') {
                $descripcion_2 .= $afp['prima_seguro'] . "%";
            } else if ($descuentos[$i]['cod_detalle_concepto'] == '0608') {
                $descripcion_2 .= $afp['aporte_obligatorio'] . "%";
            }
        }
        //,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,   

        if (strlen($descripcion_2) > 29):
            $descripcion_2 = substr($descripcion_2, 0, 26);
            $descripcion_2.= "...";
        endif;

        fwrite($fp, str_pad($descripcion_2, 29, " ", STR_PAD_RIGHT));
        $descuento_boo = ($descuentos[$i]['monto_pagado']) ? number_format_var($descuentos[$i]['monto_pagado']) : '';
        fwrite($fp, str_pad($descuento_boo, 9, " ", STR_PAD_LEFT));
        fwrite($fp, $BORDE_L);
        fwrite($fp, $PUNTO);

        fwrite($fp, $BORDE_R);
        $descripcion_3 = ($aportes[$i]['descripcion_abreviada'] == "") ? $aportes[$i]['descripcion'] : $aportes[$i]['descripcion_abreviada'];
//   $descripcion_3 = "aaaaaaaaaaaaaaaaaaaaaaaaaaaa";

        if (strlen($descripcion_3) > 28):
            $descripcion_3 = substr($descripcion_3, 0, 25);
            $descripcion_3.= "...";
        endif;
        fwrite($fp, str_pad($descripcion_3, 28, " ", STR_PAD_RIGHT));
        $aporte_boo = ($aportes[$i]['monto_pagado']) ? number_format_var($aportes[$i]['monto_pagado']) : '';
        fwrite($fp, str_pad($aporte_boo, 9, " ", STR_PAD_LEFT));
        fwrite($fp, $BORDE_L);
        fwrite($fp, $PUNTO);
        fwrite($fp, $BREAK);

    endfor;
//----------------------------PINTAR EN TABLA-----------------------------------
// ----- FIN CUERPO  

    fwrite($fp, $LINEA);
    fwrite($fp, $BREAK);


    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("TOTAL REMUNERACIONES ", 29, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad(number_format_var($sum_i), 9, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);


    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("TOTAL RETENC./DESCUENTOS ", 29, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad(number_format_var($sum_d), 9, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);

    fwrite($fp, $PUNTO);
    fwrite($fp, $BORDE_R);
    fwrite($fp, str_pad("TOTAL APORTACIONES", 28, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad(number_format_var($sum_a), 9, " ", STR_PAD_LEFT));
    fwrite($fp, $BORDE_L);
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);
    fwrite($fp, $LINEA);
    fwrite($fp, $BREAK2);

    if ($mayor < 7) {
        for ($d = 7; $mayor < $d; $d--):
            fwrite($fp, $VACIO);
            fwrite($fp, $BREAK);
        endfor;
    }

    //--------- 
    $arreglo_numero = array();
    $arreglo_numero = roundFaborContra(($sum_i - $sum_d));

    $linea_caja = str_repeat('-', 29);
    fwrite($fp, $linea_caja);
    fwrite($fp, $BREAK);

    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('', 1, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad("REDONDEO", 13, " ", STR_PAD_RIGHT));
    fwrite($fp, ":");
    fwrite($fp, str_pad($arreglo_numero['decimal'], 11, " ", STR_PAD_LEFT));
    fwrite($fp, str_pad('', 1, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);
    fwrite($fp, $BREAK);
    fwrite($fp, $linea_caja);


    fwrite($fp, $BREAK);
    fwrite($fp, $PUNTO);
    fwrite($fp, str_pad('', 1, " ", STR_PAD_RIGHT));
    fwrite($fp, str_pad("NETO A PAGAR", 13, " ", STR_PAD_RIGHT));
    fwrite($fp, ":");
    fwrite($fp, str_pad(number_format_var($arreglo_numero['numero']), 11, " ", STR_PAD_LEFT));
    fwrite($fp, str_pad('', 1, " ", STR_PAD_LEFT));
    fwrite($fp, $PUNTO);

    fwrite($fp, str_pad('_________________________', 53, " ", STR_PAD_LEFT));
    fwrite($fp, str_pad('_________________________', 43, " ", STR_PAD_LEFT));
    fwrite($fp, $BREAK);
    fwrite($fp, $linea_caja);
    fwrite($fp, str_pad('P.' . NAME_EMPRESA, 49, " ", STR_PAD_LEFT)); //VO
    fwrite($fp, str_pad('RECIBI CONFORME', 43, " ", STR_PAD_LEFT));
    fwrite($fp, $BREAK);

    $array_mixto['numero'] = $arreglo_numero['numero'];

    return $array_mixto; //$arreglo_numero['numero'];
}

