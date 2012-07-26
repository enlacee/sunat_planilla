<?php
//*******************************************************************//
require_once '../../view/ide2.php';
//*******************************************************************//
//require_once('../../util/funciones.php');
require_once('../../dao/AbstractDao.php');
//require_once('../../controller/ideController2.php');

//require_once('../../model/Dcem_Pdescuento.php');
require_once('../../dao/Dcem_PdescuentoDao.php');
require_once('../../controller/PlameDcem_PdescuentoController.php');


$ID_PTRABAJADOR = $_REQUEST['id_ptrabajador'];

//$datas = new Dcem_Pd();
//$data_cantidad = cantidadDetalleConceptoEM( $cod_concepto, ID_EMPLEADOR_MAESTRO );
$pdescuento = array();
$pdescuento = listarDcem_Pdescuento($ID_PTRABAJADOR);

//echo "<pre>";
//print_r($pdescuento);
//echo "</pre>";
?>
<div class="ptrabajador">
  <h3>Descuentos:  </h3>
    <hr />
  <table width="670" border="1">
    <tr>
      <td width="10">&nbsp;</td>
      <td width="55">C&oacute;digo</td>
      <td width="202">Concepto</td>
      <td width="96">Monto(S/.)</td>
    </tr>
    
    
        <?php
        if (count($pdescuento) >= 1):

            for ($i = 0; $i < count($pdescuento); $i++):
                ?>  
    
    
    
    
    <tr>
      <td><?php echo $pdescuento[$i]['id_pdcem_pdescuento']; ?></td>
      <td><label for="pt_codigo"></label>
      <input name="pt_codigo" type="text" id="pt_codigo" size="5" 
      value="<?php echo $pdescuento[$i]['cod_detalle_concepto'];?>" />
      </td>
      <td><?php echo $pdescuento[$i]['descripcion'];?></td>
      <td><label for="pt_monto"></label>
      <input name="pt_monto" type="text" id="pt_monto" size="8" 
       value="<?php echo $pdescuento[$i]['monto'];?>" />
       </td>
    </tr>
    
    
                <?php
            endfor;
        endif;
        ?>   
    
    
    
    
    
  </table>
  <p>&nbsp;</p>
</div>
<table width="670" border="1">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>TOTAL DESCUENTOS:</td>
    <td><label for="pt_total_devengado"></label>
      <input name="pt_total_devengado" type="text" id="pt_total_devengado" value="0.00" size="8" /></td>
  </tr>
</table>