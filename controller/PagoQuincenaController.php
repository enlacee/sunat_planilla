<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';

    require_once ('../dao/AbstractDao.php');
    require_once ('../model/PagoQuincena.php');
    require_once ('../dao/PagoQuincenaDao.php');
    require_once('../model/Empleador.php');
    require_once('../dao/PlameDao.php');

//---
    require_once '../dao/RegistroPorConceptoDao.php';

//variables conceptos
    require_once '../controller/ConfConceptosController.php';

    //configuracion sueldo basico ++
    // IDE CONFIGURACION 
    require_once '../dao/ConfAsignacionFamiliarDao.php';
    require_once '../dao/ConfSueldoBasicoDao.php';
    require_once '../dao/ConfEssaludDao.php';
    require_once '../dao/ConfOnpDao.php';
    require_once '../dao/ConfUitDao.php';

    require_once '../controller/ConfController.php';
    
    //-- lib para reportes
    require_once '../dao/EstablecimientoDao.php';
    require_once '../dao/EmpresaCentroCostoDao.php';
    require_once '../dao/EstablecimientoDireccionDao.php';
    // conciliar vacaciones en 15CENA
    require_once '../dao/VacacionDao.php';
    // busqueda detallle de mes de vacacion
    require_once '../dao/VacacionDetalleDao.php';
    
    //libreria de ayuda
    require_once '../controller/funcionesAyuda.php';
    //ZIP
    require_once '../util/zip/zipfile.inc.php';    
}

$responce = NULL;

if ($op == "cargar_tabla") {
    $responce = cargar_tabla();
} elseif ($op == "add") {
    $responce = nuevoPQ();
}else if($op =='recibo15'){
    reciboQuincena();
}else if($op =='del'){
 $responce = eliminarPQ();
}

echo (!empty($responce)) ? json_encode($responce) : '';

function eliminarPQ() {
    $dao = new PagoQuincenaDao();
    if($_REQUEST['id'] == 'all'){
        if($_REQUEST['id_pdeclaracion']){
        return $dao->delAll($_REQUEST['id_pdeclaracion']);; 
        }
    }else{
        return $dao->del($_REQUEST['id']);
    }     
}


