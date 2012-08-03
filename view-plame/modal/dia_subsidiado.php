<?php
require_once '../../dao/AbstractDao.php';
require_once '../../dao/ComboCategoriaDao.php';
require_once '../../controller/ComboCategoriaController.php';

$suspencion1 = comboSuspensionLaboral_1();

//$data = comboSuspensionLaboral_2();

echo "<pre>";
//print_r($suspencion1);
echo "</pre>";
?>

<script type="text/javascript">
//var objCombo = document.getElementById('cbo_tipo_suspension-1');


function cargarSuspension_1(objCombo){
//var objCombo = document.getElementById('cbo_ds_tipo_suspension-1');	
	//01
	var test = new Array(
	<?php $counteo = count($suspencion1); 
	for($i=0;$i<$counteo;$i++): ?>	
	<?php
		if($i == $counteo-1){ 
			echo "{id:'".$suspencion1[$i]['cod_tipo_suspen_relacion_laboral']."', descripcion:'".$suspencion1[$i]['cod_tipo_suspen_relacion_laboral']."-".$suspencion1[$i]['descripcion_abreviada']."' }"; 
		}else{
			echo "{id:'".$suspencion1[$i]['cod_tipo_suspen_relacion_laboral']."', descripcion:'".$suspencion1[$i]['cod_tipo_suspen_relacion_laboral']."-".$suspencion1[$i]['descripcion_abreviada']."' },"; 
		}
	?>
	<?php endfor; ?>
	);//end array

	
	var counteo = 	test.length;		
	objCombo.options[0] = new Option('-', '');
	for(var i=0;i<counteo;i++){
		objCombo.options[i+1] = new Option(test[i].descripcion, test[i].id);
	}


	
	
}//ENDFN


//--------------------------------------------------------------------------------


function nuevoDiaSubsidiado(){
	//alert("holaaaaa");
	
	//Capa Contenedora
	var tabla = document.getElementById("tb_dsubsidiado");	
	
	var num_fila_tabla = contarTablaFila(tabla);
	
	num_fila_tabla = num_fila_tabla - 1;
	
	var COD_DETALLE_CONCEPTO =  num_fila_tabla + 1;
	
	//var id_check = idCheckConcepto1000EM( COD_DETALLE_CONCEPTO );

	//INICIO div
	var div = document.createElement('tr');
	//div.setAttribute('id','establecimiento-'+id);
	//
	tabla.appendChild(div); //PRINCIPAL	
	

	
	

//-------------------------------------------------------------
//---- CODIGO
var id = '<input type="hidden" size="4" id="pdia_subsidiado-'+ COD_DETALLE_CONCEPTO +'" name="pdia_subsidiado[]" value ="" >';
var estado = '<input type="hidden" size="4" id="estado-'+ COD_DETALLE_CONCEPTO +'" name="estado[]" value ="0" >';
//var codigo = '<input type="text" size="4" id="cod_detalle_concepto_" name="cod_detalle_concepto[]" value = '+ COD_DETALLE_CONCEPTO +'>';

var input_cdia = '<input name="ds_cantidad_dia[]" type="text" id="ds_cantidad_dia-'+COD_DETALLE_CONCEPTO+'" size="7" />';
//-----input Descripcion
var span1;
var span2;


var finSpan = '</span>';

span1 = '<span title="editar">';
span1 +="<a href=\"javascript:editar_ds('"+COD_DETALLE_CONCEPTO+"')\"><img src=\"images/edit.png\"></a>";
span1 += finSpan;


span2 = '<span title="editar">';
span2 +="<a href=\"javascript:eliminar_ds('"+COD_DETALLE_CONCEPTO+"')\"><img src=\"images/cancelar.png\"></a>";
span2 += finSpan;


var combo = "";
combo +='     <select name="cbo_ds_tipo_suspension[]" id="cbo_ds_tipo_suspension-'+COD_DETALLE_CONCEPTO+'"  ';
combo +='	  style="width:150px;"  onchange="" >';
combo +='     </select>';


	//inicio html	
var html ='';
var cerrarTD = '<\/td>';


html +='<td>';
html += id;
html += estado;
html += combo;
html += cerrarTD;


html +='<td>';	
html += input_cdia;
html += cerrarTD;

html +='<td>';	
html += span1;
html += cerrarTD;

html +='<td>';	
html += span2;
html += cerrarTD;


////---
div.innerHTML=html;

//-------   - - --  -cargar combo
cbo = document.getElementById('cbo_ds_tipo_suspension-'+COD_DETALLE_CONCEPTO);
//alert()
//console.dir(cbo);
cargarSuspension_1(cbo);	
	
//	console.dir(tabla);
//	console.log("-----------------");
//	console.dir(tabla.rows[0]);
//	console.log("-----------------");

}




</script>
<div class="tb" style="width:450px;" >
<table width="450" border="1" id="tb_dsubsidiado">
  <tr>
    <td width="217">tipo desuspens&oacute;n</td>
    <td width="81">cantidad de dias</td>
    <td width="88">Modificar</td>
    <td width="74">Eliminar</td>
  </tr>
  <!--
  <tr>
    <td>          
      <input name="pdia_subsidiado[]" type="text" id="pdia_subsidiado" size="1" />
      <select name="cbo_ds_tipo_suspension[]" id="cbo_ds_tipo_suspension-1" style="width:180px;">
      </select>      
      <input name="estado" type="text" id="estado" size="1" />
    </td>
    <td>
    <input name="ds_cantidad_dia[]" type="text" id="ds_cantidad_dia" size="7" /></td>
    <td>
    
    <span title="editar">
        <a href="javascript:editar_ds('')"><img src="images/edit.png"></a>
    </span>	
    </td>
    <td>
    <span title="Cancelar">
        <a href="javascript:eliminar_ds('')"><img src="images/cancelar.png"></a>
    </span>
    </td>
  </tr>
  -->
</table>
</div>
<br>
<div style="width:150px; margin:0 0 0 174px;">
<label for="ds_total">TOTAL</label>
<input name="ds_total" type="text" id="ds_total" size="7">
</div>
<p>
  <input type="button" name="btnGrabar" id="btnGrabar" value="Grabar" />
  <input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onclick="nuevoDiaSubsidiado()" />
</p>
