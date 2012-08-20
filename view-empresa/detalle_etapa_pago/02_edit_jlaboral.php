<?php
//*******************************************************************//
require_once('../../view/ide2.php');
//*******************************************************************//
//require_once('../../dao/AbstractDao.php');
//--------------combo CATEGORIAS
require_once('../../util/funciones.php');
// inicombos
require_once '../../dao/ComboCategoriaDao.php';
require_once '../../controller/ComboCategoriaController.php';

$suspencion1 = comboSuspensionLaboral_1();

$suspencion2 = comboSuspensionLaboral_2();
//fin combos
//ACTUALIZAR
require_once '../../model/Pago.php';
require_once '../../dao/PagoDao.php';
require_once '../../controller/PagoController.php';

//--- Dia subsidiado
require_once '../../controller/PlameDiaSubsidiadoController.php';
require_once '../../model/PdiaSubsidiado.php';
require_once '../../dao/PdiaSubsidiadoDao.php';

//--- Dia no subsidiado
require_once '../../controller/PlameDiaNoSubsidiadoController.php';
require_once '../../dao/PdiaNoSubsidiadoDao.php';
require_once '../../model/PdiaNoSubsidiado.php';


$id_ptrabajador = $_REQUEST['id_ptrabajador'];
$ID_PAGO = ($_REQUEST['id_pago']);


$obj_pago = new Pago();
$obj_pago = buscarPagoPor_ID($ID_PAGO);

//echo "<pre>PjornadaLaboral";
//echo print_r($obj_pago);
//echo "</pre>";

$data_ds = buscarDiaSPor_IdPago($obj_pago->getId_pago());
$data_dns = buscarDiaNoSPor_IdPago($obj_pago->getId_pago());


//Obtener dias Subsidiados
$dia_laborado_calc = $obj_pago->getDia_total();//$obj_pago->getDia_laborado();

$dia_subsidiado = 0;
$dia_nosubsidiado = 0;

for ($i = 0; $i < count($data_ds); $i++) {    
    $dia_subsidiado += $data_ds[$i]->getCantidad_dia();
}

for ($i = 0; $i < count($data_dns); $i++) {
    $dia_nosubsidiado = $dia_nosubsidiado + $data_dns[$i]->getCantidad_dia();
}

// Operacion

$dia_laborado_calc = $dia_laborado_calc - ($dia_subsidiado + $dia_nosubsidiado);


?>
<script type="text/javascript">

    //----------------------------------------------------------
    // INICIO DIAS SUBSIDIADOS	
    //----------------------------------------------------------

    var dia_laborado = parseInt(document.getElementById('dia_laborado').value);

	var dia_total =  parseInt(document.getElementById('dia_total').value);
	
	
    var num_dia_subsidiado = parseInt(document.getElementById('dia_subsidiado').value); 	
    var num_dia_nosubsidiado = parseInt(document.getElementById('dia_nosubsidiado').value); 
	
	var max_dias = (dia_laborado + num_dia_subsidiado + num_dia_nosubsidiado);  // =23
	
	
	
    var subs = new Array(
<?php $counteo = count($suspencion1);
for ($i = 0; $i < $counteo; $i++):
    ?>	
    <?php
    if ($i == $counteo - 1) {
        echo "{id:'" . $suspencion1[$i]['cod_tipo_suspen_relacion_laboral'] . "', descripcion:'" . $suspencion1[$i]['cod_tipo_suspen_relacion_laboral'] . "-" . $suspencion1[$i]['descripcion_abreviada'] . "' }";
    } else {
        echo "{id:'" . $suspencion1[$i]['cod_tipo_suspen_relacion_laboral'] . "', descripcion:'" . $suspencion1[$i]['cod_tipo_suspen_relacion_laboral'] . "-" . $suspencion1[$i]['descripcion_abreviada'] . "' },";
    }
    ?>
<?php endfor; ?>
);//end array




    function cargarSuspension_1(objCombo,ids){

        var counteo = 	subs.length;
        console.dir(subs);
        var z =0;
        //variables
        var arreglo = new Array();
        var eliminados = new Array();

        for(i=0; i<ids.length;i++){
	
            for(var j=0;j<subs.length;j++){
		
                if( subs[j].id == ids[i] ){ //ENCONTRO
                    //continue;			
                    eliminados = subs.splice(j,1);
                    console.log(eliminados);
                }	
						
            }//ENDFOR 2
	
	
        }//ENDFOR 1


        for(var i=0;i<subs.length;i++){
            objCombo.options[i] = new Option(subs[i].descripcion, subs[i].id);
        }
		
	
    }//ENDFN


