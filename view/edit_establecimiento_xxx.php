<?php
//session_start();
//*******************************************************************//
//require_once('ide.php');
//*******************************************************************//
require_once('../util/funciones.php');
require_once('../dao/AbstractDao.php');
// llenar combos
require_once('../dao/ComboDao.php');
require_once('../controller/ComboController.php');
require_once('../dao/EstablecimientoDao.php');


//:::UBIGEO_RENIEC
require_once('../dao/UbigeoReniecDao.php');
require_once('../controller/UbigeoReniecController.php');

//BUSCAR ESTABLECIMIENTO
require_once('../model/Establecimiento.php');
require_once('../dao/EstablecimientoDao.php');
require_once('../controller/EstablecimientoController.php'); // NO TIENE includes en FILE

//BUSCAR ESTABLECIMIENTO DIRECCION
require_once('../model/Direccion.php');
require_once('../model/EstablecimientoDireccion.php');
require_once('../dao/EstablecimientoDireccionDao.php');
require_once('../controller/EstablecimientoDireccionController.php'); // NO TIENE includes FILE


//:::UBIGEO_RENIEC
require_once('../dao/UbigeoReniecDao.php');
require_once('../controller/UbigeoReniecController.php');


$ID_ESTABLECIMIENTO = $_REQUEST['id_establecimiento'];

//-------------------------------------------------------------------------
//-------------------------------------------------------------------------
// --- (00) BUSCAR Principal Establecimiento
$obj_establecimiento = new Establecimiento();	
$obj_establecimiento =  buscarEstablecimientoPorId($ID_ESTABLECIMIENTO);

echo "<pre>";
//print_r($obj_establecimiento);
echo "</pre>";
//ECHO "<HR>";
// --- (01) BUSQUEDA 
$obj_establecimiento_direccion = new EstablecimientoDireccion();	
$obj_establecimiento_direccion =  buscarEstablecimientoDireccionPorId($ID_ESTABLECIMIENTO);




// COMBO 01
$cbo_tipo_establecimiento = comboEstablecimiento($ID_EMPLEADOR,true);

//-----UBIGEO_RENIEC
$COD_UBIGEO_RENIEC = $obj_establecimiento_direccion->getCod_ubigeo_reinec();

//---- (02) Datos tabla Ubigeo base = (Departamento,Provincia, Distrito)
$data_ubigeo = buscarUbigeoReniecPorId($COD_UBIGEO_RENIEC);

//---- (03)
//---- (03)
// COMBO 01
$cbo_departamento = comboUbigeoDepartamentos();
// COMBO 02
$cbo_provincia = comboUbigeoProvincias($data_ubigeo['cod_departamento']); /*$data_ubigeo['cod_provincia']*/
// COMBO 03
$cbo_distrito = comboUbigeoReniec($data_ubigeo['cod_provincia']);/*$data_ubigeo['cod_ubigeo_reniec']*/

echo "<pre>";
//print_r($obj_establecimiento_direccion);
echo "</pre>";

?>


<script>
    //INICIO HISTORIAL
    $(document).ready(function(){
						   
        //demoApp = new Historial();                  
        $( "#tabs").tabs();
//$( "#accordion_trabajador" ).accordion();
//$("#form_direccion").validate();
	$("#form_edit_establecimiento").validate({
            rules: {
				cbo_tipo_establecimiento:{
					required : true
				},
                cbo_departamento: {
                    required: true,
					min: 1                    
				},
                cbo_departamento: {
                    required: true,
					min: 1                    
                },
				txt_cod_establecimiento:{
					required: true,
					 number: true
				}
            },
			submitHandler: function(data) { 
				//alert("submitHandler true");				
			//-----------------------------------------------------------------------				
				var from_data =  $("#form_edit_establecimiento").serialize();
				$.getJSON('sunat_planilla/controller/EstablecimientoController.php?'+from_data,
					function(data){
					//funcion.js index.php
					disableForm('form_edit_establecimiento');	
						if(data){						//document.getElementById('id_persona').value = data.id_persona;

						//ID = data.id_persona;
						alert("Se Guardaron los datos correctamente "+data);
						
						cargar_pagina('sunat_planilla/view/view_establecimiento.php?id_empleador=<?php echo $obj_establecimiento->getId_empleador();?>','#CapaContenedorFormulario')					

						}else{
							alert("Ocurrio un error, intente nuevamente no hay datos JSON");
						}
					}); 
			//-----------------------------------------------------------------------			
			//alert("final");			   		
			}	
		
        });//ENDVALIDATION



    //-------------------------------------------------------------
    });

    //******************************************************************************
	// SCRIPT DETALLE_DIRECCION.PHP	 INICIO
   //******************************************************************************
   
   
   
   
   

