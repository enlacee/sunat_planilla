<?php

// 01 intancia Login
if ($_SESSION['sunat_empleador']['id_empleador']):

    // 02 intancia 
    if ($_SESSION['sunat_empleador']['id_empleador_maestro']):
    // datos estan cargagos en $_SESSION
    //echo "no se conecto BD";
    else:
        require_once '../../dao/EmpleadorMaestroDao.php';
        require_once '../../dao/EstablecimientoDao.php';
        require_once '../../controller/EmpleadorMaestroController.php';        
        $data_Maestro = buscarIdEmpleadorMaestroPorIDEMPLEADOR($_SESSION['sunat_empleador']['id_empleador']);
        $dao_df = new EstablecimientoDao();
        $cadena = $dao_df->domicilioFiscal($_SESSION['sunat_empleador']['id_empleador']);
        $_SESSION['sunat_empleador']['id_empleador_maestro'] = $data_Maestro['id_empleador_maestro'];
        $_SESSION['sunat_empleador']['domicilio_fical'] = $cadena;
        //echo "se conecto BD";
    endif;
    
    define('NAME_COMERCIAL', $_SESSION['sunat_empleador']['nombre_comercial']);
    define('NAME_EMPRESA', $_SESSION['sunat_empleador']['razon_social_concatenado']);      
    
    define('ID_EMPLEADOR', $_SESSION['sunat_empleador']['id_empleador']);
    define('RUC', $_SESSION['sunat_empleador']['ruc']);
    define('ID_EMPLEADOR_MAESTRO', $_SESSION['sunat_empleador']['id_empleador_maestro']);
    define('DIRECCION_FISCAL', $_SESSION['sunat_empleador']['domicilio_fical']);
    
else:
    header('Location: www.google.com');
endif;

?>