//validarNuevoInput_2
    function validarNuevoInput(){
		
        if(parseInt(dia_laborado)<=0){
            alert("No hay mas dias para ser subsidiados");
        }else{

            if(subs.length == 1){
                alert("No existe mas Conceptos por agregar");
            }else{
                nuevoDiaSubsidiado();
            }
        }

    }
    //--------------------------------------------------------------------------------


    function nuevoDiaSubsidiado(){
        //alert("holaaaaa");	
        //Capa Contenedora
        var tabla = document.getElementById("tb_dsubsidiado");
        var num_fila_tabla = contarTablaFila(tabla);
        num_fila_tabla = num_fila_tabla - 1;
        var COD_DETALLE_CONCEPTO =  num_fila_tabla + 1;

        var div = document.createElement('tr');
        div.setAttribute('id','dia_subsidiado-'+COD_DETALLE_CONCEPTO);
        //
        tabla.appendChild(div); //PRINCIPAL	
	

        //-------------------------------------------------------------
        //---- CODIGO
        var id = '<input type="hidden" size="4" id="pdia_subsidiado-'+ COD_DETALLE_CONCEPTO +'" name="pdia_subsidiado[]" value ="" >';
        var estado = '<input type="hidden" size="4" id="estado-'+ COD_DETALLE_CONCEPTO +'" name="estado[]" value ="0" >';
        //var codigo = '<input type="text" size="4" id="cod_detalle_concepto_" name="cod_detalle_concepto[]" value = '+ COD_DETALLE_CONCEPTO +'>';

        var input_cdia = '<input name="ds_cantidad_dia[]" type="text" id="ds_cantidad_dia-'+COD_DETALLE_CONCEPTO+'" size="7"  onblur="calcDiaSubsidiado()"/>';
        //-----input Descripcion
        var span1;
        var span2;


        var finSpan = '</span>';

        span1 = '<span title="editar">';
        span1 +="<a href=\"javascript:editar_ds('"+COD_DETALLE_CONCEPTO+"')\"><img src=\"images/edit.png\"></a>";
        span1 += finSpan;

        //html +='    <a href="javascript:eliminarElemento( document.getElementById( \'plaboral-row-'+id+'\' ) )" > delete </a>';
        span2 = '<span title="editar">';
        span2 +="<a href=\"javascript:eliminar_ds( 'dia_subsidiado-"+COD_DETALLE_CONCEPTO+"' )\"><img src=\"images/cancelar.png\"></a>";
        span2 += finSpan;


        var combo = "";
        combo +='     <select name="cbo_ds_tipo_suspension[]" id="cbo_ds_tipo_suspension-'+COD_DETALLE_CONCEPTO+'"  ';
        combo +='	  style="width:150px;"  onchange="" >';
        combo +='     </select>';


        //inicio html	
        var html ='';
        var cerrarTD = '<\/td>';


        html +='<td>';
        html += id;
        html += estado;
        html += combo;
        html += cerrarTD;


        html +='<td>';	
        html += input_cdia;
        html += cerrarTD;

        html +='<td>';	
        html += span1;
        html += cerrarTD;

        html +='<td>';	
        html += span2;
        html += cerrarTD;


        ////---
        div.innerHTML=html;

        //-------   - - --  -cargar combo
        cbo = document.getElementById('cbo_ds_tipo_suspension-'+COD_DETALLE_CONCEPTO);

        var ids = getIdsCombos();
        cargarSuspension_1(cbo,ids);	
	

    }

    function eliminar_ds(elementId,ID){ //alert (" "+elementId);
	
        var obj = document.getElementById(elementId)
        eliminarElemento(obj);
		
		$.ajax({
			type: "POST",
			url: "sunat_planilla/controller/PlameDiaSubsidiadoController.php",
			data: {oper : 'del', id : ID},
			async:true,
			success: function(datos){
				
			}
		   }); 



    }



    function getIdsCombos(){
        var ids = new Array();
	
        var combos = document.getElementById('formDiaSubsidiado');	
        var numElementos = combos.elements.length;
	
        for(var i=0;i<combos.elements.length;i++){
            if(combos.elements[i].type == 'select-one'){
                var id_combo = combos.elements[i].id;
                var value = combos.elements[i].value;			
                ids.push(value);
            }
        }
	

        return ids;
    }


    function grabarDiaSubsidiado(){
       
		
		if(  calcDiaSubsidiado() ==true){
			
			document.getElementById('dia_subsidiado').value = num_dia_subsidiado;
			document.getElementById('dia_laborado').value = dia_laborado;//dia_laborado - num_dia_subsidiado;				
			alert("SESSION = La informacion se Guardo\nCorrectamente.");	
        //-----
        var data = $("#formDiaSubsidiado").serialize();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'sunat_planilla/controller/PlameDiaSubsidiadoController.php?'+data,
            data: {/*id_pjornada_laboral: id_pjornada_laboral*/},
            success: function(json){
				
            }
        });
		//----
		}
	
	

    }
	

    function calcDiaSubsidiado(){
		var ESTADO = true;

        var tabla = document.getElementById("tb_dsubsidiado");
        var num_fila = parseInt(contarTablaFila(tabla));
        num_fila = num_fila - 1;		
        	
        var total = document.getElementById('ds_total');		
		
		//-----------------------------------------
		var max_dias_main = (max_dias) - num_dia_nosubsidiado;
		var max_dias_local = (max_dias) - num_dia_nosubsidiado;
		//-----------------------------------------
		
		var num_dia_subsidiado_local=0;		
		//alert("max_dias_local = "+max_dias_local);
		

        for(var i= 1; i <= num_fila; i++){ console.log("ciclo iiii="+ i);
	
            var cbo = document.getElementById('cbo_ds_tipo_suspension-'+i);
            var dia = document.getElementById('ds_cantidad_dia-'+i);		
		
            //	
            var diaint = parseInt( (dia.value == '' || dia.value < 0) ? 0 : dia.value );
            //rpta
            num_dia_subsidiado_local = num_dia_subsidiado_local + (diaint);	
		
		
            if( i == num_fila ){ //ULTIMO NO RESTA
                //max_dias_local = max_dias_local - diaint;	
            }else {
                max_dias_local = max_dias_local - diaint;
            }		
		
            if(max_dias_local<0){
                alert("El maximo numero de dias esS: "+ max_dias_local);
                dia.value = '';			
                diaint = 0;
				ESTADO = false;
                break;
            }		
				
            if(num_dia_subsidiado_local > max_dias_main){
                alert("El maximo numero de dias es : "+max_dias_local);
                dia.value = '';
                //num_dia_subsidiado = num_dia_subsidiado - (diaint);
                diaint = 0;
				ESTADO = false;
			
            }
		
            //rpta
           //num_dia_subsidiado + (diaint);
            dia.value = (diaint == 0) ? '' : diaint;		
		
        }
		
		//Variables Globales		
		num_dia_subsidiado = (num_dia_subsidiado_local>max_dias_main)? '' : num_dia_subsidiado_local;
        total.value = (num_dia_subsidiado_local > max_dias_main) ? '' : num_dia_subsidiado_local;

		if(num_dia_subsidiado == max_dias_main ){
			dia_laborado = 0;
		}else if(max_dias_main > num_dia_subsidiado){
			dia_laborado = max_dias_main - num_dia_subsidiado;
		}
		
		
        //alert("DIA TOTAL "+ num_dia);	
		return ESTADO;
    }
	
	function calcDiaSubsidiadoMain(){ alert("calcDiaSubsidiadoMain");	
		document.getElementById('dia_nosubsidiado').value = num_dia_nosubsidiado;
		document.getElementById('dia_laborado').value = dia_laborado - num_dia_nosubsidiado;	
	}
	
    //----------------------------------------------------------
    // FINAL DIAS SUBSIDIADOS	
    //----------------------------------------------------------











    //----------------------------------------------------------
    // INICIO DIAS NO-SUBSIDIADOS	
    //----------------------------------------------------------

    var nosubs = new Array(
<?php $counteo = count($suspencion2);
for ($i = 0; $i < $counteo; $i++):
    ?>	
    <?php
    if ($i == $counteo - 1) {
        echo "{id:'" . $suspencion2[$i]['cod_tipo_suspen_relacion_laboral'] . "', descripcion:'" . $suspencion2[$i]['cod_tipo_suspen_relacion_laboral'] . " - " . $suspencion2[$i]['descripcion_abreviada'] . "' }";
    } else {
        echo "{id:'" . $suspencion2[$i]['cod_tipo_suspen_relacion_laboral'] . "', descripcion:'" . $suspencion2[$i]['cod_tipo_suspen_relacion_laboral'] . " - " . $suspencion2[$i]['descripcion_abreviada'] . "' },";
    }
    ?>
<?php endfor; ?>
        );//end array






            function cargarSuspension_2(objCombo,ids){

                console.dir(nosubs);
                var z =0;
                //variables
                var arreglo = new Array();
                var eliminados = new Array();
                //alert("ids.length = "+ids.length);

                for(i=0; i<ids.length;i++){
	
                    for(var j=0;j<nosubs.length;j++){
		
                        if( nosubs[j].id == ids[i] ){ //ENCONTRO
                            //continue;				
                            eliminados = nosubs.splice(j,1);
                            console.log(eliminados);
                        }	
						
                    }//ENDFOR 2
	
	
                }//ENDFOR 1


                //console.log("********************");
                //console.dir(nosubs);
                //console.log("********************");

                for(var i=0;i<nosubs.length;i++){
                    objCombo.options[i] = new Option(nosubs[i].descripcion, nosubs[i].id);
                }
	
            }//ENDFN



       function validarNuevoInput_2(){
		
        if(parseInt(dia_laborado)<=0){
            alert("No hay mas dias para ser subsidiados");
        }else{

                       
            if(nosubs.length == 1){
                alert("No existe mas Conceptos por agregar");
            }else{
                nuevoDiaSubsidiado_2();
            }
        }	

            }
            //--------------------------------------------------------------------------------


            function nuevoDiaSubsidiado_2(){
                //alert("holaaaaa");	
                //Capa Contenedora
                var tabla = document.getElementById("tb_dnolaborado");
                var num_fila_tabla = contarTablaFila(tabla);
                num_fila_tabla = num_fila_tabla - 1;
                var COD_DETALLE_CONCEPTO =  num_fila_tabla + 1;

                var div = document.createElement('tr');
                //
                div.setAttribute('id','dia_nosubsidiado-'+COD_DETALLE_CONCEPTO);	
                tabla.appendChild(div); //PRINCIPAL	
	

                //-------------------------------------------------------------
                //---- CODIGO
                var id = '<input type="hidden" size="4" id="id_pdia_nosubsidiado-'+ COD_DETALLE_CONCEPTO +'" name="id_pdia_nosubsidiado[]" value ="" >';
                var estado = '<input type="hidden" size="4" id="estado-'+ COD_DETALLE_CONCEPTO +'" name="estado[]" value ="0" >';
                //var codigo = '<input type="text" size="4" id="cod_detalle_concepto_" name="cod_detalle_concepto[]" value = '+ COD_DETALLE_CONCEPTO +'>';

                var input_cdia = '<input name="dns_cantidad_dia[]" type="text" id="dns_cantidad_dia-'+COD_DETALLE_CONCEPTO+'" size="7" onblur="calcDiaNoSubsidiado()" />';
                //-----input Descripcion
                var span1;
                var span2;


                var finSpan = '</span>';

                span1 = '<span title="editar">';
                span1 +="<a href=\"javascript:editar_dns('"+COD_DETALLE_CONCEPTO+"')\"><img src=\"images/edit.png\"></a>";
                span1 += finSpan;


                span2 = '<span title="editar">';
                span2 +="<a href=\"javascript:eliminar_dns('dia_nosubsidiado-"+COD_DETALLE_CONCEPTO+"')\"><img src=\"images/cancelar.png\"></a>";
                span2 += finSpan;


                var combo = "";
                combo +='     <select name="cbo_dns_tipo_suspension[]" id="cbo_dns_tipo_suspension-'+COD_DETALLE_CONCEPTO+'"  ';
                combo +='	  style="width:150px;"  onchange="" >';
                combo +='     </select>';


                //inicio html	
                var html ='';
                var cerrarTD = '<\/td>';


                html +='<td>';
                html += id;
                html += estado;
                html += combo;
                html += cerrarTD;


                html +='<td>';	
                html += input_cdia;
                html += cerrarTD;

                html +='<td>';	
                html += span1;
                html += cerrarTD;

                html +='<td>';	
                html += span2;
                html += cerrarTD;


                ////---
                div.innerHTML=html;

                //-------   - - --  -cargar combo
                cbo = document.getElementById('cbo_dns_tipo_suspension-'+COD_DETALLE_CONCEPTO);

                var ids = getIdsCombos_2();
                cargarSuspension_2(cbo,ids);	
	

            }



	function eliminar_dns(elementId,ID){ //alert (" "+elementId);

		var obj = document.getElementById(elementId)
		eliminarElemento(obj);
	
		$.ajax({
			type: "POST",
			url: "sunat_planilla/controller/PlameDiaNoSubsidiadoController.php",
			data: {oper : 'del', id : ID},
			async:true,
			success: function(datos){
				
			}
		   }); 


	}



            function getIdsCombos_2(){
                var ids = new Array();
	
                var combos = document.getElementById('formDiaNoSubsidiado');	
                var numElementos = combos.elements.length;
	
                for(var i=0;i<combos.elements.length;i++){
                    if(combos.elements[i].type == 'select-one'){
                        var id_combo = combos.elements[i].id;
                        var value = combos.elements[i].value;			
                        ids.push(value);
                    }
                }

                return ids;
	
            }




            function grabarDiaNoLaborado(){
	
                //calcDiaSubsidiado();
                document.getElementById('dia_nosubsidiado').value = num_dia_subsidiado;
                document.getElementById('dia_laborado').value = dia_laborado - num_dia_subsidiado;	
		
		
                alert("***SESSION = La informacion se Guardo\nCorrectamente.");
				
                //-----
                var data = $("#formDiaNoSubsidiado").serialize();
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: 'sunat_planilla/controller/PlameDiaNoSubsidiadoController.php?'+data,
                    data: {/*id_pjornada_laboral: id_pjornada_laboral*/},
                    success: function(json){
				
                    }
                });

	
            }




