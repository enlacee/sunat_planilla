<?php
//*******************************************************************//
require_once('../../view/ide2.php');
//*******************************************************************//
require_once '../../controller/ideController2.php';
//$data = $_SESSION['sunat_empleador'];
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

?>
<script type="text/javascript">

//-------------------------------------------------------------------
var total_ingreso =	document.getElementById('total_ingreso').value;

function calcularSueldo(){
	var ingreso = document.getElementById('ingreso').value;
	var descuento = document.getElementById('descuento').value;
	
	
	ingreso = (ingreso) ? parseFloat(ingreso):0;
	descuento = (descuento) ? parseFloat(descuento): 0;
	
	


	if(descuento>ingreso){
		alert("No se permite numero superior a Ingreso");
		document.getElementById('descuento').value ='';
		document.getElementById('total_ingreso').value = parseFloat(ingreso);
	}else{
	var total_ingreso_local = ingreso - descuento;
	document.getElementById('total_ingreso').value =total_ingreso_local;
	}



}

calcularSueldo();
</script>
<div class="ptrabajador">
  <div class="section" style="background:#CDFEFE">            
  <h3>Datos Sueldo</h3>
  <hr />
  <div class="article fila1">
    <p>Sueldo mensual 
      <input name="sueldo_base" type="text" id="sueldo_base" size="7" 
      value="<?php echo $obj_pago->getSueldo_Base(); ?>" readonly="readonly">
    </p>
    <p>Ingreso o Adelanto
      <input name="ingreso"  id="ingreso" type="text" value="<?php echo $obj_pago->getSueldo(); ?>" size="7" readonly="readonly" />
    </p>
    <p><span class="red">descuento</span> 
        <input name="descuento" id="descuento" type="text" size="7"  value="<?php echo $obj_pago->getDescuento(); ?>" 
         onkeyup="calcularSueldo()"
         />
</p>
    <p>Total 
        a pagar
      <input name="total_ingreso"  id="total_ingreso" type="text"  value="<?php echo $obj_pago->getSueldo_neto(); ?>" size="7" readonly="readonly" />
    </p>
  </div>
    <div class="article fila2">
      <p><span class="red">Descripcion Dscto.</span></p>
      <p>
        <textarea name="descripcion" id="descripcion" cols="45" rows="5"><?php echo $obj_pago->getDescripcion();?></textarea>
      </p>
    </div>
  
  </div>
</div>