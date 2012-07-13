<?php

//COMBO FILE
require_once('../dao/AbstractDao.php');
require_once('../dao/ComboDao.php');
require_once('../controller/ComboController.php');

require_once '../dao/ComboCategoriaDao.php';
require_once '../controller/ComboCategoriaController.php';

//--
//require_once('../dao/DerechohabienteDao.php');
//require_once('../model/Derechohabiente.php');
//require_once('../controller/DerechohabienteController.php');

// COMBO 01
$cbo_tipo_documento = comboTipoDocumento();

// COMBO 02
$cbo_pais_emisor_documento = comboPaisEmisorDocumento();

// COMBO 03
//$cbo_nacionalidades = comboNacionalidades();

// COMBO 04
$cbo_telefono_codigo_nacional = comboTelefonoCodigoNacional();

// COMOBO 05
$cbo_estado_civil = comboEstadosCiviles();


//--------------- COMBO DERECHO HABIENTE -------------------//
// COMBO 01
$cbo_vinculos_familiares = comboVinculoFamiliar();

// COMBO 02
//$cbo_documentos_vinculos_familiares =comboDocumentoVinculoFamiliar($ID);

// COMBO 03 cod_situacion

$cbo_situaciones =comboSituacion();

//---------------------------- EDITAR DERECHOHABIENTE--------------------------------- //

?>

            <script>
			
                
                //INICIO HISTORIAL
			$(document).ready(function(){
						   
                    $( "#tabs").tabs();
					//alert ("d");
					crearDialogoDerechohabienteDireccion();
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//$('#form_new_personal').ajaxForm( { beforeSubmit: validate } ); 
			$("#form_new_derechohabiente").validate({
			
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
					number: true,
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
                cbo_vinculo_familiar:{
                    required: true
                },
				cbo_documento_vinculo_familiar:{
					required: true
				},
				txt_vf_num_documento:{
					required: true,
					number: true,
					digits: true,
				},
				txt_vf_mes_concepcion:{
					required: true,
					//requerid: function(element){
						
					//}					
				},
				txt_telefono:{					
					required: function(element) {
					return $("#cbo_telefono_codigo_nacional").val() != 0;
					},
					rangelength: [7, 15],				
				}								
				
            },			
			   submitHandler: function() { 

			   		//alert("Submitted2!\n.....");					
				var from_data =  $("#form_new_derechohabiente").serialize();
				
				$.getJSON('sunat_planilla/controller/DerechohabienteController.php?'+from_data,
					function(data){								
						if(data){
						//document.getElementById('id_persona').value = data.id_persona;
						disableForm('form_new_derechohabiente');
						cargarTablaDerechohabienteDireccion(data.id_derechohabiente);
						//ID = data.id_persona;
						
						alert("Se guardo Correctamente. \n");	
						}else{
							alert("Ocurrio un error");
						}
					}); 

					
			   //Inicio Submit
			   }
			   
			})
					
					

//-------------------------------------------------------------------
                }); //End Ready
				


//-------------------------------------------------------------
///--------- Inico select dependiente----------//
    function llenarComboVF(json, combo){ //console.log(json);
        combo.options[0] = new Option('-', '');
        for(var i=0;i<(json.length);i++){
			console.dir(json);
            combo.options[i+1] = new Option(json[i].nombre_doc_vinculos_familiares, json[i].cod_documento_vinculo_familiar);
        }
    }

    function SeleccionandoCombo_VF(cbo_depa, cbo_provin){
        cbo_provin = document.getElementById(cbo_provin);
	
        if(cbo_depa.options[cbo_depa.selectedIndex].value >=1){
            
            $.ajax({
                type: 'get',
                dataType: 'json',
                url: 'sunat_planilla/controller/ComboController.php',
                data: {id: cbo_depa.options[cbo_depa.selectedIndex].value, oper: 'listar_doc_vinculosf'},
                success: function(json){
					$('#cbo_documento_vinculo_familiar').find('option').remove().end();
                    llenarComboVF(json, cbo_provin);
								
                }
            });
        }else if(cbo_depa.options[cbo_depa.selectedIndex].value == ""){		

        }//endif
    }
///--------- Final select dependiente----------//
//-------------------------------------------------------------
//--------------------------------------------------------------
//--------------------------------------------------------------

		</script>








<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Nuevo DerechoHabiente</a></li>			
			
        </ul>
        <div id="tabs-1">
<!-- INICIO TAB1 -->	
<div id="tabs">
	
    <form action="sunat_planilla/controller/DerechohabienteController.php" method="post" 
	name="form_edit_derechohabiente" id="form_new_derechohabiente" novalidate="novalidate">
                
                <input name="oper" type="hidden" value="add">
                <fieldset>
                    <legend>Datos de Identificacion </legend>
<div class="ocultar">
					<label>id_persona</label>
					<input name="id_persona" type="text" id="id_persona"  value="<?php echo $_REQUEST['id_persona']; ?>" readonly="readonly">					
	</div>				
                    <br/>
                    <div class="fila_input">
                        <label>Tipo de Documento: </label>

					<select name="cbo_tipo_documento" id="cbo_tipo_documento"  onchange="eventoKeyComboPeruDerechoHabiente(this)" >
						<option value="" selected="selected">-</option>
