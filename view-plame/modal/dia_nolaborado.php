<?php
/*
*	 ALERT()::
*	Primero debe Ejecutars Dia Subsidiado 
*	para tener las varibales defaul bajo control	
*
*/


require_once '../../dao/AbstractDao.php';
require_once '../../dao/ComboCategoriaDao.php';
require_once '../../controller/ComboCategoriaController.php';

 
$suspencion2 = comboSuspensionLaboral_2();


//datos
$id_pjoranada_laboral = $_REQUEST['id_pjoranada_laboral'];
$dia_subsidiado = $_REQUEST['dia_subsidiado'];


//$data = comboSuspensionLaboral_2();

echo "<pre>";
//var_dump($suspencion2);
echo "</pre>";
?>


<script type="text/javascript">
alert("num_max_dia = "+ num_max_dia);
	//01
	var test = new Array(
	<?php $counteo = count($suspencion2); 
	for($i=0;$i<$counteo;$i++): ?>	
	<?php
		if($i == $counteo-1){ 
			echo "{id:'".$suspencion2[$i]['cod_tipo_suspen_relacion_laboral']."', descripcion:'".$suspencion2[$i]['cod_tipo_suspen_relacion_laboral']." - ".$suspencion2[$i]['descripcion_abreviada']."' }"; 
		}else{
			echo "{id:'".$suspencion2[$i]['cod_tipo_suspen_relacion_laboral']."', descripcion:'".$suspencion2[$i]['cod_tipo_suspen_relacion_laboral']." - ".$suspencion2[$i]['descripcion_abreviada']."' },"; 
		}
	?>
	<?php endfor; ?>
	);//end array


var dia_subsidiado = <?php echo ($dia_subsidiado) ? $dia_subsidiado : 0;?>;




function cargarSuspension_2(objCombo,ids){

	console.dir(test);
	var z =0;
	//variables
	var arreglo = new Array();
	var eliminados = new Array();
	//alert("ids.length = "+ids.length);

for(i=0; i<ids.length;i++){
	
	for(var j=0;j<test.length;j++){
		
		if( test[j].id == ids[i] ){ //ENCONTRO
			//continue;				
			eliminados = test.splice(j,1);
			console.log(eliminados);
		}	
						
	}//ENDFOR 2
	
	
}//ENDFOR 1


//console.log("********************");
//console.dir(test);
//console.log("********************");

	for(var i=0;i<test.length;i++){
		objCombo.options[i] = new Option(test[i].descripcion, test[i].id);
	}
	
}//ENDFN



function validarNuevoInput_2(){
	
	if(test.length == 1){
		alert("No existe mas Conceptos por agregar");
	}else{
		nuevoDiaSubsidiado_2();
	}

}
//--------------------------------------------------------------------------------


function nuevoDiaSubsidiado_2(){
	//alert("holaaaaa");	
	//Capa Contenedora
	var tabla = document.getElementById("tb_dnolaborado");
	var num_fila_tabla = contarTablaFila(tabla);
	num_fila_tabla = num_fila_tabla - 1;
	var COD_DETALLE_CONCEPTO =  num_fila_tabla + 1;

	var div = document.createElement('tr');
	//
	div.setAttribute('id','dia_nosubsidiado-'+COD_DETALLE_CONCEPTO);	
	tabla.appendChild(div); //PRINCIPAL	
	

//-------------------------------------------------------------
//---- CODIGO
var id = '<input type="hidden" size="4" id="id_pdia_nl_ns-'+ COD_DETALLE_CONCEPTO +'" name="id_pdia_nl_ns[]" value ="" >';
var estado = '<input type="hidden" size="4" id="estado-'+ COD_DETALLE_CONCEPTO +'" name="estado[]" value ="0" >';
//var codigo = '<input type="text" size="4" id="cod_detalle_concepto_" name="cod_detalle_concepto[]" value = '+ COD_DETALLE_CONCEPTO +'>';

var input_cdia = '<input name="dn_cantidad_dia[]" type="text" id="dn_cantidad_dia-'+COD_DETALLE_CONCEPTO+'" size="7" />';
//-----input Descripcion
var span1;
var span2;


var finSpan = '</span>';

span1 = '<span title="editar">';
span1 +="<a href=\"javascript:editar_ds('"+COD_DETALLE_CONCEPTO+"')\"><img src=\"images/edit.png\"></a>";
span1 += finSpan;


span2 = '<span title="editar">';
span2 +="<a href=\"javascript:eliminar_dns('dia_nosubsidiado-"+COD_DETALLE_CONCEPTO+"')\"><img src=\"images/cancelar.png\"></a>";
span2 += finSpan;


var combo = "";
combo +='     <select name="cbo_dn_tipo_suspension[]" id="cbo_dn_tipo_suspension-'+COD_DETALLE_CONCEPTO+'"  ';
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
cbo = document.getElementById('cbo_dn_tipo_suspension-'+COD_DETALLE_CONCEPTO);

var ids = getIdsCombos_2();
cargarSuspension_2(cbo,ids);	
	

}



