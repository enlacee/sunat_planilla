<?php

$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    // IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    require_once '../dao/PlameDao.php';
    require_once '../dao/PlameDeclaracionDao.php';
    require_once '../dao/DeclaracionDconceptoDao.php';
    require_once '../dao/TrabajadorPdeclaracionDao.php';

    //Conceptos afectos
    require_once '../dao/PlameDetalleConceptoEmpleadorMaestroDao.php';
    require_once '../controller/ConfController.php';
    require_once '../controller/ConfConceptosController.php';

    // IDE CONFIGURACION  -- generarConfiguracion(periodo);
    require_once '../dao/ConfAsignacionFamiliarDao.php';
    require_once '../dao/ConfSueldoBasicoDao.php';
    require_once '../dao/ConfEssaludDao.php';
    require_once '../dao/ConfOnpDao.php';
    require_once '../dao/ConfUitDao.php';

    //nuevas tablas
    require_once '../model/TrabajadorGratificacion.php';
    require_once '../dao/TrabajadorGratificacionDao.php';

    require_once '../model/DeclaracionDConceptoGrati.php';
    require_once '../dao/DeclaracionDConceptoGratiDao.php';

    // boleta gratifiacion
    require_once '../dao/EstablecimientoDao.php';
    require_once '../dao/EmpresaCentroCostoDao.php';
    require_once '../dao/EstablecimientoDireccionDao.php';
    require_once '../dao/DetalleRegimenPensionarioDao.php';
    require_once '../dao/PersonaDireccionDao.php';
    require_once '../dao/DetallePeriodoLaboralDao.php';
    require_once '../dao/RegistroPorConceptoDao.php';
    //afp boleta
    require_once '../dao/ConfAfpDao.php';
    
    //ZIP
    require_once '../util/zip/zipfile.inc.php';
    
    
}

if ($op == "gratificacion") {

    $response = grafificacion();
} else if ($op == "boleta_gratifiacion") {
    boletaGratificacion();
}

echo (!empty($response)) ? json_encode($response) : '';