function LimpiarCombo(combo){ 
console.log("INICIO LIMPIAR");

$('#MiSelect').find('option').remove().end();
	//console.log(combo.options[1].text);
	//console.dir(combo);
	var counteo = combo.length;
	alert(counteo);	
	for(i=0; i<counteo; i++){
	console.log('entro -> '+i);
	//console.log(combo.options[i].value)
	console.log(combo.options[i]);
	combo.options[i] = null;
	//console.log("eliminado; = "+combo.options[i].value);
	}
	
	console.log("FIN LIMPIAR");
}

function LlenarCombo(json, combo){ //console.log(json);
	combo.options[0] = new Option('-', '');
	for(var i=0;i<(json.length);i++){
		combo.options[i+1] = new Option(json[i].descripcion, json[i].cod_provincia);
	}
}

function SeleccionandoCombo_1(cbo_depa, cbo_provin){
	cbo_provin = document.getElementById(cbo_provin); //con jquery: $("#"+cbo_provin)[0];
	
	
	if(cbo_depa.options[cbo_depa.selectedIndex].value >=1){
	
	//alert('entro a funcion LimpiarCombo');
	$('#cbo_provincia').find('option').remove().end();
	$('#cbo_distrito').find('option').remove().end();
	//LimpiarCombo(cbo_provin); //console.log(145);
		//cbo_depa.disabled = true;
		//cbo_provin.disabled = true;
		//$("#cbo_distrito").attr('disabled',true);
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'sunat_planilla/controller/ComboController.php',
			data: {id_departamento: cbo_depa.options[cbo_depa.selectedIndex].value, oper: 'listar_provincias'},
			success: function(json){
				LlenarCombo(json, cbo_provin);
				$("#cbo_provincia").removeAttr('disabled');
				$("#cbo_distrito").removeAttr('disabled');
				
			}
		});
	}else if(cbo_depa.options[cbo_depa.selectedIndex].value == ""){		
		//$("#cbo_provincia").attr('disabled',true);
		$("#cbo_provincia").attr('disabled',true);
		$("#cbo_distrito").attr('disabled',true);
	}//endif
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
	
	//LimpiarCombo(cbo_distrito); 
	
	if(cbo_depa.options[cbo_depa.selectedIndex].value >=1){
	$('#cbo_distrito').find('option').remove().end();
		//cbo_depa.disabled = true;
		//cbo_distrito.disabled = true;
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'sunat_planilla/controller/ComboController.php',
			data: {id_provincia: cbo_depa.options[cbo_depa.selectedIndex].value, oper: 'listar_distritos'},
			success: function(json){
				LlenarCombo_2(json, cbo_distrito);
				$("#cbo_distrito").removeAttr('disabled');
			}
		});
	}else if (cbo_depa.options[cbo_depa.selectedIndex].value==''){
		$("#cbo_distrito").attr('disabled',true);
		//$("#cbo_distrito").removeAttr('disabled');
	}
}


    //******************************************************************************
	// SCRIPT DETALLE_DIRECCION.PHP	 FINAL
   //******************************************************************************
</script>
<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Nuevo Establecimiento</a></li>			

        </ul>
        <div id="tabs-1">
          <form action="" method="post" name="form_edit_establecimiento" id="form_edit_establecimiento" novalidate="novalidate">
            <fieldset>
                    <legend>Datos de Identificacion </legend>
