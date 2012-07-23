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


//############################################################################
require_once('../../model/PTrabajador.php');
require_once('../../dao/PtrabajadorDao.php');
require_once('../../controller/PlameTrabajadorController.php');

/**
********* BUSQUEDA 01 EDIT = TRA-bajador por ID importante
*/
$ID_PTRABAJADOR = $_REQUEST['id_ptrabajador'];

echo"<pre>ID_PTRABAJADOR..";
print_r($ID_PTRABAJADOR);
echo"</pre>";

$ptrabajador = new PTrabajador();
$ptrabajador = buscar_IDPtrabajador($ID_PTRABAJADOR);



echo "<pre>";
print_r($ptrabajador);
echo "</pre>";



?>

<label for="pt_apaterno">Apellido Paterno</label>
<input type="text" name="pt_apaterno" id="pt_apaterno" />

<label for="pt_apaterno">Apellido Materno</label>
<input type="text" name="pt_materno" id="pt_materno" />

<label for="pt_apaterno">Nombres</label>
<input type="text" name="pt_nombres" id="pt_nombres" />

<h1>DATOS LABORALES Y DE SEGURIDAD SOCIAL</h1>

<p><b>Periodo Laboral</b>
  
</p>

  <p>
    <label for="pt_fecha_inicio">Fecha de inicio</label>
    <input type="text" name="pt_fecha_inicio" id="pt_fecha_inicio" />
    
    
    <label for="pt_fecha_fin">Fecha de fin</label>
    <input type="text" name="pt_fecha_fin" id="pt_fecha_fin" />
    
    <a href="#">ver detalle</a></p>
  
<p>
  <label for="pt_tipo_trabajador">Tipo de trabajador</label>
  <select name="pt_tipo_trabajador" id="pt_tipo_trabajador">
  </select>
  
  
  <label for="pt_situacion">Situaci&oacute;n</label>
  <select name="pt_situacion" id="pt_situacion">
  </select>
</p>

<p>
  <label for="pt_regimen_salud">R&eacute;gimen de salud</label>
  <select name="pt_regimen_salud" id="pt_regimen_salud">
  </select>
</p>
<p>
  <label for="pt_regimen_pensionario">R&eacute;gimen de pensionario</label>
  <select name="pt_regimen_pensionario" id="pt_regimen_pensionario">
  </select>
</p>






<p>
  <label for="rbtn_pt_sctr">Aporta a Es Salud - SCTR</label>
  <input name="rbtn_pt_sctr" type="radio" value="1" />SI
  <input name="rbtn_pt_sctr" type="radio" value="0" />NO
</p>
<p>
  <label for="rbtn_pt_evida">Aporta a Es Salud + Vida</label>
  <input name="rbtn_pt_evida" type="radio" value="1" />SI
<input name="rbtn_pt_evida" type="radio" value="0" />NO</p>
<p>
  <label for="rbtn_pt_evida">Aporta a Asegura tu Pensi&oacute;n</label>
  <input name="rbtn_pt_evida" type="radio" value="1" />SI
<input name="rbtn_pt_evida" type="radio" value="0" />NO</p>
<p> <b>DATOS TRIBUTARIOS</b></p>
<p>
  
  
  
  <label for="rbtn_pt_docimiliado">Condici&oacute;n de domicilio seg&uacute;n impuesto a la renta</label>
  <input name="rbtn_pt_docimiliado" type="radio" value="1" />SI
  <input name="rbtn_pt_docimiliado" type="radio" value="0" />NO
</p>
<p>&nbsp;</p>
