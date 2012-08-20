<?php
require_once('../../dao/AbstractDao.php');
require_once('../../dao/PersonaDao.php');

require_once('../../controller/PersonaController.php');


// combo 01
$cboDepartamentos = comboUbigeoDepartamentos(); 

// combo 01
//$cboProvincias = comboUbigeoProvincias(); 



?>


<style>
label{
	font-weight:900;
}
</style>

<!-- <script type="text/javascript" src="../../../js/jquery.js"></script>-->

<script type="text/javascript">
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
			url: '../../controller/PersonaController.php',
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
			url: '../../controller/PersonaController.php',
			data: {id_provincia: cbo_depa.options[cbo_depa.selectedIndex].value, oper: 'listar_distritos'},
			success: function(json){
				LlenarCombo_2(json, cbo_distrito);
				cbo_depa.disabled = false;
				cbo_distrito.disabled = false;
			}
		});
	}
}



</script>

 </head>

<body>

<h3>Registro de Direccion</h3>
<table width="800" border="1">
  <tr>
    <td> <label>Departamento </label></td>
    <td> <label>Provincia </label></td>
    <td> <label><label>Distrito</label></label> </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><select name="cbo_departamento" id="cbo_departamento" onchange="SeleccionandoCombo_1(this, 'cbo_provincia');">
          <option value="">-</option>
<?php

foreach ($cboDepartamentos as $indice) {
	
	//if ($indice['id_banco'] == $obj_banco_liqui->getId_banco() ) {
		
		//$html = '<option value="'. $indice['cod_tipo_documento'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
	//} else {
		$html = '<option value="'. $indice['cod_departamento'] .'" >' . $indice['descripcion'] . '</option>';
	//}
	echo $html;
}

?>
	
      </select>    </td>
    <td><select name="cbo_provincia" id="cbo_provincia" onchange="SeleccionandoCombo_2(this, 'cbo_distrito');">
          <option>--</option>
<?php
/*
foreach ($cboProvincias  as $indice) {
	
	//if ($indice['id_banco'] == $obj_banco_liqui->getId_banco() ) {
		
		//$html = '<option value="'. $indice['cod_tipo_documento'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
	//} else {
		$html = '<option value="'. $indice['cod_provincia'] .'" >' . $indice['descripcion'] . '</option>';
	//}
	echo $html;
}
*/

?>
 </select></td>
    <td><select name="cbo_distrito" id="cbo_distrito" >
          <option value="">-</option>
		  </select></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td> <label><label>Tipo de v&iacute;a </label></label></td>
    <td> <label>Nombre de v&iacute;a </label></td>
    <td> <label>N&uacute;mero</label> </td>
    <td> <label>Dpto</label> </td>
    <td> <label>Interior</label> </td>
    <td> <label>Manzana</label> </td>
    <td> <label>Lote</label> </td>
    <td> <label>Km</label> </td>
    <td> <label>Block</label> </td>
    <td> <label>Etapa</label> </td>
  </tr>
  <tr>
    <td><select name="cbo_tipo_via" id="cbo_tipo_via">
        </select></td>
    <td><input name="txt_nombre_via" type="text" id="txt_nombre_via" size="20" /></td>
    <td><input name="txt_numero" type="text" id="txt_numero" size="5" /></td>
    <td><input name="txt_dpto" type="text" id="txt_dpto" size="5" /></td>
    <td><input name="txt_interior" type="text" id="txt_interior" size="5" /></td>
    <td><input name="txt_manzana" type="text" id="txt_manzana" size="5" /></td>
    <td><input name="txt_lote" type="text" id="txt_lote" size="5" /></td>
    <td><input name="txt_kilometro" type="text" id="txt_kilometro" size="5" /></td>
    <td><input name="txt_block" type="text" id="txt_block" size="5" /></td>
    <td><input name="txt_etapa" type="text" id="txt_etapa" size="5" /></td>
  </tr>
  <tr>
    <td> <label>Tipo de zona </label></td>
    <td> <label>Nombre de zona </label></td>
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
    <td><select name="cbo_tipo_zona" id="cbo_tipo_zona">
        </select></td>
    <td><input name="txt_zona" type="text" id="txt_zona" size="20" /></td>
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
    <td> <label>Referencia </label></td>
    <td colspan="6"><input name="txt_referencia" type="text" id="txt_referencia" size="50" /></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
    <td> <label>Referente para Centro Asistencia EsSalud:</label> </td>
    <td><input name="rbtn_ref_essalud" type="radio" value="radiobutton" />
      Si
        <input name="rbtn_ref_essalud" type="radio" value="radiobutton" />
    No</td>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
<p>
  <input type="submit" name="Submit" value="Aceptar" />
  <input type="reset" name="Submit2" value="Limpiar" />
  <input type="button" name="Submit3" value="Retornar" />
</p>

</form>
