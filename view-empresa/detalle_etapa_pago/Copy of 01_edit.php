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


//-----------------------------------------------------------------------
// HORAS
//-----------------------------------------------------------------------
	var hora_o = document.getElementById('hora_ordinaria_hh');
	var min_o = document.getElementById('hora_ordinaria_mm');
	
	var hora_s = document.getElementById('hora_sobretiempo_hh');
	var min_s = document.getElementById('hora_sobretiempo_mm');
	
	var total_hora = document.getElementById('total_hora_hh');
	var total_min = document.getElementById('total_hora_mm');

//	var expr_hora =/^\d{3}$/;
//	var bandera = expresion_regular_vf_mes.test(valor_txt);

function calcHoraLaborada_2(){ 

	//------------
	// Hora total
	var total_hora_local = 0;
	var hora_o_local = ( parseInt(hora_o.value)>=0 ) ? parseInt(hora_o.value) : 0;
	var hora_s_local = ( parseInt(hora_s.value)>=0 ) ? parseInt(hora_s.value) : 0;		
		total_hora_local = hora_o_local + hora_s_local;
		
	// Min total
	var total_min_local = 0;
	var min_o_local = ( parseInt(min_o.value)>=0 ) ? parseInt(min_o.value) : 0;
	var min_s_local = ( parseInt(min_s.value)>=0 ) ? parseInt(min_s.value) : 0;
		total_min_local = min_o_local + min_s_local;
		
	console.log("hora_o_local "+hora_o_local);
	console.log("hora_s_local "+hora_s_local);
	
	console.log("min_o_local "+min_o_local);
	console.log("min_s_local "+min_s_local);	
	//------------	
	//------------	
	
	var divicion = (total_min_local/60);
	
	//alert("total_min_local "+total_min_local);
	
	while( (total_min_local/60) >= 1 ){ 			
		
		total_min_local = total_min_local - 60;		
		total_hora_local = total_hora_local + 1;
	}
	
	//------
	total_hora.value = total_hora_local;
	total_min.value = total_min_local;
	//------
	
	console.log("total_hora_local "+total_hora_local);
	console.log("total_min_local "+total_min_local);
	

}
//-------------------------------------------------------------
var dia_total = document.getElementById('dia_total').value;
function calcular_dia_local(){ 
	
	var dia_nolaborado = document.getElementById('dia_subsidiado').value;
	var dia_total_local = document.getElementById('dia_total').value;
	
	dia_total_local = (dia_total_local) ? parseInt(dia_total_local) : 0;
	dia_nolaborado = (dia_nolaborado) ? parseInt(dia_nolaborado) : 0;
	

	if(dia_nolaborado>dia_total){
		alert("No se permite numero superior a dia laborados");
		document.getElementById('dia_subsidiado').value ='';
		document.getElementById('dia_laborado').value = dia_total;
	}else{
			var resta = dia_total_local - dia_nolaborado;

		document.getElementById('dia_laborado').value = resta;
	}
	
	
	
	
}

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
calcular_dia_local();

	// FUNCION DE INICIO 
//------------------------------------------------------------	
	
	
	
calcHoraLaborada_2();
</script>
<div class="ptrabajador">
  <div class="section" style="background:#CDFEFE">            
  <h3>Datos Laborales</h3>
  <hr />
  Centro de Costo 
  <span class="section" style="background:#CDFEFE">
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
  </span>
<h3>Datos Sueldo</h3>
  <hr />
  <div class="article fila1">
    <p>Imgreso 
        <input name="ingreso"  id="ingreso" type="text" value="<?php echo $obj_pago->getSueldo(); ?>" size="7" readonly="readonly" />
</p>
    <p><span class="red">descuento</span> 
        <input name="descuento" id="descuento" type="text" size="7"  value="<?php echo $obj_pago->getDescuento(); ?>" 
         onkeyup="calcularSueldo()"
         />
</p>
    <p>Total 
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

  <div class="section">
      <div class="article fila1">
  <h3>Dias de Jornada</h3>
            <hr />
            <p>
                <label for="dia_laborado">Laborados</label>
              <input name="dia_laborado" type="text" id="dia_laborado" size="4" readonly="readonly"
                     value="<?php //echo  ?>" />
            </p>
            <p>
                <label for="dia_subsidiado">Subsidiados</label>
                <input name="dia_subsidiado" type="text" id="dia_subsidiado" 
                       value="<?php //echo $dia_subsidiado; ?>" size="4" readonly="readonly" />
                <span>
                    <a href="javascript:editarDiaSubcidiado( '<?php //echo $PjornadaLaboral->getId_pjornada_laboral(); ?>')">
                        <img src="images/edit.png"></a></span>
            </p>
  <p>
                <label for="dia_nosubsidiado">No laborados y no subsidiados:</label>
                <input name="dia_nosubsidiado" type="text" id="dia_nosubsidiado" 
                       value="<?php //echo $dia_nosubsidiado; ?>" size="4" readonly="readonly" />
                <span >
                    <a href='javascript:editarDiaNoLaborado("<?php //echo $PjornadaLaboral->getId_pjornada_laboral(); ?>")'>
                        <img src="images/edit.png"></a></span>
            </p>
            <p>TOTAL: 
                <label for="dia_total"></label>
              <input name="dia_total" type="text" id="dia_total" size="4" readonly="readonly"
                     value="<?php echo $obj_pago->getDia_total(); ?>" />
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
              <input type="button" name="btnCalular" id="btnCalular" value="Calcular" onclick="calcHoraLaborada_2()" />
            </h3>
            <p>&nbsp;</p>
        </div>
  </div>


</div>