<?php
foreach ($cbo_tipo_documento as $indice) {
	
	if ( $indice['cod_tipo_documento'] == 0 ) {
		
		$html = '<option value="'. $indice['cod_tipo_documento'] .'" >' . $indice['descripcion_abreviada'] . '</option>';		
	} else {
		$html = '<option value="'. $indice['cod_tipo_documento'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
}
?>


                      </select>
                    </div>
                    <div>
                        <label>Numero Documento </label>
                        <input name="txt_num_documento" type="text" class="required"  id="txt_num_documento">
                    </div>
                    <div style="clear:both">
                        <label>Fecha Nacimiento</label>                        
						<input type="text" id="txt_fecha_nacimiento" name="txt_fecha_nacimiento">
                    </div>
                    <div style="clear:both">
                        <label>País Emisor del Doc:</label>
                        <select name="cbo_pais_emisor_documento" id="cbo_pais_emisor_documento" style="width:180px"
                           >
<option selected="selected">-</option>
<?php

foreach ($cbo_pais_emisor_documento as $indice) {
	
	if ($indice['cod_pais_emisor_documento'] == 0 ) {
		
		$html = '<option value="" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_pais_emisor_documento'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}

?>

                        </select>
                    </div>
                    <div style="clear:both">
                        <label>Apellido Paterno:</label>
                        <input name="txt_apellido_paterno" type="text" id="txt_apellido_paterno">
                    </div>
                <div style="clear:both">
                        <label>Apellido Materno:</label>
                        <input name="txt_apellido_materno" type="text"  id="txt_apellido_materno">
                </div>
                <div style="clear:both">
                        <label>Nombres:</label>
                        <input name="txt_nombre" type="text" id="txt_nombre">
                </div>
                    <div style="clear:both">
                        <label>Sexo:</label>
                        <input name="rbtn_sexo" type="radio" 
						value="M" >
                        M
                        <input name="rbtn_sexo" type="radio" 
						value="F" >
                        F </div>
                    <div>
                        <label style="clear:both">Estado Civil:</label>
						<select name="cbo_estado_civil" style="width:180px">
                            <option value="" selected="selected">-</option>
<?php
foreach ($cbo_estado_civil as $indice) {
	
	if ($indice['id_estado_civil'] == 0 ) {		
		$html = '<option value="'. $indice['id_estado_civil'] .'"  >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['id_estado_civil'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}

?>
						
						</select>						
					</div>

                </fieldset>

<p></p>

<fieldset><legend>V&iacute;nculo Familiar</legend>



<div class="fila_input">
<label>Vínculo Familiar:</label>
<select name="cbo_vinculo_familiar" onchange="SeleccionandoCombo_VF(this, 'cbo_documento_vinculo_familiar')" 
style="width:180px;" >
<option value="">-</option>
<?php
foreach ($cbo_vinculos_familiares as $indice) {
	
	if ($indice['cod_vinculo_familiar'] == 0 ) {		
		$html = '<option value="" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_vinculo_familiar'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
}

?>
</select>
</div>
<div class="fila_input">
<label>Doc que sustenta vínculo:</label>
<select name="cbo_documento_vinculo_familiar" id="cbo_documento_vinculo_familiar" style="width:170px;">
<?php
foreach ($cbo_documentos_vinculos_familiares as $indice) {
	
	if ($indice['cod_documento_vinculo_familiar'] == 0 ) {		
		$html = '<option value="" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_documento_vinculo_familiar'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
}

?>
</select>
</div>


<div class="fila_input">
<label>N&uacute;mero de Doc:</label>
<input name="txt_vf_num_documento" type="text"  id="txt_vf_num_documento" />
</div>

<div class="fila_input">
<label>Mes de Concepci&oacute;n:</label>
<input name="txt_vf_mes_concepcion" type="text"  id="txt_vf_mes_concepcion"
onblur="validarvfMesConcepcion(this)" /> 
(mm/aaaa)
</div>

<div style="fila_input ocultar">
situaci&oacute;n: <select name="cbo_situacion" style="width:170px;"   >
<?php
foreach ($cbo_situaciones as $indice) {
	
	if ($indice['cod_situacion'] == 1 ) {		
		$html = '<option value="'. $indice['cod_situacion'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_situacion'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
}

?>
</select>
</div>



</fieldset>

<p></p>



                <fieldset id=""><legend>datos opcionales</legend>


                    <div class="fila_input">
                        <label>Teléfono( c&oacute;digo y n&uacute;mero ): </label>
                         <select name="cbo_telefono_codigo_nacional" id="cbo_telefono_codigo_nacional" style="width:160px;" >
                            <?php
foreach ($cbo_telefono_codigo_nacional as $indice) {
	
	if ($indice['cod_telefono_codigo_nacional'] == 0 ) {
		
		$html = '<option value="'. $indice['cod_telefono_codigo_nacional'] .'" selected="selected" >-</option>';
	} else {
		$html = '<option value="'. $indice['cod_telefono_codigo_nacional'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>
                      </select>
                          <input name="txt_telefono" type="text" id="txt_telefono" />
                    
                    </div>

                    <div class="fila_input">
                        <label>Correo Electronico </label> 
                        <input name="txt_correo_electronico" type="text">
                    </div>



                </fieldset>
				
				
				
<p></p>


			
				
				
				<!-- DIRECCION 2-->
				<input name="btn_grabar" type="submit" id="btn_grabar" value="Grabar Derechohabiente">
    </form>
    <div   style=" display:block; " id="DIV_GRID_DIRECCION">
      
    <table id="list"></table>
            <div id="pager"></div>
			
      	
			
</div>
    <input type="submit" name="Submit" 
				value="Retornar" 
				onclick="cargar_pagina('sunat_planilla/view/view_derechohabiente2.php?id_persona=<?php echo $_REQUEST['id_persona']; ?>','#CapaContenedorFormulario')" />
</div>
<!-- FINAL TAB1 -->	


        </div>

        
    </div>
</div>

<!-- -->			
			
			

<!-- -->

<!-- -->

<div id="dialog-form-editarDireccion-Derechohabiente" title="Editar Direccion">
    <div id="editarDerechohabienteDireccion" align="left"></div>
</div>

