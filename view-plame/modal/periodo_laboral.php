<?php 
//session_start();
//*******************************************************************//
require_once('../../view/ide2.php');
//*******************************************************************//
require_once('../../util/funciones.php');
//require_once('../../dao/AbstractDao.php');
//--------------combo CATEGORIAS

//--------------- sub detalle_1
require_once('../../model/PperiodoLaboral.php');
require_once('../../dao/PperiodoLaboralDao.php');
require_once('../../controller/PlamePeriodosLaboralesController.php');



//--- sub 1 Periodo Laboral
//$objTRADetalle_1 = new DetallePeriodoLaboral();

if($_REQUEST['id_ptrabajador']){
    $dataObj = buscarPLPorIdPtrabajador( $_REQUEST['id_ptrabajador'] );
}

//echo "<pre>";
//var_dump($dataObj);
//echo "</pre>";


?>

<table width="230" border="1">
  <tr>
    <th width="115" scope="col">Fecha de inicio</th>
    <th width="115" scope="col">Fecha de fin</th>
  </tr>

<?php for($i=0;$i<count($dataObj);$i++): ?>
  
  <tr>
    <td ><?php echo getFechaPatron($dataObj[$i]->getFecha_inicio(), "d/m/Y"); ?></td>
    <td><?php echo getFechaPatron($dataObj[$i]->getFecha_fin(), "d/m/Y"); ?></td>
  </tr>
  
  
<?php endfor; ?>    
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

</table>
