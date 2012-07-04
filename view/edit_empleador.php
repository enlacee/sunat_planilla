<?php
session_start();
//*******************************************************************//
//require_once('ide.php');
//*******************************************************************//

require_once('../util/funciones.php');
require_once('../dao/AbstractDao.php');
//Combo
require_once('../dao/ComboDao.php');
require_once('../controller/ComboController.php');
//Combo Categoria
require_once('../dao/ComboCategoriaDao.php');
require_once('../controller/ComboCategoriaController.php');
//Empleador
require_once('../dao/EmpleadorDao.php');
require_once('../model/Empleador.php');
require_once('../controller/EmpleadorController.php');


//Variables REQUEST
$ID_EMPLEADOR = $_REQUEST['id_empleador'];

$obj_empleador = new Empleador();
$obj_empleador = buscarEmpleadorPorId($ID_EMPLEADOR);

//Variables REQUEST
$ESTADO_EMPLEADOR = $obj_empleador->getEstado_empleador();



// COMBO 02
$cbo_tipo_empleador = comboTipoEmpleador();
//echo "<pre>";
//print_r($obj_empleador);
//echo "</pre>";

//echo $obj_empleador->getCorreo();


// COMBO 01
$cbo_tipo_sociedad_comercial = comboTipoSociedadComercial();


//combo
$cbo_tipo_actividad = comboTipoActividad();

// COMBO 04
$cbo_telefono_codigo_nacional = comboTelefonoCodigoNacional();

//var_dump($cbo_telefono_codigo_nacional);
//echo "<pre>";
//print_r($cbo_telefono_codigo_nacional);
//echo "</pre>";

/**
 * Cargando Combo Box
 */
?>
<style type="text/css"> 
#detalle_sub{
	margin:0 0 0 132px;
}


</style>

<script>
    //INICIO HISTORIAL
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
//$('#form_new_personal').ajaxForm( { beforeSubmit: validate } ); 
        $("#form_edit_empleador").validate({
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
                txt_cod_tipo_actividad:{
                    required: true
                },
                cbo_tipo_actividad:{
                    required: true
                },				
				cbo_telefono_codigo_nacional:{
					required: true	
				},				
                txt_telefono:{
                    required: true	
                },
                txt_correo:{
                    required: true
                },
				cbo_empresa_dedica:{
					required: true	
				}/*,
				rbtn_remype_tipo_empresa:{					
					required:function(element) {
					return "#rbtn_remype:selected";//FUCKfalse ;//$("!#rbtn_remype:selected"); //RPTA = FALSE = REQUERID
					}								
				}	*/			
				
            },
			submitHandler: function(data) {				
			
			//-----------------------------------------------------------------------				
			var from_data =  $("#form_edit_empleador").serialize();
				$.getJSON('sunat_planilla/controller/EmpleadorController.php?'+from_data,
					function(data){
					console.log('entro');
						if(data){
							alert ('Se Guardo Correctamente.');
							cargar_pagina('sunat_planilla/view/view_empleador.php','#CapaContenedorFormulario');
						}else{
							//alert("Ocurrio un error");
						}
					}); 
			//-----------------------------------------------------------------------

			}//ENDsubmitHandler			
			
        });
		
		
		
		//------
		$('#txt_ruc').focusout(function() {
				//alert ("ccc");
				var ruc 	= $("#txt_ruc").val();
				var ruc_db = $("#txt_ruc_a").val();
				//alert (ruc+'  = '+ruc_db);
				
				if( ruc != ruc_db){
				
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
			
				}else{
				
				$("#error_ruc").text("");
				$("#btn_grabar").removeAttr("disabled")
				}
					
		});//END
		



    //-------------------------------------------------------------
    });

    //-------------------------------------------------------------




	function mostrarDestaques(id){
		
		if(id){ //Empleadores a quienes destaco o desplazo personal: 
			cargar_pagina('sunat_planilla/view/view_empleador.php','#CapaContenedorFormulario');	
		}else{
			
		}
	}
	



	function verDetalleActividadRiesgoRadio(obj){ //obj esta Sobrando

		var frm = document.form_edit_empleador;
		
		var bandera = false;
		var counteo = frm.rbtn_remype.length;
			
		for(var i=0;i<counteo;i++){
			if(frm.rbtn_actividad_riesgo[i].value == 1 && frm.rbtn_actividad_riesgo[i].checked){			
				bandera = true;
				//alert ("sss"+frm.rbtn_remype[i].value);
			}
		}//ENDFOR
		
		var remype_true = document.getElementById('detalle_actividad_riesgo');
		//var hijo_tipo_empresa = frm.rbtn_remype_tipo_empresa;
		console.log (bandera);
		if(bandera==true){
			remype_true.style.display='block';
			//hijo_tipo_empresa[0].checked=true;
		}else{
			remype_true.style.display='none';
			//For No Nedd
			//hijo_tipo_empresa[0].checked = null;
			//hijo_tipo_empresa[1].checked = null;
		}

	
	}


	
	function validarRadioRemype(obj){ //obj esta Sobrando
	/**
	* OJO para que el codigo sea reutilizable
	* Pueden Pasar como paramero el Nombre del Formulario si Asi lo quieren.
	* ------------------------------------------------------------------------
	* Formulario Radio Buton tiene 4 alternativas 
	* El proposito es si marca radio.value = 1 << QUE PASE ALGO >>
	* en este caso ocultar y ver div.
	*/
		//Form
		//console.dir(obj);
		var frm = document.form_edit_empleador;
		//console.dir(frm.rbtn_remype);
		//console.log(frm.rbtn_remype);
		//alert(frm.rbtn_remype.length);
		
		var bandera = false;
		var counteo = frm.rbtn_remype.length;
			
		for(var i=0;i<counteo;i++){
			if(frm.rbtn_remype[i].value == 1 && frm.rbtn_remype[i].checked){			
				bandera = true;
				//alert ("sss"+frm.rbtn_remype[i].value);
			}
		}//ENDFOR
		
		var remype_true = document.getElementById('remype-true');
		var hijo_tipo_empresa = frm.rbtn_remype_tipo_empresa;
		
		if(bandera==true){
			remype_true.style.display='block';
			hijo_tipo_empresa[0].checked=true;
		}else{
			remype_true.style.display='none';
			//For No Nedd
			hijo_tipo_empresa[0].checked = null;
			hijo_tipo_empresa[1].checked = null;
		}

	
	}
