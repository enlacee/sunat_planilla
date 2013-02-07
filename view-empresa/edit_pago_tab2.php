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
	

	var id = document.getElementById('id_pdeclaracion').value;	
	cargarTablaTrabajadoresPorEtapa(id);
	
</script>


<div align="left">
    
<div id="tabs2">
<ul>
      <li><a href="#tabs-2-1">Trabajadores</a></li>
            <!--<li><a href="#tabs-2-2">Pensionistas</a></li>	
            <li><a href="#tabs-2-3">PS 4Ta Categoria</a></li>-->		

        </ul>
        <div id="tabs-2-1">

          <a href="#">OPERACIONES</a>
<input type="button" name="delete_all" id="delete_all" value="Delete All" onclick="eliminarPagoAll()" />     
 
 
 <div id="detalle_declaracion_trabajador">

<input type="hidden" name="reporte15_01" id="reporte15_01" value="01 Recibo Individual">
<input type="button" name="reporte15_02" id="reporte15_02" value="Recibo Total" /> 
<input type="button" name="reporte15_mas" id="reporte15_mas" value="mas op" />

<br />
<table id="list">
</table>
<div id="pager">
</div>


  
  
</div>

 
 
 
 
 
 
 
       
        </div>
        <!--<div id="tabs-2-2">
        22222        
        </div>
        <div id="tabs-2-3">
        3333        
        </div>-->
        
        
</div>

</div>




<!--  -->

<div id="dialog_editarPagoMasOP" title="Mas Opciones">

    <div id="data_editarPagoMasOP" align="left"></div>
    
</div>
