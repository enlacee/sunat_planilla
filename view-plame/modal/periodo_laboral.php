<?php 
//session_start();
//*******************************************************************//
require_once('../../view/ide2.php');
//*******************************************************************//
require_once('../../util/funciones.php');
//require_once('../../dao/AbstractDao.php');
//--------------combo CATEGORIAS

//--------------- sub detalle_1
require_once('../../model/DetallePeriodoLaboral.php');
require_once('../../dao/DetallePeriodoLaboralDao.php');
require_once('../../controller/DetallePeriodoLaboralController.php');



//--- sub 1 Periodo Laboral
$objTRADetalle_1 = new DetallePeriodoLaboral();

if($_REQUEST['id_trabajador']){
	$objTRADetalle_1 = buscarDetallePeriodoLaboral( $_REQUEST['id_trabajador'] );
}

//echo "<pre>";
//var_dump($objTRADetalle_1);
//echo "</pre>";


?>

<table width="230" border="1">
  <tr>
    <th width="115" scope="col">Fecha de inicio</th>
    <th width="115" scope="col">Fecha de fin</th>
  </tr>
  <tr>
    <td ><?php echo getFechaPatron($objTRADetalle_1->getFecha_inicio(), "d/m/Y"); ?>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
