<?php

/**
 *
 * @param string $fecha_es_us Formato fecha ISO o por default
 * @param string $patron_date Patron date(Y-m-d)
 * @return string fecha
 */
function getFechaPatron($fecha_es_us, $patron_date) {

    if (!isset($fecha_es_us) || $fecha_es_us == "0000-00-00") {
        return null;
    }
    //WARNING
    $fecha_es_us = eregi_replace("/", "-", $fecha_es_us);

    $fecha = date("Y-m-d", strtotime($fecha_es_us));

    if (isset($patron_date) && !empty($fecha_es_us)) {
        $fecha = date($patron_date, strtotime($fecha));
    } else {
        return null;
    }

    return $fecha;
}

function echoo($obj) {
    if (is_null($obj)) {
        //var_dump($obj)."\n\n";
        //echo "nullo!";
    } else {
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }
}

/**
 *
 * @param array $arreglo 
 * @return array devuelve un arreglo unico y ordenado 
 */
function array_unico_ordenado($id1, $id2) {
    $return = null;

    if (is_array($id1) && is_array($id2)) {
        $array = array_merge($id1, $id2);
        $inputarray = array_unique($array);
        $return = array_values($inputarray);
    }

    return $return;
}

/**
 *
 * @param integer $num Numero del mes Espaniol
 * @return string Nombre del mes
 */
function getNameMonth($num, $opcion = null) {
    $mes = array("Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Novienbre",
        "Diciembre"
    );

    $num = intval($num);
    if ($opcion == 1):
        return substr($mes[$num - 1], 0, 3);
    else:
        return strtoupper($mes[$num - 1]);
    endif;
}

//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// ---------- FUNCIONES LOGICAS DEL SISTEMA
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------

/**
 *
 * @param float $m_max_ventas
 * @param float $m_max_ventas_tasa
 * @param float $m_max_tx
 * @param float $m_max_tx_tasa
 * @return float  Numero Redondeado con 2 decimales
 */
function calcularGarantiaFR($m_max_ventas, $m_max_ventas_tasa, $m_max_tx, $m_max_tx_tasa) {

    $a1 = (float) $m_max_ventas;
    $a2 = (float) ($m_max_ventas_tasa / 100);

    $b1 = (float) $m_max_tx;
    $b2 = (float) ($m_max_tx_tasa / 100);

    $rr = ($a1 * $a2) + ($b1 * $b2);

    round($rr, 2);

    return $rr;
}

//echo calcularGarantiaFR(1100, 10, 10, 0.5);
//------------------------------------------------------------------------------


function getRangoFechaSemana($fecha_ISO) {
//  $fecha = "14/01/2012";
//  $fecha = "31/12/2011"; //año  ERROR

    $fecha = explode("/", $fecha_ISO);

    $diax = $fecha[0];
    $mesx = $fecha[1];
    $aniox = $fecha[2];

    $format_fecha = "$aniox-$mesx-$diax";
    $fff = strtotime($format_fecha);
    $fecha_string = date("l d F Y", $fff);

//Sabado Inicia //CONDICIONAR SABADOOOOOOOOOOO !!!!
//-----------------------------
    $fecha_inicio = "";
    $fecha_final = "";
//-----------------------------
    $dia_nombre = date("l", $fff);
    if ($dia_nombre == "Saturday") {
        $inicia = strtotime($fecha_string . "Saturday");
        $fecha_inicio2 = date("Y-m-d", $inicia);
        $fecha_inicio = $fecha_string;
    } else {
        $inicia = strtotime($fecha_string . " last Saturday"); //last Monday  +1 day
        $fecha_inicio2 = date("l d F Y", $inicia);
        $fecha_inicio = date("Y-m-d", $inicia);
    }

//Viernes Cierra
    $cierra = strtotime($fecha_string . " Friday");
    $fecha_final2 = date("l d F Y", $cierra);
    $fecha_final = date("Y-m-d", $cierra);

//Miercoles Siguiente Pago( "next WEDNESDAY" ) Cierra
    $pago = strtotime($fecha_string . " next Wednesday");
    $fecha_pago2 = date("l d F Y", $pago);
    $fecha_pago = date("Y-m-d", $pago);




//return
    $rpta = array("fecha" => $fecha_string,
        "inicio" => $fecha_inicio,
        "inicio2" => $fecha_inicio2,
        "final" => $fecha_final,
        "final2" => $fecha_final2,
        "pago" => $fecha_pago
    );

    return $rpta;
}

