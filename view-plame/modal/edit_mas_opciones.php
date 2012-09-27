<?php
require_once('../../dao/AbstractDao.php');
require_once('../../controller/ideController2.php');
require_once('../../dao/EmpleadorDao.php');

require_once('../../controller/EmpleadorDestaqueController.php');

//require_once('../');
require_once('../../controller/EmpresaCentroCostoController.php');
require_once('../../dao/EmpresaCentroCostoDao.php');

//EmpleadorDao


$estableciento = listarEstablecimientoDestaque($id_empleador);

//echo "<pre>";
//print_r($estableciento);
//echo "</pre>";

//$CCosto = listarCentroCosto($objTRA->getId_establecimiento(),"all");


?>
<h2>Planilla Mensual:</h2>
<div class="ocultar">
  <p>
    <label for="id_pdeclaracion"></label>
    id_pdeclaracion
    <input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
	value="<?php echo $_REQUEST['id_pdeclaracion']; ?>" />
    <br />
    <label for="id_etapa_pago"></label>
    id_etapa_pago
    <input type="text" name="id_etapa_pago" id="id_etapa_pago" 
    value="<?php echo $_REQUEST['id_etapa_pago']; ?>"
     />
  </p>
</div>
<p>
  Establecimientos 
  <select name="id_establecimientos" id="id_establecimientos" style="width:180px;" 
  onchange="seleccionarLocalDinamico(this)"  >
    <option value="0" selected="selected">-</option>
 <?php for($i=0;$i<count($estableciento);$i++):
	
	echo '<option value="'.$estableciento[$i]['id'].'">'.$estableciento[$i]['descripcion'].'</option>';
  
 endfor;
 ?>
  
  </select>
</p>
<p>Centro de Costo 
  <select name="cboCentroCosto" id="cboCentroCosto" style="width:180px;">
  <option value="0" selected="selected">-</option>
 <?php for($i=0;$i<count($CCosto);$i++):
	
	echo '<option value="'.$CCosto[$i]['id_empresa_centro_costo'].'">'.$estableciento[$i]['descripcion'].'</option>';
  
 endfor;
 ?>
  
  
  
  </select>
</p>
