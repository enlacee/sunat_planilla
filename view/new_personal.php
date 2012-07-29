<?php
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//

require_once('../util/funciones.php');
require_once('../dao/AbstractDao.php');
require_once('../dao/ComboDao.php');
require_once('../controller/ComboController.php');

//require_once('../dao/PersonaDao.php');

//require_once('../controller/PersonaController.php');


// COMBO 01
$cbo_tipo_documento = comboTipoDocumento();
//print_r($cbo_tipo_documento);
// COMBO 02
$cbo_pais_emisor_documento = comboPaisEmisorDocumento();

// COMBO 03
$cbo_nacionalidades = comboNacionalidades();

// COMBO 04
$cbo_telefono_codigo_nacional = comboTelefonoCodigoNacional();


// COMOBO 05
$cbo_estado_civil = comboEstadosCiviles();


//---------

//comboUbigeoDepartamentos

//var_dump($cbo_telefono_codigo_nacional);
//echo "<pre>";
//print_r($cbo_telefono_codigo_nacional);
//echo "</pre>";

/**
 * Cargando Combo Box
 */
?>

<script>
               
    //INICIO HISTORIAL
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		crearDialogoPersonaDireccion();

    //-------------------------------------------------------------
    });

    //-------------------------------------------------------------
		
function validarFormNewPersonalPrincipal(obj){
	alert(validarFormNewPersonal());
	
	if( validarFormNewPersonal()) { 
		
		//-----------------------------------------------------------------------				
			var from_data =  $("#form_new_personal").serialize();
			$.getJSON('sunat_planilla/controller/PersonaController.php?'+from_data,
				function(data){
				//funcion.js index.php
				
					if(data=='true'){

					//alert(data);
					console.log(data);
					cargarTablaPersonalDireccion(data);					
					disableForm('form_new_personal');
					$("#DIV_GRID_DIRECCION").show();
					alert ('Se Guardo Correctamente.\nAhora registre su Direccion');

					}else{
						alert("El Num de Documento:"+$("#txt_num_documento").val()+"Ya se encuentra registrado!\n no se puede registrar nuevamente");
						//alert("Ocurrio un error, intente nuevamente no hay datos JSON");
					}
				}); 
		//-----------------------------------------------------------------------
			
				
}
	
}


    //******************************************************************************
	// SCRIPT DETALLE_DIRECCION.PHP	 INICIO
   //******************************************************************************
   

function LimpiarCombo(combo){
	//console.log(combo.options[1].text);
	for(i=0; i<combo.length; i++){
	combo.options[i] = null;
	console.log("eliminado;");
	
	}
}

function LlenarCombo(json, combo){ console.log(json);
	combo.options[0] = new Option('-', '');
	for(var i=0;i<(json.length);i++){
		combo.options[i+1] = new Option(json[i].descripcion, json[i].cod_provincia);
	}
}

function SeleccionandoCombo_1(cbo_depa, cbo_provin){
	cbo_provin = document.getElementById(cbo_provin); //con jquery: $("#"+cbo_provin)[0];
	
	LimpiarCombo(cbo_provin);
	
	if(cbo_depa.options[cbo_depa.selectedIndex].value != ""){
		cbo_depa.disabled = true;
		cbo_provin.disabled = true;
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'sunat_planilla/controller/PersonaController.php',
			data: {id_departamento: cbo_depa.options[cbo_depa.selectedIndex].value, oper: 'listar_provincias'},
			success: function(json){
				LlenarCombo(json, cbo_provin);
				cbo_depa.disabled = false;
				cbo_provin.disabled = false;
			}
		});
	}
}


