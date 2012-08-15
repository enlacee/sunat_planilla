<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
require_once '../controller/ideController.php';
$data = $_SESSION['sunat_empleador'];

$PERIODO = ($_REQUEST['periodo']) ? $_REQUEST['periodo'] : "00/0000";

//echo "DDDDDDDDDD".$PERIODO;
?>
<script type="text/javascript">
//VARIABLES GLOBALES
//var PERIODOX = '<?php echo $PERIODO;?>';

    $(document).ready(function(){
                  
        $( "#tabs2").tabs();
		
	});
	
	
	cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/view_trabajador.php' ,'#tabs-2-1');
	//cargar_pagina('sunat_planilla/view-plame/declaraciones_detalle/view_trabajador.php','#tabs-2-2');
	//cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/view_p4ta_catecoria.php?periodo='+$PERIODO ,'#tabs-2-3');

	
	
	cargarTablaPTrabajadores(PERIODO);
	
	
</script>


<div align="left">

RUC: <?php echo $data['ruc']. " - ". $data['razon_social_concatenado']; ?>
    <div id="tabs2">
        <ul>
            <li><a href="#tabs-2-1">Trabajadores</a></li>
            <li><a href="#tabs-2-2">Pensionistas</a></li>	
            <li><a href="#tabs-2-3">PS 4Ta Categoria</a></li>		

        </ul>
        <div id="tabs-2-1">
        11111        
        </div>
        <div id="tabs-2-2">
        22222        
        </div>
        <div id="tabs-2-3">
        3333        
        </div>
        
        
</div>

</div>

