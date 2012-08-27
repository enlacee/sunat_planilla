<?php
//*******************************************************************//
require_once('../../view/ide2.php');
//*******************************************************************//
require_once '../../controller/ideController2.php';
//$data = $_SESSION['sunat_empleador'];

$ID_PAGO = ($_REQUEST['id_pago']);
//echo "ID_EMPLEADOR_MAESTRO = ".ID_EMPLEADOR_MAESTRO;
//echo "<br>";
//echo "DDDDDDDDDD".$PERIODO;
?>

<!-- -->
<!-- EDITAR PAGO -->
<!-- -->
<script type="text/javascript">
//VARIABLES GLOBALES
var ID_PAGO = '<?php echo $ID_PAGO;?>';
console.log("ID_PAGO = "+ID_PAGO);
console.log("sunat_planilla/view-empresa/detalle_etapa_pago/editar_trabajador.php");

    $(document).ready(function(){
                  
        $( "#tabs3").tabs();
		
	});
	
	
	cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/01_edit_dtrabajador.php?id_pago='+ID_PAGO ,'#tabs-3-1');
	cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/02_edit_jlaboral.php?id_pago='+ID_PAGO ,'#tabs-3-2');
	cargar_pagina('sunat_planilla/view-empresa/detalle_etapa_pago/03_edit_ingresos.php?id_pago='+ID_PAGO ,'#tabs-3-3');
	
	//cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/02_edit_jlaboral.php?id_ptrabajador='+id1+'&id_trabajador='+id2 ,'#tabs-3-2');
	//cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/03_edit_ingresos.php?id_ptrabajador='+id1+'&id_trabajador='+id2 ,'#tabs-3-3');
	//cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/04_edit_descuentos.php?id_ptrabajador='+id1+'&id_trabajador='+id2 ,'#tabs-3-4');
	//cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/05_edit_taportes.php?id_ptrabajador='+id1+'&id_trabajador='+id2 ,'#tabs-3-5');
	
	cargarTablaPagoGrid_Lineal(ID_PAGO);
	
</script>
<table id="list_lineal">
</table>

<div id="pager">
</div>

<form id="formPago" name="formPago"  method="post" action="" style="border:2px solid #00F;">
<div class="ocultar">id_pago
  <input name="id_pago" type="text" id="id_pago" value="<?php echo $ID_PAGO; ?>" readonly="readonly" />
</div>
<div align="left">
  <br>
<div style="width:150px; margin:0 0 0 500px; background: #FF0; ">
  <a href="javascript:cargar_pagina('sunat_planilla/view-empresa/edit_pago_tab2.php','#detalle_declaracion_trabajador')">Cerrar Detalle</a>
</div>

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

  <input type="button" name="button" id="button" value="Grabar" onclick="validarPago()" />
</form>



<!-- finn-->