//--------------[2]
function LlenarCombo_2(json, combo){ //console.log(json);
	//combo.options[0] = new Option('Selecciona un item', '');
	for(var i=0;i<json.length;i++){
		combo.options[i] = new Option(json[i].descripcion, json[i].cod_ubigeo_reniec);
	}
}
function SeleccionandoCombo_2(cbo_depa, cbo_distrito){
	cbo_distrito = document.getElementById(cbo_distrito); //con jquery: $("#"+cbo_distrito)[0];
	
	LimpiarCombo(cbo_distrito);
	
	if(cbo_depa.options[cbo_depa.selectedIndex].value != ""){
		cbo_depa.disabled = true;
		cbo_distrito.disabled = true;
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'sunat_planilla/controller/PersonaController.php',
			data: {id_provincia: cbo_depa.options[cbo_depa.selectedIndex].value, oper: 'listar_distritos'},
			success: function(json){
				LlenarCombo_2(json, cbo_distrito);
				cbo_depa.disabled = false;
				cbo_distrito.disabled = false;
			}
		});
	}
}


    //******************************************************************************
	// SCRIPT DETALLE_DIRECCION.PHP	 FINAL
   //******************************************************************************


function validarTipoDocumentoYNumero(){
	cbo = document.getElementById('cbo_tipo_documento');
	input = document.getElementById('txt_num_documento').value;
	
	if(cbo.value == '01' && input.length==8){
		return true;
	}else if(cbo.value == '04' && input.length>8 && input.length<=11 ){
		return true;
	}else if (cbo.value == '06' && input.length==11){
		return true;
	}else if(cbo.value == '07' && input.length>8 && input.length<=15){
		return true;
	}else if(cbo.value == '11' && input.length>8 && input.length<=15){
		return true;
	}else{
		return false;	
	}
	
	
	
}

//----- 
function validarFormNewPersonal(){
	var estado = null;
	var form = document.form_new_personal;

	if( validarTipoDocumentoYNumero()==false ){
		alert("Tipo de Documento Y Numero de Documentos no son Validados");
	}else if( validarFechaNacimiento(form.txt_fecha_nacimiento.value)==false){
		alert("Error en Fecha de Nacimiento");
	
	}else if(form.cbo_pais_emisor_documento.value=="" || form.cbo_pais_emisor_documento.value==0){
		alert("Debe seleccionar Pais Emisor de Documento");
	}else if(form.txt_apellido_paterno.value =="" || form.txt_apellido_materno.value ==""  || form.txt_nombre.value ==""){
		alert("Debe Ingresar Datos cOrrectos en Nombre y Apellidos");
	}else if( validarRadioMarcado(form.rbtn_sexo)==false){
		alert("Debe Seleccionar Sexo");
	}else if (form.cbo_estado_civil=="" || form.cbo_estado_civil==0){
		alert ("Debe seleccionar Estado Civil");
	}else if(form.cbo_Nacionalidad==""|| form.cbo_Nacionalidad==0){
		alert("Debe Seleccionar Nacionalidad");
	}else if(form.cbo_telefono_codigo_nacional.value > 0 ){ 
		if(form.txt_telefono.value=="" || form.txt_telefono.value.length <=6){
			alert("Debe Igresar un Numero Telefonico \n 7 Digitos como Minimo");
		}else{
		estado = true;
		}
	
	}else{	
		estado = true;
		//alert("true");
	}	
	return estado;
	
}






function validarFechaNacimiento(fecha){// alert("hola"+fecha);

	var estado = esFechaValida(fecha);
	
	if(estado){
		var dia  =  parseInt(fecha.substring(0,2),10);
		var mes  =  parseInt(fecha.substring(3,5),10);
		var anio =  parseInt(fecha.substring(6),10);	
		
		var fecha = new Date(anio,mes - 1 ,dia, 0,0,0);
		var hoy = new Date();
		
		var rpta = hoy.getFullYear() - fecha.getFullYear();		
		
		if(rpta>15){
			return true
		}else{
			return false;
		}
			
	}else{
		return false;
	}
	
	
}

//-----------------------------------------------------

