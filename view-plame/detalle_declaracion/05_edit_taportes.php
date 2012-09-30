<?php
//*******************************************************************//
require_once '../../view/ide2.php';
//*******************************************************************//
require_once('../../util/funciones.php');
require_once('../../dao/AbstractDao.php');

//IDE
require_once('../../controller/ideController2.php');

require_once('../../dao/PlameDetalleConceptoEmpleadorMaestroDao.php');
require_once('../../controller/PlameDetalleConceptoEmpleadorMaestroController.php');

// trabajadorPdeclaracion
require_once('../../model/TrabajadorPdeclaracion.php');
require_once('../../dao/TrabajadorPdeclaracionDao.php');
require_once('../../controller/TrabajadorPdeclaracionController.php');

$ID_TRABAJADOR_PDECLARACION = $_REQUEST['id_ptrabajador'];

require_once("../../controller/DeclaracionDconceptoController.php");
require_once("../../dao/DeclaracionDconceptoDao.php");
//-------------------------------------------------------------------------------
$calc_conceptos = array();
$calc_conceptos = listar_concepto_calc_ID_TrabajadorPdeclaracion($ID_TRABAJADOR_PDECLARACION);


$obj = new TrabajadorPdeclaracion();
$obj = buscar_ID_TrabajadorPdeclaracion($ID_TRABAJADOR_PDECLARACION);

// Filtro si es:
// -- ONP
// -- AFP

//ONP-Opcionales = 0604 , 06012

$onp = array('0602' ,'0604','0605', '0607', '0611','0612','0803','0804','0809');

//AFP-Opcionales = 0604
$afp = array('0601', '0602','0604','0605' , '0606','0608', '0609', '0611','0612', '0801','0803','0804','0809');

// ONP = 02
// AFP = 21,22,23,24


$codigo = $obj->getCod_regimen_pensionario();// '02' //$objTRADetalle_5->getCod_regimen_pensionario();
$detalle_concepto = array();

if($codigo =='02'){
	$detalle_concepto = $onp;
}else if($codigo =='21'||$codigo =='22'||$codigo =='23' || $codigo =='24'){
	$detalle_concepto = $afp;
}




//$datas = new Dcem_Pd();
//$data_cantidad = cantidadDetalleConceptoEM( $cod_concepto, ID_EMPLEADOR_MAESTRO );
//$ptaporte = array();
//$ptaporte = listarDcem_PtributoAporte($ID_PTRABAJADOR);
//$pingreso = listarDcem_Pingreso($ID_PTRABAJADOR);
$conceptos= array('600','800');

$ptaporte = view_listarConcepto(ID_EMPLEADOR_MAESTRO,$conceptos,0);

//echo "<pre>";
//print_r($calc_conceptos);
//echo "</pre>";


/*
echo "<pre> lista a buscar detalle_concepto";
print_r($detalle_concepto);
echo "</pre>";
*/

?>

<div class="ptrabajador">

<div class="ocultar">
id_dcem_ptributo_aporte<input name="id_dcem_ptributo_aporte" type="text" readonly="readonly" />
</div>
<h3>Aportaciones del Trabajadores: </h3>
  <hr />
  
 <!--FIN  0600 -->
 
 

    
    
<table width="670" border="1">
    <tr>
      <td width="14">&nbsp;</td>
      <td width="71">C&oacute;digo</td>
      <td width="244">Concepto</td>
      <td width="134">Base de C&aacute;lculo(S/.)</td>
      <td width="173">Monto(S/.)</td>
    </tr>
<?php
//        if (count($ptaporte) >= 1):

	for ($i = 0; $i < count($ptaporte); $i++):
			
		//INICIO ---- Aportaciones del trabajador 0600
		if($ptaporte[$i]['cod_concepto'] == '0600'):
		
?>
                
<?php

if( in_array($ptaporte[$i]['cod_detalle_concepto'],$detalle_concepto) ): //final conceptos 0600 , 0800
?>
                  
    <tr>
      <td><input name="ptta_id_dcem_ptributo_aporte[]" type="text" id="ptta_id_dcem_ptributo_aporte" size="5" 
      value="<?php echo $ptaporte[$i]['id_detalle_concepto_empleador_maestro']; ?>" /></td>
      <td><label for="pt_codigo"></label>
      <input name="ptta_cod_detalle_concepto[]" type="text" id="ptta_cod_detalle_concepto" size="5" 
       value="<?php echo $ptaporte[$i]['cod_detalle_concepto'];?>"/>
      <label for="id_ptrabajador"></label>
      <input name="id_ptrabajador" type="hidden" id="id_ptrabajador" size="5" />
      </td>
      <td><?php echo $ptaporte[$i]['descripcion'] ?></td>
      <td><label for="ptta_base"></label>
      <input name="ptta_base[]" type="hidden" id="ptta_base" size="8" /></td>
      
      <td><input name="ptta_monto[]" type="text" id="ptta_monto" size="8"
      value="<?php 
	   for($x=0; $x<count($calc_conceptos); $x++):
		   if($ptaporte[$i]['cod_detalle_concepto'] == $calc_conceptos[$x]['cod_detalle_concepto'] ):
			   echo $calc_conceptos[$x]['monto_pagado'];
			   break;
			endif;
		endfor;
	   ?>"
       /></td>
    </tr>
