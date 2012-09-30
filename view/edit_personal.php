<?php
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//
 ?>
 
<?php
require_once('../util/funciones.php');
require_once('../dao/PersonaDao.php');
//--ini -obj_persona
require_once('../model/Persona.php');
//--end 
require_once('../controller/PersonaController.php');
//COMBO BASICO
require_once('../dao/ComboDao.php');
require_once('../controller/ComboController.php');



//--------------combo CATEGORIAS
require_once('../dao/ComboCategoriaDao.php');
require_once('../controller/ComboCategoriaController.php');

// COMBO 01
$cbo_tipo_documento = comboTipoDocumento(); //var_dump($cbo_tipo_documento);

// COMBO 02
$cbo_pais_emisor_documento = comboPaisEmisorDocumento();

// COMBO 03
$cbo_nacionalidades = comboNacionalidades();

// COMBO 04
$cbo_telefono_codigo_nacional = comboTelefonoCodigoNacional();

// COMOBO 05
$cbo_estado_civil = comboEstadosCiviles();


//---------------------------- EDITAR PERSONA--------------------------------- //

$ID_PERSONA = $_REQUEST['id_persona'];
$ID_TRABAJADOR = $_REQUEST['id_trabajador'];
//-------------------------ESTADO
$COD_ESTADO = $_REQUEST['cod_situacion'];

$obj_persona = new Persona();
// funcion del Controlador
$obj_persona = buscarPersonaPorId($ID_PERSONA);


?>

<script type="text/javascript" src="sunat_planilla/view/js/misvalidacion_pcategoria.js"></script>
            <script >
			//INICIO HISTORIAL
			
			//------------
			$(document).ready(function(){
               		
             $( "#tabs").tabs();
			// -----------Validacion
			$("#form_edit_personal").validate({
			
            rules: {
                txt_fecha_nacimiento: {
                    required: true,
                    date: true
                },
                cbo_pais_emisor_documento: {
                    required: true				  
                },				
                cbo_tipo_documento:{
                    required:true					
                },
                txt_num_documento:{
                    required: true,
                    rangelength: [8, 15]
                },
                txt_apellido_paterno:{
                    required: true
                },
                txt_apellido_materno:{
                    required: true
                },
                txt_nombre:{
                    required: true
                },
                rbtn_sexo:{
                    required: true
                },
                cbo_estado_civil:{
                    required: true	
                },
                cbo_Nacionalidad:{
                    required: true
                },
				cbo_telefono_codigo_nacional:{
					required: true
				}				
				
            },			
			  // errorLabelContainer: "#messageBox",
			  // wrapper: "li",
			   submitHandler: function() { 
			   //Inicio Submit
			   		//alert("Submitted!\n.....");
					//disableForm('form_edit_personal');
				var from_data =  $("#form_edit_personal").serialize();
				
				//-----------------------------------------------------------------------	
				$.getJSON('sunat_planilla/controller/PersonaController.php?'+from_data,
					function(data){

						if(data){
							
						disableForm('form_edit_personal');
						alert("Se guardo Correctamente JSON");					
						
						}else{
							alert("Ocurrio un error, intente nuevamente no hay datos JSON");
						}
					}); 
				//-----------------------------------------------------------------------
			   //Inicio Submit
			   }
			   
			})

	//-------------------------------------------------------------------
}); //End Ready
	//-------------------------------------------------------------------
	
	
	
	var id = $("#id_persona").val();
	var id2 = $("#id_trabajador").val();	
	//id_persona = document.form_edit_personal.id_persona.value;
	cargarTablaPersonalDireccion(id);		
	
	
	var cod_situacion =<?php echo $COD_ESTADO; ?>;	
	cargar_pagina('sunat_planilla/view/categoria/trabajador.php?id_persona='+id+'&id_trabajador='+id2+'&cod_situacion='+cod_situacion,'#tabs-2');
	
//	cargar_pagina('sunat_planilla/view/categoria/pensionista.php?id_persona='+id,'#tabs-3');

//	setTimeout(cargar_pagina('sunat_planilla/view/categoria/persona-en-formacion.php?id_persona='+id,'#tabs-4'), 3000);

//	cargar_pagina('sunat_planilla/view/categoria/persona-en-formacion.php?id_persona='+id,'#tabs-4');

//	cargar_pagina('sunat_planilla/view/categoria/persona-de-terceros.php?id_persona='+id,'#tabs-5');




