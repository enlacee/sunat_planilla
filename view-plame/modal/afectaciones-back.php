<?php
//session_start();

require_once('../../util/funciones.php');
require_once('../../dao/AbstractDao.php');

require_once('../../dao/PlameDetalleConceptoAfectacionDao.php');
require_once('../../controller/PlameDetalleConceptoAfectacionController.php');


$cod_detalle_concepto = $_REQUEST['id_detalle_concepto'];

//$data_cantidad = cantidadDetalleConceptoAfectacion($cod_detalle_concepto);

$data_dca = listarDetalleConceptoAfectacion($cod_detalle_concepto);

//echo $data_cantidad ;
//echo "<pre>";
//print_r($data_dca);
//echo "</pre>";



?>
<script>                
/*
$(document).ready(function(){
	$( "#tabs").tabs();
});
*/
</script>
<div style="border:1px solid blue; width:400px">


  <p>C&oacute;digo:</p>
  <table width="400" border="1" cellpadding="0" cellspacing="0"  class="ftablas" >
    <tr>
      <td width="12" class="oocultar">ID</td>
      <td width="268">Descripci&oacute;n</td>
      <td colspan="2">¿Afecto?</td>
    </tr>
    
<?php for($i=0;$i<count($data_dca);$i++): ?>    
    
    <tr>
      <td class="oocultar">
      <input name="cod_afectacion" type="text" id="cod_afectacion" size="4"
      value="<?php echo $data_dca[$i]['id_detalle_concepto_afectacion']; ?>"
       />
       </td>id_detalle_concepto_afectacion
      <td><?php echo $data_dca[$i]['descripcion']; ?> <input name="txt_seleccionado" type="text" size="4" /></td>
      <td width="43"><input type="radio" name="rbtn_afectacion_" id="" value="radio" />
        SI</td>
      <td width="49"><input type="radio" name="rbtn_afectacion_" id="" value="radio" />
        NO</td>
    </tr>
    
<?php endfor; ?>
    
  </table>
</div>
