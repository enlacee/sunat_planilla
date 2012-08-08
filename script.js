

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
	
                if(num_dia_subsidiado == max_dias){
                    alert("No hay mas dias para ser No Subsidiados");
                }else{
	
                    //if(nosubs.length == 1){
                    //alert("No existe mas Conceptos por agregar");
                    //}else{
                    nuevoDiaSubsidiado_2();
                    //}
	
	
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



            function eliminar_dns(elementId){ alert (" "+elementId);

                var obj = document.getElementById(elementId)
                eliminarElemento(obj);
                /*
        console.log("master intacto");
        console.dir(master);
                 */

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

        var tabla = document.getElementById("tb_dnolaborado");
        var num_fila = parseInt(contarTablaFila(tabla));
        num_fila = num_fila - 1;	
		
		//dia_laborado
		alert("num_dia_nosubsidiado ="+ num_dia_nosubsidiado);
		alert("dia_laborado ="+ dia_laborado);

        num_dia_nosubsidiado = num_dia_nosubsidiado +  dia_laborado; 
		
		alert("SUMA  num_dia_nosubsidiado ="+ num_dia_nosubsidiado);
			
        var total = document.getElementById('dns_total');
        var max_dias_local = max_dias;
	

        for(var i= 1; i <= num_fila; i++){ console.log("ciclo iiii="+ i);
	
            //var cbo = document.getElementById('cbo_ds_tipo_suspension-'+i);
            var dia = document.getElementById('dns_cantidad_dia-'+i);		
		
            //	
            var diaint = parseInt( (dia.value == '' || dia.value < 0) ? 0 : dia.value );
            //rpta
            num_dia_nosubsidiado = num_dia_nosubsidiado + (diaint);	
		
		
            if( i == 1 ){
                max_dias_local = max_dias_local - diaint;	
            }else {
                //max_dias_local = max_dias_local - diaint;
            }		
		
            if(max_dias_local<0){
                alert("El maximo numero de dias esS: "+ max_dias);
                dia.value = '';			
                diaint = 0;
                break;
            }		

            if(num_dia_nosubsidiado > max_dias){
                alert("El maximo numero de dias es: "+ max_dias_local);
                dia.value = '';
                num_dia_nosubsidiado = num_dia_nosubsidiado - (diaint);
                diaint = 0;
			
            }
		
            //rpta
            //num_dia_nosubsidiado = num_dia_nosubsidiado + (diaint);
			
            dia.value = (diaint == 0) ? '' : diaint;		
		
        }
			//alert("total "+total.value);
		  total.value = (num_dia_nosubsidiado > max_dias) ? '' : num_dia_nosubsidiado;

		  
		
}//ENDFN

//-----
function grabarDiaNoSubsidiado(){
	calcDiaNoSubsidiado();
	
	
	alert("max_dias = "+ max_dias);
	

}



            //----------------------------------------------------------
            // FINAL DIAS NO-SUBSIDIADOS	
            //----------------------------------------------------------

