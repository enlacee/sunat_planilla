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
inicioCalc();

//var 
	var tabla = document.getElementById("tb_Pingreso");
	var num_fila_tabla = contarTablaFila(tabla);
	num_fila_tabla = num_fila_tabla - 1; //Descuento de Cabecera.
	
	var ID =  num_fila_tabla; //+ 1;

	
	//var div = document.createElement('tr');

	//while(){
		
	//}

function calcularPingreso(){
	
	// d = devengado
	// p = pagado
	var d_total = document.getElementById('pt_total_devengado');
	var p_total = document.getElementById('pt_total_pagado');
	
	console.log("***********************************");
	console.log("***********INICIOOO****************");
	console.log("***********************************");
//	console.log("num_fila_tabla "+ num_fila_tabla);
//	console.log("ID "+ ID);
	
	var d_suma = 0;
	var p_suma = 0;
	for(var i=1;i<=parseInt(ID);i++){

		//console.log("");
		var d_num = document.getElementById('pt_devengado-'+i).value;
		var p_num = document.getElementById('pt_pagado-'+i).value;
		
		d_num = (parseInt(d_num)>0) ? parseInt(d_num) : 0; 		
		p_num = (parseInt(p_num)>0) ? parseInt(p_num) : 0;
//			console.log("----"+i+"----");	
//			console.log("d_num "+d_num);
//			console.log("p_num "+p_num);
		
					
		d_suma = d_suma + d_num;
		p_suma = p_suma + p_num;
	}
	//----	
	d_total.value = d_suma;
	p_total.value = p_suma;
	//----
	

}
//-------------------
//--------------------
calcularPingreso();

//---------------------
/*
function duplicarDevengado(''){
	var document.getElementById('');
	var document.getElementById('');

}
*/

///------------------------------------------------------------------------------------


$(document).ready(function() {
	//generate_code( $("#text_form").val() );	
	
	alert("refy");
	
	$("#text_form").keyup(function() {
		generate_code( $("#text_form").val() );	//le pasa el caracter A la funcion
	});
	
	/*$(".opt").change(function() {
		if ( $("#text_form").val() != "" )					  
			generate_code( $("#text_form").val() );						  
	});*/

});


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

	console.log("string "+string);
	
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
//console.log("innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn n n nn   n n n");
var data = $(".idd");
for(var id = 1; id<=data.length;id++){
	console.log("entro "+id);
	var d = document.getElementById('pt_devengado-'+id);
	var p = document.getElementById('pt_pagado-'+id);
	console.log(id+" "+d.value);
	console.log(id+" "+p.value);
	p.value=d.value;
}
//console.log(data);
//console.dir(data);
//console.log("finnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn n n nn   n n n");
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
    <table width="670" border="1" id="tb_Pingreso">
        <tr>
            <td width="22">&nbsp;</td>
            <td width="57">C&oacute;digo</td>
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
                    <input type="hidden" class="idd" name="id[]" size="1" />                    <?php echo $pingreso[$i]['id_detalle_concepto_empleador_maestro']; ?></td>
                    <td><label for="pt_codigo"></label>
                        <input name="pt_codigo" type="text" id="" size="4" 
                               value="<?php echo $pingreso[$i]['cod_detalle_concepto']; //echo $pingreso[$i]['ID_TRABAJADOR_PDECLARACION'];   ?>"      />
                      <label for="id_declaracion_dconcepto"></label>
                        <input name="id_declaracion_dconcepto" type="text" id="id_declaracion_dconcepto" size="2"
                        
                        value="<?php for($x=0; $x<count($calc_conceptos); $x++):
								   if($pingreso[$i]['cod_detalle_concepto'] == $calc_conceptos[$x]['cod_detalle_concepto'] ):
									   echo $calc_conceptos[$x]['id_declaracion_dconcepto'];
									   break;
									endif;
								endfor;?>" />
                      <label for="estado"></label>
                        <input name="estado" type="text" id="estado" size="1" />

                    </td>
                    <td><?php echo $pingreso[$i]['descripcion']; ?></td>
                    <td><label for="pt_devengado"></label>
                        <input name="pt_devengado" type="text" id="pt_devengado-<?php echo $ID;?>" size="8"
                              maxlength="7" onkeyup="duplicarDatoDevengado(<?php echo $ID;?>)"
                               value="<?php 							   
							   for($x=0; $x<count($calc_conceptos); $x++):
								   if($pingreso[$i]['cod_detalle_concepto'] == $calc_conceptos[$x]['cod_detalle_concepto'] ):
									   echo $calc_conceptos[$x]['monto_pagado'];
									   break;
									endif;
								endfor;
							   ?>" />
                    </td>
                    <td><input name="pt_pagado" type="text" id="pt_pagado-<?php echo $ID;?>" size="8"
                               value="" maxlength="7" />
                    </td>
                </tr>

                <?php
            endfor;
        endif;
        ?>   











    </table>
    <br />
    701 = adelanto = suma de todos los adelantos dentro del mes
    <p>&nbsp;</p>



</div>

















<table width="670" border="1" class="tb">
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>TOTAL INGRESOS:
        <input type="button" name="btncalc" id="btncalc" value="Calcular"  onclick="calcularPingreso()"/></td>
        <td><label for="pt_total_devengado"></label>
            <input name="pt_total_devengado" type="text" id="pt_total_devengado" value="0.00" size="8" /></td>
        <td><input name="pt_total_pagado" type="text" id="pt_total_pagado" value="0.00" size="8" /></td>
    </tr>
</table>
<p id="text_display">&nbsp;</p>

