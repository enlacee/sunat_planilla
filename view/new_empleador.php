<?php
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//

require_once('../util/funciones.php');
require_once('../dao/AbstractDao.php');

//INITIO combos
require_once('../dao/ComboDao.php');
require_once('../controller/ComboController.php');
//FINALL combos
require_once('../dao/ComboCategoriaDao.php');
require_once('../controller/ComboCategoriaController.php');

/*
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
*/
//---------
//COMBO Empleador
// COMBO 01
$cbo_tipo_sociedad_comercial = comboTipoSociedadComercial();

// COMBO 02
$cbo_tipo_empleador = comboTipoEmpleador();

//COMBO 03
$cbo_tipo_actividad = comboTipoActividad();

// COMBO 04
$cbo_telefono_codigo_nacional = comboTelefonoCodigoNacional();

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
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//$('#form_new_personal').ajaxForm( { beforeSubmit: validate } ); 
        $("#form_new_empleador").validate({
            rules: {
                cbo_tipo_empleador: {
                    required: true                    
                },
                txt_ruc: {
                    required: true				  
                },				
                txt_fecha_nacimiento:{
                    required:true					
                },
                txt_razon_social:{
                    required: true                    
                },
                cbo_tipo_sociedad_comercial:{
                    required: true
                },
                txt_nombre_comercial:{
                    required: true
                },                
                cbo_tipo_actividad:{
                    required: true
                },
                txt_correo:{
                    required: true
                },
				cbo_empresa_dedica:{
					required: true	
				},
				rbtn_actividad_riesgo:{
					required:true
				},
				txt_telefono:{					
					required: function(element) {
					return $("#cbo_telefono_codigo_nacional").val() != 0;
					},
					rangelength: [7, 15],				
				}								
				
				
            },
			submitHandler: function(data) { 
			
			//-----------------------------------------------------------------------				
				var from_data =  $("#form_new_empleador").serialize();
				$.getJSON('sunat_planilla/controller/EmpleadorController.php?'+from_data,
					function(data){
						if(data){
							alert ('Se Guardo Correctamente.');
							cargar_pagina('sunat_planilla/view/view_empleador.php','#CapaContenedorFormulario');
						}else{
							alert("El ruc:"+$("#txt_ruc").val()+"Ya se encuentra registrado!\n no se puede registrar nuevamente");
						}
					}); 
			//-----------------------------------------------------------------------
	   		
			}//endsubmitHandler			
			
        });
		
		
	/*	
		//------
		$('#txt_ruc').focusout(function() {
				//alert ("ccc");
				var ruc 	= $("#txt_ruc").val();
				if( ruc!= ""){
				
					$.getJSON('sunat_planilla/controller/EmpleadorController.php?oper=04&txt_ruc='+ruc,
						function(data){   //VARIBALE rpta DEFINIDA						
						if(data == true){
							$("#error_ruc").text("RUC Ya se encuentra Registrado");
							$("#btn_grabar").attr('disabled',true);
							
						}else{
							$("#error_ruc").text("");
							$("#btn_grabar").removeAttr("disabled")
						}
											
					});
			
				}
					
		});//END
		
*/


    //-------------------------------------------------------------
    });

    //-------------------------------------------------------------



</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Nuevo Empleador</a></li>			

        </ul>
        <div id="tabs-1">
          <form action="sunat_planilla/controller/EmpleadorController.php" method="POST" name="form_new_empleador" id="form_new_empleador" >
                
              <input name="oper" type="hidden" value="add">
            <fieldset>
              <legend>Datos de Identificacion</legend>
              <div>
              <label>Estado Empleador:</label>
              <input type="radio" name="rbtn_titular" id="rbtn_estado_empleador" value="titular" disabled="disabled" />
              <label for="rbtn_estado_empleador"></label>
              Titular 
              <input name="rbtn_titular" type="radio" id="radio" value="no-titular" checked="checked"  disabled="disabled"/>
              <label for="rbtn_titular"></label>
              No-Titular
              </div>
              
              
<div class="fila_input"  style="clear:both">
<label>Tipo de Empleador: </label>

                      <select name="cbo_tipo_empleador" id="cbo_tipo_empleador" >
                          <option value="">-</option>