//-----------
    function calcDiaNoSubsidiado(){
		
		//-----
		var ESTADO = true;

        var tabla = document.getElementById("tb_dnolaborado");
        var num_fila = parseInt(contarTablaFila(tabla));
        num_fila = num_fila - 1;		
        	
        var total = document.getElementById('dns_total');		
		
		//-----------------------------------------
		var max_dias_main = (max_dias) - num_dia_subsidiado;
		var max_dias_local = (max_dias) - num_dia_subsidiado;
		//-----------------------------------------
		
		var num_dia_nosubsidiado_local=0;
//------
		
        for(var i= 1; i <= num_fila; i++){ console.log("ciclo iiii="+ i);
	
            var cbo = document.getElementById('cbo_dns_tipo_suspension-'+i);
            var dia = document.getElementById('dns_cantidad_dia-'+i);		
		
            //	
            var diaint = parseInt( (dia.value == '' || dia.value < 0) ? 0 : dia.value );
            //rpta
            num_dia_nosubsidiado_local = num_dia_nosubsidiado_local + (diaint);	
		
		
            if( i == num_fila ){ //ULTIMO NO RESTA
                //max_dias_local = max_dias_local - diaint;	
            }else {
                max_dias_local = max_dias_local - diaint;
            }		
		
            if(max_dias_local<0){
                alert("El maximo numero de dias esS: "+ max_dias_local);
                dia.value = '';			
                diaint = 0;
				ESTADO = false;
                break;
            }		
				
            if(num_dia_nosubsidiado_local > max_dias_main){
                alert("El maximo numero de dias es : "+max_dias_local);
                dia.value = '';                
                diaint = 0;
				ESTADO = false;
			
            }
		
            //rpta          
            dia.value = (diaint == 0) ? '' : diaint;		
		
        }
//-----------

		//Variables Globales		
		num_dia_nosubsidiado = (num_dia_nosubsidiado_local>max_dias_main)? '' : num_dia_nosubsidiado_local;
        total.value = (num_dia_nosubsidiado_local > max_dias_main) ? '' : num_dia_nosubsidiado_local;

		if(num_dia_nosubsidiado == max_dias_main ){
			dia_laborado = 0;
		}else if(max_dias_main > num_dia_nosubsidiado){
			dia_laborado = max_dias_main - num_dia_nosubsidiado;
		}
		
		
        //alert("DIA TOTAL "+ num_dia);	
		return ESTADO;
//----------


		  
		
}//ENDFN

