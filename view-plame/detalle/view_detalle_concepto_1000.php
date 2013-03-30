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

//CONCEPTO
require_once('../../dao/PlameConceptoDao.php');
require_once('../../controller/PlameConceptoController.php');

//require_once('../../');


$cod_concepto = $_REQUEST['id_concepto'];

$data_concepto = buscar_concepto_por_id($cod_concepto);

//$data_cantidad = cantidadDetalleConceptoEM( $cod_concepto, ID_EMPLEADOR_MAESTRO );
$data_detalle_concepto = listarDetalleConceptoEM( $cod_concepto, ID_EMPLEADOR_MAESTRO);


//echo "<pre>";
//print_r($data_concepto);
//echo "</pre>";


//echo "<pre>";
//print_r($data_detalle_concepto);
//echo "</pre>";


$DATA_DESCRIPCION_1000 = array();
for($i=0; $i<count($data_detalle_concepto); $i++){
	$nombreConceptoIgualEstado = (strpos($data_detalle_concepto[$i]['descripcion'], "OTROS CONCEPTOS") === 0) ? true : false;
	if($nombreConceptoIgualEstado == false){
		$DATA_DESCRIPCION_1000[] = $i;//$data_detalle_concepto[$i]['descripcion_1000'];
	}
}



?>
<script type="text/javascript"> 


function validarDescripcionLupa(id){
	
	var input_dsc = document.getElementById("concepto_descripcion_"+id);
	
	if(input_dsc.value.length==0){
		alert("Debe Ingresar una descripcion del \n del concepto");
		input_dsc.focus();
	}else{
		editarAfectacion(id);	
	}


}

//--------------------------------------------
function validarPlameDetalleConcepto1000(){
	
var from_data =  $("#formDetalleConcepto1000").serialize();
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
			//console.log(data);
			if(data){
				alert("Se guardo correctamente");
			}else{
				alert("Ocurrio un error");
			}
			
		}
	});
}

//--
function contarTablaFilasMas1(tabla){
	var num_filas = 1000;		
	num_filas = (1000 + contarTablaFila(tabla)) - 1; //fila cabecera	
	//sumando 1
	return num_filas + 1;
}


function nuevoFilaConcepto1000(){
	
	//Capa Contenedora
	var tabla = document.getElementById("table_1000");	
	var COD_DETALLE_CONCEPTO =  contarTablaFilasMas1(tabla);
	
	//var id_check = idCheckConcepto1000EM( COD_DETALLE_CONCEPTO );

	//INICIO div
	var div = document.createElement('tr');
	//div.setAttribute('id','establecimiento-'+id);
	//
	tabla.appendChild(div); //PRINCIPAL	
	

//-------------------------------------------------------------
//---- CODIGO
var id = '<input type="text" size="4" id="id_'+ COD_DETALLE_CONCEPTO +'" name="id[]" value ="'+COD_DETALLE_CONCEPTO+'" >';

var codigo = '<input type="text" size="4" id="cod_detalle_concepto_" name="cod_detalle_concepto[]" value = '+ COD_DETALLE_CONCEPTO +'>';

//-----input Descripcion
var input_des;
input_des = '<input type="text" id="concepto_descripcion_'+ COD_DETALLE_CONCEPTO +'" name="concepto_descripcion_1000[]">';

///----LUPA
var lupa = '';
lupa +='	<div id="divEliminar_Editar">';
lupa +='	<span title="Detalle Concepto" >';

lupa +='	<a href="javascript:validarDescripcionLupa(\''+ COD_DETALLE_CONCEPTO +'\')">';
lupa +='	<img width="18" height="18" src="images/search2.png"></a>';

lupa +='	</span>';	
lupa +='    </div>';

//----CHECK
var check ='';
check = '<input type="checkbox" value="" id="chk_detalle_concepto_'+ COD_DETALLE_CONCEPTO +'" name="chk_detalle_concepto[]">';
//-------------------------------------------------------------	


	//inicio html	
var html ='';
var cerrarTD = '<\/td>';


html +='<td>';
html += id;
html += codigo;
html += cerrarTD;

html +='<td>';	
html += input_des;
html += cerrarTD;

html +='<td>';	
html += lupa;
html += cerrarTD;

html +='<td>';	
html += check;
html += cerrarTD;



////---


div.innerHTML=html;

idCheckConcepto1000EM( COD_DETALLE_CONCEPTO );
//	console.dir(tabla);
//	console.log("-----------------");
//	console.dir(tabla.rows[0]);
//	console.log("-----------------");

}




function idCheckConcepto1000EM(cod_detalle_concepto){
/*
//var ID;
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'sunat_planilla/controller/PlameDetalleConceptoEmpleadorMaestroController.php',
		data: {oper: 'id_check', cod_detalle_concepto : cod_detalle_concepto },
		beforeSend: function(objeto){},
        complete: function(objeto, exito){ },
		success: function(data){			
			if(data){					
				//console.log("dentro "+ID);
				$('#chk_detalle_concepto_'+cod_detalle_concepto).val(data);
				$('#id_'+cod_detalle_concepto).val(data);						
			}else{
			 	alert ("no existe id");
			}

		}
	});
//--------
*/
}

</script>
<div style="width:700px; height:auto; overflow:hidden; border:1px solid red">

<table width="670" border="1" class="tabla_gris">
  <tr>
    <td width="252">Codigo 1000</td>
    <td width="408">Descripcion</td>
  </tr>
  <tr>
    <td><label><?php echo $cod_concepto; ?></label></td>
    <td><label><?php echo $data_concepto[0]['descripcion']; ?></label></td>
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


<form action="" method="get" name="formDetalleConcepto1000" id="formDetalleConcepto1000">
<input name="oper" type="hidden" value="actualizar-concepto-1000" />

<input type="hidden" name="cod_concepto" value="<?php echo $cod_concepto; ?>" />
<div id="view_detalle_concepto">
  <table width="670" border="1" id="table_1000" class="tabla_gris">
<tr>
      <td width="45">Codigo</td>
      <td width="320">Descripcion</td>
      <td width="125">Afectacion</td>
      <td width="157"> <input type="checkbox" name="checkbox" id="checkbox" 
    onclick="estadoCheck(this,'formDetalleConcepto1000')" />
        Seleccionar Todos</td>
  </tr>
    
    





    <?php for($i=0; $i< count($DATA_DESCRIPCION_1000); $i++): ?>  
    <tr>  
      <td>
        
	<input name="id[]" type="hidden"  
    value="<?php echo $data_detalle_concepto[$i]['cod_detalle_concepto']; ?>"
     size="5"/>
   
        
        <?php echo $data_detalle_concepto[$i]['cod_detalle_concepto']; //-?></td>
      <td>
	  <input type="text" name="concepto_descripcion_1000[]" size="40"
      id="concepto_descripcion_<?php echo $data_detalle_concepto[$i]['cod_detalle_concepto']; //-?>"
	  value ="<?php echo $data_detalle_concepto[$i]['descripcion']; //-?>" 
      />
      </td>
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
            <a href="javascript:validarDescripcionLupa('<?php echo $data_detalle_concepto[$i]['cod_detalle_concepto']; //-?>')"><img src="images/search2.png" width="18" height="18"></a>
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
<input type="button" name="btnGrabar"  value="Guardar" class="submit-go"
onclick="validarPlameDetalleConcepto1000()" />

<input type="button" name="btnNuevo"  value="Nuevo" class="submit-nuevo"
disabled="disabled"
onclick="nuevoFilaConcepto1000()" />
<br />
<br />
</form>




















</div>


