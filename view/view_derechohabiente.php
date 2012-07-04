<?php
//*******************************************************************//
require_once('ide.php');
//echo "<pre>";
//print_r($_SESSION);
//echo "<pre>";
$EMP = $_SESSION['sunat_empleador'];
//*******************************************************************//
?>

 <script language="javascript" type="text/javascript" src="js/function.js"></script>



<script>

//INICIO HISTORIAL
$(document).ready(function(){
	 $( "#tabs").tabs();	 

});


//------------------------
function buscarDerechoHabiente(){
	
	var form = document.form1;
	
	//var id_empleador = form.id_empleador.value;	
	var tipo_doc = form.cbo_tipo_documento.value;	
	var  numero = form.birds.value;
	
//-----------------------------------------------------------------------				
	//var from_data =  $("#form_trabajador").serialize();
	$.getJSON('sunat_planilla/controller/DerechohabienteController.php?oper=buscar&tipo_documento='+tipo_doc+'&num_documento='+numero,
		function(data){
			if(data){
				
			var url = 'sunat_planilla/view/view_derechohabiente2.php?id_persona='+data;				
			javascript:cargar_pagina(url,'#CapaContenedorFormulario')

			}else{
				alert("No se Encontro al Prestador de Servicio\nVerifique los datos ingresados");
			}
		}); 
//-----------------------------------------------------------------------


}

</script>

            
			
			
<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro de Derechohabientes</a></li>			
			
        </ul>
        <div id="tabs-1">
        <div class="ayuda">
          <p>
            Sr. Empleador:
            Esta opción de la aplicación le permitirá registrar el alta y baja de los derechohabientes, de sus trabajadores y pensionistas (titulares), así como la modificación de sus datos, de manera individual.
            Ingrese el tipo y número de documento de identidad del titular al cual se va adicionar, modificar o dar de baja un derechohabiente.
            
          </p>
        </div>  
        
		<h2>
		  
		  
		  Datos de Titular		  </h2>
		<form name="form1" method="post" action="">
		  <br />
        <div class="input_fila">         
            Tipo de Documento
              <select name="cbo_tipo_documento" id="cbo_tipo_documento" >
              <option value="01" selected>01:dni</option>
              <option value="04" >02:CARNET-EXT</option>
              <option value="07">:PASAPORTE</option>
            </select>
          
        </div>
        <p>Número de Documento
          <input name="birds" type="text" id="birds" size="30">
          <input type="button" name="buscar" id="buscar" value="buscar" onclick="buscarDerechoHabiente()" />
          <br />
</form>
  <table id="list"><tr><td/></tr></table>
            <div id="pager"></div>

		

        </div>

        
    </div>
</div>

