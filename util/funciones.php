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


    $fecha_es_us = eregi_replace("/", "-", $fecha_es_us);

    $fecha = date("Y-m-d", strtotime($fecha_es_us));

    if (isset($patron_date) && !empty($fecha_es_us)) {
        $fecha = date($patron_date, strtotime($fecha));
    } else {
        return null;
    }

    return $fecha;
}

/**
 *
 * @param array $arreglo 
 * @return array devuelve un arreglo unico y ordenado 
 */
function array_unique_ordenado($arreglo) {

    $array_unico = array_unique($arreglo);

    $array = array();

    foreach ($array_unico as $key) {

        $array[] = $key;
    }

    return $array;
}

/**
 *
 * @param integer $num Numero del mes Espaniol
 * @return string Nombre del mes
 */
function getNameMonth($num) {
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
    return $mes[$num - 1];
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






function getMonthDays($Month, $Year)
{
   //Si la extensión que mencioné está instalada, usamos esa.
   if( is_callable("cal_days_in_month"))
   {
      return cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
   }
   else
   {
      //Lo hacemos a mi manera.
      return date("d",mktime(0,0,0,$Month+1,0,$Year));
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


?>