<div class="ocultar">
 oper
 <input name="oper" type="text" value="edit" />
 <br />
 id_empleador
  <input name="id_empleador" type="text" 
					value="<?php echo $obj_establecimiento->getId_empleador(); ?>">
  <br />

                    
                    id_establecimiento
					<input name="id_establecimiento" type="text" 
					value="<?php echo $obj_establecimiento->getId_establecimiento(); ?>">
  </div>                   
                    
                    <div class="fila_input">
                      <label>Codigo Establecimiento</label>
                      <input name="txt_cod_establecimiento" type="text" id="txt_cod_establecimiento" 
					value="<?php echo $obj_establecimiento->getCod_establecimiento();?>"  />
                      <span style="color:#09F;" id="txt_cod_establecimiento_error">d</span> Ejem: 0000,0001,0003</div>
                    <div class="fila_input" >
                    <label>Tipo de Establecimiento: </label>

                        <select name="cbo_tipo_establecimiento" id="cbo_tipo_establecimiento" >
                            <option value="">-</option>
<?php
foreach ($cbo_tipo_establecimiento as $indice) {
	
	if ($indice['id_tipo_establecimiento'] == $obj_establecimiento->getId_tipo_establecimiento()) {
		
		$html = '<option value="'. $indice['id_tipo_establecimiento'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['id_tipo_establecimiento'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>


                      </select>


                        cbo</div>
                <div class="fila_input ocultar">
                    <label>Actividad de Riesgo </label>
                    <input name="rbtn_actividad_riesgo" type="radio" value="radiobutton" />
                    Si
                    <input name="rbtn_actividad_riesgo" type="radio" value="radiobutton" />
                    No
                    </div>
            </fieldset>
            <fieldset id="">
            <legend>Direccion</legend>
            <div style=" background-color:#FFFFCC; display:block; " id="direccion_1">
              <?php
	
		//require_once('dao/AbstractDao.php');
		//require_once('dao/PersonaDao.php');
		//require_once('controller/PersonaController.php');
				
		// combo 01
		//$cboDepartamentos = comboUbigeoDepartamentos(); 
		//require_once 'modal/detalle_direccion.php';
		
		
		//combo 02 cod_via
		
		$cboVias = comboVias(); 
		
		$cboZonas = comboZonas();

						
						 ?>
          <table width="708" height="218" border="1">
                    <tr>
                      <td width="140"> Departamento
                      <input name="id_persona_direccion" type="hidden" id="id_persona_direccion"  value="<?php echo $_REQUEST['id_persona_direccion'];?>"/></td>
                      <td width="167"> Provincia </td>
                      <td width="54"> Distrito </td>
                      <td width="35">&nbsp;</td>
                      <td width="48">&nbsp;</td>
                      <td width="59">&nbsp;</td>
                      <td width="32">&nbsp;</td>
                      <td width="30">&nbsp;</td>
                      <td width="40">&nbsp;</td>
                      <td width="39">&nbsp;</td>
                    </tr>
                    <tr>
                      <?php //echo "<pre>".print_r($cboDepartamentos)."</pre"?>
                      <td><select name="cbo_departamento" id="cbo_departamento" onchange="SeleccionandoCombo_1(this, 'cbo_provincia');" 
	style="width:140px;" class="required">
                        <option value="">-</option>
                          <?php
foreach ($cbo_departamento as $indice) { 
	if ($indice['cod_departamento'] == $data_ubigeo['cod_departamento']  ) {
		$html = '<option value="'. $indice['cod_departamento'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_departamento'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>
                        </select>
                      </td>
                      <td><select name="cbo_provincia" id="cbo_provincia" onchange="SeleccionandoCombo_2(this, 'cbo_distrito');"
	style="width:150px;" >
                          <?php
foreach ($cbo_provincia as $indice) { 
	if ($indice['cod_provincia'] == $data_ubigeo['cod_provincia']  ) {
		$html = '<option value="'. $indice['cod_provincia'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_provincia'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>
                      </select></td>
                      <td colspan="4"><select name="cbo_distrito" id="cbo_distrito" style="width:150px;">
                          <?php
foreach ($cbo_distrito as $indice) { 
	if ($indice['cod_ubigeo_reniec'] == $data_ubigeo['cod_ubigeo_reniec']  ) {
		$html = '<option value="'. $indice['cod_ubigeo_reniec'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_ubigeo_reniec'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>
                      </select></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td> Tipo de v&iacute;a </td>
                      <td> Nombre de v&iacute;a </td>
                      <td> N&uacute;mero </td>
                      <td> Dpto </td>
                      <td> Interior </td>
                      <td> Manzana </td>
                      <td> Lote </td>
                      <td> Km </td>
                      <td> Block </td>
                      <td> Etapa </td>
                    </tr>
                    <tr>
                      <td><select name="cbo_tipo_via" id="cbo_tipo_via" style="width:120px;">
                          <?php
	
foreach ($cboVias as $indice) {
	
	if ($indice['cod_via'] == $obj_establecimiento_direccion->getCod_via() ) {		
		$html = '<option value="'. $indice['cod_via'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_via'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
	?>
                      </select></td>
                      <td><input name="txt_nombre_via" type="text" id="txt_nombre_via" size="20"  value="<?php echo $obj_establecimiento_direccion->getNombre_via(); ?>" /></td>
                      <td><input name="txt_numero_via" type="text" id="txt_numero_via" size="5" value="<?php echo $obj_establecimiento_direccion->getNumero_via(); ?>"/></td>
                      <td><input name="txt_dpto" type="text" id="txt_dpto" size="5" value="<?php echo $obj_establecimiento_direccion->getDepartamento(); ?>"/></td>
                      <td><input name="txt_interior" type="text" id="txt_interior" size="5" value="<?php echo $obj_establecimiento_direccion->getInterior(); ?>" /></td>
                      <td><input name="txt_manzana" type="text" id="txt_manzana" size="5" value="<?php echo $obj_establecimiento_direccion->getManzanza(); ?>" /></td>
                      <td><input name="txt_lote" type="text" id="txt_lote" size="5" value="<?php echo $obj_establecimiento_direccion->getLote(); ?>" /></td>
                      <td><input name="txt_kilometro" type="text" id="txt_kilometro" size="5" value="<?php echo $obj_establecimiento_direccion->getKilometro(); ?>" /></td>
                      <td><input name="txt_block" type="text" id="txt_block" size="5" value="<?php echo $obj_establecimiento_direccion->getBlock(); ?>" /></td>
                      <td><input name="txt_etapa" type="text" id="txt_etapa" size="5" value="<?php echo $obj_establecimiento_direccion->getEstapa(); ?>" /></td>
                    </tr>
                    <tr>
                      <td> Tipo de zona </td>
                      <td> Nombre de zona </td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><select name="cbo_tipo_zona" id="cbo_tipo_zona" style="width:120px;">
                          <?php
	
foreach ($cboZonas as  $indice) {
	
	if ($indice['cod_zona'] == $obj_establecimiento_direccion->getCod_zona() ) {		
		$html = '<option value="'. $indice['cod_zona'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_zona'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
	?>
                      </select></td>
                      <td><input name="txt_zona" type="text" id="txt_zona" size="20" value="<?php echo $obj_establecimiento_direccion->getNombre_zona(); ?>"/></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td> Referencia </td>
                      <td colspan="6"><input name="txt_referencia" type="text" id="txt_referencia" size="50" 
	value="<?php echo $obj_establecimiento_direccion->getReferencia();?>" /></td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                </div>
                </fieldset>

            <!-- DIRECCION 2-->
				<br />
				<input name="btn_grabar" type="submit" id="btn_grabar" value="Retornar"
                onclick="javascript:cargar_pagina('sunat_planilla/view/view_establecimiento.php?id_empleador=<?php echo $_REQUEST['id_empleador']; ?>','#CapaContenedorFormulario')"
                >

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