function grafificacion() {

    $periodo = $_REQUEST['periodo'];
    generarConfiguracion($periodo);
    $periodo_rel = $periodo; //periodo_relativo
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];

  /*  if (getFechaPatron($periodo, "m") == '07') { // JULIO   
        
        echo "\nJULIO MES DE GRATIFICACIONES\n";
        
    } else */if (getFechaPatron($periodo, "m") == '12' || getFechaPatron($periodo, "m") == '07') { // DICIEMBRE
        //DAO
        $dao_plame = new PlameDao();
        $dao_pdeclaracion = new PlameDeclaracionDao();
        $dao_dconcepto = new DeclaracionDconceptoDao();
        $dao_tpd = new TrabajadorPdeclaracionDao();


        $ap = getMesInicioYfin($periodo);
        echoo($ap);
        $data = $dao_plame->listarTrabajadoresPorPeriodo_global(ID_EMPLEADOR_MAESTRO, $ap['mes_inicio'], $ap['mes_fin']);
        
        //SUELDO REFERENCIA : mes 12=11 o 7=6
        //....................................................................................................
        $periodo_noviembre = crearFecha($periodo, 0, -1, 0);
        //....................................................................................................
        $data_idpd = $dao_pdeclaracion->Buscar_IDPeriodo(ID_EMPLEADOR_MAESTRO, $periodo_noviembre);
        echo "\n";
        echo "<br>PERIODO DE noviembre JuNio = ".$periodo_noviembre;
        echo "\n";
        //$concepto_ingresos = arrayConceptosIngresos();

        for ($i = 0; $i < /*2*/count($data); $i++) {

            $variable_sueldo = 0;
            $descuento = 0;
            $descuento = 0;
            $ESTADO_ASIG_FAMILIAR = false;
            $detalle = array();

            //INGRESOS EN EL MES de Noviembre 2012-11-01
            $sueldo_noviembre = $dao_tpd->buscar_what($data_idpd['id_pdeclaracion'], $data[$i]['id_trabajador'], 'sueldo_neto');
            echo "\nsueldo_noviembre =" . $sueldo_noviembre;

            $data_dconcepto = $dao_dconcepto->listarTrabajadorPorDeclaracion($data[$i]['id_trabajador'], $data_idpd['id_pdeclaracion']);
            //--
            $conceptos = array('0201', '0909', '0304');
            $sueldo_adjunto = 0;
            for ($j = 0; $j < count($data_dconcepto); $j++) {
                if (in_array($data_dconcepto[$j]['cod_detalle_concepto'], $conceptos)) {
                    echo "\n for sum =" . $data_dconcepto[$j]['cod_detalle_concepto'];
                    echo " = " . $data_dconcepto[$j]['monto_pagado'];
                    //$detalle[][$data_dconcepto[$j]['cod_detalle_concepto']] = $data_dconcepto[$j]['monto_pagado'];

                    $sueldo_adjunto = $sueldo_adjunto + $data_dconcepto[$j]['monto_pagado'];
                }
            }
            //--
            echo "\n\n data[$i]['id_trabajador'] = " . $data[$i]['id_trabajador'];
            echo "\nHOLAsueldo_adjunto =" . $sueldo_adjunto;
            // NUMERO DE MESES
            $num_meses = contarNumerosMesesCompletos($data[$i]['fecha_inicio'], $periodo);


            //ERROR  DIVICION EN CERO
            $v1 = ($sueldo_noviembre + $sueldo_adjunto) / 6;
            if ($sueldo_noviembre > 0) {
                $v1_dia = $v1 / DIA_BASE;
            } else {
                $v1_dia = 0;
            }
            echo "\n/6*Numero meses = " . $num_meses;
            echo "\nv1=" . $v1;

            //de JULIO A DICIEMBRE                       
            //--------------------------------------------------------------------------------------------------------------
            //formula 
            $variable1 = 0;
            $variable2 = 0;
            $variable3 = 0;

            for ($a = 0; $a < $num_meses; $a++) { // periodo = 2012-12-01 diciembre hacia atras.
                // NUN_MES - TRABAJADO
                $mes_negativo = ($a * -1);
                
                //....................................................................................................
                //Ecepcion de Julio.
                $periodo_rel = (getFechaPatron($periodo_rel, "m") =='07') ? crearFecha($periodo_rel,0,-1,0) : $periodo_rel;
                //....................................................................................................
                
                $periodo_creado = crearFecha($periodo_rel, 0, $mes_negativo, 0);

                $id_pdeclaracion_data = $dao_pdeclaracion->Buscar_IDPeriodo(ID_EMPLEADOR_MAESTRO, $periodo_creado);


                // DIA laborado en el mes .TRABAJADOr
                $dia_laborado = $dao_tpd->buscar_what($id_pdeclaracion_data['id_pdeclaracion'], $data[$i]['id_trabajador'], 'dia_laborado');
                $dia_total = $dao_tpd->buscar_what($id_pdeclaracion_data['id_pdeclaracion'], $data[$i]['id_trabajador'], 'dia_total');

                $dia_laborado = ($dia_laborado == null) ? 0 : $dia_laborado;
                $dia_total = ($dia_total == null) ? 0 : $dia_total;

                $dia_falto = $dia_total - $dia_laborado;

                //Descuento x falta
                $v2 = $v1_dia * $dia_falto;

                $total = ($v1 - $v2);
                echo "\n = $periodo_creado   = " . $total;
                $variable1 = $variable1 + $total;


                $data_dconcepto_mes = $dao_dconcepto->listarTrabajadorPorDeclaracion($data[$i]['id_trabajador'], $id_pdeclaracion_data['id_pdeclaracion']);

                // variable 02 = sobretiempo y +
                $variable2_mes = 0;
                $conceptos2 = array('0105', '0106', '0107', '0115');
                $detalle[$i][$a]['periodo'] = $periodo_creado;
                $indice = 0;
                for ($j = 0; $j < count($data_dconcepto_mes); $j++) {

                    if (in_array($data_dconcepto_mes[$j]['cod_detalle_concepto'], $conceptos2)) {
                        //echo " = ".$data_dconcepto[$j]['monto_pagado'];
                        $variable2_mes = $variable2_mes + $data_dconcepto_mes[$j]['monto_pagado'];

                        echo "\n**" . $data_dconcepto[$j]['cod_detalle_concepto'] . " = " . $data_dconcepto_mes[$j]['monto_pagado'];
                        $detalle[$i][$a]['concepto'][$indice]['name'] = $data_dconcepto_mes[$j]['cod_detalle_concepto'];
                        $detalle[$i][$a]['concepto'][$indice]['valor'] = $data_dconcepto_mes[$j]['monto_pagado'];
                        //$detalle[$i][$a]['concepto'][$data_dconcepto[$j]['cod_detalle_concepto']] = $data_dconcepto_mes[$j]['monto_pagado'];
                        $indice++;
                    }
                }
                $variable2 = $variable2 + $variable2_mes;
            }
            //--------------------------------------------------------------------------------------------------------------            
            echo "\nvariable 2 = $variable2";

            $variable3 = ($variable1 + $variable2) * (T_ESSALUD / 100);


            $total = $variable1 + $variable2 + $variable3;

            echo "\nvariable1 = " . $variable1;
            echo "\nvariable2 = " . $variable2;
            echo "\nT_ESSALUD = " . T_ESSALUD;
            echo "\nvariable3 = " . $variable3;
            echo " ID_TRABAJADOR = " . $data[$i]['id_trabajador'];
            ECHO "TOTAL = " . $total;

            echo "\n**********************************************************\n";

            // Preguntar si existe Gratificacion Registrada en dicho periodo
            $dao_tg = new TrabajadorGratificacionDao();
            echo "\nPERIODO A REGISTRAR ES : periodo = $periodo";
            echo "\nPERIODO A REGISTRAR ES : periodo_rel = $periodo_rel";
            $data_db = $dao_tg->existePersonaGratificacion($data[$i]['id_trabajador'], $periodo);

            if ($data_db['id_trabajador'] == '') {

                // Insert Table    
                //UNO
                $tg = new TrabajadorGratificacion();
                $tg->setId_trabajador($data[$i]['id_trabajador']);
                $tg->setFecha($periodo);
                $tg->setFecha_creacion(date("Y-m-d"));
                $id = $dao_tg->add($tg);
                //MUCHOS            
                $dao_ddg = new DeclaracionDConceptoGratiDao();
                // 01
                $model1 = new DeclaracionDConceptoGrati();
                $model1->setId_trabajador_grati($id);
                if($num_meses>=6){
                    $model1->setCod_detalle_concepto(C406);//Grati Completa.
                }else{
                    $model1->setCod_detalle_concepto(C407);//Grati proporcional    
                }
                $model1->setMonto_devengado(0);
                $model1->setMonto_pagado($variable1);
                //02 
                $model2 = new DeclaracionDConceptoGrati();
                $model2->setId_trabajador_grati($id);
                $model2->setCod_detalle_concepto(C107);
                $model2->setMonto_devengado(0);
                $model2->setMonto_pagado($variable2);
                //03 
                $model3 = new DeclaracionDConceptoGrati();
                $model3->setId_trabajador_grati($id);
                $model3->setCod_detalle_concepto(C312);
                $model3->setMonto_devengado(0);
                $model3->setMonto_pagado($variable3);

                $dao_ddg->add($model1);
                $dao_ddg->add($model2);
                $dao_ddg->add($model3);
                // new                
                $dao_rpc = new RegistroPorConceptoDao();
                $data_val = array();
                $data_val = $dao_rpc->buscar_RPC_PorTrabajador($id_pdeclaracion, $data[$i]['id_trabajador'], C703);
                if (floatval($data_val['valor']) > 0) {
                    $model4 = new DeclaracionDConceptoGrati();
                    $model4->setId_trabajador_grati($id);
                    $model4->setCod_detalle_concepto(C703);
                    $model4->setMonto_devengado(0);
                    $model4->setMonto_pagado($data_val['valor']);
                    $dao_ddg->add($model4);
                }
                
                
                
                
            } else {
                //trabajador existe!.
            }

            // echoo($detalle);
        }
    }
}

