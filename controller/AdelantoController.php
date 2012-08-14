<?php
//class PlameDiaSubsidiadoController {}
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    
    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    //require_once '../model/PdiaSubsidiado.php';
    //require_once '../dao/PdiaSubsidiadoDao.php';
    
    //PlameDao
    require_once '../dao/PlameDao.php';
    
    //adelanto
    require_once '../model/Adelanto.php';
    require_once '../dao/AdelantoDao.php';
    //jornada laboral
    require_once '../model/JornadaLaboral.php';
    require_once '../dao/JornadaLaboralDao.php';    
    //periodo remuneracion
    require_once '../model/PeriodoRemuneracion.php';
    require_once '../dao/PeriodoRemuneracionDao.php';
}


$response = NULL;

if ($op == "edit") {
    $response = editarAdelanto();
} else if ($op == "add_15") {
    $response = nuevoAdelanto_15();
} else if( $op == ""){
    
}

echo (!empty($response)) ? json_encode($response) : '';




function nuevoAdelanto_15(){
   $ID_EM = ID_EMPLEADOR_MAESTRO; 
   $ID_DECLARACION = $_REQUEST['id_declaracion'];
   $PERIODO = $_REQUEST['periodo'];
   //$periodo = '01/01/2012'; 
   
   $FECHAX = getFechasDePago($PERIODO);
   $FECHA['inicio'] =  $FECHAX['first_day'];
   $FECHA['fin'] = $FECHAX['second_weeks'];
    //listar Trabajadores dentro de los 15 dias de 
   $dao = new PlameDao();
   $data_tra = $dao->listarTrabajadoresPorPeriodo_15($ID_EM, $FECHA['inicio'],$FECHA['fin']);
    
   //----------------------******************-----------------------------------
   $estado =false;    
   echo "<pre>";
   print_r($data_tra);
   echo "counteo =".count($data_tra);
   echo "<pre>";
   
    if (count($data_tra)>=1) {
        ////EVALUA SI ES NECESARIO UN TRY CATH!!!!!!!!!!!!!   
        echo "sss count =".count($data_tra);
        $datafor = array();
        for ($i = 0; $i < count($data_tra); $i++) {// PRIMERO 
            ECHO "I =".$i;
            $z = 0;
            for ($j = 0; $j < count($data_tra); $j++) {// SEGUNDO
                ECHO "J =".$j;
                 
                if ($data_tra[$i]['id_persona'] == $data_tra[$j]['id_persona']) { //$i = x AHI ENCUNETRA TODO
                    echo "<<<<".$data_tra[$j]['id_persona'].">>>>";
                    $datafor[$i]['id_persona'] = $data_tra[$j]['id_persona'];
                    $datafor[$i][$z]['fecha_inicio'] = $data_tra[$j]['fecha_inicio'];
                    $datafor[$i][$z]['fecha_fin'] = $data_tra[$j]['fecha_fin'];
                    
                    $z++;
                    if ((count($data_tra) - 1) == $j) {
                        echo "BREAK";
                        break;
                    }
                    // }//EIF2
                }//EIF1
            }
        }
        //----------------------------------------------------------------------
        //-- Variables globales
        echo "FECHA INICI".$FECHA['inicio'];
        echo "FECHA FIN".$FECHA['fin'];
        
        $p_fi = $FECHA['inicio'];
        $p_fi_time = strtotime($p_fi);

        $p_ff = $FECHA['fin'];
        $p_ff_time = strtotime($p_ff);

        $tra_unico = retornan_Id_Persona_UnicoPeriodo($data_tra);
        $min_periodo = array();
        //echo "trabajador UNICO".count($tra_unico);

        for ($i = 0; $i < count($tra_unico); $i++) {

            for ($j = 0; $j < count($datafor); $j++) { //FOR 1x
                //echo "ENTRO J == ".$j.";
                if ($tra_unico[$i]['id_persona'] == $datafor[$j]['id_persona']) {//ok unico
                    //echo "encontro id_persona";                  
                    $conteo_datafor = count($datafor[$j]) - 1;
                    //echo "ENTRO J == [".$j."]";

                    for ($x = 0; $x < ($conteo_datafor); $x++) {

                        //:: VARIABLES ::                       
                        $fi = $datafor[$j][$x]['fecha_inicio'];
                        $fi_time = strtotime($fi);

                        $ff = $datafor[$j][$x]['fecha_fin'];
                        $ff_time = strtotime($ff); //Return FALSE error
                        //VAR GLOB
                        $f1 = null;
                        $f2 = null;
                        echo "datafor echa_inicio 111   =  ".$datafor[$j][$x]['fecha_inicio'];
                        echo "FECHA INICIO 2222 =  ".$p_fi;
                        if ($fi_time == $p_fi_time) {                           
                            echo "FECHA MADRE == ".$p_fi;
                            $f1 = $fi_time;
                        } else if ($fi_time > $p_fi_time) {
                            $f1 = $fi_time;                           
                        } else if ($fi_time < $p_fi_time) {                            
                            $f1 = $p_fi_time;
                        } else {
                            $f1 = "error critico";
                        }

                        if($ff_time){
                        
                        if ($ff_time == $p_ff_time) {//SI ESTA ESTABLECIDO   rpta bd                        
                            $f2 = $ff_time;
                        } else if($ff_time>$p_ff_time){ //sino 
                            $f2 = $p_ff_time;
                        }else if($ff_time<$p_ff_time){
                            $f2 = $ff_time;
                        }else{
                             $f2 = "error critico";
                        }
                        
                        }else{
                            $f2 = $p_ff_time;
                        }
                        
                        
                        ECHO"/////////////////////////";
                        echo "MAYOR FECHA FIN #".date("Y-m-d", $f2);
                        
                        echo "MAYOR FECHA inicio #".date("Y-m-d", $f1);
                        ECHO"/////////////////////////";

                        $dia_f2 = date("j", $f2);
                        $dia_f1 = date("j", $f1);

                        //echo "ANIBAL = ".$ff_time."==".$ff."#DIA F2 = ".date("j",$f2);
                        $RESTA_DIA = ($dia_f2 - $dia_f1) + 1;  //Añade 1 Dia MAS
                        //---
                        $min_periodo[$i][$x]['id_persona'] = $tra_unico[$i]['id_persona'];
                        $min_periodo[$i][$x]['dia_laborado'] = $RESTA_DIA;

                        $min_periodo[$i][$x]['fecha_inicio'] = $datafor[$j][$x]['fecha_inicio']; //date("Y-m-d",$f1);
                        $min_periodo[$i][$x]['fecha_fin'] = $datafor[$j][$x]['fecha_fin']; //date("Y-m-d",$f2);
                        //---
                        //break;
                    }

                    //##########################################################

                    break; //SI ECONTRO BREAKKKK ok FOR 1X ELIMINADO                      
                }
            }//END FOR
        }


        //----------------------------------------------------------------------

        echo "SALIO";
        echo "<pre>";
        echo "<hr>min_periodo";
        print_r($min_periodo);        
        echo "</pre>";
        //INICIO NEW CON ID_UNICOS  Y periodos y dias laborados dentro del MES.
        //------INICIO    
        //PASO 01
        //$id_pdeclaracion = $dao->registrar($id_empleador_maestro, $periodo);

        for ($i = 0; $i < count($tra_unico); $i++) { // UNICO
            $dias_laborados = 0;
            $data_obj_ppl = array();

            for ($j = 0; $j < count($min_periodo[$i]); $j++) {

                if ($tra_unico[$i]['id_persona'] == $min_periodo[$i][$j]['id_persona']) {

                    echo "fecha_inicio " . $min_periodo[$i][$j]['fecha_inicio'];
                    echo " **************************** ";
                    echo "fecha fin = " . $min_periodo[$i][$j]['fecha_fin'];

                    /*$model_ppl = new JornadaLaboral();
                    //$model_ppl->setId_ptrabajador($tra_unico[$i]['id_trabajador']);
                    $model_ppl->setFecha_inicio($min_periodo[$i][$j]['fecha_inicio']);
                    $model_ppl->setFecha_fin($min_periodo[$i][$j]['fecha_fin']);

                    $data_obj_ppl[] = $model_ppl;*/
                    $dias_laborados = $dias_laborados + $min_periodo[$i][$j]['dia_laborado'];
                }
            }


            /* 
              echo "***************************************************";
              echo "<pre> ULTIMOOOOO";
              print_r($data_obj_ppl);
              echo "</pre>";
              echo "dia laborado = " . $dias_laborados;
              echo "***************************************************";
             */
            $Oadelanto = new Adelanto();
            $Oadelanto->setId_declaracion($ID_DECLARACION);
            $Oadelanto->setId_trabajador($tra_unico[$i]['id_trabajador']);
            $Oadelanto->setCod_periodo_remuneracion($tra_unico[$i]['cod_periodo_remuneracion']);
            $Oadelanto->setCod_periodo_remuneracion($tra_unico[$i]['cod_periodo_remuneracion']);
            $Oadelanto->setId_empresa_centro_costo($tra_unico[$i]['id_empresa_centro_costo']);
            //$Oadelanto->setValor();
            $Oadelanto->setFecha_inicio($FECHA['inicio']);
            $Oadelanto->setFecha_fin($FECHA['fin']);
            $Oadelanto->setFecha_creacion(date("Y-m-d"));
            //DAO
            $daoa = new AdelantoDao();
            $ID_ADELANTO = $daoa->registrar($Oadelanto);
            
            $Ojornadal = new JornadaLaboral();
            $Ojornadal->setId_adelanto($ID_ADELANTO);
            $Ojornadal->setDia_total($dias_laborados);
            //$Ojornadal->setDia_nosubsidiado();
            $Ojornadal->setOrdinario_hora($dias_laborados * 8);
            //$Ojornadal->setOrdinario_min();
            //$Ojornadal->setSobretiempo_hora();
            //$Ojornadal->setSobretiempo_min();
            //DAO
            $daoj = new JornadaLaboralDao();
            $daoj->registrar($Ojornadal);
            
            //ACT
            //CALCULAR SUELDO
            //--------------------------------------------
            $daopr = new PeriodoRemuneracionDao(); //BUSCAR PERIODO DE PAGO 7 , 15
            $data = $daopr->buscar_ID($Oadelanto->getCod_periodo_remuneracion());            
            
            $percent = ($data['tasa_pago']) ? $data['tasa_pago'] : 0;            
            //$dia = $data['dia'];            
            $SUELDO = $tra_unico[$i]['monto_remuneracion'];
            
            
            if($dias_laborados == $data['dia'] ){ //15 == 15
               // $SUELDO_CAL = $SUELDO * ($percent/100); 
            }else if($dias_laborados < $data['dia']){
                $porcentaje_x_dia = ($percent / $data['dia']) ;
                $percent = ($porcentaje_x_dia * $dias_laborados);
                //$SUELDO_CAL = $SUELDO * ($percent/100);                
            }          
            //--------------------------------------------
            $SUELDO_CAL = $SUELDO * ($percent/100);
            //DAO
            $daoa->actualizar($ID_ADELANTO, $SUELDO_CAL);
            
            //registrarPTrabajadores($tra_unico[$i]['id_trabajador'], $id_pdeclaracion, $id_empleador_maestro, $data_obj_ppl, $dias_laborados);
        }


        //--------------------------------------------------------------------------
        //------FIN
        $estado = true;
    } else {
        $estado = false;
    }
//----------------------******************-----------------------------------   
   
    return "okk".$estado;
   
   
   
   
    
}