function getMesInicioYfin($fecha) {
    /*
      $fecha = explode("/", $fecha_ISO);
      $diax = $fecha[0];
      $mesx = $fecha[1];
      $aniox = $fecha[2];
      $format_fecha = "$aniox-$mesx-$diax";
     */
    $format_fecha = getFechaPatron($fecha, "Y-m-d");

    $fff = strtotime($format_fecha);
    $fecha_string = date("l d F Y", $fff);

    // data 1
    $mes_inicio_seg = strtotime($fecha_string . "first day");
    $mes_inicio = date("Y-m-d", $mes_inicio_seg);

    $mes_fin_seg = strtotime($fecha_string . "last day");
    $mes_fin = date("Y-m-d", $mes_fin_seg);

    //echo  date("Y-m-d", $f);
    //return
    $rpta = array("fecha" => $fecha_string,
        "mes_inicio" => $mes_inicio,
        "mes_fin" => $mes_fin
    );

    return $rpta;
}

// 01 ADICIONAL
function getRangoJulio($anio = 1988) {
    //gratificacion para diciembre se toma en cuenta:
    // ENERO A JUNIO
    $inicio = $anio . "-01-01";
    $data = getMesInicioYfin($anio . "-06-01"); // antes de Julio Ok!..

    $arreglo = array();
    $arreglo['inicio'] = $inicio;
    $arreglo['fin'] = $data['mes_fin'];

    return $arreglo;
}

//var_dump(getMesRangoDiciembre("2011"));

function getRangoDiciembre($anio = 1988) {
    //gratificacion para diciembre se toma en cuenta:
    // JULIO A DICIEMBRE
    // inicio = 2012-07-01 
    // fin = 2012-11-30

    $inicio = $anio . "-07-01";
    $data = getMesInicioYfin($anio . "-11-01"); // antes de Dicimenbre OK!

    $arreglo = array();
    $arreglo['inicio'] = $inicio;
    $arreglo['fin'] = $data['mes_fin'];

    return $arreglo;
}

function getMonthDays($Month, $Year) {
    //Si la extensión que mencioné está instalada, usamos esa.
    if (is_callable("cal_days_in_month")) {
        return cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
    } else {
        //Lo hacemos a mi manera.
        return date("d", mktime(0, 0, 0, $Month + 1, 0, $Year));
    }
}

//-------------------------
function getFechasDePago($fecha) {

    $format_fecha = getFechaPatron($fecha, "Y-m-d");

    $fff = strtotime($format_fecha);
    $fecha_string = date("l d F Y", $fff);

    // data 1
    $dos_sem_seg = strtotime($fecha_string . "second weeks");
    $dos_sem = date("Y-m-d", $dos_sem_seg);
    //segunda semana + 1 dia
    $DOS_SEM_MAS_SEG = date("Y-m-d", strtotime("$dos_sem + 1 day"));
//-----------
//$hoy= date("Y-m-d");
//echo date("Y-m-d", strtotime( "$hoy + 1 day")) ; 	
//-----------	
    $mes_inicio_seg = strtotime($fecha_string . "first day");
    $mes_inicio = date("Y-m-d", $mes_inicio_seg);
    //
    $mes_fin_seg = strtotime($fecha_string . "last day");
    $mes_fin = date("Y-m-d", $mes_fin_seg);

    //echo  date("Y-m-d", $f);
    //return
    $rpta = array("fecha" => $fecha_string,
        "second_weeks" => $dos_sem,
        "second_weeks_mas1" => $DOS_SEM_MAS_SEG,
        "first_day" => $mes_inicio,
        "last_day" => $mes_fin
    );

    return $rpta;
}

//-------------------------19/octubre 2012

function getFechasDePago2($anio, $mes) {

    if (intval($mes) > 12) {
        if ($mes >= 14) {
            throw new Exception('Error fatal ANB!');
        } else {
            $anio = intval($anio) + 1;
            $mes = intval($mes) - 12;
        }
    }

    $fecha_construida = "$anio-$mes-01";

    $format_fecha = getFechaPatron($fecha_construida, "Y-m-d");

    $fff = strtotime($format_fecha);
    $fecha_string = date("l d F Y", $fff);

    // data 1
    $dos_sem_seg = strtotime($fecha_string . "second weeks");
    $dos_sem = date("Y-m-d", $dos_sem_seg);
    //segunda semana + 1 dia
    $DOS_SEM_MAS_SEG = date("Y-m-d", strtotime("$dos_sem + 1 day"));
//-----------
//$hoy= date("Y-m-d");
//echo date("Y-m-d", strtotime( "$hoy + 1 day")) ; 	
//-----------	
    $mes_inicio_seg = strtotime($fecha_string . "first day");
    $mes_inicio = date("Y-m-d", $mes_inicio_seg);
    //
    $mes_fin_seg = strtotime($fecha_string . "last day");
    $mes_fin = date("Y-m-d", $mes_fin_seg);

    //echo  date("Y-m-d", $f);
    //return
    $rpta = array("fecha" => $fecha_string,
        "second_weeks" => $dos_sem,
        "second_weeks_mas1" => $DOS_SEM_MAS_SEG,
        "first_day" => $mes_inicio,
        "last_day" => $mes_fin
    );

    return $rpta;
}

