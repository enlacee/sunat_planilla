<style type="text/css">
<!--
.cesta-productos{
	text-align:center;
	width:700px;
/*	display:inline-block;*/
	display:block;

	
}
.celda{
	float:left;
	min-height:22px;
	padding:5px 0 5px 0;
	margin-right:1px;
}
.negrita .celda{
	min-height:45px;
	background-color:#6FF;
	font: 14px/12px inherit;	
	
}


.eliminar{
	width:50px;
	background-color:#fcac36;
}

.producto{
	width:130px;
	background-color:#fcac36;
}


.cantidad{
	width:270px;
	background-color:#fcac36;
}
.precio,.sub-total{
	width:100px;
	background-color:#fcac36;

}
.dialog_detalle_1{
/*	display:inline-block;*/
	display:inline;
	
}

-->
</style>
 <script type="text/javascript" language="javascript" >
 
/*******************************************************************************************************
** ----------------------------- DETALLE 01 ------------------------------------------------------------
 [trabajador.php]= Periodos Laborales
********************************************************************************************************/

	var motivos_baja = new Array(
	<?php 
	//echo "<pre>";
	//print_r($new_motivo_baja);
	//echo "</pre>";
	$new_motivo_baja = array();
	$new_motivo_baja = $cbo_motivo_baja_registro_cat_trabajador;
	
	$counteo = count($new_motivo_baja);	
	for($i=0;$i<$counteo;$i++): ?>	
	<?php
		if($i == $counteo-1){ 
			echo "{id:'".$new_motivo_baja[$i]['cod_motivo_baja_registro']."', descripcion:'".$new_motivo_baja[$i]['descripcion_abreviada']."' }"; 
		}else{
			echo "{id:'".$new_motivo_baja[$i]['cod_motivo_baja_registro']."', descripcion:'".$new_motivo_baja[$i]['descripcion_abreviada']."' },"; 
		}
	?>
	<?php endfor; ?>
	);	
//----------------------------------
function llenarComboDetalle_1(objCombo){

	var test = new Array();
	test = motivos_baja;
	var counteo = 	test.length;
	//objCombo.options[0] = new Option('-', '');
	for(var i=0;i<counteo;i++){
		objCombo.options[i] = new Option(test[i].descripcion, test[i].id);
	}
}//ENDFN

 
 </script>


<div id="dialog_detalle_1"  class="detalle" title="Detalle 1">
  <div id="detalle_1_contenido" align="left"></div>
    
    
    
<div id="detalle_1">
<form action="" method="post" name="frm_detalle_1" id="frm_detalle_1">
<fieldset id="fieldset-detalle_1">
  <legend>fielset</legend>

<input type="button" name="button" id="button" value="Agregar"  onclick="crearElementoDetalle_1()"/>

  <div class="cesta-productos">
  
      <div class="negrita" >
	    <div class="celda eliminar"></div>
				  <div class="celda producto">Fecha Inicio</div>
			    <div class="celda precio">Fecha Fin</div>
					<div class=" cantidad celda">Motivo de baja del registro</div>
				  <div class="celda sub-total">quitar.</div>
                  <div class="clear"></div>
</div>
<!-- END AJAX cesta de productos -->




  <div  id="contenedor_plaboral">
    <div id="plaboral-row-1">
            <div class="celda eliminar"> 
                <input type="text" id="id_detalle_plaboral-1" size="2" name="id_detalle_plaboral[]"
                value="" >
            </div>	
            <div class="celda producto">
                <input type="text" size="16" id="txt_plaboral_fecha_inicio-1" name="txt_plaboral_fecha_inicio[]" >
            </div>	
            <div class="celda precio">
              <input  type="text" id="txt_plaboral_fecha_fin-1"  name="txt_plaboral_fecha_fin[]"size="16" />      
            </div>	
            <div class="celda cantidad">
                <select onchange="" style="width:250px;" id="cbo_motivo_baja-1" name="cbo_motivo_baja[]">
		<?php 
		// 2DO fOR php =
        foreach ($cbo_motivo_baja_registro_cat_trabajador as $indice) {
        
        if ($indice['cod_motivo_baja_registro']==0 ) {
        
        $html = '<option value="'. $indice['cod_motivo_baja_registro'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
        } else {
        $html = '<option value="'. $indice['cod_motivo_baja_registro'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
        }
        echo $html;
        } 
        ?>

                </select>


            </div>	
            <div class="celda sub-total"> editar</div>
        </div><!-- html fin-->

          <!--<div class="clear"></div>-->
</div>

<!-- fin HTML 2-->










<!-- END AJAX cesta de productos -->
    </div>
 </fieldset>   
</form> 
</div> <!-- FIN DETALLE 1 --> 
    
<div style="clear:both"></div>  
</div>