//
function retornan_Id_Persona_UnicoPeriodo($data_tra) {
    $arrayid = array();
    for ($i = 0; $i < count($data_tra); $i++) {
        $arrayid[] = $data_tra[$i]['id_persona'];
    }
    $listaSimple = array_unique($arrayid);
    $arrayidFinal = array_values($listaSimple);
    // Array Unico

    $unico = array();
    for ($i = 0; $i < count($arrayidFinal); $i++) {
        $unico[$i]['id_persona'] = $arrayidFinal[$i];
        $unico[$i]['contador'] = 0;
    }
    //----------------------------------------------------------------------
    for ($i = 0; $i < count($unico); $i++) { //ID
        for ($j = 0; $j < count($data_tra); $j++) { //TRA
            if ($unico[$i]['id_persona'] == $data_tra[$j]['id_persona']) {
                $unico[$i]['contador']++;

                $unico[$i]['id_trabajador'] = $data_tra[$j]['id_trabajador'];
                $unico[$i]['cod_periodo_remuneracion'] = $data_tra[$j]['cod_periodo_remuneracion'];
                $unico[$i]['id_empresa_centro_costo'] = $data_tra[$j]['id_empresa_centro_costo'];
                $unico[$i]['monto_remuneracion'] = $data_tra[$j]['monto_remuneracion'];
                
            }
        }
    }
    //----------------------------------------------------------------------        
    return $unico;
}







?>
