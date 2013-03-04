<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
$id_pdeclaracion = ($_SESSION['sunat_empleador']['config']['id_pdeclaracion']) ? $_SESSION['sunat_empleador']['config']['id_pdeclaracion'] : 'null';
$periodo = ($_SESSION['sunat_empleador']['config']['periodo']) ? $_SESSION['sunat_empleador']['config']['periodo'] : 'null';
?>

<script type="text/javascript">
// variables LOCALES
var id_pdeclaracion = $('#id_pdeclaracion').val();
var periodo 		= $('#periodo').val();	
console.log("DATOS BASICOS DE PLANILLA");
console.log('id_pdeclaracion',id_pdeclaracion);
console.log('periodo',periodo);
/*
var id_pdeclaracion
var periodo;*/

    $(document).ready(function(){
		
		var request_id_pdeclaracion = <?php echo $id_pdeclaracion;?>;                  
        $( "#tabs").tabs();
		//cargarTablaLiquidaciones();
		console.log("request_id_pdeclaracion = "+request_id_pdeclaracion);
		cargarTablaPdeclaracionEmpresa(request_id_pdeclaracion);
		

		// TAB2 - funcion en espera de evento
		$('#reporte_liquidacion').click(function(){
			id_pdeclaracion = $('#id_pdeclaracion').val();
			periodo = $('#periodo').val();			
			
			var vacios = new Array(); //vacios[id_pdeclaracion, periodo];			
			vacios.push(id_pdeclaracion,periodo);
			//console.log(vacios);		
			
			//var estado = validarVacios(vacios);
			//console.log(estado);
			if(/*estado==false*/true){				
				var parametro = 'oper=reporte_liquidacion&id_pdeclaracion='+id_pdeclaracion+'&periodo='+periodo;
				var url = "sunat_planilla/controller/EstructuraLiquidacionController.php?"+parametro;			
				window.location.href = url;
				//console.log(url);
			}else{
				alert("Usted no selecciono un periodo");	
			}

		});			
		
	});


//----------------------------------
/*
function validarVacios(arreglo = new Array()){		
	var flag = false;
	for(var i =0;i<arreglo.length;i++){
		console.log(i);
		if(arreglo[i]==''){
			flag = true;
			break;
		}	
	}
	return flag;	
}
*/
//script
		
//-----------------------------------------------------------------
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

	
$('#eliminar_declaracion').click(function(){	
		var id_pdeclaracion = $("#id_pdeclaracion").val();
		var boton = $(this);
		if(id_pdeclaracion!=""){
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



</script>
<div class="demo" align="left">
<div class="ocultar">   
  id_pdeclaracion<input type="text" name="id_pdeclaracion" id="id_pdeclaracion" value="<?php echo $id_pdeclaracion;?>" />
  periodo<input type="text" name="periodo" id="periodo" value="<?php echo $periodo; ?>" />
</div>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Lista de  Periodos</a></li>
             <li><a href="#tabs-2">Operacion Anual</a></li>			
        </ul>
        <div id="tabs-1">
            <input type="button" name="button" id="button"  class="submit-nuevo"
            value="Nuevo Periodo"
            onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/new_periodo.php','#CapaContenedorFormulario')" />
            <h3>Seleccionar  Periodo
              <label for="anio"></label>
              <select name="anio" id="anio" onchange="cargarTablaPdeclaracionEmpresa()">
                <option value="2011">2011</option>
                <option value="2012">2012</option>
                <option value="2013" selected="selected">2013</option>
              </select>
            </h3>
            <input type="hidden" name="eliminar_declaracion" id="eliminar_declaracion" value="Elimina Declaracion" class="button-del" />

<div class="sectionx">
	<table id="list">
	</table>
	<div id="pager"></div>
    

<a href="#" 
onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/view_vacacion.php','#CapaContenedorFormulario')">Planilla de Vacaciones</a>
<!--
 GRID ANTIGUO
	<div class="article fila1">
	<table id="list">
	</table>
	<div id="pager"></div>
	</div>
    
	<div class="article fila2">
		<table id="list10_d">
		</table>
		<div id="list10_d"></div>
	</div> -->
</div>
        </div>
<div id="tabs-2">
<h2>Anual</h2>
<fieldset><legend>Remuneraciones</legend>
<div class="ayuda">Generar archivo exel de ...</div>
<table width="429" border="1">
  <tr>
    <th width="18">&nbsp;</th>
    <th width="301">Reporte</th>
    <th width="88">&nbsp;</th>
  </tr>
  <tr>
    <td>1.-</td>
    <td>Liquidacion anual de aportes y retenciones previsionales</td>
    <td><input name="reporte_liquidacion"  id= "reporte_liquidacion" type="button" value="Generar arhivo.xls"/></td>
  </tr>
</table>
<div class="ocultar">
  <p>&nbsp;</p>
</div>
</fieldset>
</div> 
</div>
</div>