<?php
endif; //inicio conceptos 0600 , 0800				
?>				


<?php			
endif;
endfor;
//FINALL ---- Aportaciones del trabajador 0600

?>
<!-- HTML PIE INICIO 0600 -->
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">TOTAL APORTES DEL TRABAJADOR:</td>
      <td><label for="pt_total_aporte_trabajador"></label>
      <input name="pt_total_aporte_trabajador" type="text" id="pt_total_aporte_trabajador" size="8" /></td>
    </tr>
  </table>
<!-- HTML PIE FIN 0600 -->


	
	
	
	
	
	
	
	
	
	
	
	
				





<h3>Aportaciones del Empleador:</h3>
<hr />

<table width="670" border="1">
  <tr>
    <td width="14">&nbsp;</td>
    <td width="71">C&oacute;digo</td>
    <td width="244">Concepto</td>
    <td width="134">Base de C&aacute;lculo(S/.)</td>
    <td width="173">Monto(S/.)</td>
  </tr>
<?php 

for ($i = 0; $i < count($ptaporte); $i++):

if($ptaporte[$i]['cod_concepto'] == '0800'):
?>


<?php
if( in_array($ptaporte[$i]['cod_detalle_concepto'],$detalle_concepto) ): //FILTRO CUERPO				
?>
<!-- CUERPO INICIO 0800 -->
    <tr>
      <td><input name="ptta_id_dcem_ptributo_aporte[]" type="text" id="ptta_id_dcem_ptributo_aporte" size="5" 
      value="<?php echo $ptaporte[$i]['id_detalle_concepto_empleador_maestro']; ?>" /></td>
      <td><label for="pt_codigo"></label>
      <input name="ptta_cod_detalle_concepto[]" type="text" id="ptta_cod_detalle_concepto" size="5" 
       value="<?php echo $ptaporte[$i]['cod_detalle_concepto'];?>"/>
      </td>
      <td><?php echo $ptaporte[$i]['descripcion'] ?></td>
      <td><label for="ptta_base"></label>
      <input name="ptta_base[]" type="hidden" id="ptta_base" size="8" /></td>
      
      <td><input name="ptta_monto[]" type="text" id="ptta_monto" size="8"
      value="<?php 
	   for($x=0; $x<count($calc_conceptos); $x++):
		   if($ptaporte[$i]['cod_detalle_concepto'] == $calc_conceptos[$x]['cod_detalle_concepto'] ):
			   echo $calc_conceptos[$x]['monto_pagado'];
			   break;
			endif;
		endfor;
	   ?>"
       /></td>
    </tr>
<!-- CUERPO FIN 0800 -->

<?php
endif; ////FILTRO CUERPO

?>
<?php

endif;//END IF 0800 				
endfor;
?>
<!-- HTML PIE FIN 0800 -->
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">TOTAL APORTES DEL EMPLEADOR</td>
    <td><label for="pt_total_aporte_trabajador"></label>
      <input name="pt_total_aporte_trabajador2" type="text" id="pt_total_aporte_trabajador" size="8" /></td>
  </tr>
</table>
<!-- HTML PIE FIN 0800 -->

  
  
  
  
  
  
  
  
  
  
  

<h3>Aportaciones del Empleador:</h3>
<hr />
<div class="ocultar">
<table width="670" border="1">
  <tr>
    <td width="14">&nbsp;</td>
    <td width="71">C&oacute;digo</td>
    <td width="244">Concepto</td>
    <td width="134">Base de C&aacute;lculo(S/.)</td>
    <td width="173">Monto(S/.)</td>
  </tr>
  <tr>
    <td><input name="ptta_id_dcem_ptributo_aporte[]" type="text" id="ptta_id_dcem_ptributo_aporte" size="5" 
      value="<?php  ?>" /></td>
    <td><label for="pt_codigo2"></label>
      <input name="pt_codigo2" type="text" id="pt_codigo2" size="5" /></td>
    <td><?php ?></td>
    <td><label for="pt_devengado2"></label>
      <input name="pt_devengado2" type="text" id="pt_devengado2" size="8" /></td>
    <td><input name="pt_pagado2" type="text" id="pt_pagado2" size="8" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">TOTAL APORTES DEL EMPLEADOR</td>
    <td><label for="pt_total_aporte_trabajador"></label>
      <input name="pt_total_aporte_trabajador2" type="text" id="pt_total_aporte_trabajador" size="8" /></td>
  </tr>
</table>

</div>
<p>&nbsp; </p>
  <p>&nbsp;</p>
  
  
</div>