function getTipoMonedaPago($valor) {
    $tipoVal = array("%", "$");

    if ($valor) {
        for ($i = 0; $i < count($tipoVal); $i++) {
            $dato = stripos($valor, $tipoVal[$i]);

            if ($dato != false) {
                $encontro = $tipoVal[$i];
                break;
            }
        }
    }
    return $encontro;
}

function getDayThatYear($Ymd) {
    return date("z", strtotime($Ymd));
}

function numMesQueFalta($mes_inicio, $mes_fin) {
    $mes_inicio = intval($mes_inicio);
    $mes_fin = intval($mes_fin);
    // meses = 12
    //$mes_inicio = 3; // MARZO
    //$mes_fin = x;  // Mes respuesta calculado    

    if ($mes_inicio < 0 || $mes_inicio > 12) {
        $mes_inicio = 1;
    }

    if ($mes_fin < 0 || $mes_fin > 12) {
        $mes_fin = 1;
    }

    $rpta = 0;
    if ($mes_fin <= $mes_inicio) {
        $mes_fin = 0;
        $mes_inicio = 0;
    } else {
        $rpta = ($mes_fin - $mes_inicio) + 1; //xq es el mes de inicio y se cuenta.
    }

    //$mes_base =
    return $rpta;
}

// LOG ERROOR
//echo numMesQueFalta(11, 11);
//echo numMesQueFalta(06, 06);

/**
 *
 * @param type $fecha
 * @param type $day
 * @param type $month
 * @param type $year
 * @return type 
 * 
 * @var Ayuda a crear una fecha proxima o anterior segun sea el caso.
 */
function crearFecha($fecha, $day = 0, $month = 0, $year = 0) {
    //   $fecha = "2012-05-01";

    $mes = date("m", strtotime($fecha));
    $dia = date("d", strtotime($fecha));
    $anio = date("Y", strtotime($fecha));

    $str_time = mktime(0, 0, 0, (intval($mes) + $month), ($dia + $day), ($anio + $year));

    return date("Y-m-d", $str_time);
}

/**
 *  Function devuelde los dias de una fecha determinada
 *  return tipo array.
 * @param type $date
 * @param type $start
 * @return type
 */
function arregloDiaMes($date, $start = 1) { // inicia a contar desde el mes 1
    $f = getFechasDePago($date);
    $inicio = $f['first_day'];
    $fin = $f['last_day'];

    $i = 0;
    $day = array();
    do {
        $fecha_variable = crearFecha($inicio, $i, 0, 0);
        $day[] = getFechaPatron($fecha_variable, 'd');
        $i++;
    } while ($fin != $fecha_variable);

    $r = array();
    foreach ($day as $indice => $value) {
        if ($value >= $start) {
            $r[] = $value;
        }
    }
    return $r;
}
//$data = arregloDiaMes2('2012-01-05','2012-01-02');
//$d  = rangoDeFechas('2012-01-05', '2012-01-02', 'd');
//delete 
function arregloDiaMes2($finicio, $ffin) { // inicia a contar desde el mes 1
    $inicio = $finicio;
    $fin = $ffin;

    $i = 0;
    $day = array();
    do {
        $fecha_variable = crearFecha($inicio, $i, 0, 0);
        $day[] = getFechaPatron($fecha_variable, 'd');
        $i++;
    } while ($fin != $fecha_variable);

    $r = array();
    foreach ($day as $indice => $value) {
        //if($value>=$start){
        $r[] = $value;
        //}
    }
    return $r;
}

/**
 * 
 * @param type $finicio fecha menor
 * @param type $ffin fecha mayor
 * @param type $format formato de fechas a retornar
 * @return type array
 * @descripcion Util para obtener rango de fechas
 */
function rangoDeFechas($finicio, $ffin, $format) {
    $inicio = $finicio;
    $fin = $ffin;
    if ($fin >= $inicio) {
        $i = 0;
        $day = array();
        do {
            $fecha_variable = crearFecha($inicio, $i, 0, 0);
            $day[] = getFechaPatron($fecha_variable, $format);
            $i++;
        } while ($fin != $fecha_variable);

        $r = array();
        foreach ($day as $indice => $value) {
            $r[] = $value;
        }
    } else {
        $r = "Error en fechas : Buque Infinito";
    }
    return $r;
}

