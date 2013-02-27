<?php

// 01 intancia Login
if ($_SESSION['sunat_empleador']['id_empleador']):

    // 02 intancia 
    if ($_SESSION['sunat_empleador']['id_empleador_maestro']):
    // datos estan cargagos en $_SESSION
    //echo "no se conecto BD";
    else:
        require_once '../dao/EmpleadorMaestroDao.php';
        require_once '../dao/EstablecimientoDao.php';
        require_once '../controller/EmpleadorMaestroController.php';        
        $data_Maestro = buscarIdEmpleadorMaestroPorIDEMPLEADOR($_SESSION['sunat_empleador']['id_empleador']);
        $dao_df = new EstablecimientoDao();
        $cadena_df = $dao_df->domicilioFiscal($_SESSION['sunat_empleador']['id_empleador']);
        $_SESSION['sunat_empleador']['id_empleador_maestro'] = $data_Maestro['id_empleador_maestro'];
        $_SESSION['sunat_empleador']['domicilio_fical'] = $cadena_df;
        //echo "se conecto BD";
    endif;
    
    define('NAME_COMERCIAL', $_SESSION['sunat_empleador']['nombre_comercial']);
    define('NAME_EMPRESA', $_SESSION['sunat_empleador']['razon_social_concatenado']);      
    
    define('ID_EMPLEADOR', $_SESSION['sunat_empleador']['id_empleador']);
    define('RUC', $_SESSION['sunat_empleador']['ruc']);
    define('ID_EMPLEADOR_MAESTRO', $_SESSION['sunat_empleador']['id_empleador_maestro']);
    define('DIRECCION_FISCAL', $_SESSION['sunat_empleador']['domicilio_fical']);
    
    define('ID_PDECLARACION', $_SESSION['sunat_empleador']['config']['id_pdeclaracion']);
    define('PERIODO', $_SESSION['sunat_empleador']['config']['periodo']);
    
else:
    header('Location: www.google.com');
endif;

//var_dump($_SESSION);
/*
  echo "<pre>ID_EMPLEADOR";
  print_r(ID_EMPLEADOR);
  echo "</pre>";
  echo "<hr>";
  echo "<pre>RUC";
  print_r(RUC);
  echo "</pre>";
  echo "<hr>";
  echo "<pre>ID_EMPLEADOR_MAESTRO";
  print_r(ID_EMPLEADOR_MAESTRO);
  echo "</pre>";
  echo "<hr>";
  print_r(DIRECCION_FISCAL);
 */
?>
