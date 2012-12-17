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


//-------------------------------------------------------------------------------

//$datas = new Dcem_Pingreso();
//$data_cantidad = cantidadDetalleConceptoEM( $cod_concepto, ID_EMPLEADOR_MAESTRO );

//$pingreso = listarDcem_Pingreso($ID_TRABAJADOR_PDECLARACION);
$conceptos= array('100','200','300','400','500','900');
//$conceptos = "90";
$pingreso = array();
$pingreso = view_listarConcepto(ID_EMPLEADOR_MAESTRO,$conceptos);

//echo "ID_EMPLEADOR_MAESTRO essss = ".ID_EMPLEADOR_MAESTRO;
/*
echo "<pre>";
print_r($calc_conceptos);
echo "</pre>";
*/



?>
<script type="text/javascript">
$(document).ready(function() {
	
	$("#text_form").keyup(function() {
		generate_code( $("#text_form").val() );	//le pasa el caracter A la funcion
	});
	

});

// VARIBALES BASE  
var tabla = document.getElementById("tb_Pingreso");
var num_fila_tabla_ingreso = contarTablaFila(tabla);
num_fila_tabla_ingreso = num_fila_tabla_ingreso - 1; //Descuento de Cabecera.


inicioCalc();
calcularPingreso();

	
	//var div = document.createElement('tr');

function calcularPingreso(){	
	// d = devengado
	// p = pagado
	var d_total = document.getElementById('pt_total_devengado');
	var p_total = document.getElementById('pt_total_pagado');
	

	console.log("num_fila_tabla_ingreso "+ num_fila_tabla_ingreso);
	var ID = num_fila_tabla_ingreso;
	
	var d_suma = 0;
	var p_suma = 0;
	for(var i=1;i<=parseInt(ID);i++){
		
	console.log("*INICIO. i="+i+"  ID = "+ID);
		//console.log("");
		var d_num = document.getElementById('pt_devengado-'+i).value;
		var p_num = document.getElementById('pt_pagado-'+i).value;
		
		d_num = (parseFloat(d_num)>0) ? parseFloat(d_num) : 0; 		
		p_num = (parseFloat(p_num)>0) ? parseFloat(p_num) : 0;
		
		
			console.log("----"+i+"----");	
			console.log("devengado d_num "+d_num);
			console.log("pagado p_num "+p_num);
			
			console.log("-----sum "+i+"----");
			console.log("devengado d_suma "+d_suma);
			console.log("pagado p_suma "+p_suma);			
					
		d_suma = d_suma + d_num;
		p_suma = p_suma + p_num;
		
		console.log("*ACABO. i="+i+"  ID = "+ID);
	}
	//----	
	d_total.value = d_suma;
	p_total.value = p_suma;
	//----
	console.log("total es : devengado = "+d_suma);
	console.log("total es : pagado = "+p_suma);	
	
}
//-------------------
//--------------------


///------------------------------------------------------------------------------------




function duplicarDatoDevengado(id){ console.log(id);
	
	var d = document.getElementById('pt_devengado-'+id);
	var p = document.getElementById('pt_pagado-'+id);
	
	//-----------------------//
	var text = d.value;
	var string = "";
	for(var i=0; i<text.length; i++){
		string = string + text.charAt(i)
	}
	
	//string = ponerdecimales(string);
	
	p.value = string;

	//console.log("string "+string);
	
/*
	$("#"+id_text_devengado).keyup(function() {
		generate_code( $("#"+id_text_devengado).val(), pt_pagado );	//le pasa el caracter A la funcion
	});*/
		

}



function generate_code(text,txt_id) {
	var str = "";
	
	for(i = 0; i < text.length; i++) {
		ch = text.charAt(i).toLowerCase(); //el primer caracter .convierte en minuscula
		str = str + ch; 
		console.log("str "+str);
	}
	
	str = ponerdecimales(str);
	
	$("#"+txt_id).html( str );

}

