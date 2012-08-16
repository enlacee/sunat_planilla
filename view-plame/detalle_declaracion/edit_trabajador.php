<?php
//*******************************************************************//
require_once('../../view/ide2.php');
//*******************************************************************//
require_once '../../controller/ideController2.php';
//$data = $_SESSION['sunat_empleador'];
$PERIODO = ($_REQUEST['periodo']) ? $_REQUEST['periodo'] : "00/0000";
$ID_PTRABAJADOR = $_REQUEST['id_ptrabajador'];
$ID_TRABAJADOR = $_REQUEST['id_trabajador'];

//echo "ID_EMPLEADOR_MAESTRO = ".ID_EMPLEADOR_MAESTRO;
//echo "<br>";
//echo "DDDDDDDDDD".$PERIODO;
?>
<style type="text/css">

.section{
	background-color:#FFDFEF;	
	overflow:hidden;
}
.article{
	float:left;
	width: 48%;
	padding: 1%;
}

</style>


<script type="text/javascript">
//VARIABLES GLOBALES
//var PERIODOX = '<?php echo $PERIODO;?>';
var id1 = '<?php echo $ID_PTRABAJADOR;?>';
var id2 = '<?php echo $ID_TRABAJADOR;?>';

    $(document).ready(function(){
                  
        $( "#tabs3").tabs();
		
	});
	
	
	cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/01_edit_dtrabajador.php?id_ptrabajador='+id1+'&id_trabajador='+id2 ,'#tabs-3-1');
	cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/02_edit_jlaboral.php?id_ptrabajador='+id1+'&id_trabajador='+id2 ,'#tabs-3-2');
	cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/03_edit_ingresos.php?id_ptrabajador='+id1+'&id_trabajador='+id2 ,'#tabs-3-3');
	cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/04_edit_descuentos.php?id_ptrabajador='+id1+'&id_trabajador='+id2 ,'#tabs-3-4');
	cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/05_edit_taportes.php?id_ptrabajador='+id1+'&id_trabajador='+id2 ,'#tabs-3-5');
	
	//cargarTablaPTrabajadores(PERIODO);
	
	
</script>
<form id="formPtrabajador" name="formPtrabajador" method="post" action="" style="border:2px solid #F0F;">



<div align="left">


<div id="tabs3">
        <ul>
            <li><a href="#tabs-3-1">Datos del Trabajador</a></li>
            <li><a href="#tabs-3-2">Jornada Laboral</a></li>	
            <li><a href="#tabs-3-3">Ingresos</a></li>	
            <li><a href="#tabs-3-4">Descuentos</a></li>
            <li><a href="#tabs-3-5">Tributos Y Aportes</a></li>	            	

        </ul>
        <div id="tabs-3-1">
        11111        
        </div>
        <div id="tabs-3-2">
        22222        
        </div>
        <div id="tabs-3-3">
        3333        
        </div>
        <div id="tabs-3-4">
        444        
        </div>
        <div id="tabs-3-5">
        5555        
        </div>        
        
</div>

</div>


























<p>
  <!-- Inicio-->
</p>

  <input type="button" name="button" id="button" value="Grabar" onclick="validarPtrabajadores()" />
</form>
<div>

  <table width="627" border="1">
  <tr>
    <th width="69" scope="col">Tip-Num-Doc</th>
    <th width="78" scope="col">Apellidos y Nombres</th>
    <th width="31" scope="col">Dias</th>
    <th width="58" scope="col">Ingresos</th>
    <th width="78" scope="col">Descuentos</th>
    <th width="111" scope="col">Apor.Trabajador</th>
    <th width="50" scope="col">Neto a Pagar</th>
    <th width="42" scope="col">Aport Empl</th>
    <th width="52" scope="col">Est</th>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br>
<div style="float:right; padding:0 40px 0 0; ">
  <a href="javascript:cargar_pagina('sunat_planilla/view-plame/edit_declaracion_tab2.php','#detalle_declaracion_trabajador')">Cerrar Detalle</a>
</div>

</div>
<!-- finn-->