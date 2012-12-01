<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
$id_pdeclaracion = ($_REQUEST['id_pdeclaracion']) ? $_REQUEST['id_pdeclaracion'] : 'null';
?>



<!--<link rel="stylesheet" href="sunat_planilla/view/ui/themes/ui-lightness/jquery.ui.all.css">-->

<!--<link rel="stylesheet" type="text/css" media="screen" href="sunat_planilla/view/css/ui.jqgrid.css" />

<link rel="stylesheet" href="sunat_planilla/view/css/ui.jqgrid.css">
<link rel="stylesheet" href="sunat_planilla/view/css/ui-lightness/jquery-ui-1.8.20.custom.css">

-->
<!--<script src="sunat_planilla/view/js/jquery-1.7_min.js" type="text/javascript"></script>-->

<script type="text/javascript">

	



    $(document).ready(function(){
		var request_id_pdeclaracion = <?php echo $id_pdeclaracion;?>;                  
        $( "#tabs").tabs();
		//cargarTablaLiquidaciones();
		console.log("request_id_pdeclaracion = "+request_id_pdeclaracion);
		cargarTablaPdeclaracionEmpresa(request_id_pdeclaracion);	
		
	});
	
	//--------------------------------------
	
$("#eliminar_datos_mes").click(function(){
	
	
var id_pdeclaracion = $("#id_pdeclaracion").val();
var periodo = $("#periodo").val();


var boton = $(this);
		
				
if(id_pdeclaracion!=""){		
//------
	boton.val("Procesando...");
   $.ajax({
   type: "POST",
   url: "sunat_planilla/controller/TrabajadorPdeclaracionController.php",
   data: {id_pdeclaracion : id_pdeclaracion, oper : 'eliminar_data_mes', periodo : periodo },
   async:true,
   success: function(datos){

		if(datos){
			alert("Se borraron los datos correctamente");
			jQuery("#list10_d").trigger('reloadGrid');
			boton.val('Eliminar Datos del Mes');
		}else{
			boton.val('Eliminar Datos del Mes');
			alert("Existio un error");
		}
		
		
   }
   }); 
//------			
}else{
alert("Debe seleccionar un periodo.\n");
}

});
	
	
//cargarTablaLiquidaciones();
//cargarTablaPdeclaracionEmpresa();
	
//-----------------------------------------------------------------


function edit_15(){}

function add_15(id_declaracion,periodo,num15){ //QUINCENAL
   alert("15");
   $.ajax({
   type: "POST",
   url: "sunat_planilla/controller/AdelantoController.php",
   data: {id_declaracion : id_declaracion, periodo : periodo ,num15: num15 ,oper : 'add_15'},
   async:true,
   success: function(datos){
	//alert("datos ::? "+datos);

   }
   }); 
   
   
}
	
//------------------------------

//funcion chekkk
function habilitarBotonEliminarMes(){
	var obj = document.getElementById('checkbox_data');
	
	if(obj.checked == true){ //alert("entro true");
	console.log("true");
		//$("#eliminar_datos_mes").removeAttr('disabled');
		$(".button-del").removeAttr('disabled');
		
		
			console.log("true");
	}else{
		//$("#eliminar_datos_mes").attr('disabled','disabled');		
		$(".button-del").attr('disabled','disabled');	
	}

}
	habilitarBotonEliminarMes();
	
	
	//---------------------------------------------------------
	
	
	$('#eliminar_declaracion').click(function(){
		
			var id_pdeclaracion = $("#id_pdeclaracion").val();
			var boton = $(this);
					
							
			if(id_pdeclaracion!=""){		
			//------
				boton.val("Procesando...");
			   $.ajax({
			   type: "POST",
			   url: "sunat_planilla/controller/PlameDeclaracionController.php",
			   data: {id_pdeclaracion : id_pdeclaracion,oper : 'del-id_pdeclaracion'},
			   async:true,
			   success: function(datos){
			
					if(datos){
						alert("Se borraron los datos correctamente");
						jQuery("#list").trigger('reloadGrid');
						//jQuery("#list10_d").trigger('reloadGrid');
						boton.val('Eliminar Datos del Mes');
					}else{
						boton.val('Eliminar Datos del Mes');
						alert("Existio un error");
					}
					
					
			   }
			   }); 
			//------			
			}else{
			alert("Debe seleccionar un periodo.\n");
			}
		
	});
	

//::: OPERACIONES::::
//00
$('#reporte_liquidacion').click(function(){
	console.log("reporte liquidacion txt");
	var url = "sunat_planilla/controller/TrabajadorPdeclaracionController.php";
	url +="?oper=reporte_liquidacion&id_pdeclaracion="+ID_DECLARACION
	console.log(url);
	//window.location.href = url;
});	




	
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Lista de  Periodos</a></li>
            
             <li><a href="#tabs-2">Operacion Anual</a></li>			

        </ul>
        <div id="tabs-1">
            <input type="button" name="button" id="button"  class="submit-nuevo"
            value="Nuevo Periodo"
            onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/new_periodo.php','#CapaContenedorFormulario')" />
            
            <h2>lista de periodos 
              <label for="anio"></label>
              <select name="anio" id="anio" onchange="cargarTablaPdeclaracionEmpresa()">
                <option value="2011">2011</option>
                <option value="2012" selected="selected">2012</option>
                <option value="2013">2013</option>
              </select>
            </h2>
            <p>&nbsp;</p>
            
            <input type="checkbox" name="checkbox_data" id="checkbox_data" onclick="habilitarBotonEliminarMes()" />
            <input name="eliminar_datos_mes" id="eliminar_datos_mes"  value="Eliminar Datos del Mes"type="button" class="button-del"  />
            <input type="hidden" name="eliminar_declaracion" id="eliminar_declaracion" value="Elimina Declaracion" class="button-del" />
<div class="ocultar">   
  id_pdeclaracion<input type="text" name="id_pdeclaracion" id="id_pdeclaracion" value="" />
  <br />
periodo<input type="text" name="periodo" id="periodo"
value="<?php echo $_REQUEST['periodo']; ?>" />
  
  
</div>
<div class="section">
<div class="article fila1">
<table id="list">
</table>
<div id="pager"></div>

              </div>
              <div class="article fila2">
              
<table id="list10_d">
</table>
<div id="list10_d"></div>
              </div>
            </div>
            
            
            
            

            

        
        </div>
        
<div id="tabs-2">
<h2>Anual</h2>
<fieldset><legend>Remuneraciones</legend>


<input name="reporte_liquidacion_anual"  id= "reporte_liquidacion_anual" type="button" value="Liquidacion Anual"/>

<div class="ayuda">Generar archivo exel de ...</div>


</fieldset>




</div>        
        
        
</div>

</div>

















