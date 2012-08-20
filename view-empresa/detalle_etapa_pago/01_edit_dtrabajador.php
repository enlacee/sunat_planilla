<?php
//session_start();
//*******************************************************************//
require_once('../../view/ide2.php');
//*******************************************************************//
//require_once('../../dao/AbstractDao.php');
//--------------combo CATEGORIAS
require_once('../../util/funciones.php');
require_once('../../dao/ComboCategoriaDao.php');
require_once('../../controller/ComboCategoriaController.php');

// PAGO
require_once("../../model/Pago.php"); 
require_once"../../dao/PagoDao.php";
require_once "../../controller/PagoController.php";

$ID_PAGO = ($_REQUEST['id_pago']);

$obj_pago = new Pago();
$obj_pago = buscarPagoPor_ID($ID_PAGO);
echo "<pre>";
//print_r($obj_pago);
echo "</pre>";


$cbo_ccosto = comboCentroCosto();


?>
<div class="ptrabajador">

<h1>DATOS LABORALES</h1>

<p>
  <label for="cboCentroCosto">Centro de costo</label>
    <select name="cboCentroCosto" disabled="disabled" style="width:180px;">
      <?php
foreach ($cbo_ccosto as $indice) {
	
	if ( $indice['id_empresa_centro_costo'] == $obj_pago->getId_empresa_centro_costo()) {
		
		$html = '<option value="'. $indice['id_empresa_centro_costo'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';		
	} else {
		$html = '<option value="'. $indice['id_empresa_centro_costo'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>
    </select>
</p>
<p>imagen</p>
<p><img src="images/icons/candado_1.png" width="72" height="72" /></p>
<p>Fecha de ultima modificaci&oacute;n
  <br />
  <input name="fecha_modificacion" type="text" id="fecha_modificacion" 
  value="<?php echo $obj_pago->getFecha_modificacion();?>" readonly="readonly" />
</p>
<p><br />
</p>
</div>



<!---  DIALOG -->
<div id="dialog-pt-periodo-laboral">

<div id="editarPTperiodoLaboral"> </div>

</div>