function nuevoPQ() {
    //VARIABLES    
    $ID_PDECLARACION = $_REQUEST['id_declaracion'];
    $PERIODO = $_REQUEST['periodo'];
    $anio = getFechaPatron($PERIODO, 'Y');
    $ids = $_REQUEST['ids'];   // ids trabajador 
    generarConfiguracion($PERIODO);


    // INCIO PROCESO    
    $dao_pagoquincena = new PagoQuincenaDao();
    $dao_plame = new PlameDao();

    $fecha = getFechasDePago($PERIODO);
    $fecha_inicio = $fecha['first_day'];
    $fecha_fin = $fecha['second_weeks'];
//|-------------------------------------------------------------------------
//| Aki para mejorar. la aplicacion debe de preguntar por un Trabajador en 
//| concreto:
//|
//| XQ esta funcion devuelve una lista de trabajadores. Si la persona tubiera
//| por registros de trabajador. el sistema crearia :
//| reportes de la persona.. duplicadooooo.
//|-------------------------------------------------------------------------
    $data_tra = $dao_plame->listarTrabajadoresPorPeriodo_global(ID_EMPLEADOR_MAESTRO, $fecha_inicio, $fecha_fin);

    if (isset($ids)) {
        echo "<pre>[idsS]  Que Usted Selecciono en el Grid\n";
        print_r($ids);
        echo "</pre>";
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
        $data_tra = $ids_tra;
    }

//========== ELIMINAR LO QUE YA EXISTE en BD ===================//
// se preguntara Antes de Guardar  Si existe trabajador en QUINCENA 
//========== ELIMINAR LO QUE YA EXISTE en BD ===================//

    $data_id_tra_db = $dao_pagoquincena->listarPorPdeclaracion($ID_PDECLARACION);

    if (count($data_id_tra_db) > 0) {
        $data_tra_ref = $data_tra;
        for ($i = 0; $i < count($data_id_tra_db); $i++):
            for ($j = 0; $j < count($data_tra_ref); $j++):
                if ($data_id_tra_db[$i]['id_trabajador'] == $data_tra_ref[$j]['id_trabajador']):
                    $data_tra_ref[$j]['id_trabajador'] = null;
                    break;
                endif;
            endfor;
        endfor;
        $data_tra = null;
        $data_tra = array_values($data_tra_ref);
    }
    
    //--------------------------------------------------------------------
    //         validar trabajador con vacacion
    $daov = new VacacionDao();
    $data_trav = $daov->trabajadoresConVacacion(ID_EMPLEADOR_MAESTRO, $anio);
   if (count($data_trav) > 0) {        
        for ($i = 0; $i < count($data_trav); $i++):
            for ($j = 0; $j < count($data_tra); $j++):
                if ($data_trav[$i]['id_trabajador'] == $data_tra[$j]['id_trabajador']):
                    //$data_tra[$j]['id_trabajador'] = null;
                    $data_tra[$j]['_vacacion'] = true;
                    $data_tra[$j]['_id_vacacion'] = $data_trav[$i]['id_vacacion'];
                    break;
                endif;
            endfor;
        endfor;        
        $data_tra = array_values($data_tra);    
   }
    
    //echoo($data_tra);

    if (count($data_tra) >= 1) {
        $dao_rpc = new RegistroPorConceptoDao();
        
        for ($i = 0; $i < count($data_tra); $i++) {
            if ($data_tra[$i]['id_trabajador'] != null) {
                $model = new PagoQuincena();
                //SUELDO X DEFAUL
                if ($data_tra[$i]['monto_remuneracion_fijo']): // ESTADO  = 0,1
                else:
                //$data_tra[$i]['monto_remuneracion'] = sueldoDefault($data_tra[$i]['monto_remuneracion']);
                endif;
                // Variables
                $datax = $dao_rpc->buscar_RPC_PorTrabajador($ID_PDECLARACION, $data_tra[$i]['id_trabajador'], C701);
                $percent = (($datax['valor'] == null || '')) ? 0 : $datax['valor'];
                
                
                //-----------------------------------------------------------
                if ($data_tra[$i]['fecha_inicio'] > $fecha_inicio) {
                    
                } else if ($data_tra[$i]['fecha_inicio'] <= $fecha_inicio) {
                    $data_tra[$i]['fecha_inicio'] = $fecha_inicio;
                }           
                
                if (is_null($data_tra[$i]['fecha_fin'])) {
                    $data_tra[$i]['fecha_fin'] = $fecha_fin;
                } else if ($data_tra[$i]['fecha_fin'] >= $fecha_fin) { //INSUE
                    $data_tra[$i]['fecha_fin'] = $fecha_fin;
                }
                //-----------------------------------------------------------
                $dia_laborado = count(rangoDeFechas($data_tra[$i]['fecha_inicio'], $data_tra[$i]['fecha_fin'],'d'));                
                //**************************************************************
                $dia_vacacion = 0;
                if($data_tra[$i]['_vacacion']==true){
                    $daovd = new VacacionDetalleDao();
                    $data_vdetalle = $daovd->vacacionDetalle($data_tra[$i]['_id_vacacion']);
                    //$fecha = getFechasDePago($PERIODO);
                    //$f_inicio = $fecha['first_day'];
                    //$f_fin = $fecha['last_day'];
                    $data_ask = leerVacacionDetalle($data_vdetalle, $PERIODO,$fecha_inicio,$fecha_fin);      
                    $dia_vacacion = $data_ask['dia'];
                    echo "\n\nENTRO  VACACION TRUE = ".$dia_vacacion;
                    
                    $percent = ($data_tra[$i]['_vacacion']==true) ? 50 : $percent;
                }
                //**************************************************************
                $dia_laborado = $dia_laborado - $dia_vacacion;
                $SUELDO_CAL = 0;
                if ($dia_laborado == 15) { // 15 dias                    
                    $SUELDO_CAL = $data_tra[$i]['monto_remuneracion'] * ($percent / 100);
                } else {
                    $smpd = sueldoMensualXDia($data_tra[$i]['monto_remuneracion']);
                    $SUELDO_CAL = $smpd * $dia_laborado;
                }

                $round_sueldo = array();
                $round_sueldo = getRendondeoEnSoles($SUELDO_CAL);

                if ($round_sueldo['decimal'] > 0) {
                    $model->setDevengado($round_sueldo['decimal']);
                    //$dao_plame->editMontoDevengadoTrabajador($data_tra[$i]['id_trabajador'], $round_sueldo['decimal']);
                    $SUELDO_CAL = $round_sueldo['numero'];
                }
                $model->setId_pdeclaracion($ID_PDECLARACION);
                $model->setId_trabajador($data_tra[$i]['id_trabajador']);
                $model->setId_empresa_centro_costo($data_tra[$i]['id_empresa_centro_costo']);
                $model->setDia_laborado($dia_laborado);
                $model->setSueldo_base($data_tra[$i]['monto_remuneracion']);
                $model->setSueldo_porcentaje($percent);
                $model->setSueldo($SUELDO_CAL);
                $model->setFecha_creacion(date('Y-m-d'));
                
                //echo "<pre>";
                //print_r($model);
                //echo "</pre>";
                //ADD DB
                $dao_pagoquincena->add($model);
            }//FIN
        }
    }
}

