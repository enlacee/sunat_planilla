<?php
//session_start();

require_once('../../util/funciones.php');
require_once('../../dao/AbstractDao.php');
//require_once('../../dao/PersonaDao.php');
//require_once('../../controller/PersonaController.php');

require_once('../../dao/ComboDao.php');
require_once('../../controller/ComboController.php');


//--Herencia Direccion
require_once '../../model/Direccion.php';
require_once '../../model/PersonaDireccion.php';
require_once('../../dao/PersonaDireccionDao.php');

//:::PERSONA_DIRECCION
require_once('../../controller/PersonaDireccionController.php');

//:::UBIGEO_RENIEC
require_once('../../dao/UbigeoReniecDao.php');
require_once('../../controller/UbigeoReniecController.php');

//OBJ PERSONA
$ID_PERSONA_DIRECCION = $_REQUEST['id_persona_direccion'];

// --- (01) BUSCAR Persona Direccion
$obj_persona_direccion = new PersonaDireccion();

$obj_persona_direccion = buscarPersonaDireccionPorId($ID_PERSONA_DIRECCION);
$COD_UBIGEO_RENIEC = $obj_persona_direccion->getCod_ubigeo_reinec();

//---- (02) Datos tabla Ubigeo base = (Departamento,Provincia, Distrito)
$data_ubigeo = buscarUbigeoReniecPorId($COD_UBIGEO_RENIEC);

//---- (03)
// COMBO 01
$cbo_departamento = comboUbigeoDepartamentos();
// COMBO 02
$cbo_provincia = comboUbigeoProvincias($data_ubigeo['cod_departamento']); /*$data_ubigeo['cod_provincia']*/
// COMBO 03
$cbo_distrito = comboUbigeoReniec($data_ubigeo['cod_provincia']);/*$data_ubigeo['cod_ubigeo_reniec']*/



//var_dump($cbo_telefono_codigo_nacional);

/**
 * Cargando Combo Box
 */
?>
<script>

                
    //INICIO HISTORIAL
    $(document).ready(function(){
						   
        //demoApp = new Historial();
                  
        $( "#tabs").tabs();
        //$( "#accordion_trabajador" ).accordion();

//$("#form_direccion").validate();
	$("#form_direccion").validate({
            rules: {
                cbo_departamento: {
                    required: true                    
                },
                cbo_provincia: {
                    required: true				  
                },				
                cbo_distrito:{
                    required:true					
                },
                cbo_tipo_via:{
                    required: true
                },
                cbo_tipo_zona:{
                    required: true
                }								
				
            }
        });

    //-------------------------------------------------------------
    });
				
    //-------------------------------------------------------------

    //------------------------------------------------------------------


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
	combo.options[0] = new Option('-', '0');
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
function actualizarRadioCentroEssalud(obj){
	document.getElementById("txt_radio_essalud").value=obj.value;
} 

</script>

<div style=" border:2px solid #009; background-color:#FF9; display:block; " id="direccion_1">
<?php
	
		//require_once('dao/AbstractDao.php');
		//require_once('dao/PersonaDao.php');
		//require_once('controller/PersonaController.php');
				
		// combo 01
		$cboDepartamentos = comboUbigeoDepartamentos(); 
		//require_once 'modal/detalle_direccion.php';
		
		
		//combo 02 cod_via
		
		$cboVias = comboVias(); 
		
		$cboZonas = comboZonas();

/*echo "<pre>";
print_r($obj_persona_direccion);
echo "</pre>";*/
						
						 ?>
