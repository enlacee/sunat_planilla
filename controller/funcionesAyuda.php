<?php
/*
  $daovd = new VacacionDetalleDao();
  $data_vdetalle = $daovd->vacacionDetalle(2);
  $id_pdeclaracion = 29;
  $periodo = '2013-02-01';
  echo $periodo;
  echoo($data_vdetalle);
  echo leerVacacionDetalle($data_vdetalle, $periodo);
 */
// modificado
function leerVacacionDetalle($data, $periodo,$fecha_inicio,$fecha_fin) {
    /*$fecha = getFechasDePago($periodo);
    $fecha_inicio = $fecha['first_day'];
    $fecha_fin = $fecha['last_day'];*/
    //echo "RANGO DE FECHAS $fecha_inicio A $fecha_fin";
    $dia = 0;
    $fecha_lineal = null;
    for ($i = 0; $i < count($data); $i++) {
        $estado_inicio = (getFechaPatron($data[$i]['fecha_inicio'], "m") != getFechaPatron($periodo, "m")) ? false : true;
        $estado_fin = (getFechaPatron($data[$i]['fecha_fin'], "m") != getFechaPatron($periodo, "m")) ? false : true;

        if ($estado_inicio || $estado_fin) { // true pasa.
            //-----------------------------------------------------------
            if ($data[$i]['fecha_inicio'] > $fecha_inicio) {
                
            } else if ($data[$i]['fecha_inicio'] <= $fecha_inicio) {
                $data[$i]['fecha_inicio'] = $fecha_inicio;
            }
            if (is_null($data[$i]['fecha_fin'])) {
                $data[$i]['fecha_fin'] = $fecha_fin;
            } else if ($data[$i]['fecha_fin'] >= $fecha_fin) { //INSUE
                $data[$i]['fecha_fin'] = $fecha_fin;
            }
            //----------------------------------------------------------- 
            $rangoVaca = rangoDeFechas($data[$i]['fecha_inicio'], $data[$i]['fecha_fin'], "Y-m-d");
            //echoo($rangoVaca);
            //echo count($rangoVaca);            
            if (is_array($rangoVaca)) {
                $dia = $dia + (count($rangoVaca));
                $fecha_lineal .= $data[$i]['fecha_inicio'] . "_" . $data[$i]['fecha_fin'] . ",";
            }
        }
    }
    return $arreglo = array('dia' => $dia, 'fecha_lineal' => $fecha_lineal);
}



?>
