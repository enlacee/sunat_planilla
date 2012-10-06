<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
require_once '../controller/ideController.php';
$data = $_SESSION['sunat_empleador'];
$id_declaracion = $_REQUEST['id_declaracion'];

$PERIODO = ($_REQUEST['periodo']) ? $_REQUEST['periodo'] : "00/0000";

//echo "DDDDDDDDDD".$PERIODO;

?>
<script type="text/javascript">
//VARIABLES GLOBALES
//var PERIODOX = '<?php //echo $PERIODO;?>';

    $(document).ready(function(){
                  
        $( "#tabs2").tabs();
		
	});
	
	
	//cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/view_trabajador.php?id_declaracion='+ID_DECLARACION ,'#tabs-2-1');
	//cargar_pagina('sunat_planilla/view-plame/declaraciones_detalle/view_trabajador.php','#tabs-2-2');
	//cargar_pagina('sunat_planilla/view-plame/detalle_declaracion/view_p4ta_catecoria.php?periodo='+$PERIODO ,'#tabs-2-3');

	
	cargarTablaTrabajadorPdeclaracion(ID_DECLARACION);
	//cargarTablaPTrabajadores(ID_DECLARACION);
	
	// funciones
	
	

   //02  = total
	$("#reporte30_02").click(function(){
		
		//registrarEtapa(null);
		var url = "sunat_planilla/controller/TrabajadorPdeclaracionController.php";
		url +="?oper=recibo30&id_pdeclaracion="+ID_DECLARACION
		url +="&todo=todo";
		
		window.location.href = url;
		//window.open(url);

	});
	
	   //02  = total
	$("#reporte30_mas").click(function(){		
		editarTDMasOpciones(ID_DECLARACION);		

	});
	
	
	//03  = total
	$("#reporte_plame").click(function(){		
		
		var url = "sunat_planilla/controller/Estructura_PlanillaMensualController.php";
		url +="?oper=estructura-plame&id_pdeclaracion="+ID_DECLARACION		
		
		window.location.href = url;
				

	});

	
	//04 =
	$("#reporte_emp_01").click(function(){	console.log("ENTRO EN  reporte_emp_01");
		
		var url = "sunat_planilla/controller/TrabajadorPdeclaracionController.php";
		url +="?oper=reporte_emp_01&id_pdeclaracion="+ID_DECLARACION
		url +="&todo=todo";	
		
		window.location.href = url;
				

	});
	 
	
	
	
	
	//------------------- funciones
	
	function editarTDMasOpciones(id_pdeclaracion){
		crearDialogoTDMasOp();
		$('#dialog_editarTDMasOP').dialog('open');
	   $.ajax({
	   type: "POST",
	   url: "sunat_planilla/view-plame/modal/edit_mas_opciones.php",
	   data: {id_pdeclaracion : id_pdeclaracion},
	   async:true,
	   success: function(datos){
		$('#editarTDMasOP').html(datos);
		
		//$('#dialog_editarTDMasOP').dialog('open');
	   }
	   }); 
	}




	function crearDialogoTDMasOp(){
	$("#dialog_editarTDMasOP").dialog({ 
           
			autoOpen: false,
			height: 300,
			width: 280,
			modal: true,                        
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Generar': function() {	
				var id_pdeclaracion = document.getElementById('id_declaracion').value;				
				
				var id_establecimientos = document.getElementById('id_establecimientos').value;				
				var cboCentroCosto = document.getElementById('cboCentroCosto').value;
				
				var id = id_establecimientos.split('|');
		
				
				var url = "sunat_planilla/controller/TrabajadorPdeclaracionController.php";
				url += "?oper=recibo30";
				url += "&id_pdeclaracion="+id_pdeclaracion;	
							
				url += "&id_establecimientos="+id[0];
				url += "&cboCentroCosto="+cboCentroCosto;
				//alert(url);
		
				window.location.href = url;
				
				}
                                
			},			
			open: function() {},
			close: function() {}
	});
}
	
</script>


<div align="left">

    <div id="tabs2">
    
   
        <ul>
            <li><a href="#tabs-2-1">Trabajadores</a></li>
            <!--<li><a href="#tabs-2-2">Pensionistas</a></li>	
            <li><a href="#tabs-2-3">PS 4Ta Categoria</a></li>-->		

        </ul>
        <div id="tabs-2-1">








<div id="detalle_declaracion_trabajador">


<!--  <h2>EDIT trabajador<br />
    jqgrid
    <br />
  </h2>-->
<input type="button" name="reporte30_02" id="reporte30_02" value="Reporte Mensual Total" />
<input type="button" name="reporte30_mas" id="reporte30_mas" value="Mas op" />
<input type="button" name="reporte_plame" id="reporte_plame" value="PDT PLAME"  />
<span  id="break" style="padding:0 10px; margin:0 2px; background-color:#FCF"></span>
<input type="submit" name="reporte_emp_01" id="reporte_emp_01" value="|planilla unica pagos|" />
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

<div id="dialog_editarTDMasOP" title="Mas Opciones">

    <div id="editarTDMasOP" align="left"></div>
    
</div>
