
<!-- final js -->
<style type="text/css">
.cantidad {	width:270px;
	background-color:#fcac36;
}
.celda {	float:left;
	min-height:22px;
	padding:5px 0 5px 0;
	margin-right:1px;
}
.cesta-productos {	text-align:center;
	width:700px;
/*	display:inline-block;*/
	display:inline-block;
}
.eliminar {	width:50px;
	background-color:#fcac36;
}
.precio {	width:130px;
	background-color:#fcac36;
}
.producto {	width:130px;
	background-color:#fcac36;
}
.sub-total {	width:100px;
	background-color:#fcac36;
}
</style>
 <script type="text/javascript" language="javascript" >
 
/*******************************************************************************************************
** ----------------------------- DETALLE 01 ------------------------------------------------------------
 [trabajador.php]= Periodos Laborales
********************************************************************************************************/

	var tipo_trabajador = new Array(
	<?php 
	//echo "<pre>";
	//print_r($new_motivo_baja);
	//echo "</pre>";	
	$counteo = count($new_motivo_baja);	
	for($i=0;$i<$counteo;$i++): ?>	
	<?php
		if($i == $counteo-1){ 
			echo "{id:'".$cbo_tipo_trabajador[$i]['cod_tipo_trabajador']."', descripcion:'".$cbo_tipo_trabajador[$i]['descripcion']."' }"; 
		}else{
			echo "{id:'".$cbo_tipo_trabajador[$i]['cod_tipo_trabajador']."', descripcion:'".$cbo_tipo_trabajador[$i]['descripcion']."' },"; 
		}
	?>
	<?php endfor; ?>
	);	
//----------------------------------
function llenarComboDetalle_2(objCombo){

	var test = new Array();
	test = tipo_trabajador;
	var counteo = 	test.length;
	objCombo.options[0] = new Option('-', '');
	for(var i=0;i<counteo;i++){
		objCombo.options[i] = new Option(test[i].descripcion, test[i].id);
	}
}//ENDFN
 
 </script>



<div id="dialog_detalle_2"  class="detalle" title="Detalle 2">
  <div id="detalle_2_contenido" align="left"></div>
  
  
  
<div id="detalle_2">
  <form action="" method="post" name="frm_detalle_2" id="frm_detalle_2"
  title="Tipo de Trabajador">
  <fieldset id="xx">
  <legend>fieldset</legend>

    <input type="button" name="btnDetalle_2" id="" value="Agregar"  onclick="crearElementoDetalle_2()"/>
    <div class="cesta-productos">
      <div class="negrita" >
        <div class="celda eliminar">c&oacute;digo</div>
        <div class=" cantidad celda">Tipo de trabajador</div>
        <div class="celda producto">Fecha de Inicio</div>
        <div class="celda precio">Fecha de Fin</div>
        <div class="celda sub-total">opciones.</div>
        <div class="clear"></div>
      </div>
      <!-- END AJAX cesta de productos -->
      <div  id="contenedor_ttrabajador">
        <div id="ttrabajador-row-1">
          <div class="celda eliminar">
          <input type="hidden" name="id_ttrabajador[]" id="id_ttrabajador-1" />
          
            <input type="text" id="txt_ttcodigo-1" name="txt_ttcodigo[]"
                value="0" size="1" readonly="readonly" />
            
          </div>
          <div class="celda cantidad">
            <select onchange="" style="width:250px;" id="cbo_ttrabajador-1" name="cbo_ttrabajador[]">
<?php 
foreach ($cbo_tipo_trabajador as $indice) {
	
	if ($indice['cod_tipo_trabajador'] == 0 ) {
		
		//$html = '<option value="" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_tipo_trabajador'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
} 
?>
            </select>
          </div>
          <div class="celda producto">
            <input type="text" size="14" id="txt_ttrabajador_fecha_inicio-1" name="txt_ttrabajador_fecha_inicio[]" />
          </div>
          <div class="celda precio">
            <input  type="text" id="txt_ttrabajador_fecha_fin-1"  name="txt_ttrabajador_fecha_fin[]"size="14" />
          </div>
          <div class="celda sub-total"> <a href="#">editar</a></div>
        </div>
        <!-- html fin-->
        <!--<div class="clear"></div>-->
      </div>
      <!-- fin HTML 2-->
      <!-- END AJAX cesta de productos -->
    </div>
      </fieldset>
  </form>
</div>



</div>