//-----
function grabarDiaNoSubsidiado(){

		if(  calcDiaNoSubsidiado() ==true){
			
			document.getElementById('dia_nosubsidiado').value = num_dia_nosubsidiado;
			document.getElementById('dia_laborado').value = dia_laborado;//dia_laborado - num_dia_subsidiado;				
			alert("SESSION = La informacion se Guardo\nCorrectamente.");	
        //-----
        var data = $("#formDiaNoSubsidiado").serialize();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'sunat_planilla/controller/PlameDiaNoSubsidiadoController.php?'+data,
            data: {/*id_pjornada_laboral: id_pjornada_laboral*/},
            success: function(json){
				
            }
        });
		//----
		}

}



            //----------------------------------------------------------
            // FINAL DIAS NO-SUBSIDIADOS	
            //----------------------------------------------------------



//-----------------------------------------------------------------------
// HORAS
//-----------------------------------------------------------------------
	var hora_o = document.getElementById('hora_ordinaria_hh');
	var min_o = document.getElementById('hora_ordinaria_mm');
	
	var hora_s = document.getElementById('hora_sobretiempo_hh');
	var min_s = document.getElementById('hora_sobretiempo_mm');
	
	var total_hora = document.getElementById('total_hora_hh');
	var total_min = document.getElementById('total_hora_mm');