function LlenarCombo(json, combo){ //console.log(json);
	combo.options[0] = new Option('-', '');
	for(var i=0;i<(json.length);i++){
		combo.options[i+1] = new Option(json[i].nombre, json[i].cod_ocupacion_p);
	}
}//END

function SeleccionandoCombo_1(cbo_depa, cbo_provin){ //alert ("....");
	cbo_provin = document.getElementById(cbo_provin);	
	
	if(cbo_depa.options[cbo_depa.selectedIndex].value !=""){
	//alert('entro a funcion LimpiarCombo');
	$('#cboOcupacion').find('option').remove().end();	
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: 'sunat_planilla/controller/ComboCategoriaController.php',
			data: {cbo_categoria_ocupacional: cbo_depa.options[cbo_depa.selectedIndex].value, oper: 'ocupaciones'},
			success: function(json){
				LlenarCombo(json, cbo_provin);
			}
		});
	}	
}//END FUNCIONT

//########################################################################################################
//  ----- NEW SCRIPT ANB --- TRABAJADOR
//########################################################################################################
function limpiarCombo(combo){
	var counteo = combo.length; console.log("limpiar   "+combo.length);
	for(var i=0;i<counteo;i++){
		combo.options[i] = null;
	}
}//ENDFN

function cargarCategoriaEjecutivo(objCombo){
<?php 
//Controller CategoriaController
$cbo_ocupaciones = comboOcupacionPorIdCategoriaOcupacional('01');
?>	
	//01
	var test = new Array(
	<?php $counteo = count($cbo_ocupaciones); 
	for($i=0;$i<$counteo;$i++): ?>	
	<?php
		if($i == $counteo-1){ 
			echo "{id:'".$cbo_ocupaciones[$i]['cod_ocupacion_p']."', descripcion:'".$cbo_ocupaciones[$i]['nombre']."' }"; 
		}else{
			echo "{id:'".$cbo_ocupaciones[$i]['cod_ocupacion_p']."', descripcion:'".$cbo_ocupaciones[$i]['nombre']."' },"; 
		}
	?>
	<?php endfor; ?>
	);//end array

	var counteo = 	test.length;		
	objCombo.options[0] = new Option('-ejecutivo-', '');
	for(var i=0;i<counteo;i++){
		objCombo.options[i+1] = new Option(test[i].descripcion, test[i].id);
	}
}//ENDFN

function cargarCategoriaObrero(objCombo){
<?php /*Controller CategoriaController*/ $cbo_ocupaciones = comboOcupacionPorIdCategoriaOcupacional('02');?>	
	//01
	var test = new Array(
	<?php $counteo = count($cbo_ocupaciones); 
	for($i=0;$i<$counteo;$i++): ?>	
	<?php
		if($i == $counteo-1){ 
			echo "{id:'".$cbo_ocupaciones[$i]['cod_ocupacion_p']."', descripcion:'".$cbo_ocupaciones[$i]['nombre']."' }"; 
		}else{
			echo "{id:'".$cbo_ocupaciones[$i]['cod_ocupacion_p']."', descripcion:'".$cbo_ocupaciones[$i]['nombre']."' },"; 
		}
	?>
	<?php endfor; ?>
	);//end array

	var counteo = 	test.length;		
	objCombo.options[0] = new Option('-obrero-', '');
	for(var i=0;i<counteo;i++){
		objCombo.options[i+1] = new Option(test[i].descripcion, test[i].id);
	}
}//ENDFN

function cargarCategoriaEmpleado(objCombo){
<?php /*Controller CategoriaController*/ $cbo_ocupaciones = comboOcupacionPorIdCategoriaOcupacional('03');?>	
	//01
	var test = new Array(
	<?php $counteo = count($cbo_ocupaciones); 
	for($i=0;$i<$counteo;$i++): ?>	
	<?php
		if($i == $counteo-1){ 
			echo "{id:'".$cbo_ocupaciones[$i]['cod_ocupacion_p']."', descripcion:'".$cbo_ocupaciones[$i]['nombre']."' }"; 
		}else{
			echo "{id:'".$cbo_ocupaciones[$i]['cod_ocupacion_p']."', descripcion:'".$cbo_ocupaciones[$i]['nombre']."' },"; 
		}
	?>
	<?php endfor; ?>
	);//end array

	var counteo = 	test.length;		
	objCombo.options[0] = new Option('-empleador-', '');
	for(var i=0;i<counteo;i++){
		objCombo.options[i+1] = new Option(test[i].descripcion, test[i].id);
	}

}//ENDFN