//$a = arregloDiaMes2('2013-01-16','2013-01-17');
//echoo($a);
/**
 *
 * @param type $num
 * @return Decimal Numero Redondeado a dos Decimales
 */
function roundTwoDecimal($num) { //SIN REDONDEO OK number_format =redondea a 2 :D
    $valor = number_format($num, 4);
    $redondeo = round(($valor * 100));
    return ($redondeo / 100);
}

/*
  function roundTwoDecimal($num) {
  $valor = number_format($num, 2);

  $redondeo = ($valor * 100);
  $redondeo = $redondeo / 100;

  $redondeo = round($redondeo);
  return $redondeo;
  }
 */

function number_format_var($number) {
    return number_format($number, 2, '.', ',');
}

//
function number_format_2($number) {
    return round($number, 2);
}


function roundFaborContra($num) {
    $numero = array();

    $roundFC = (float) 0.00;
    if (is_float($num)) {
        $valor = number_format($num, 2); //Redondeo        
        $aux = (string) $valor;
        $decimal = substr($aux, strpos($aux, "."));
        //quitar el punto decimal
        $decimal = str_replace('.', '', $decimal);

        //CONDICION        
        if (intval($decimal) < 50) {
            $roundFC = $decimal * (-1);
            $numero['numero'] = intval($num);
        } else {
            $roundFC = 100 - $decimal;
            $numero['numero'] = intval($num) + 1;
        }

        $roundFC = $roundFC / 100;
    }


    $numero['valor'] = $num;
    //$numero['numero'] = intval($num);
    $numero['decimal'] = $roundFC;

    return $numero;
}

function getRendondeoEnSoles($num) {
    $numero = array();

    if (is_float($num)) {
        $valor = number_format($num, 2); //Redondeo        
        $aux = (string) $valor;
        $decimal = substr($aux, strpos($aux, "."));
        //quitar el punto decimal
        $decimal = str_replace('.', '', $decimal);
        //CONDICION        
        if (intval($decimal) > 0) {
            $roundFC = $decimal;
        }
        $roundFC = $roundFC / 100;
    }
    $numero['valor'] = $num;
    $numero['numero'] = intval($num);
    $numero['decimal'] = $roundFC;

    return $numero;
}

//$monto = 98.54;
//echoo(getRendondeoEnSoles($monto));



function textoaMedida($num_limite, $texto) {
    //return sizeof(explode(" ", $texto));  
    //$num_palabra = str_word_count($texto);
    //$num_len = strlen($texto);

    $texto = limpiar_caracteres_especiales_plame($texto);

    $txt = str_replace(" ", ",", $texto);
    $arreglo_txt = (str_getcsv($txt, ","));

    $cadena = null;
    $count_leng = 0;
    for ($i = 0; $i < count($arreglo_txt); $i++) {
        $count_leng = $count_leng + intval(strlen($arreglo_txt[$i]));
        if ($count_leng <= $num_limite) {
            if ($i == 0):
                $cadena .= $arreglo_txt[$i];
            else:
                $cadena .= " " . $arreglo_txt[$i];
                $count_leng = $count_leng + 1; //sum espacio
            endif;
        }else {
            break;
        }
    }
    return $cadena;
}

function limpiar_caracteres_especiales_plame($s) {
    $s = str_replace("Ñ", chr(165)/* "N" */, $s);
    $s = str_replace("ñ", chr(164)/* "N" */, $s);
    return $s;
}

function limpiar_caracteres_especiales($s) {
    $s = ereg_replace("[áàâãª]", "a", $s);
    $s = ereg_replace("[ÁÀÂÃ]", "A", $s);
    $s = ereg_replace("[éèê]", "e", $s);
    $s = ereg_replace("[ÉÈÊ]", "E", $s);
    $s = ereg_replace("[íìî]", "i", $s);
    $s = ereg_replace("[ÍÌÎ]", "I", $s);
    $s = ereg_replace("[óòôõº]", "o", $s);
    $s = ereg_replace("[ÓÒÔÕ]", "O", $s);
    $s = ereg_replace("[úùû]", "u", $s);
    $s = ereg_replace("[ÚÙÛ]", "U", $s);
    $s = str_replace(" ", "-", $s);
    $s = str_replace("ñ", "n", $s);
    $s = str_replace("Ñ", "N", $s);
    //para ampliar los caracteres a reemplazar agregar lineas de este tipo:
    //$s = str_replace("caracter-que-queremos-cambiar","caracter-por-el-cual-lo-vamos-a-cambiar",$s);
    return $s;
}

