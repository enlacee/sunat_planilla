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

    $(document).ready(function(){
                  
        $( "#tabs2").tabs();
		
	});
	
	
	cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/view_trabajador.php' ,'#tabs-2-1');


	var ID_ESTAPA_PAGO = document.getElementById('id_etapa_pago').value;	
	cargarTablaTrabajadoresPorEtapa(ID_ESTAPA_PAGO);
	//cargarTablaPTrabajadores(PERIODO);
	
	
</script>


<div align="left">
    <div class="blue">RUC: <?php echo $data['ruc']. " - ". $data['razon_social_concatenado']; ?></div>
    
<div id="tabs2">
        <ul>
            <li><a href="#tabs-2-1">Trabajadores</a></li>
            <!--<li><a href="#tabs-2-2">Pensionistas</a></li>	
            <li><a href="#tabs-2-3">PS 4Ta Categoria</a></li>-->		

        </ul>
        <div id="tabs-2-1">
        11111        
        </div>
        <!--<div id="tabs-2-2">
        22222        
        </div>
        <div id="tabs-2-3">
        3333        
        </div>-->
        
        
</div>

</div>