<form action="" method="get" name="form_direccion" id="form_direccion">
<table width="500" height="218" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="160"> Departamento 
      <input name="id_persona_direccion" type="text" id="id_persona_direccion"  value="<?php echo $_REQUEST['id_persona_direccion'];?>"/>
      <input name="id_persona" id="id_persona" type="text" value=" <?php echo $obj_persona_direccion->getId_persona(); ?>" />
      <input type="text" name="txt_estado_direccion" id="txt_estado_direccion"  
      value="<?php  echo $obj_persona_direccion->getEstado_direccion(); ?>"/></td>
    <td width="150"> Provincia </td>
    <td width="54"> Distrito </td>
    <td width="35">&nbsp;</td>
    <td width="48">&nbsp;</td>
    <td width="59">&nbsp;</td>
    <td width="32">&nbsp;</td>
    <td width="30">&nbsp;</td>
    <td width="40">&nbsp;</td>
    <td width="101">&nbsp;</td>
  </tr>
  <tr>
  <?php //echo "<pre>".print_r($cboDepartamentos)."</pre"?>
    <td><select name="cbo_departamento" id="cbo_departamento" onchange="SeleccionandoCombo_1(this, 'cbo_provincia')" 
	style="width:140px;" class="required">
    <!--<option value="">-</option>-->
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
	
      </select>    </td>
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
	
	if ($indice['cod_via'] == $obj_persona_direccion->getCod_via() ) {		
		$html = '<option value="'. $indice['cod_via'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_via'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
	?>
	
        </select></td>
    <td><input name="txt_nombre_via" type="text" id="txt_nombre_via" size="20"  value="<?php echo $obj_persona_direccion->getNombre_via(); ?>" /></td>
    <td><input name="txt_numero_via" type="text" id="txt_numero_via" size="5" value="<?php echo $obj_persona_direccion->getNumero_via(); ?>"/></td>
    <td><input name="txt_dpto" type="text" id="txt_dpto" size="5" value="<?php echo $obj_persona_direccion->getDepartamento(); ?>"/></td>
    <td><input name="txt_interior" type="text" id="txt_interior" size="5" value="<?php echo $obj_persona_direccion->getInterior(); ?>" /></td>
    <td><input name="txt_manzana" type="text" id="txt_manzana" size="5" value="<?php echo $obj_persona_direccion->getManzanza(); ?>" /></td>
    <td><input name="txt_lote" type="text" id="txt_lote" size="5" value="<?php echo $obj_persona_direccion->getLote(); ?>" /></td>
    <td><input name="txt_kilometro" type="text" id="txt_kilometro" size="5" value="<?php echo $obj_persona_direccion->getKilometro(); ?>" /></td>
    <td><input name="txt_block" type="text" id="txt_block" size="5" value="<?php echo $obj_persona_direccion->getBlock(); ?>" /></td>
    <td><input name="txt_etapa" type="text" id="txt_etapa" size="5" value="<?php echo $obj_persona_direccion->getEstapa(); ?>" /></td>
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
	
	if ($indice['cod_zona'] == $obj_persona_direccion->getCod_zona() ) {		
		$html = '<option value="'. $indice['cod_zona'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_zona'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
	?>
	
	
        </select></td>
    <td><input name="txt_zona" type="text" id="txt_zona" size="20" value="<?php echo $obj_persona_direccion->getNombre_zona(); ?>"/></td>
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
	value="<?php echo $obj_persona_direccion->getReferencia();?>" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <?php 
//  echo "<pre>";
//  print_r($obj_persona_direccion);
//  echo "</pre>";
  
  
  
  ?>
  
  <tr <?php echo ($obj_persona_direccion->getEstado_direccion() == '1') ? 'class="ocultar"' : ''; ?> >
    <td> Referente para Centro Asistencia EsSalud: </td>
    <td><input name="rbtn_ref_essalud" type="radio" value="1"
    onclick="actualizarRadioCentroEssalud(this)"
	<?php 
	echo ($obj_persona_direccion->getReferente_essalud() == '1') ? ' checked="checked"' :'';
	?>
    />Si
<input name="rbtn_ref_essalud" type="radio" value="0" 
onclick="actualizarRadioCentroEssalud(this);"
		<?php echo ($obj_persona_direccion->getReferente_essalud() == '0') ? ' checked="checked"' : ''; ?> />
    No    
    <input name="txt_radio_essalud" type="text" id="txt_radio_essalud" size="3" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  
</table>
</form>
</div>