function eliminar_dns(elementId){ alert (" "+elementId);

	var obj = document.getElementById(elementId)
	eliminarElemento(obj);

	console.log("master intacto");
	console.dir(master);


}



function getIdsCombos_2(){
	var ids = new Array();
	
	var combos = document.getElementById('formDiaNoSubsidiado');	
	var numElementos = combos.elements.length;
	
	for(var i=0;i<combos.elements.length;i++){
		if(combos.elements[i].type == 'select-one'){
			var id_combo = combos.elements[i].id;
			var value = combos.elements[i].value;			
			ids.push(value);
		}
	}

	return ids;
	
}




function grabarDiaNoLaborado(){
	
$.ajax({
   type: "post",
   url: "sunat_planilla/view-plame/modal/dia_nolaborado.php",
   data: "id_ptrabajador="+id,
   async:true,
   success: function(datos){
    $('#editarDiaNoLaborado').html(datos);
    
    $('#dialog-dia-noLaborado').dialog('open');
   }
});

	
}

</script>



<form action="" method="get" name="formDiaNoSubsidiado" id="formDiaNoSubsidiado">

oper
        <input name="oper" type="text" value="dual" />
        <br />
        id_pjoranada_laboral
        <label for="id_pjoranada_laboral"></label>
        <input type="text" name="id_pjoranada_laboral" id="" value="<?php echo $id_pjoranada_laboral; ?>" />
<table width="450" border="1" id="tb_dnolaborado">
  <tr>
    <td width="217">tipo desuspens&oacute;n</td>
    <td width="81">cantidad de dias</td>
    <td width="88">Modificar</td>
    <td width="74">Eliminar</td>
  </tr>
  <!--
  <tr>
    <td>
    <input name="id_pdia_nl_ns[]" type="text" id="id_pdia_nl_ns" size="1" />
      <select name="cbo_dn_tipo_suspension[]" id="cbo_dn_tipo_suspension" style="width:180px;">
    </select>

    <input name="estado" type="text" id="estado" size="1" /></td>
    <td>
    <input name="dn_cantidad_dia" type="text" id="dn_cantidad_dia" size="7" /></td>
    <td>
    <span title="Editar">
		<a href="javascript:editarPersonaDireccion('')"><img src="images/edit.png"></a>
	</span>
    </td>
    <td>
    <span title="Cancelar">
        <a href="javascript:eliminarDerechohabiente('')"><img src="images/cancelar.png"></a>
    </span>
    </td>
  </tr>
 --> 
</table>
<br>
<div style="width:150px; margin:0 0 0 174px;">
<label for="dn_total_dia_subsidiado">TOTAL</label>
<input name="dn_total_dia_subsidiado" type="text" id="dn_total_dia_subsidiado-1" size="7">
</div>
<p>
  <input type="button" name="btnGrabar" id="btnGrabar" value="Grabar" onclick="grabarDiaNoLaborado()" />
  <input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onclick="validarNuevoInput_2()" />
</p>
</form>
