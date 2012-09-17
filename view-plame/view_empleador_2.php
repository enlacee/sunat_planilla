<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
require_once '../controller/ideController.php';

//---------------------------------------------------------------
require_once('../controller/Concepto_EController.php');
require_once('../dao/Concepto_EDao.php');

require_once('../controller/Concepto_E_EmpleadorController.php');
require_once('../dao/Concepto_E_EmpleadorDao.php');
//---------------------------------------------------------------

$data_concepto = listarConceptosE($cod_concepto);

$array_id_concepto_select = listarConceptosEmpleador();


//registrarDetalleConceptoEM(ID_EMPLEADOR_MAESTRO);

//Table_detalle_conceptos_afectaciones();
//--- Finall Carga de Conceptos

//
//echo "<pre>";
//print_r($array_id_concepto_select);
//echo "</pre>";

//require_once('../controller/ideController.php');


?>
<script type="text/javascript">
    $(document).ready(function(){                  
        $( "#tabs").tabs();
		//---------------------
	
	});
	
//--------------------------------------------
function validarConceptoEmpleador(){
	
var from_data =  $("#formConceptoEmpleador").serialize();

	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'sunat_planilla/controller/Concepto_E_EmpleadorController.php?'+from_data,
		//data: {oper: 'lista_emp_dest'},
		success: function(data){
			//console.log("holaaaa "+data);
			if(data){
				alert("Se registro correctamente.");
			}
			
			cargar_pagina('sunat_planilla/view-plame/view_empleador_2.php','#CapaContenedorFormulario')
		}
	});	



}
	
	
	function  marcar(obj,nameId){

	//alert(obj.checked);
	var marca = document.getElementById(nameId);

	if(obj.checked == true){ 
	//alert("entro true");
		marca.value = 1;
	}else{
		//alert("entro false");
		marca.value = 0;
	}
	
	
	}
	
</script>


<div class="demo" align="left">
    <div id="tabs">
      <ul>
            			
			<li><a href="#tabs-2">Mantenimiento de Conceptos Empresa</a></li>				            	
      </ul>

      <div id="tabs-2">
        <div class="titulo">Mantenimiento de Conceptos Empresa</div>
        <br />
        RUC: <label><?php echo $data['ruc']." - ".$data['razon_social_concatenado']; ?></label>
        <br />
        <br />

<div id="detalle_concepto">
</div>



<form action="HOLA.PHP" method="get" name="formConceptoEmpleador" id="formConceptoEmpleador">
<div class="ocultar">
  <input name="oper" type="text" value="dual" />
  
  
</div>
<div id="view_detalle_concepto" style="height:200px; overflow:scroll;">
  <table width="595" border="1" class="tabla_gris">
    <tr>
      <td width="18">id</td>
      <td width="45">Estado</td>
      <td width="347">Descripcion</td>
<td width="157"><div class="ocultar">
  <input type="checkbox" name="checkbox" id="checkbox" 
    onclick="estadoCheck(this,'formConceptoEmpleador')" />
  Seleccionar Todos</div></td>
      </tr>
    
    
    <?php 
	$bandera = array();
	
	for($i=0; $i< count($data_concepto); $i++):
	$seleccionado = (in_array($data_concepto[$i]['id_concepto_e'], $array_id_concepto_select)) ? true : false;
	$bandera[$i]=false;
	
	for($j=0;$j<count($array_id_concepto_select); $j++):
	
		if($data_concepto[$i]['id_concepto_e'] == $array_id_concepto_select[$j]['id_concepto_e']):
		
	?>  
    
    <tr>
      <td><input name="id_concepto_e[]" type="text" id="id_concepto_e" size="3" value="<?php echo $data_concepto[$i]['id_concepto_e']; ?>" /></td>  
      <td>       

        <input name="estado[]" type="text" id="estado" size="3" 
        value="<?php  echo ($array_id_concepto_select[$j]['id_concepto_e']) ? 1 : 0; ?>"
        
         /></td>
      <td><?php echo $data_concepto[$i]['descripcion']; //-?></td>
<td>
        <input name="id_concepto_e_empleador[]" type="text" id="id_concepto_e_empleador" size="3"
      value="<?php echo $array_id_concepto_select[$j]['id_concepto_e_empleador']; ?>"
       />
        <input type="checkbox" name="chk_detalle_concepto[]" id="chk_detalle_concepto_<?php echo $data_detalle_concepto[$i]['cod_detalle_concepto']; //-?>" 
        
    value="0"
    
    
    
    <?php echo ($array_id_concepto_select[$j]['seleccionado'] == "1" ) ? ' checked="checked"' : ''; ?>
    
    
    onclick="marcar(this,'seleccionado-<?php echo $i; ?>')"
    
     />
        <input name="seleccionado[]" id="seleccionado-<?php echo $i; ?>"  type="text" size="2" 
        value="<?php echo ($array_id_concepto_select[$j]['seleccionado'] == "1" ) ? 1 : 0; ?>"
        /></td>
      </tr>
<?php
	$bandera[$i] = true;
	break;	
	endif;	
	endfor;


	if($bandera[$i]==false):
?>
    <tr>
      <td><input name="id_concepto_e[]" type="text" id="id_concepto_e" size="3" value="<?php echo $data_concepto[$i]['id_concepto_e']; ?>" /></td>  
      <td>
        <input name="estado[]" type="text" id="estado" size="3" 
        value="0"
         /></td>
      <td><?php echo $data_concepto[$i]['descripcion']; //-?></td>
<td>
        <input name="id_concepto_e_empleador[]" type="text" id="id_concepto_e_empleador" size="3"
      value=""
       />
        <input type="checkbox" name="chk_detalle_concepto[]" id="chk_detalle_concepto_<?php echo $i;?>"         
    value="1" onclick="marcar(this,'seleccionado-<?php echo $i; ?>')" />
    

        <input name="seleccionado[]" type="text"  size="2" 
        id="seleccionado-<?php echo $i; ?>"
        value="0"
        />
    
    </td>
</tr>

<?php
endif;
endfor;
?> 
  </table>
  <p>&nbsp;</p>
</div>

<input name="btnGrabar" type="button" value="Grabar" onclick="validarConceptoEmpleador()" />

</form>


















        
      </div>
      
      
      <!--tabs-2-->  
      
</div>







<!-- -->

<div id="dialog-form-editarAfectacion" title="Editar Afectacion">
    <div id="editarAfectacion" align="left"></div>
</div>