//	var expr_hora =/^\d{3}$/;
//	var bandera = expresion_regular_vf_mes.test(valor_txt);

function calcHoraLaborada(){ 

	//------------
	// Hora total
	var total_hora_local = 0;
	var hora_o_local = ( parseInt(hora_o.value)>=0 ) ? parseInt(hora_o.value) : 0;
	var hora_s_local = ( parseInt(hora_s.value)>=0 ) ? parseInt(hora_s.value) : 0;		
		total_hora_local = hora_o_local + hora_s_local;
		
	// Min total
	var total_min_local = 0;
	var min_o_local = ( parseInt(min_o.value)>=0 ) ? parseInt(min_o.value) : 0;
	var min_s_local = ( parseInt(min_s.value)>=0 ) ? parseInt(min_s.value) : 0;
		total_min_local = min_o_local + min_s_local;
		
	console.log("hora_o_local "+hora_o_local);
	console.log("hora_s_local "+hora_s_local);
	
	console.log("min_o_local "+min_o_local);
	console.log("min_s_local "+min_s_local);	
	//------------	
	//------------	
	
	var divicion = (total_min_local/60);
	
	//alert("total_min_local "+total_min_local);
	
	while( (total_min_local/60) >= 1 ){ 			
		
		total_min_local = total_min_local - 60;		
		total_hora_local = total_hora_local + 1;
	}
	
	//------
	total_hora.value = total_hora_local;
	total_min.value = total_min_local;
	//------
	
	console.log("total_hora_local "+total_hora_local);
	console.log("total_min_local "+total_min_local);
	

}