//Funcion sumar 30 o 15 dias a la fecha
//fech inicio y fech fin

function tipoFechaVacacionMasDias($fecha, $tipo) {

    $data = array();
    $data['fecha_inicio'] = $fecha;
    $data['fecha_fin'] = null;

    if ($tipo == 1) { // MES
        $data['fecha_fin'] = crearFecha($fecha, 29, 0, 0);
    } else if ($tipo == 2) {// quincena
        $data['fecha_fin'] = crearFecha($fecha, 14, 0, 0);
    } else if ($tipo == 3) { //semanal
        $data['fecha_fin'] = crearFecha($fecha, 6, 0, 0);
    }

    return $data;
}

/**
 *
 * @param type $numeros
 * @return type 
 * $var Numero mayor Podria utilizar max(array);
 */
function getNumeroMayor($numeros = array()) {
    $contador = array();

    for ($i = 0; $i < count($numeros); $i++) {
        $contador[$i] = 0;
        for ($j = 0; $j < count($numeros); $j++) {
            if ($numeros[$i] >= $numeros[$j] && $numeros[$i] != $numeros[$j]) {
                $contador[$i] = $contador[$i] + 1;
            }
        }
    }

    $mayor = max($contador);
    for ($x = 0; $x < count($contador); $x++):
        if ($contador[$x] == $mayor):
            $indice = $x;
            break;
        endif;
    endfor;

//echoo($indice);
//echo "<br>";
//echoo($numeros[$indice]);
    return $numeros[$indice];
}

function arrayId($data, $name_array) {
    $new = array();
    if (is_array($data)&& count($data)>0) {        
        for ($i = 0; $i < count($data); $i++) {
            $new[] = $data[$i][$name_array];
        }
    }
    return $new;
}

//CASO EXEPCION!! !!! beta
function arrayId_Id($data, $name_array) {
    if (is_array($data)) {
        $new = array();
        for ($i = 0; $i < count($data); $i++) {
            $new[$data[$i][$name_array]] = $data[$i][$name_array];
        }
    }
    return $new;
}

//funcion ayuda reportes 'plame'
function afpArrayConstruido() {
    $mi_afp = array();
    $mi_afp[0]['codigo'] = 21;
    $mi_afp[1]['codigo'] = 22;
    $mi_afp[2]['codigo'] = 23;
    $mi_afp[3]['codigo'] = 24;
    //
    $mi_afp[0]['nombre'] = 'INTEGRA AFP';
    $mi_afp[1]['nombre'] = 'HORIZONTE AFP';
    $mi_afp[2]['nombre'] = 'PROFUTURO AFP';
    $mi_afp[3]['nombre'] = 'PRIMA AFP';

    return $mi_afp;
}

// nueva funcion asociada
/**
 * 
 * @param type $inicio
 * @param type $fin
 * @return type
 */
//delete
function diasLaborados($inicio, $fin) {
//$inicio = '2013-01-01';
//$fin = '2013-01-15';
    /*
      echo "<br>";
      echo "<pre>inicio = ";
      print_r($inicio);
      echo "</pre>";
      echo "<br>";

      echo "<br>";
      echo "<pre>fin = ";
      print_r($fin);
      echo "</pre>";
      echo "<br>";
     */
    $dias = array();
    $i = 0;
    do {
        $fecha_variable = crearFecha($inicio, $i, 0, 0);
        //$dias [] = $fecha_variable;    
        $dias [] = getFechaPatron($fecha_variable, 'd');
        $i++;
    } while ($fin != $fecha_variable);


    $diasv = array();
    /*
      $data_vacacion = array(
      array('fecha_inicio' => '2013-01-01', 'fecha_fin' => '2013-01-05'),
      array('fecha_inicio' => '2013-01-13', 'fecha_fin' => '2013-01-17')
      );
      for ($a = 0; $a < count($data_vacacion); $a++) {
      if (getFechaPatron($data_vacacion[$a]['fecha_inicio'], 'd') > 15)
      continue;
      if (getFechaPatron($data_vacacion[$a]['fecha_fin'], 'd') > 15)
      $data_vacacion[$a]['fecha_fin'] = $fin;

      $i = 0;
      do {
      $fecha_variable = crearFecha($data_vacacion[$a]['fecha_inicio'], $i, 0, 0);
      //$diasv [] = $fecha_variable;
      $diasv [] = getFechaPatron($fecha_variable, 'd');
      $i++;
      } while ($data_vacacion[$a]['fecha_fin'] != $fecha_variable);
      }
     */
    $diast = array_values(array_diff($dias, $diasv));

    return count($diast);
}

?>