function combosVinculados(cbo){
	//alert("entro "+cbo.value);
	
	var oper = cbo.value;
	var comboDestino = document.getElementById('cboOcupacion');
	
	limpiarCombo(comboDestino);
	
	if(oper == '01'){//console.log("entro 01");
		cargarCategoriaEjecutivo(comboDestino);	
	}else if(oper == '02'){
		cargarCategoriaObrero(comboDestino);
	}else if(oper == '03'){
		cargarCategoriaEmpleado(comboDestino);
	}else{
		
	}
	
	

}//ENDFN


//-------------------------------------------------
function buscarCodigoDeInputaCombo(idInput,idCombo){
	
	var obj =document.getElementById(idInput);
	var aguja = obj.value;	
	var eCombo = document.getElementById(idCombo);
	var counteo = eCombo.options.length;
	
	var encontro = false
	
	for(i=0;i<counteo;i++){
		if(aguja == eCombo.options[i].value){			
			eCombo.options[i].selected = true;
			encontro = true;
			break;
		}			
	}//end for	
	if(encontro==false){
		eCombo.options[0].selected = true;
		obj.value="";
	}
}//ENDFN
//--------------------------------------------------

function seleccionarOcupacionComboPorInput(e,obj){
var tecla=(document.all) ? e.keyCode : e.which;	
	if (tecla==13) {	
		//var aguja = obj.value;
		var idInput = obj.id;
		buscarCodigoDeInputaCombo(idInput,'cboOcupacion');	
	}//ENDIF
}//ENDFN

function seleccionarOcupacionComboPorInputTab(obj){
		var idInput = obj.id;
		buscarCodigoDeInputaCombo(idInput,'cboOcupacion');
}



function seleccionarOcupacionInputPorCombo(obj){
	var objCombo = obj.value;
	var input = document.getElementById('txt_ocupacion_codigo');
	input.value=null;
	input.value = obj.value;
}









</script>
		
	
<style>
/*
	.ui-combobox {
		position: relative;
		display: inline-block;
	}
	.ui-button {
		position: absolute;
		top: 0;
		bottom: 0;
		margin-left: -1px;
		padding: 0;
	}
	.ui-autocomplete-input {
		margin: 0;
		padding: 0.3em;
	}
	
	*/
</style>		

<div class="demo" align="left">

    <div id="tabs">
  

<form action="sunat_planilla/controller/PersonaController.php" method="get" name="form_edit_personal" id="form_edit_personal" novalidate="novalidate">
                
                <input name="oper" type="hidden" value="edit">

<fieldset>
      <legend>Datos de Identificacion </legend>
                    <div class="ocultar">
                      id_persona
                      <input type="text" name="id_persona" id="id_persona" value="<?php echo $obj_persona->getId_persona() ?>" >
                      <br />
                      id_trabajador
                      <label for="id_trabajador"></label>
                      <input type="text" name="id_trabajador" id="id_trabajador" 
                      value="<?php echo $ID_TRABAJADOR; ?>"/>
                    </div>
<div>
<label>Tipo de Documento: </label>

					<select name="cbo_tipo_documento" id="cbo_tipo_documento" onChange="cambioSelect()" 
					class="chzn-select"  style="width:70px">
						<option value="">-</option>