function inicioCalc(){
// copiar valores de un INPUT A OTRO INPUT
	var data = $(".idd");	
	console.log("contador tabla ingresos = "+data.length);
	
	for(var id = 1; id<=data.length;id++){
		//console.log("entro "+id);
		var d = document.getElementById('pt_devengado-'+id);
		var p = document.getElementById('pt_pagado-'+id);
		
		if(d.value =='' ||d.value ==null || d == null){
			p.value= null;	
		}else{
			p.value= d.value;
		}		
		//console.log(id+" "+d.value);
		//console.log(id+" "+p.value);
		p.value= d.value;
	}
console.log('inicioCalc FIN');
}

</script>
<div class="ptrabajador">
  <div class="ocultar">
  id_dcem_pingreso<input name="id_dcem_pingreso" type="text" readonly="readonly" />
  <label for="text_form"></label>
  <input type="text" name="text_form" id="text_form" />
  </div>
    <h3>Ingresos:  </h3>
    <hr />
    <table width="670" border="1" id="tb_Pingreso" class="tabla_gris">
        <tr>
          <td width="17">&nbsp;</td>
          <td width="62">C&oacute;digo</td>
            <td width="266">Concepto</td>
            <td width="121">Devengado(S/.)</td>
            <td width="170">Pagado(S/.)</td>
        </tr>



        <?php
		
        if (count($pingreso) >= 1):
			//ID
			$ID = 0;
            for ($i = 0; $i < count($pingreso); $i++):
			$ID++;
        
		?>  



                <tr>
                    <td>
                    <input type="hidden" class="idd" name="id[]" size="1"
                    value="<?php echo $pingreso[$i]['id_detalle_concepto_empleador_maestro']; ?>" />
			    </td>
                    <td>
                        <input name="pt_codigo" type="hidden" id="" size="4" 
                               value="<?php echo $pingreso[$i]['cod_detalle_concepto'];?>"/>
                               <?php echo $pingreso[$i]['cod_detalle_concepto'];?>
                      
                      
                      <input name="id_declaracion_dconcepto" type="hidden" id="id_declaracion_dconcepto" size="2"                        
                        value="<?php for($x=0; $x<count($calc_conceptos); $x++):
								   if($pingreso[$i]['cod_detalle_concepto'] == $calc_conceptos[$x]['cod_detalle_concepto'] ):
									   echo $calc_conceptos[$x]['id_declaracion_dconcepto'];
									   break;
									endif;
								endfor;?>" />
                      <label for="estado"></label>
                        <input name="estado" type="hidden" id="estado" size="1" />

                    </td>
                    <td><?php echo $pingreso[$i]['descripcion']; ?></td>
                    <td><label for="pt_devengado"></label>
                        <input name="pt_devengado" type="text" id="pt_devengado-<?php echo $ID;?>" onkeyup="duplicarDatoDevengado(<?php echo $ID;?>)"
                               value="<?php 							   
							   for($x=0; $x<count($calc_conceptos); $x++):
								   if($pingreso[$i]['cod_detalle_concepto'] == $calc_conceptos[$x]['cod_detalle_concepto'] ):
									   echo $calc_conceptos[$x]['monto_pagado'];
									   break;
									endif;
								endfor;
							   ?>" size="8"
                              maxlength="7" readonly="readonly" />                    </td>
                    <td><input name="pt_pagado" type="text" id="pt_pagado-<?php echo $ID;?>"
                               value="" size="8" maxlength="7" readonly="readonly" />                    </td>
                </tr>

                <?php
            endfor;
        endif;
        ?>   











    </table>
    <br />
    <div class="ocultar">701 = adelanto = suma de todos los adelantos dentro del mes
    </div>
  <p>&nbsp;</p>



</div>

















<table width="670" border="1" class="tb">
    <tr>
        <td width="13">&nbsp;</td>
        <td width="13">&nbsp;</td>
        <td width="320">TOTAL INGRESOS:
        </td>
        <td width="121"><label for="pt_total_devengado"></label>
            <input name="pt_total_devengado" type="text" id="pt_total_devengado" value="0.00" size="8" readonly="readonly" /></td>
        <td width="169"><input name="pt_total_pagado" type="text" id="pt_total_pagado" value="0.00" size="8" readonly="readonly" /></td>
    </tr>
</table>
<p id="text_display">&nbsp;</p>

