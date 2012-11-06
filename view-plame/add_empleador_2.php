<?php

require_once('../controller/Concepto_EController.php');
require_once('../dao/Concepto_EDao.php');

require_once('../controller/Concepto_E_EmpleadorController.php');
require_once('../dao/Concepto_E_EmpleadorDao.php');


$data_concepto = listarConceptosE($cod_concepto);

$data_concepto_empleador = listarConceptosEmpleador();


//echo "<pre>";
//print_r($data_detalle_concepto);
//echo "</pre>";

?>
<script type="text/javascript"> 


//--------------------------------------------
function validarPlameDetalleConcepto(){
	
var from_data =  $("#formDetalleConcepto").serialize();
console.log(".... __ ---");

//-------
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'sunat_planilla/controller/PlameDetalleConceptoEmpleadorMaestroController.php?'+from_data,
		//data: {oper: 'lista_emp_dest'},
		beforeSend: function(objeto){ /*alert("Adiós, me voy a ejecutar");*/ },
        complete: function(objeto, exito){ /*alert("Me acabo de completar");
			if(exito=="success"){alert("Y con éxito");}*/
        },
		success: function(data){
			console.log(data);

      if(data){
          alert("Se Guardo Correctamente");
      }else{
          alert("Ocurrio un Error");
      }
			
		}
	});	








}

</script>
<div style="width:700px; height:auto; overflow:hidden; border:1px solid red">



<br />
<div style="float:right; padding:0 40px 0 0; ">
  <a href="javascript:cargar_pagina('sunat_planilla/view-plame/detalle/conceptos.php','#detalle_concepto')">Cerrar Detalle</a>
</div>
<br /><br />



<!--<table id="list">
</table>
<div id="pager">
</div>
-->


<form action="HOLA.PHP" method="get" name="formDetalleConcepto" id="formDetalleConcepto">
<input name="oper" type="text" value="dual" />

<input type="text" name="cod_concepto" value="<?php echo $cod_concepto; ?>" />
<div id="view_detalle_concepto" style="height:200px; overflow:scroll;">
  <table width="670" border="1" class="tabla_gris">
    <tr>
      <td width="45">Codigo</td>
      <td width="320">Descripcion</td>
      <td width="125">Afectacion</td>
      <td width="157"> <input type="checkbox" name="checkbox" id="checkbox" 
    onclick="estadoCheck(this,'formDetalleConcepto')" />
        Seleccionar Todos</td>
      </tr>
    
    
    <?php for($i=0; $i< count($data_detalle_concepto); $i++): ?>  
    <tr>  
      <td>
        <!-- 
	<input name="id_detalle_concepto_em[]" type="text"  
    value="<?php //echo $data_detalle_concepto[$i]['id_detalle_concepto_empleador_maestro']; ?>"
     size="5"/>
     -->
        
        <?php echo $data_concepto[$i]['id_concepto_e']; //-?></td>
      <td><?php echo $data_concepto[$i]['descripcion']; //-?></td>
      <td>
        
        <?php 
	
	/*
	* Logic Para Mostrar Afectaciones ( segun cod_concepto );
	*
	*/
	$estado = false;
	$estado =( ($cod_concepto == '0700') ||  ($cod_concepto == '0600') || ($cod_concepto == '0800') ) ? false : true;
	
	
	if ( $estado ): ?>    
        <div id="divEliminar_Editar" >				
          <span title="Detalle Concepto">
            <a href="javascript:editarAfectacion('<?php echo $data_detalle_concepto[$i]['cod_detalle_concepto']; //-?>')"><img src="images/search2.png" width="18" height="18"></a>
            </span>	
          </div>   
        <?php endif; ?>                
        
        </td>
      <td>
        <input type="checkbox" name="chk_detalle_concepto[]" id="chk_detalle_concepto_<?php echo $data_detalle_concepto[$i]['cod_detalle_concepto']; //-?>" 
    value="<?php echo $data_detalle_concepto[$i]['id_detalle_concepto_empleador_maestro']; ?>"
    <?php echo ($data_detalle_concepto[$i]['seleccionado'] == 1) ? ' checked="checked"' : ''; ?>
     />
        
        </td>
      </tr>
    <?php endfor; ?>   
  </table>
  <p>&nbsp;</p>
  
  
  
  
  
  
  
  
  
  
  
  
  
</div>

<input name="btnGrabar" type="button" value="Grabar" onclick="validarPlameDetalleConcepto()" />

</form>




















</div>


