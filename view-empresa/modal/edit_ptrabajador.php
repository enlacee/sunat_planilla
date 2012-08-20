<?php
require_once('../../util/funciones.php');
require_once('../../dao/AbstractDao.php');

require_once('../../model/Ptrabajador.php');
require_once('../../dao/PtrabajadorDao.php');
require_once('../../controller/PlameTrabajadorController.php');

//---
require_once('../../dao/TrabajadorPdeclaracionDao.php');

$_ID_TRABAJADOR = $_REQUEST['id_trabajador'];

$ID_PTRABAJADOR = existeID_TrabajadorPoPtrabajador($_ID_TRABAJADOR);

$obj_ptrabajador = buscar_ID_Ptrabajador($ID_PTRABAJADOR);

//echo"<pre>";
//print_r($obj_ptrabajador);
//echo "</pre>";

// Busca Datos Segun Caracteristicas 
// - TIPO DE REGIMEN DE SALUD
// - Tipo de Regimen Pensionario
$dao = new TrabajadorPdeclaracionDao();
$DATA_TRA = $dao->buscar_ID_trabajador($_ID_TRABAJADOR);


?>
<h2>Otras condiciones del Trabajador</h2>
<form action="" method="post" name="frmPtrabajador" id="frmPtrabajador">
<p class="ocultar">
id_ptrabajador <input name="id_ptrabajador"  id="id_ptrabajador" type="text"  
value="<?php echo $obj_ptrabajador->getId_ptrabajador();?>" />
</p>
<p>
  <label for="rbtn_apension">Aporta a Es Salud + Vida</label>
  <input name="rbtn_essaludvida" type="radio" value="1"
<?php 
echo ($obj_ptrabajador->getAporta_essalud_vida() == 1) ? ' checked="checked"' : '';
?> />
  SI
  <input name="rbtn_essaludvida" type="radio" value="0" 
<?php 
echo ($obj_ptrabajador->getAporta_essalud_vida() == 0) ? ' checked="checked"' : '';
?>

 />
  NO</p>
<p>
  <label for="rbtn_apension">Aporta a Asegura tu Pensi&oacute;n</label>
  <input name="rbtn_apension" type="radio" value="1" 
<?php 
if($DATA_TRA['cod_regimen_pensionario']=='02'){ //DIFERENTE DE ONP

echo ($obj_ptrabajador->getAporta_asegura_tu_pension()== 1)? ' checked="checked"' : '';

}else{
echo ' disabled="disabled"'; 
}
?>
  />
  SI
  <input name="rbtn_apension" type="radio" value="0" 
<?php 
if($DATA_TRA['cod_regimen_pensionario']=='02'){ //DIFERENTE DE ONP

echo ($obj_ptrabajador->getAporta_asegura_tu_pension()==0)? ' checked="checked"' : '';

}else{
echo ' disabled="disabled"'; 
echo ' checked="checked"';
}
?>


/>
  NO</p>
<p><b>DATOS TRIBUTARIOS</b></p>
<p>
  <label for="rbtn_pt_domiciliado">Condici&oacute;n de <strong>domicilio</strong> seg&uacute;n impuesto a la renta</label>
  <input name="rbtn_pt_domiciliado" type="radio" value="1" 
<?php echo ($obj_ptrabajador->getDomiciliado()==1) ? ' checked="checked"' : ''; ?> />
  SI
  <input name="rbtn_pt_domiciliado" type="radio" value="0" 
<?php echo ($obj_ptrabajador->getDomiciliado()==0) ? ' checked="checked"' : ''; ?>        />
  NO </p>
</form>