<?php
foreach ($cbo_tipo_documento as $indice) {
	
	if ( $indice['cod_tipo_documento'] == $obj_persona->getCod_tipo_documento() ) {
		
		$html = '<option value="'. $indice['cod_tipo_documento'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';		
	} else {
		$html = '<option value="'. $indice['cod_tipo_documento'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
}
?>


                      </select>
                  </div>
                    <div class="fila_input">
                        <label>Numero Documento </label>
                        <input name="txt_num_documento" type="text"  id="txt_num_documento" 
						value="<?php echo $obj_persona->getNum_documento(); ?>">
                    </div>
                    <div class="fila_input">
                        <label>Fecha Nacimiento</label>
                        <input name="txt_fecha_nacimiento" type="text"  id="txt_fecha_nacimiento" 
						value="<?php echo getFechaPatron($obj_persona->getFecha_nacimiento(),"d/m/Y"); ?>">
                    </div>
                    <div class="fila_input">
                        <label>País Emisor del Documento:</label>
                        <select name="cbo_pais_emisor_documento" id="cbo_pais_emisor_documento" style="width:170px;">
                            <option value="">-</option>
<?php

foreach ($cbo_pais_emisor_documento as $indice) {
	
	if ($indice['cod_pais_emisor_documento'] == $obj_persona->getCod_pais_emisor_documentos() ) {
		
		$html = '<option value="'. $indice['cod_pais_emisor_documento'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
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
                        <input name="txt_apellido_paterno" type="text" id="txt_apellido_paterno" 
						value="<?php echo $obj_persona->getApellido_paterno();?>">
                    </div>
                    <div style="clear:both">
                        <label>Apellido Materno:</label>
                        <input name="txt_apellido_materno" type="text"  id="txt_apellido_materno" 
						value="<?php echo $obj_persona->getApellido_materno();?>">
                    </div>
                    <div class="fila_input">
                        <label>Nombres:</label>
                        <input name="txt_nombre" type="text" id="txt_nombre" value="<?php echo $obj_persona->getNombres();?>">
                    </div>
                    <div class="fila_input">
                        <label>Sexo:</label>
                        <input name="rbtn_sexo" type="radio" 
						value="1" <?php if($obj_persona->getSexo() == "1"){;?>checked="checked" <?php }?>>
                        M
                        <input name="rbtn_sexo" type="radio" 
						value="2" <?php if($obj_persona->getSexo() == "2"){;?>checked="checked" <?php }?>>
                        F </div>
                    <div>
                        <label style="clear:both">Estado Civil:</label>
											<select name="cbo_estado_civil">

                            <option value="">-</option>
<?php

foreach ($cbo_estado_civil as $indice) {
	
	if ($indice['id_estado_civil'] == $obj_persona->getId_estado_civil() ) {		
		$html = '<option value="'. $indice['id_estado_civil'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['id_estado_civil'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}

?>
						
</select>						
</div>
<div class="fila_input">
<label>Nacionalidad:</label>
<select name="cbo_Nacionalidad" style="width:170px;" >
<option value="">-</option>
          <?php
foreach ($cbo_nacionalidades as $indice) {
	
	if ($indice['cod_nacionalidad'] == $obj_persona->getCod_nacionalidad() ) {
		
		$html = '<option value="'. $indice['cod_nacionalidad'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_nacionalidad'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>

</select>
</div>
</fieldset>

<p></p>

<fieldset id=""><legend>datos opcionales</legend>
   <div>
        <label>Teléfono( código y número ): </label>
        <select name="cbo_telefono_codigo_nacional"  style="width:170px;">
<?php
foreach ($cbo_telefono_codigo_nacional as $indice) {
	
	if ($indice['cod_telefono_codigo_nacional'] == $obj_persona->getCod_telefono_codigo_nacional() ) {
		
		$html = '<option value="'. $indice['cod_telefono_codigo_nacional'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_telefono_codigo_nacional'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>

                        </select>
                        <input name="txt_telefono" type="text" id="txt_telefono" 
						value="<?php echo $obj_persona->getTelefono(); ?>">
                        
                  </div>

                    <div style="clear:both">
                        <label>Correo Electronico </label> 
                        <input name="txt_correo_electronico" type="text" value="<?php echo $obj_persona->getCorreo(); ?>">
                    </div>



                </fieldset>
				
				
				
<p></p>


<div style="display:block; " id="DIV_GRID_DIRECCION">

<table id="list"></table>
<div id="pager"></div>

			
</div>
<p></p>				
				
				
<input name="btn_grabar" type="submit" id="btn_grabar" value="Grabar Persona">

  </form>
  

  
    <fieldset><legend>Categoria</legend>
      <br />
  <ul>
            
            <li ><a href="#tabs-2">Trabajador</a></li>
            <!--		
            <li ><a href="#tabs-3">Pensionistas</a></li>
            <li><a href="#tabs-4">Personal en Formaci&oacute;n Laboral</a></li>			
            <li><a href="#tabs-5">Personal de Terceros</a></li>
            -->
            
        </ul>
			
        

      
<div id="tabs-2">
<p class="style2"> CATEGORIA TRABAJADOR</p>
</div>
<!--  
<div id="tabs-3">
<p>CATEGORIA PENSIONISTA</p>
</div>

<div id="tabs-4">
<p>CATEGORIA PERSONA-EN-FORMACION</p>
</div>

<div id="tabs-5">
<p>CATEGORIA PERSONA-DE-TERCEROS</p>
</div>
-->

    
</fieldset>  
</div>


<div id="dialog-form-editarDireccion" title="Editar Direccion">
    <div id="editarPersonaDireccion" align="left"></div>
</div>