//----------------------------------------------------------
/*
function calcHoraLaboradaEvento(event){
    // 8 -> borrado
    // 9 -> tabulador
    // 37-40 -> flechas
    // 188 -> .
    // 190 -> ,    
    if ( event.keyCode == 8 || event.keyCode == 9 || (event.keyCode >= 37 && event.keyCode <= 40)
            || event.keyCode == 188 || event.keyCode == 190 ) {
        // permitimos determinadas teclas, no hacemos nada
		//calcHoraLaborada();
    } else {
		//calcHoraLaborada();
        // Nos aseguramos que sea un numero, sino evitamos la accion
        if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
            event.preventDefault();
			//calcHoraLaborada();
        }   
    }
}
*/


calcHoraLaborada();

</script>

<div class="ptrabajador">

    <div class="section">
      <div class="article fila1">
            
    <h3>Dias de Jornada</h3>
            <hr />
            <p>
                <label for="dia_laborado">Laborados</label>
                <input name="dia_laborado" type="text" id="dia_laborado" size="4" readonly="readonly"
                       value="<?php echo $dia_laborado_calc; ?>" />
            </p>
        <p>
              <label for="dia_subsidiado">Subsidiados</label>
              <input name="dia_subsidiado" type="text" id="dia_subsidiado" 
                       value="<?php echo $dia_subsidiado; ?>" size="4" readonly="readonly" />
            <span>
                <a href="javascript:editarDiaSubsidiado( '<?php echo $obj_pago->getId_pago(); ?>')">
                    <img src="images/edit.png"></a></span>            </p>
  <p>
                <label for="dia_nosubsidiado">No laborados y no subsidiados:</label>
                <input name="dia_nosubsidiado" type="text" id="dia_nosubsidiado" 
                       value="<?php echo $dia_nosubsidiado; ?>" size="4" readonly="readonly" />
                <span >
                <a href='javascript:editarDiaNoLaborado("<?php echo $obj_pago->getId_pago(); ?>")'>
                <img src="images/edit.png"></a></span>    </p>
            <h3>TOTAL: 
                <label for="dia_total"></label>
                <input name="dia_total" type="text" id="dia_total" size="4" readonly="readonly"
                       value="<?php echo $obj_pago->getDia_total(); ?>" />
            </h3>
            <p>&nbsp;</p>
      </div>
        <div class="article fila2">
            <h3>Horas Laboradas</h3>
            <hr />
            <p>        
                <label for="hora_ordinaria_hh">Ordinarias (HHHH:MM)</label>
                <input name="hora_ordinaria_hh" type="text" id="hora_ordinaria_hh"
                       value="<?php echo $obj_pago->getOrdinario_hora();?>"
                 onkeydown="soloNumeros(event)" size="5" maxlength="3" readonly="readonly" />
                :
                <input name="hora_ordinaria_mm" type="text" id="hora_ordinaria_mm"
                       value="<?php echo $obj_pago->getOrdinario_min();?>"
                onkeydown="soloNumeros(event)" size="5" maxlength="2" readonly="readonly" />
            </p>
            <p>
                <label for="hora_sobretiempo_hh">Sobretiempo(HHH:MM)</label>
                <input name="hora_sobretiempo_hh" type="text" id="hora_sobretiempo_hh" size="5" maxlength="3"
                       value="<?php echo $obj_pago->getSobretiempo_hora(); ?>"
                onkeydown="soloNumeros(event)" />
                :
                <input name="hora_sobretiempo_mm" type="text" id="hora_sobretiempo_mm" size="5" maxlength="2"
                       value="<?php echo $obj_pago->getSobretiempo_min();?>"
                onkeydown="soloNumeros(event)" onblur="" />
            </p>
            <h3>TOTAL HORAS:
                <label for="total_hora_hh"></label>
                <input name="total_hora_hh" type="text" id="total_hora_hh" size="5" readonly="readonly" />
                :
                <label for="total_hora_mm"></label>
                <input name="total_hora_mm" type="text" id="total_hora_mm" size="5" readonly="readonly" />
              <input type="button" name="btnCalular" id="btnCalular" value="Calcular" onclick="calcHoraLaborada()" />
            </h3>
            <p>&nbsp;</p>
        </div>
    </div>


</div>   



<!---  DIALOG -->
<div id="dialog-dia-subsidiado">

    <div id="editarDiaSubsidiado"> </div>

</div> 


<!---  DIALOG -->
<div id="dialog-dia-noLaborado">

    <div id="editarDiaNoLaborado"> </div>

</div> 