<?php
foreach ($cbo_tipo_empleador as $indice) {
	
	//if ($indice['id_tipo_empleador'] == '01'/*$obj_banco_liqui->getId_banco()*/ ) {
	//$html = '<option value="'. $indice['cod_tipo_documento'] .'"  >' . $indice['descripcion'] . '</option>';
	//} else {
		$html = '<option value="'. $indice['id_tipo_empleador'] .'" >' . $indice['descripcion'] . '</option>';
	//}
	echo $html;
}
?>


                      </select>
            </div>
              <div  class="fila_input"><label>RUC:</label>
<input name="txt_ruc" type="text"  id="txt_ruc" size="11" maxlength="11">
              </div>
                    <div class="fila_input"><label>Razon Social:</label>
                      <input name="txt_razon_social" type="text"  id="txt_razon_social">
                      Tipo de Sociedad
                      <label for="cbo_tipo_sociedad_comercial"></label>
                      <select name="cbo_tipo_sociedad_comercial" id="cbo_tipo_sociedad_comercial">
<?php
foreach ($cbo_tipo_sociedad_comercial as $indice) {
	
	if ($indice['id_tipo_sociedad_comercial'] == '01'/*$obj_banco_liqui->getId_banco()*/ ) {
		
		$html = '<option value="'. $indice['id_tipo_sociedad_comercial'] .'"  >' . $indice['descripcion_abreviada'] . '</option>';
	} else {
		$html = '<option value="'. $indice['id_tipo_sociedad_comercial'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
}
?>
                      
                      
                      </select>
                  </div>
<div class="fila_input">
                        <label>Nombre Comercial:</label>
                        <label for="txt_nombre_comercial"></label>
        <input type="text" name="txt_nombre_comercial" id="txt_nombre_comercial" />
</div>
                    <div class="fila_input">
                        <label>Tipo de Actividad:</label>
                      <label for="cbo_tipo_actividad"></label>
                      <label for="txt_cod_tipo_actividad"></label>
                        <input name="txt_cod_tipo_actividad" type="text" id="txt_cod_tipo_actividad" size="12" />
                        <select name="cbo_tipo_actividad" id="cbo_tipo_actividad" onchange="seleccionarTipoActividadInputPorCombo(this)">
<?php
foreach ($cbo_tipo_actividad as $indice) {
	
	if ($indice['cod_tipo_actividad'] == '01'/*$obj_banco_liqui->getId_banco()*/ ) {
		
		//$html = '<option value="'. $indice['cod_tipo_documento'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_tipo_actividad'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>
                        
                        
                        </select>
              </div>
              
                <label>Teléfono( código y número ): </label>
                <select name="cbo_telefono_codigo_nacional"  id="cbo_telefono_codigo_nacional">
                  <?php
foreach ($cbo_telefono_codigo_nacional as $indice) {
	
	if ($indice['cod_telefono_codigo_nacional'] == 0/* $obj_banco_liqui->getId_banco()*/ ) {
		
		$html = '<option value="'.$indice['cod_telefono_codigo_nacional'].'"  >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_telefono_codigo_nacional'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>
                </select>
                
                <input name="txt_telefono" type="text"  id="txt_telefono" />
              
              
<div style="clear:both"></div>
<div class="fila_input">
                        <label>Correo:</label>
                <input name="txt_correo" type="text" id="txt_correo">
            </div>
            <div class="fila_input">
              <label>Empresa se Dedica:</label>
              <label for="cbo_empresa_dedica"></label>
              <select name="cbo_empresa_dedica" id="cbo_empresa_dedica">
              	<option value="0">-</option>
                <option value="NINGUNA">NINGUNA</option>
                <option value="INTERMEDIACION">INTERMEDIACION</option>
                <option value="TERCERIZACION">TERCERIZACION</option>
              </select>
            </div>
            <div class="fila_input" >
              <label>Actividad de Riesgo</label>
              :
              <input name="rbtn_actividad_riesgo" type="radio" value="1"/>			  
              Si
  <input name="rbtn_actividad_riesgo" type="radio" value="0" />
              No</div>
            </fieldset>

<p></p>

				
				
				
				
				<!-- DIRECCION 2-->
				<input name="btn_grabar" type="submit" id="btn_grabar" value="Grabar"  >

          </form>

          <!--
	FORMULARIO DIRECCION 2
-->
      </div>
    </div>
</div>

<!-- -->

<!-- -->

<div id="dialog-form-editarDireccion" title="Editar Direccion">
    <div id="editarPersonaDireccion" align="left"></div>
</div>