//-------------
function contarNumerosMesesCompletos($fecha_inicio, $fecha_fin) {

    $inicio_dia = intval(getFechaPatron($fecha_inicio, 'd'));

    $inicio_mes = intval(getFechaPatron($fecha_inicio, 'm'));
    $fin_mes = intval(getFechaPatron($fecha_fin, 'm'));

    $num_mes = ($fin_mes - $inicio_mes) + 1;

    //condicion 01

    if ($inicio_dia > 1):
        $num_mes = $num_mes - 1;
    endif;


    //condicion 02
    if (getFechaPatron($fecha_inicio, 'Y') < getFechaPatron($fecha_fin, 'Y')) {
        $num_mes = 6;
    } else {


        if ($num_mes >= 6):
            //echo "MESES MAS DE 6 ==| " . $num_mes;            
            $num_mes = 6;
        //GRATIFICACION MORMAL
        elseif ($num_mes > 0 && $num_mes < 6): // tienen trabajador 5 meses o menos  
            //echo "MENOS DE 6 MESES  $num_mes";
        //GRATIFICACION PROPORCIONAL
        else:
            //ECHO "0 meses o negativo  $num_mes";
            $num_mes = 0;
        //SIN GRATIFICACION
        endif;
    }

    return $num_mes;
}

function boletaGratificacion() {
//---------------------------------------------------
    $periodo = $_REQUEST['periodo'];
    generarConfiguracion($periodo);
    //$id_pdeclaracion = $_REQUEST['id_pdeclaracion'];

    //echoo($_REQUEST);
    $master_est = null;
    $master_cc = null;
//echoo($_REQUEST);
    if ($_REQUEST['todo'] == "todo") {
        $cubo_est = "todo";
    }

    $id_est = $_REQUEST['id_establecimientos'];
    $id_cc = $_REQUEST['cboCentroCosto']; // OJOO SI NO SE HA SELECCIONADO C.C.

    $master_est = (!is_null($id_est)) ? $id_est : '';
    $master_cc = (!is_null($id_cc)) ? $id_cc : '';


    $nombre_mes = getNameMonth(getFechaPatron($periodo, "m"));
    $anio = getFechaPatron($periodo, "Y");

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
    $dao_est = new EstablecimientoDao();
    $est = array();
    $est = $dao_est->listar_Ids_Establecimientos(ID_EMPLEADOR);
    $contador_break = 0;
    /*
    echoo($est);
    echo "<br>\nmaster_est = $master_est";
    echo "<br>\ncubo_est = $cubo_est";
    echo "<br>\ncubo_est = $cubo_est";*/
    // paso 02 listar CENTROS DE COSTO del establecimento.    
    if (count($est) > 0) {

        //DAO
        $dao_cc = new EmpresaCentroCostoDao();
        $dao_pago = new TrabajadorGratificacionDao();// TrabajadorPdeclaracionDao(); //[OK]
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
                        $data_tra = $dao_pago->listar_2($periodo, $est[$i]['id_establecimiento'], $ecc[$j]['id_empresa_centro_costo']);
 
                        //echoo($data_tra);
                        if (count($data_tra) > 0) {

                            $SUM_TOTAL_CC[$i][$j]['establecimiento'] = $data_est_direc['ubigeo_distrito'];
                            $SUM_TOTAL_CC[$i][$j]['centro_costo'] = strtoupper($ecc[$j]['descripcion']);
                            $SUM_TOTAL_CC[$i][$j]['monto'] = 0;

                            // .......................Inicio Cabecera  $fpx ........ 
                            fwrite($fpx, NAME_EMPRESA);

                            fwrite($fpx, str_pad($ecc[$j]['id_empresa_centro_costo']."xxx".$est[$i]['id_establecimiento']." FECHA : ".$periodo, 56, " ", STR_PAD_LEFT));
                            fwrite($fpx, str_pad(date("d/m/Y"), 11, " ", STR_PAD_LEFT));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("PAGINA :", 69, " ", STR_PAD_LEFT));
                            $contador_break++;
                            fwrite($fpx, str_pad($contador_break, 11, " ", STR_PAD_LEFT));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("MENSUAL/GRATIFICACION", 80, " ", STR_PAD_BOTH));
                            fwrite($fpx, $BREAK);
                            fwrite($fpx, str_pad("PLANILLA DEL MES DE " . strtoupper($nombre_mes) . " DEL " . $anio, 80, " ", STR_PAD_BOTH));
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
                                fwrite($fp, str_pad("BOLETA DE PAGO/GRATIFICACION", 136, " ", STR_PAD_BOTH));
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


                                fwrite($fp, str_pad(" ", 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad($afp_carnet_value, 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad($afp_nombre_value, 44, " ", STR_PAD_RIGHT));
                                fwrite($fp, $BREAK);


                                $num_mes = intval(getFechaPatron($periodo, "m"));
                                $fecha_0 = getNameMonth($num_mes);
                                $fecha_1 = getFechaPatron($periodo, "d.Y");
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

                                //$cadena_dialab = $data_tra[$k]['dia_laborado'] . " DIAS TRAB. " . $data_tra[$k]['ordinario_hora'] . " HORAS TRAB.";

                                fwrite($fp, str_pad('', 49, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad("DIRECCION : " . $cadena, 88, " ", STR_PAD_RIGHT));

                                fwrite($fp, $BREAK);
                                
                                $id_trabajador_grati = $dao_pago->buscarIdTrabajadorGrati($data_tra[$k]['id_trabajador'],$periodo);
                               //echoo($id_trabajador_grati);
                               $array_mixto = boletaTablaGratificacion($fp, $id_trabajador_grati, $data_tra[$k]['cod_regimen_pensionario'], $periodo, $BREAK, $BREAK2);

                                $neto_pagar = $array_mixto['numero'];

                                if ($array_mixto['centinela'] == true) {
                                    //SALIO DE MARCO SGTE PAGINA!
                                    fwrite($fp, chr(12));
                                } else {
                                    fwrite($fp, chr(12));
                                }


                                //++                            
                                // Generar Boleta...
                                $data = array();
                                $data = $data_tra[$k];

                                //...................................................
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

function boletaTablaGratificacion($fp, $id_trabajador_grati, $cod_regimen_pensionario, $periodo,$BREAK, $BREAK2) {
    $array_mixto = array();
    //..............................................................................
    $cod_conceptos_ingresos = array('100', '200', '300', '400', '500', '900');

    $cod_conceptos_descuentos = array('600', '700');

    $cod_conceptos_aportes = array(/* '600', */'800');
    //..............................................................................

    $dao_ddc = new TrabajadorGratificacionDao(); //DeclaracionDconceptoDao();  //  ();
    $dao_pdcem = new DeclaracionDConceptoGratiDao(); //PlameDetalleConceptoEmpleadorMaestroDao();


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
    //$calc = array();
    //$calc = $dao_ddc->buscarConceptos($periodo, $id_trabajador); //NOT NOT


    // INGRESOS
    // 01 lista de todos conceptos Ingresos
    $c_pingreso = array();
    $c_pingreso = $dao_pdcem->view_listarConcepto( $id_trabajador_grati, $cod_conceptos_ingresos);
            
    $sum_i = 0.00;    
    for ($o = 0; $o < count($c_pingreso); $o++):        
        $sum_i = $sum_i + $c_pingreso[$o]['monto_pagado'];
    endfor;
    $ingresos = $c_pingreso;

//echoo($ingresos);
//------------------------------------------------------------------------------
// DESCUENTOS
// 01 lista de todos conceptos 
    $c_pdescuento = array();
    $c_pdescuento = $dao_pdcem->view_listarConcepto($id_trabajador_grati, $cod_conceptos_descuentos);

    $sum_d = 0.00;
    for ($o = 0; $o < count($c_pdescuento); $o++):
       $sum_d = $sum_d + $c_pdescuento[$o]['monto_pagado'];
    endfor;
    $descuentos = $c_pdescuento;//array();
    
    /*
      //------------------------------------------------------------------------------
      // 01 lista de todos conceptos
      $c_paporte = array();
      $c_paporte = $dao_pdcem->view_listarConcepto(ID_EMPLEADOR_MAESTRO, $cod_conceptos_aportes, 0);

      // armado de array
      $array_aporte = array();
      for ($i = 0; $i < count($c_paporte); $i++) {
      $array_aporte[] = $c_paporte[$i]['cod_detalle_concepto'];
      }


      $aportes = array();
      $x = 0;
      $sum_a = 0.00;
      for ($o = 0; $o < count($calc); $o++):
      if (in_array($calc[$o]['cod_detalle_concepto'], $array_aporte)):
      $aportes[$x]['descripcion'] = $calc[$o]['descripcion'];
      $aportes[$x]['descripcion_abreviada'] = $calc[$o]['descripcion_abreviada'];
      $aportes[$x]['monto_pagado'] = $calc[$o]['monto_pagado'];
      $sum_a = $sum_a + $calc[$o]['monto_pagado'];
      $x++;
      endif;
      endfor;

     */
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
            $descripcion_1 = substr($descripcion_1, 0, 2);//26
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
        //$descripcion_2 = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
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

?>
