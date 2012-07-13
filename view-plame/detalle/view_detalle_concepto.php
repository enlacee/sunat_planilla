<?php
//session_start();
//*******************************************************************//
require_once '../../view/ide2.php';
//*******************************************************************//
//require_once('../../util/funciones.php');
//require_once('../../dao/AbstractDao.php');
require_once('../../controller/ideController2.php');


//require_once('../../dao/PlameDetalleConceptoDao.php');
//require_once('../../controller/PlameDetalleConceptoController.php');

require_once('../../dao/PlameDetalleConceptoEmpleadorMaestroDao.php');
require_once('../../controller/PlameDetalleConceptoEmpleadorMaestroController.php');

//require_once('../../');


$cod_concepto = $_REQUEST['id_concepto'];

$data_cantidad = cantidadDetalleConceptoEM( $cod_concepto, ID_EMPLEADOR_MAESTRO );
$data_detalle_concepto = listarDetalleConceptoEM( $cod_concepto, ID_EMPLEADOR_MAESTRO);


//echo "<pre>";
//print_r($data_cantidad);
//echo "</pre>";

?>
<script type="text/javascript"> 

var id_concepto = document.getElementById('cod_concepto').value;
cargarTablaDetalleConcepto(id_concepto);


//--------------------------------------------
function validarPlameDetalleConcepto(){
	
var from_data =  $("#formDetalleConcepto").serialize();

//-------	
	$.ajax({
	type: 'get',
	dataType: 'json',
	url: 'sunat_planilla/controller/PlameDetalleConceptoEmpleadorMaestroController.php?'+from_data,
	//data: {id_empleador: valor, oper: 'lista_establecimiento'},
	success: function(json){
	//console.log(json);
		alert("guardado "+json);
		
		
		}
	});
//-------

	


}

</script>
<div style="width:700px; height:auto; overflow:hidden; border:1px solid red">

<table width="670" border="1">
  <tr>
    <td width="252">Codigo</td>
    <td width="408">Descripcion</td>
  </tr>
  <tr>
    <td><label><?php echo $cod_concepto; ?></label></td>
    <td><label><?php //echo utf8_decode($_REQUEST['concepto']); ?></label></td>
  </tr>
</table>
  <input type="hidden" name="cod_concepto" id="cod_concepto"  
value="<?php echo $cod_concepto; ?>"/>
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
<input name="oper" type="text" value="edit" />
<table width="670" border="1">
  <tr>
    <td width="45">Codigo</td>
    <td width="320">Descripcion</td>
    <td width="125">Afectacion</td>
    <td width="157"> <input type="checkbox" name="checkbox" id="checkbox" />
      Seleccionar Todos</td>
  </tr>
  
  
<?php for($i=0; $i<$data_cantidad; $i++): ?>  
  <tr>  
    <td><?php echo $data_detalle_concepto[$i]['cod_detalle_concepto']; //-?></td>
    <td><?php echo $data_detalle_concepto[$i]['descripcion']; //-?></td>
    <td>
                <div id="divEliminar_Editar">				
				<span title="Detalle Concepto">
				<a href="javascript:editarAfectacion('<?php echo $data_detalle_concepto[$i]['cod_detalle_concepto']; //-?>')"><img src="images/search2.png" width="18" height="18"></a>
				</span>	
                </div>    
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

<input name="btnGrabar" type="button" value="Grabar" onclick="validarPlameDetalleConcepto()" />

</form>




</div>
<!-- -->