function existePersonaRegistrada(){

	//get data
	var cbo_tipo_documento = document.getElementById('cbo_tipo_documento').value;
	var num_documento = document.getElementById('txt_num_documento').value;
	
	
$.getJSON(
'sunat_planilla/controller/PersonaController.php?oper=existe_persona&tipo_documento='+cbo_tipo_documento+"&num_documento="+num_documento,
function(ID){
	
	//console.log(dataa);
	//console.dir(dataa);
	//Existe persona pero se registrara como NUEVO TRABAJADOR
	if(ID){	
		alert("Persona Encontrada id_persona = "+ ID);		
		document.getElementById('id_persona_existe').value = ID;
//-----------------------		
		//ENVIA A OTR VISTA SI EXISTE ID_PERSONA registrada.
		
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'sunat_planilla/controller/PersonaController.php',
		data: {oper: 'add_persona_newtrabajador', id_persona_existe : ID },
		success: function(data){
			console.log("SE REGISTRO NUEVO TRABAJADOR  = "+data);
			//var cbo_base = document.getElementById('cbo_establecimiento');
		//	cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona='+id+'&cod_situacion=1','#CapaContenedorFormulario')
			
			
			
		}
	});	
//--------------------
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
});



/*
var id = document.getElementById('id_persona_existe').value;

alert("no pasa id ttx "+ id);
if(id.length>=1){ alert("a pasar a ajax "+ id);
//ENVIA A OTR VISTA SI EXISTE ID_PERSONA registrada.
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'sunat_planilla/controller/PersonaController.php',
		data: {oper: 'add_persona_newtrabajador', id_persona_existe : id }
        },
		success: function(data){
			console.log(data);
			//var cbo_base = document.getElementById('cbo_establecimiento');
			cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona='+id+'&cod_situacion=1','#CapaContenedorFormulario')
			
			
			
		}
	});

}

*/













	
	
}


</script>
<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Nuevo Prestador de Servicio</a></li>			

        </ul>
        <div id="tabs-1">
          <div class="bloke1">
            <form action="" method="get" name="form_new_personal" id="form_new_personal" novalidate="novalidate">
                
                <input name="oper" type="hidden" value="add">
                <fieldset>
                  <legend>Datos de Identificacion </legend>
                  
                  
                  
                  <div class="ocultar"><label>id_empleador</label><input name="id_empleador" type="text" 
					value="<?php echo $DATA_EMPLEADOR['id_empleador']; ?>"><br />
                    </div>
                  <div class="fila_input">
                    <label>Tipo de Documento: </label>
                    
                    <select name="cbo_tipo_documento" id="cbo_tipo_documento" onChange="eventoKeyComboPeruPersonal(this)" >
                      <option value="" selected="selected">-</option>
  <?php