//NEW
function cargar_tabla() {
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
    $dao = new PagoQuincenaDao();

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
    $count = $dao->listarCount($ID_PDECLARACION, $WHERE);

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
    $lista = $dao->listar($ID_PDECLARACION,$WHERE, $start, $limit, $sidx, $sord);
    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $response;
    }
    foreach ($lista as $rec) {

        $param = $rec["id_pago_quincena"];
        $_00 = $rec['id_trabajador'];
        $_01 = $rec['cod_tipo_documento'];
        $_02 = $rec['num_documento'];
        $_03 = $rec['apellido_paterno'];
        $_04 = $rec['apellido_materno'];
        $_05 = $rec['nombres'];
        $_06 = $rec['dia_laborado'];
        $_07 = $rec['sueldo'];
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


function reciboQuincena(){  
    $ids = $_REQUEST['ids'];
    $PERIODO = $_REQUEST['periodo'];
    $ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
//---------------------------------------------------
// Variables secundarios para generar Reporte en txt
    $master_est = null; //2;
    $master_cc = null; //2;

    if ($_REQUEST['todo'] == "todo") { // UTIL PARA EL BOTON Recibo Total
        $cubo_est = "todo";
        $cubo_cc = "todo";
    }

    $id_est = $_REQUEST['id_establecimientos'];
    $id_cc = $_REQUEST['cboCentroCosto'];

    if ($id_est) {
        $master_est = $id_est;
    } else if($id_est=='0') {
        $cubo_est = "todo";
    }

    if ($id_cc) {
        $master_cc = $id_cc;
    } else if($id_cc == '0'){
        $cubo_cc = "todo";
    }

    $nombre_mes = getNameMonth(getFechaPatron($PERIODO, "m"));
    $anio = getFechaPatron($PERIODO, "Y");

    $file_name = '01.txt';
    $file_name2 = '02.txt';
    $fpx = fopen($file_name2, 'w');
    $fp = fopen($file_name, 'w');
    
    //..........................................................................
    $FORMATO_0 = chr(27).'@'.chr(27).'C!';
    $FORMATO = chr(18).chr(27)."P";
    $BREAK = chr(13) . chr(10);
    //$BREAK = chr(14) . chr(10);
    //chr(27). chr(100). chr(0)
    $LINEA = str_repeat('-', 80);
    //..............................................................................  
    fwrite($fp,$FORMATO); 

    // paso 01 Listar ESTABLECIMIENTOS del Emplearo 'Empresa'
    $dao_est = new EstablecimientoDao();
    $est = array();
    $est = $dao_est->listar_Ids_Establecimientos(ID_EMPLEADOR);
    $pagina = 1;

    // paso 02 listar CENTROS DE COSTO del establecimento.    
    if (is_array($est) && count($est) > 0) {
        //DAO
        $dao_cc = new EmpresaCentroCostoDao();
        $dao_pagoquincena = new PagoQuincenaDao();
        $dao_estd = new EstablecimientoDireccionDao();
        // -------- Variables globales --------//     
        $TOTAL = 0;
        $SUM_TOTAL_CC = array();
        $SUM_TOTAL_EST = array();
        for ($i = 0; $i < count($est); $i++) { // ESTABLECIMIENTO
            $bandera_1 = false;
            if ($est[$i]['id_establecimiento'] == $master_est) {
                $bandera_1 = true;
            } else if ($cubo_est == "todo") {
                $bandera_1 = true;
            }

            if ($bandera_1) { 
                $SUM_TOTAL_EST[$i]['monto'] = 0;
                //Establecimiento direccion Reniec
                $data_est_direc = $dao_estd->buscarEstablecimientoDireccionReniec($est[$i]['id_establecimiento']);
                $SUM_TOTAL_EST[$i]['establecimiento'] = $data_est_direc['ubigeo_distrito'];

                $ecc = array();
                $ecc = $dao_cc->listar_Ids_EmpresaCentroCosto($est[$i]['id_establecimiento']);
                // paso 03 listamos los trabajadores por Centro de costo 

                for ($j = 0; $j < count($ecc); $j++) {

                    $bandera_2 = false;
                    if ($ecc[$j]['id_empresa_centro_costo'] == $master_cc) {
                        $bandera_2 = true;
                    } else if ($cubo_est == 'todo' || $cubo_cc == "todo") { // $cubo_est
                        $bandera_2 = true;
                    }
                    
                    if ($bandera_2) {
                        //$contador_break = $contador_break + 1;
                        // LISTA DE TRABAJADORES
                        $data_tra = array();                        
                        $data_tra = $dao_pagoquincena->listarReporte($ID_PDECLARACION, $est[$i]['id_establecimiento'], $ecc[$j]['id_empresa_centro_costo']);

                        if (count($data_tra)>0) {
                            
                            $SUM_TOTAL_CC[$i][$j]['establecimiento'] = $data_est_direc['ubigeo_distrito'];
                            $SUM_TOTAL_CC[$i][$j]['centro_costo'] = strtoupper($ecc[$j]['descripcion']);
                            $SUM_TOTAL_CC[$i][$j]['monto'] = 0;

                            //fwrite($fp, $LINEA);                            
                            fwrite($fp, NAME_EMPRESA);
                            
                            fwrite($fp, str_pad("FECHA : ", 56, " ", STR_PAD_LEFT));
                            fwrite($fp, str_pad(date("d/m/Y"), 11, " ", STR_PAD_LEFT));
                            fwrite($fp, $BREAK);

                            fwrite($fp, str_pad("PAGINA :", 69, " ", STR_PAD_LEFT));                            
                            fwrite($fp, str_pad($pagina, 11, " ", STR_PAD_LEFT));
                            fwrite($fp, $BREAK);

                            fwrite($fp, str_pad("1RA QUINCENA", 80, " ", STR_PAD_BOTH));
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
                            
                            $pag = 0;
                            $num_trabajador = 0;
                            for ($k = 0; $k < count($data_tra); $k++) {
                                $num_trabajador = $num_trabajador +1;                                
                                if($num_trabajador>24):
                                    fwrite($fp,chr(12));
                                    $num_trabajador=0;
                                endif;
                                
                                $data = array();
                                $data = $data_tra[$k];
                                // Inicio de Boleta             
                                generarRecibo15_txt2($fpx, $data, $nombre_mes, $anio,$pag);
                                $pag = $pag +1;
                                
                                // Final de Boleta
                                $texto_3 = $data_tra[$k]['apellido_paterno'] . " " . $data_tra[$k]['apellido_materno'] . " " . $data_tra[$k]['nombres'];                                
                                fwrite($fp, $BREAK);
                                fwrite($fp, str_pad(($k + 1) . " ", 4, " ", STR_PAD_LEFT));
                                fwrite($fp, str_pad($data_tra[$k]['num_documento'], 12, " ", STR_PAD_RIGHT));
                                fwrite($fp, str_pad(limpiar_caracteres_especiales_plame($texto_3), 40, " ", STR_PAD_RIGHT));
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
                            $pagina = $pagina + 1;
                            //fwrite($fp, $BREAK . $BREAK . $BREAK . $BREAK);
                            $TOTAL = $TOTAL + $SUM_TOTAL_CC[$i][$j]['monto'];
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
         */        
            fwrite($fp, $BREAK);
            fwrite($fp, $BREAK);
            fwrite($fp, str_pad("T O T A L   G E N E R A L  --->>>", 56, " ", STR_PAD_RIGHT));
            fwrite($fp, str_pad(number_format_var($TOTAL), 15, ' ',STR_PAD_RIGHT));
            fwrite($fp, $BREAK);
            fwrite($fp, $BREAK);
        
        
    }//END IF
    
    fclose($fp);
    fclose($fpx);

    $file = array();
    $file[] = $file_name;
    $file[] = ($file_name2);

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


function generarRecibo15_txt2($fpx, $data, $nombre_mes, $anio,$pag) {
   $BREAK = chr(13) . chr(10); 
   $CORTE = chr(18).chr(27)."P";
   
    if($pag==0):
        $CERO = chr(27).'@'.chr(27).'C!';        
        fwrite($fpx,$CERO.$BREAK);        
    endif;    
    fwrite($fpx,$CORTE); 
    
    fwrite($fpx, $BREAK);    
    fwrite($fpx, str_pad(NAME_EMPRESA, 0, " ", STR_PAD_LEFT));    
    fwrite($fpx, str_pad(NAME_EMPRESA, 45, " ", STR_PAD_LEFT));
    fwrite($fpx, $BREAK);
    //--    
    fwrite($fpx, str_pad('RUC  '.RUC, 0, " ", STR_PAD_LEFT));
    fwrite($fpx, str_pad('RUC  '.RUC, 45, " ", STR_PAD_LEFT));
    fwrite($fpx, $BREAK);
    fwrite($fpx, str_pad(DIRECCION_FISCAL, 0, " ", STR_PAD_LEFT));    
    fwrite($fpx, str_pad(DIRECCION_FISCAL, 45, " ", STR_PAD_LEFT));
    fwrite($fpx, $BREAK);
    //--AVENIDA Guillermo Presccot 395 - SAN ISIDRO
    //2
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
    fwrite($fpx, str_pad(textoaMedida(31, ": ".$_NOMBRE_), 36, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad('NOMBRES', 9, " ", STR_PAD_RIGHT));
    fwrite($fpx, str_pad(textoaMedida(31, ": ".$_NOMBRE_), 31, " ", STR_PAD_RIGHT));

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
    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK);
    fwrite($fpx, $BREAK);

    fwrite($fpx,chr(12));      

}


//------------------------------------------------------------------------------
?>
