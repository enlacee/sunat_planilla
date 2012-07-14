<?php
//session_start();

require_once('../../util/funciones.php');
require_once('../../dao/AbstractDao.php');

require_once('../../dao/PlameDetalleConceptoAfectacionDao.php');
require_once('../../controller/PlameDetalleConceptoAfectacionController.php');

// Buscar Nombre Detalle Concepto: FULL
require_once('../../dao/PlameDetalleConceptoDao.php');
require_once('../../controller/PlameDetalleConceptoController.php');


$cod_detalle_concepto = $_REQUEST['id_detalle_concepto'];


$data_detalle_concepto = buscar_detalle_concepto_id($cod_detalle_concepto);

//$data_cantidad = cantidadDetalleConceptoAfectacion($cod_detalle_concepto);

$data_dca = listarDetalleConceptoAfectacion($cod_detalle_concepto);




//echo $data_cantidad ;
//echo "<pre>";
//print_r($data_dca);
//echo "AFECTACION";
//echo "</pre>";

?>
<script>                
/*
$(document).ready(function(){
	$( "#tabs").tabs();
});
*/


function validarRadioMarcadoAfectacion(form_radio, id){
	//console.dir(form_radio);
//	console.log(form_radio.name);
//	console.log("---");
//	console.log(form_radio.value);
	
	document.getElementById('seleccionado_'+id).value = form_radio.value;
	
}


</script>
<div style="border:1px solid blue; width:400px">

  <p>C&oacute;digo:
  <b><?php echo $data_detalle_concepto['cod_detalle_concepto'] ." - ". $data_detalle_concepto['descripcion'] ?></b>
  
  </p>
  
<form action="hola.php" method="get" id="formAfectacion" name="formAfectacion" >
  <table width="400" border="1" cellpadding="0" cellspacing="0"  class="ftablas" >
    <tr>
      <td width="12" class="oocultar">ID</td>
      <td width="268">Descripci&oacute;n</td>
      <td colspan="2">¿Afecto?</td>
    </tr>
    
<?php for($i=0;$i<count($data_dca);$i++): ?>    
    
    <tr>
      <td class="oocultar">
      <input name="cod_afectacion[]" type="text" size="4"
      value="<?php echo $data_dca[$i]['id_detalle_concepto_afectacion']; ?>"
       />
       </td>
      <td><?php echo $data_dca[$i]['descripcion']; ?>
      <input name="seleccionado[]" 
      id="seleccionado_<?php echo $data_dca[$i]['id_detalle_concepto_afectacion']; ?>" 
      type="hidden" 
      value="<?php echo $data_dca[$i]['afecto']; ?>" size="4" /></td>
      <td width="43"><input type="radio" 
      
      name="rbtn_afectacion_<?php echo $data_dca[$i]['id_detalle_concepto_afectacion']; ?>" 
      
	  onclick="validarRadioMarcadoAfectacion(this,<?php echo $data_dca[$i]['id_detalle_concepto_afectacion']; ?>)"
      value="1" <?php echo ($data_dca[$i]['afecto']=='1') ? ' checked="checked"' :'' ?> />
       
        SI</td>
      <td width="49"><input type="radio" 
      
      name="rbtn_afectacion_<?php echo $data_dca[$i]['id_detalle_concepto_afectacion']; ?>" 
      onclick="validarRadioMarcadoAfectacion(this,<?php echo $data_dca[$i]['id_detalle_concepto_afectacion']; ?>)"      
      value="0" <?php echo ($data_dca[$i]['afecto']=='0' ||$data_dca[$i]['afecto']==null ) ? ' checked="checked"' :'' ?>  />
      
        NO</td>
    </tr>
    
<?php endfor; ?>
    
  </table>
  
</form>
  
  
</div>
