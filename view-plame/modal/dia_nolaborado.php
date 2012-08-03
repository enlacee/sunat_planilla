<?php
require_once '../../dao/AbstractDao.php';
require_once '../../dao/ComboCategoriaDao.php';
require_once '../../controller/ComboCategoriaController.php';

$suspencion2 = comboSuspensionLaboral_2();

//$data = comboSuspensionLaboral_2();

echo "<pre>";
//var_dump($suspencion2);
echo "</pre>";
?>

<script type="text/javascript">
//var objCombo = document.getElementById('cbo_tipo_suspension-1');


function cargarSuspension_2(){
var objCombo = document.getElementById('cbo_dn_tipo_suspension-1');	
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

	
	var counteo = 	test.length;		
	objCombo.options[0] = new Option('-', '');
	for(var i=0;i<counteo;i++){
		objCombo.options[i+1] = new Option(test[i].descripcion, test[i].id);
	}


	
	
}//ENDFN



</script>
<table width="450" border="1">
  <tr>
    <td width="217">tipo desuspens&oacute;n</td>
    <td width="81">cantidad de dias</td>
    <td width="88">Modificar</td>
    <td width="74">Eliminar</td>
  </tr>
  <tr>
    <td><input name="id_pdia_nl_ns[]" type="text" id="id_pdia_nl_ns" size="2" />
      <label for="cbo_dn_tipo_suspension"></label>
      <select name="cbo_dn_tipo_suspension[]" id="cbo_dn_tipo_suspension" style="width:180px;">
    </select></td>
    <td><label for="dn_cantidad_dia"></label>
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
  <tr>
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
  </tr>
</table>
<br>
<div style="width:150px; margin:0 0 0 174px;">
<label for="dn_total_dia_subsidiado">TOTAL</label>
<input name="dn_total_dia_subsidiado" type="text" id="dn_total_dia_subsidiado-1" size="7">
</div>
<p>
  <input type="button" name="btnGrabar" id="btnGrabar" value="Grabar" />
  <input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onclick="cargarSuspension_2()" />
</p>
