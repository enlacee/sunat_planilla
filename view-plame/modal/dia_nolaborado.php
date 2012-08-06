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