</script>

<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Editar Empleador</a></li>			

        </ul>
        <div id="tabs-1">
          <form action="sunat_planilla/controller/EmpleadorController.php" method="get" name="form_edit_empleador" id="form_edit_empleador" novalidate="novalidate">
                
              <input name="oper" type="hidden" value="edit">
            <fieldset>
              <legend>Datos de Identificacion </legend>
              <div class="ocular">
              <label>Estado Empleador</label>:
              <label for="txt_estado_empleador"></label>
              <input name="txt_estado_empleador" type="text" id="txt_estado_empleador" 
			  value="<?php  echo $obj_empleador->getEstado_empleador(); ?>" disabled="disabled" />
            </div>
              
              <div style="clear:both">                
                <label>id e<span >mplead</span>or:</label>
                <input type="text" name="id_empleador" id="id_empleador" value="<?php  echo $obj_empleador->getId_empleador(); ?>" readonly="" />
              </div>
              
              
<div  style="clear:both">
<label>Tipo de Empleador: </label>

                      <select name="cbo_tipo_empleador" id="cbo_tipo_empleador" >
                          <option value="">-</option>

<?php
foreach ($cbo_tipo_empleador as $indice) {
	
	if ($indice['id_tipo_empleador'] == $obj_empleador->getId_tipo_empleador() ) {
		
		$html = '<option value="'. $indice['id_tipo_empleador'] .'"  selected="selected" >'  . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['id_tipo_empleador'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>


                      </select>
              </div>
              <div><label>RUC:</label>
<input name="txt_ruc_a" type="hidden" id="txt_ruc_a" value="<?php echo $obj_empleador->getRuc(); ?>"  >
<input name="txt_ruc" type="text" id="txt_ruc" value="<?php echo $obj_empleador->getRuc(); ?>">
<span id="error_ruc"></span>
              </div>
                    <div style="clear:both"><label>Razon Social:</label>
                      <input name="txt_razon_social" type="text"  id="txt_razon_social" 
                      value="<?php echo $obj_empleador->getRazon_social(); ?>">
                      Tipo de Sociedad
                      <label for="cbo_tipo_sociedad_comercial"></label>
                      <select name="cbo_tipo_sociedad_comercial" id="cbo_tipo_sociedad_comercial">
<?php
foreach ($cbo_tipo_sociedad_comercial as $indice) {
	
	if ($indice['id_tipo_sociedad_comercial'] == $obj_empleador->getId_tipo_sociedad_comercial() ) {
		
		$html = '<option value="'. $indice['id_tipo_sociedad_comercial'] .'" selected="selected"  >' . $indice['descripcion_abreviada'] . '</option>';
	} else {
		$html = '<option value="'. $indice['id_tipo_sociedad_comercial'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
}
?>
                      </select>
                  </div>
<div style="clear:both">
                        <label>Nombre Comercial:</label>
                        <label for="txt_nombre_comercial"></label>
        <input type="text" name="txt_nombre_comercial" id="txt_nombre_comercial"
        value="<?php echo $obj_empleador->getNombre_comercial(); ?>" />
</div>
                    <div style="clear:both">
                        <label>Tipo de Actividad:</label>
                        <label for="cbo_tipo_actividad"></label>
                        <select name="cbo_tipo_actividad" id="cbo_tipo_actividad">
<?php
foreach ($cbo_tipo_actividad as $indice) {
	
	if ($indice['cod_tipo_actividad'] == $obj_empleador->getCod_tipo_actividad() ) {
		
		$html = '<option value="'. $indice['cod_tipo_actividad'] .'" selected="selected"  >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_tipo_actividad'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>
                        
                        
                        </select>
              </div>
			  
                        <label>Tel&eacute;fono( c&oacute;digo y n&uacute;mero ): </label>
                        <select name="cbo_telefono_codigo_nacional" >
                            <option value="">-</option>
<?php
foreach ($cbo_telefono_codigo_nacional as $indice) {
	
	if ($indice['cod_telefono_codigo_nacional'] ==  $obj_empleador->getCod_telefono_codigo_nacional()) {
		
		$html = '<option value="'. $indice['cod_telefono_codigo_nacional'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_telefono_codigo_nacional'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>

                        </select><br />			  
			  
<div style="clear:both">
                        <label>Telefono:</label>
                <input name="txt_telefono" type="text"  id="txt_telefono" 
                value="<?php echo $obj_empleador->getTelefono(); ?>">
              </div>
<div style="clear:both">
                        <label>Correo:</label>
                <input name="txt_correo" type="text" id="txt_correo" value="<?php echo $obj_empleador->getCorreo();?>">
              </div>
            <div style="clear:both">
              <label>Empresa se Dedica:</label>
              <label for="cbo_empresa_dedica"></label>
              <select name="cbo_empresa_dedica" id="cbo_empresa_dedica">
              <option value="0" <?php echo($obj_empleador->getEmpresa_dedica() == 0) ? 'selected="selected"' : ''; ?>>-</option>
                <option value="NINGUNA" <?php echo($obj_empleador->getEmpresa_dedica() =='NINGUNA')? 'selected="selected"' : ''; ?> >NINGUNA</option>
                <option value="INTERMEDIACION" <?php echo ($obj_empleador->getEmpresa_dedica() =='INTERMEDIACION') ? 'selected="selected"' : '';?>>INTERMEDIACION</option>
                <option value="TERCERIZACION" <?php echo($obj_empleador->getEmpresa_dedica() =='TERCERIZACION')? 'selected="selected"' : ''; ?>>TERCERIZACION</option>
              </select>
            </div>
            
            
            
            
            
            
            
<?php 
//VER
if($_SESSION['sunat_empleador']['id_empleador'] == $ID_EMPLEADOR){
// ------------- INICIO MOSTRAR FORMULARIO ---------------//
?>
            
            
            
            
            

<div >
      <label>Actividad de Riesgo</label>:
      <input name="rbtn_actividad_riesgo" type="radio" value="1"
      <?php if($obj_empleador->getActividad_riesgo_sctr() == 1){?>checked="checked" <?php } ?> 
      onclick="verDetalleActividadRiesgoRadio(this)"
      />
Si
<input name="rbtn_actividad_riesgo" type="radio" value="0"
<?php if($obj_empleador->getActividad_riesgo_sctr() == 0){?>checked="checked" <?php } ?> 
 onclick="verDetalleActividadRiesgoRadio(this)" />
No
<div  id="detalle_actividad_riesgo" <?php if($obj_empleador->getActividad_riesgo_sctr() != 1):?>class="ocultar" <?php endif; ?> >
<ul class="detalle_sub">
<li>
<a href="javascript:cargar_pagina('sunat_planilla/view/view_empleador_establecimientos.php?id_empleador=<?php echo $obj_empleador->getId_empleador(); ?>','#CapaContenedorFormulario');">Detalle</a>
</li>
</ul>
</div>
<br />
</div>


			
			
            <div class="fila_input">
              <label>Aporta a SENATI:</label>
              <input name="rbtn_senati" type="radio" value="1"  
              <?php if($obj_empleador->getSenati() == 1){?>checked="checked" <?php } ?> />
              Si
              
              
              
              <input name="rbtn_senati" type="radio" value="0" 
               <?php if($obj_empleador->getSenati() == 0){?>checked="checked" <?php } ?> />
              No</div>
              
            <div>
              <label style="clear:both">REMYPE:</label>
              <span class="fila_input">
              <input name="rbtn_remype" id="rbtn_remype" type="radio" value="1" 
              onclick="validarRadioRemype(this)"
               <?php if($obj_empleador->getRemype()== 1){?>checked="checked" <?php } ?>/>
Si
<input name="rbtn_remype" type="radio" value="0"
onclick="validarRadioRemype(this)"
 <?php if($obj_empleador->getRemype() == 0){?>checked="checked" <?php } ?> />
No</span> <br />
<ul id="remype-true"   class="detalle_sub" <?php if($obj_empleador->getRemype() == 0){?>style="display:none" <?php } ?>>
<li><input name="rbtn_remype_tipo_empresa"  id="rbtn_remype_tipo_empresa" type="radio" value="microempresa"
 />
  MicroEmpresa
</li>
<li><input name="rbtn_remype_tipo_empresa" type="radio" value="pequenia-empresa" />
Peque&ntilde;aEmpresa</li>
</ul>




<br />
            </div>
            <div style="clear:both">
              <label>Trabajador sin Regimen Pensionario:</label>
              <label style="clear:both"></label>
              <input name="rbtn_sin_rp" type="radio" value="1"
              <?php if($obj_empleador->getTrabajador_sin_rp() == 1){?>checked="checked" <?php } ?> />
Si
<input name="rbtn_sin_rp" type="radio" value="0"
<?php if($obj_empleador->getTrabajador_sin_rp() == 0){?>checked="checked" <?php } ?>
 />
No </div>
            <div style="clear:both">
              <label>Tiene trabajadores por los que aporta al SCTR?:</label>
              <input name="rbtn_sctr" type="radio" value="1" 
			  <?php if($obj_empleador->getTrabajadores_sctr() == 1){?>checked="checked" <?php } ?> 

			  />
Si
<input name="rbtn_sctr" type="radio" value="0"
<?php if($obj_empleador->getTrabajadores_sctr() == 0){?>checked="checked" <?php } ?>
/>
No</div>
            <div style="clear:both">
              <label>Pesona con Discapacidad</label>:
			  <input name="rbtn_persona_discapacidad" type="radio" value="1" 
<?php if($obj_empleador->getPersona_discapacidad() == 1){?>checked="checked" <?php } ?>
			  />
Si
<input name="rbtn_persona_discapacidad" type="radio" value="0"
<?php if($obj_empleador->getPersona_discapacidad() == 0){?>checked="checked" <?php } ?>
 />
No</div>
            <div style="clear:both">
              <label>Es agencia de Empleo</label><input name="rbtn_agencia_empleo" type="radio" value="1"
<?php if($obj_empleador->getAgencia_empleo() == 1){?>checked="checked" <?php } ?>
			  
			   />
Si
<input name="rbtn_agencia_empleo" type="radio" value="0" 
<?php if($obj_empleador->getAgencia_empleo() == 0){?>checked="checked" <?php } ?>
/>
No</div>
            <div style="clear:both">
              <label>Desplaza Pesonal:</label>
              <input name="rbtn_desplaza_personal" type="radio" value="1"
<?php if($obj_empleador->getDesplaza_personal() == 1){?>checked="checked" <?php } ?>
			   />
Si
<input name="rbtn_desplaza_personal" type="radio" value="0"
<?php if($obj_empleador->getDesplaza_personal() == 0){?>checked="checked" <?php } ?>
 />
No</div>
<div class="fila_input" id="registro_empleadores_1" >

<input name="chk_empleador_dd1" type="checkbox" value="" />
<a href="javascript:cargar_pagina('sunat_planilla/view/view_empleador_dd1.php','#CapaContenedorFormulario');">
detalle SI Registro de Empleadores</a></div>
<div>




</div>



            <div >
              <label>Terceros desplaza Personal a Usted:</label>
              <input name="rbtn_terceros_desplaza_personal" type="radio" value="1" 
<?php if($obj_empleador->getTerceros_desplaza_usted() == 1){?>checked="checked" <?php } ?>

			  />
              Si
  <input name="rbtn_terceros_desplaza_personal" type="radio" value="0"
 <?php if($obj_empleador->getTerceros_desplaza_usted() == 0){?>checked="checked" <?php } ?>
  />
              No</div>
<div class="fila_input" id="registro_empleadores_2" >
<input name="chk_empleador_dd2" type="checkbox" value="" />
<a href="javascript:cargar_pagina('sunat_planilla/view/view_empleador_dd2.php','#CapaContenedorFormulario');">
detalle Registro Empleadores me Destacan SI</a></div>
              
<?php 
// ------------- FINAL MOSTRAR FORMULARIO ---------------//
}
?>

<br/>
            </fieldset>

<p></p>

				
				
				
				
				<!-- DIRECCION 2-->
				<input name="btn_grabar" type="submit" id="btn_grabar" value="Grabar">

            <input type="button" name="btn_retornar" id="btn_retornar" value="Retornar" 
            onclick="javascript:cargar_pagina('sunat_planilla/view/view_empleador.php','#CapaContenedorFormulario')" />
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

