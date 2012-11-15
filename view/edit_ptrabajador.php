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
	
	
	
var cod_situacion =<?php echo $COD_ESTADO;?>;	
var id = <?php echo $ID_TRABAJADOR;?>;
	
cargar_pagina('sunat_planilla/view/categoria/trabajador.php?id_trabajador='+id+'&cod_situacion='+cod_situacion,'#tabs-2');
	
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
    <fieldset>
    <legend>Datos Basicos</legend>
    <br />
    
    <table width="506" border="0" class="tabla_gris">
      <tr>
    <td width="166"><strong>Num doc:</strong></td>
    <td width="436"><?php echo $obj_persona->getCod_tipo_documento()." - ". $obj_persona->getNum_documento();?></td>
  </tr>
  <tr>
    <td><strong>Nombres y Apellidos:</strong></td>
    <td><?php echo $obj_persona->getApellido_paterno()." ".$obj_persona->getApellido_materno()." ".$obj_persona->getNombres();?></td>
    </tr>
  <tr>
    <td><strong>Sexo:</strong></td>
    <td><?php echo $obj_persona->getSexo();?></td>
  </tr>
    </table>
    <br />

    
    
    </fieldset>
    
    
    
    
    
    
    
      <fieldset>
      <legend>Categoria</legend>
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

