<?php
//*******************************************************************//
require_once('../../view/ide2.php');
//*******************************************************************//
require_once '../../controller/ideController2.php';
//$data = $_SESSION['sunat_empleador'];
//COMBO
require_once '../../dao/ComboCategoriaDao.php';
require_once '../../controller/ComboCategoriaController.php';

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

<script type="text/javascript">
//VARIABLES GLOBALES
alert("aun tengo el valor ID_PAGO EN JAVASCRIPT  "+ID_PAGO);

	//cargarTablaPTrabajadores(PERIODO);

	
</script>
<div class="ptrabajador">
  <div class="section" style="background:#CDFEFE">            
  <h3>Datos Laborales</h3>
  <hr />
  Centro de Costo 
  <select name="cboCentroCosto" disabled="disabled" style="width:150px;">
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
  <h3>Datos Sueldo</h3>
  <hr />
  <div class="article fila1">
    <p>Imgreso 
        <input name="ingreso" type="text" onkeydown="soloNumeros(event)" value="<?php echo $obj_pago->getValor(); ?>" size="7" readonly="readonly" />
</p>
    <p><span class="red">descuento</span> 
        <input name="descuento" type="text" size="7"  value="<?php echo $obj_pago->getDescuento(); ?>" onkeydown="soloNumeros(event)" />
</p>
    <p>Total 
        <input name="total_ingreso" type="text"  value="<?php echo $obj_pago->getValor_neto(); ?>" size="7" readonly="readonly" />
    </p>
  </div>
    <div class="article fila2">
      <p><span class="red">Descripcion Dscto.</span></p>
      <p>
        <textarea name="descripcion" id="descripcion" cols="45" rows="5"><?php echo $obj_pago->getDescripcion();?></textarea>
      </p>
    </div>
  
  </div>

  <div class="section">
      <div class="article fila1">
  <h3>Dias de Jornada</h3>
            <hr />
            <p>
                <label for="dia_laborado">Laborados</label>
              <input name="dia_laborado" type="text" id="dia_laborado" size="4" readonly="readonly"
                     value="<?php echo $obj_pago->getDia_total(); ?>" />



            </p>
        <p>
                <label for="dia_subsidiado">No Laborados</label>
                <input name="dia_subsidiado" type="text" id="dia_subsidiado" 
                       value="<?php echo $obj_pago->getDia_nosubsidiado(); ?>" size="4" />
        </p>
            <p>TOTAL: 
                <label for="dia_total"></label>
              <input name="dia_total" type="text" id="dia_total" size="4" readonly="readonly"
                     value="<?php echo $obj_pago->getDiaCalc(); ?>" />
</p>
            <p>&nbsp;</p>
    </div>
        <div class="article fila2">
            <h3>Horas Laboradas</h3>
            <hr />
            <p>        
                <label for="hora_ordinaria_hh">Ordinarias (HHHH:MM)</label>
                <input name="hora_ordinaria_hh" type="text" id="hora_ordinaria_hh"
                 onkeydown="soloNumeros(event)"
                 value="<?php echo $obj_pago->getOrdinario_hora(); ?>" size="5" maxlength="3" readonly="readonly" />
                :
              <input name="hora_ordinaria_mm" type="text" id="hora_ordinaria_mm"
                onkeydown="soloNumeros(event)"
                value="<?php echo $obj_pago->getOrdinario_min(); ?>" size="5" maxlength="2" readonly="readonly" />
            </p>
            <p>
                <label for="hora_sobretiempo_hh">Sobretiempo(HHH:MM)</label>
                <input name="hora_sobretiempo_hh" type="text" id="hora_sobretiempo_hh" size="5" maxlength="3"
                onkeydown="soloNumeros(event)" 
                value="<?php echo $obj_pago->getSobretiempo_hora(); ?>" />
                :
                <input name="hora_sobretiempo_mm" type="text" id="hora_sobretiempo_mm" size="5" maxlength="2"
                onkeydown="soloNumeros(event)" onblur=""
                value="<?php echo $obj_pago->getSobretiempo_min(); ?>" />
            </p>
            <h3>TOTAL HORAS:
                <label for="total_hora_hh"></label>
                <input name="total_hora_hh" type="text" id="total_hora_hh" size="5" readonly="readonly" 
                      value="" />
                :
                <label for="total_hora_mm"></label>
                <input name="total_hora_mm" type="text" id="total_hora_mm" size="5" readonly="readonly" />
              <input type="button" name="btnCalular" id="btnCalular" value="Calcular" onclick="calcHoraLaborada()" />
            </h3>
            <p>&nbsp;</p>
        </div>
  </div>


</div>