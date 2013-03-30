<?php
//*******************************************************************//
require_once '../../view/ide2.php';
//*******************************************************************//
//require_once('../../util/funciones.php');
require_once('../../dao/AbstractDao.php');

//IDE
require_once('../../controller/ideController2.php');

require_once('../../dao/PlameDetalleConceptoEmpleadorMaestroDao.php');
require_once('../../controller/PlameDetalleConceptoEmpleadorMaestroController.php');


$ID_TRABAJADOR_PDECLARACION = $_REQUEST['id_ptrabajador'];

require_once("../../controller/DeclaracionDconceptoController.php");
require_once("../../dao/DeclaracionDconceptoDao.php");
//-------------------------------------------------------------------------------
$calc_conceptos = array();
$calc_conceptos = listar_concepto_calc_ID_TrabajadorPdeclaracion($ID_TRABAJADOR_PDECLARACION);


$pdescuentoEmp = array();
$pdescuentoEmp = view_listarConcepto(ID_EMPLEADOR_MAESTRO,1000,1);

//echo "ID_EMPLEADOR_MAESTRO essss = ".ID_EMPLEADOR_MAESTRO;


?>
<script>

//alert("hello word!!!");
var tabla = document.getElementById("Pdescuento");
var num_fila_tabla = contarTablaFila(tabla);

num_fila_tabla = num_fila_tabla - 1; //Descuento de Cabecera.

var ID =  num_fila_tabla; //+ 1;

console.log("Pdescuento ID ====== "+ID);
//alert("ID = "+ID);

function calcularDescuento(){	
console.log("Pdescuento ID ====== "+ID);
	// d = descuento
	var d_total = document.getElementById('pt_total_dscto');
	
	var d_suma = 0;
	

	for(var i=1;i<=parseInt(ID);i++){
		var d_num = document.getElementById('pt_monto-'+i).value;
		d_num = (parseFloat(d_num)>0) ? parseFloat(d_num) : 0; 	
//			console.log("----"+i+"----");	
		d_suma = d_suma + d_num;
	}
	//----	
	d_total.value = d_suma;	
	//----
	

}
//-------------------
//--------------------
calcularDescuento();

</script>
<div class="ptrabajador">

<h3>Empresa:</h3>
    <hr />
  <table width="670" border="1" id="PdescuentoEmp" class="PdescuentoEmp tabla_gris">
    <tr>
      <td width="17">&nbsp;</td>
      <td width="118">C&oacute;digo</td>
      <td width="342">Concepto</td>
      <td width="165">Monto(S/.)</td>
    </tr>
    
    
        <?php
        if (count($pdescuentoEmp) >= 1):
			//ID
			$ID = 0;

            for ($i = 0; $i < count($pdescuentoEmp); $i++):
			$ID++
                ?>  
    
    
    
    
    <tr>
      <td>
<input type="hidden" class="idd" name="id_2[]" size="1"
value="<?php echo $pdescuentoEmp[$i]['id_detalle_concepto_empleador_maestro']; ?>"/>	  
	  </td>
      <td><?php echo $pdescuentoEmp[$i]['cod_detalle_concepto'];?>
      </td>
      <td><?php echo $pdescuentoEmp[$i]['descripcion'];?></td>
      <td><label for="pt_monto"></label>
      <input name="pt_monto" type="text" id="pt_monto-<?php echo $ID;?>" 
       value="<?php 
	   for($x=0; $x<count($calc_conceptos); $x++):
		   if($pdescuentoEmp[$i]['cod_detalle_concepto'] == $calc_conceptos[$x]['cod_detalle_concepto'] ):
			   echo $calc_conceptos[$x]['monto_pagado'];
			   break;
			endif;
		endfor;
	   ?>" size="8" readonly="readonly" />       </td>
    </tr>
    
    
                <?php
            endfor;
        endif;
        ?>   
    
    
    
    
    
  </table>
  <p>&nbsp;</p>
</div>
<table width="670" border="1" class="tb">
  <tr>
    <td width="21">&nbsp;</td>
    <td width="21">&nbsp;</td>
    <td width="436">TOTAL DESCUENTOS:</td>
    <td width="164"><label for="pt_total_dscto"></label>
      <input name="pt_total_dscto" type="text" id="pt_total_dscto" value="0.00" size="8" readonly="readonly" /></td>
  </tr>
</table>