foreach ($cbo_tipo_documento as $indice) {
	
	if ($indice['cod_tipo_documento'] ==  $_REQUEST['tipo_documento']/*'0'$obj_banco_liqui->getId_banco()*/ ) {
		
		$html = '<option value="'. $indice['cod_tipo_documento'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_tipo_documento'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
}
?>
                      
                      
                      </select>
                  </div>
                  <div class="fila_input" >
                    <label>Numero Documento </label>
                    <input name="txt_num_documento" type="text" id="txt_num_documento" value="<?php echo $_REQUEST['num_documento']; ?>">
                    <input type="button" name="btn_existePersona" id="btn_existePersona" value="existe persona"
                    onclick="existePersonaRegistrada()" />
                    <label for="id_persona_existe"></label>
                    <input name="id_persona_existe" type="text" id="id_persona_existe" size="4" />
                  </div>
                  <div class="fila_input">
                    <label>Fecha Nacimiento</label>
                    <input name="txt_fecha_nacimiento" type="text"  id="txt_fecha_nacimiento">
                    </div>
                  <div class="fila_input">
                    <label>País Emisor del Documento:</label>
                    <select name="cbo_pais_emisor_documento" id="cbo_pais_emisor_documento"
                        onchange="validarPaisEmisorDocumento()" >
                      <option value="" selected="selected">-</option>
  <?php

foreach ($cbo_pais_emisor_documento as $indice) {
	
	if ($indice['cod_pais_emisor_documento'] == '0'/*$obj_banco_liqui->getId_banco()*/ ) {
		
		$html = '<option value="'. $indice['cod_pais_emisor_documento'] .'"  >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_pais_emisor_documento'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}

?>
                      
                      </select>
                    </div>
                  <div class="fila_input">
                    <label>Apellido Paterno:</label>
                    <input name="txt_apellido_paterno" type="text" id="txt_apellido_paterno">
                    </div>
                  <div class="fila_input">
                    <label>Apellido Materno:</label>
                    <input name="txt_apellido_materno" type="text"  id="txt_apellido_materno">
                    </div>
                  <div class="fila_input">
                    <label>Nombres:</label>
                    <input name="txt_nombre" type="text" id="txt_nombre">
                    </div>
                  <div class="fila_input">
                    <label>Sexo:</label>
                    <input name="rbtn_sexo" type="radio" value="1">
                    M
                    <input name="rbtn_sexo" type="radio" value="2" >
                    F </div>
                  <div>
                    <label style="clear:both">Estado Civil:</label>
                    <select name="cbo_estado_civil">
                      
                      <option value="">-</option>
  <?php

foreach ($cbo_estado_civil as $indice) {
	
	if ($indice['id_estado_civil'] ) {

		$html = '<option value="'. $indice['id_estado_civil'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}

?>
                      
                      </select>
                  </div>
                  <div class="fila_input">
                    <label>Nacionalidad:</label>
                    <select name="cbo_Nacionalidad" id="cbo_Nacionalidad">
                      <option value="">-</option>
                      <?php

foreach ($cbo_nacionalidades as $indice) {
	
	//if ($indice['id_banco'] == $obj_banco_liqui->getId_banco() ) {
		
		//$html = '<option value="'. $indice['cod_nacionalidad'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	//} else {
		$html = '<option value="'. $indice['cod_nacionalidad'] .'" >' . $indice['descripcion'] . '</option>';
	//}
	echo $html;
}
?>
                      
  </select>
                    </div>
                  <br/>
                  </fieldset>
                <fieldset id="">
                  <legend>datos opcionales</legend>
                  
                  
                  <div>
                    <label>Teléfono( código y número ): </label>
                    <select name="cbo_telefono_codigo_nacional"  style="min-width:170px" >
                      <!--<option value="">-</option>-->
  <?php
foreach ($cbo_telefono_codigo_nacional as $indice) {
	
	if ($indice['cod_telefono_codigo_nacional'] == 0/* $obj_banco_liqui->getId_banco()*/ ) {
		
		$html = '<option value="'. $indice['cod_telefono_codigo_nacional'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_telefono_codigo_nacional'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>
                      
                      </select>                        
                    <input name="txt_telefono" type="text" id="txt_telefono">
                    
                    </div>
                  
                  <div class="fila_input">
                    <label>Correo Electronico </label> 
                    <input name="txt_correo_electronico" type="text">
                    </div>
                  
                  
                  
                  </fieldset>
                
                
                
  <p></p>
                
                
                
                
                
                <!-- DIRECCION 2-->
                <input name="btn_grabar" type="button" id="btn_grabar" value="Grabar" onclick="validarFormNewPersonalPrincipal(this)">
            </form>
            </div>





<div style="display:block; " id="DIV_GRID_DIRECCION">
		
    <table id="list"><tr><td/></tr></table>
    
<div id="pager"></div>
			
</div>

<input type="submit" name="btnRetornar" id="btnRetornar" value="Retornar"
  onclick="javascript:cargar_pagina('sunat_planilla/view/view_personal.php','#CapaContenedorFormulario')" />



<!--
	FORMULARIO DIRECCION 2
-->
        </div>
    </div>
</div>





<div id="rpta">  ...</div>





<!-- -->

<!-- -->

<div id="dialog-form-editarDireccion" title="Editar Direccion">
    <div id="editarPersonaDireccion" align="left"></div>
</div>

