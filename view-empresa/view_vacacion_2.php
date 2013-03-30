<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
require_once '../controller/ideController.php';
$data = $_SESSION['sunat_empleador'];
$id_pdeclaracion = $_REQUEST['id_pdeclaracion'];

$PERIODO = $_REQUEST['periodo'];
//echo "DDDDDDDDDD".$PERIODO;

?>
<script type="text/javascript">
	//VARIABLES GLOBALES
	// console.log('id_pdeclaracion',id_pdeclaracion);
	// console.log('periodo',periodo);
    $(document).ready(function(){
                  
		$( "#tabs2").tabs();
			// modal
		$("#dialogVacacionOpciones").dialog({          
				autoOpen: false,
				height: 300,
				width: 280,
				modal: true,                        
				buttons: {
					'Generar': function() {
					var id_establecimientos = document.getElementById('id_establecimientos').value;				
					var cboCentroCosto = document.getElementById('cboCentroCosto').value;				
					var id = id_establecimientos.split('|');					
					var url = "sunat_planilla/controller/TrabajadorVacacionController.php";
					url += "?oper=boletaVacacion";
					url += "&id_pdeclaracion="+id_pdeclaracion;
					url += "&periodo="+periodo;
								
					url += "&id_establecimientos="+id[0];
					url += "&cboCentroCosto="+cboCentroCosto;					
			
					window.location.href = url;	
					//console.log(url);			
					},
					'Cancelar': function() {
						$(this).dialog('close');
					},                                
				},			
				open: function() {},
				close: function() {}
		});	
		//--
		$("#eliminarVacacion").click(function(){
			$.ajax({
				type: "POST",
				dataType: 'json',
				url: "sunat_planilla/controller/TrabajadorVacacionController.php",
				data: {oper : 'delAll', id_pdeclaracion : id_pdeclaracion},
				async:true,
				success: function(data){
					jQuery("#list").trigger("reloadGrid"); 
				}
			});			
		});
		//--
		$('#reporteVacacionIndividual').click(function(){
			$('#dialogVacacionOpciones').dialog('open');
			$.ajax({
				type: "POST",
				url: "sunat_planilla/view-plame/modal/edit_mas_opciones.php",
				data: {id_pdeclaracion : id_pdeclaracion},
				async:true,
				success: function(datos){
				$('#dialogVacacionOpcionesContent').html(datos);
				
				//$('#dialog_editarTDMasOP').dialog('open');
				}
			});
		});		
		
		//end modal
		// 01
		$("#reporteVacacion").click(function(){			
			//registrarEtapa(null);
			var url = "sunat_planilla/controller/TrabajadorVacacionController.php";
			url +="?oper=boletaVacacion&id_pdeclaracion="+id_pdeclaracion+'&periodo='+periodo
			url +="&todo=todo";
			
			window.location.href = url;
			//console.log(url);
			//window.open(url);
	
		});
		// 02
		// planilla
		$("#planilla").click(function(){			
			var url = "sunat_planilla/controller/TrabajadorVacacionController.php";
			url +="?oper=planilla&id_pdeclaracion="+id_pdeclaracion+'&periodo='+periodo				
			//console.log("Planilla ",url);	
			window.location.href = url;			
		});
		
		cargarTablaTrabajadorVacacion(id_pdeclaracion,periodo);
		
	});	
	//------------------- funciones
</script>


<div align="left">

    <div id="tabs2">
    
   
        <ul>
            <li><a href="#tabs-2-1">Trabajadores con Vacaciones</a></li>
            <!--<li><a href="#tabs-2-2">Pensionistas</a></li>	
            <li><a href="#tabs-2-3">PS 4Ta Categoria</a></li>-->		

        </ul>
        <div id="tabs-2-1">
          <div id="detalle_declaracion_trabajador">
            
            
            <!--  <h2>EDIT trabajador<br />
    jqgrid
    <br />
  </h2>-->
<a href="#">OPERACIONES</a>
<input type="button" name="eliminarVacacion" id="eliminarVacacion" value="Elimar Todo" >
  
<input type="button" name="reporteVacacion" id="reporteVacacion" value="Boleta de Vacacion" />
<input type="button" name="reporteVacacionIndividual" id="reporteVacacionIndividual" value="Boleta C.C." />
<input type="button" name="planilla" id="planilla" value="planilla">
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

<div id="dialogVacacionOpciones" title="Mas Opciones">
    <div id="dialogVacacionOpcionesContent" align="left"></div>    
</div>
