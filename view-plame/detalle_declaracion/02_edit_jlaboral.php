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
require_once '../../model/PjornadaLaboral.php';
require_once '../../dao/PjoranadaLaboralDao.php';
require_once '../../controller/PlameJornadaLaboralController.php';

$id_ptrabajador = $_REQUEST['id_ptrabajador'];

$PjornadaLaboral = new PjornadaLaboral();
$PjornadaLaboral = buscarPjornadaLaboral_IdPtrabajdor($id_ptrabajador);

//echo "<pre> $id_ptrabajador";
//echo print_r($PjornadaLaboral);
//echo "</pre>";

?>
<script type="text/javascript">
 
 //----------------------------------------------------------
 // INICIO DIAS SUBSIDIADOS	
 //----------------------------------------------------------
    //var objCombo = document.getElementById('cbo_tipo_suspension-1');
	var dia_laborado = <?php echo $PjornadaLaboral->getDia_laborado();?>;
	
    var num_max_dia = document.getElementById('dia_total').value;
    var num_dia_subsidiado = 0;	
	var num_dia_nosubsidiado = 0;
    var num_dia_nosubsidiado = 0;
	
	
    var subs = new Array(
<?php $counteo = count($suspencion1);
for ($i = 0; $i < $counteo; $i++): ?>	
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



    function validarNuevoInput(){

        if(num_dia_subsidiado == num_max_dia ){
            alert("No hay mas dias para ser subsidiados");
        }else{

            var ids = getIdsCombos();
            //alert("subs.length "+subs.length);
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
        var id = '<input type="text" size="4" id="pdia_subsidiado-'+ COD_DETALLE_CONCEPTO +'" name="pdia_subsidiado[]" value ="" >';
        var estado = '<input type="text" size="4" id="estado-'+ COD_DETALLE_CONCEPTO +'" name="estado[]" value ="0" >';
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

    function eliminar_ds(elementId){ alert (" "+elementId);
	
        var obj = document.getElementById(elementId)
        eliminarElemento(obj);



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
        calcDiaSubsidiado();
        document.getElementById('dia_subsidiado').value = num_dia_subsidiado;
		document.getElementById('dia_laborado').value = dia_laborado - num_dia_subsidiado;	
		
		
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
	
	

    }

    function calcDiaSubsidiado(){

        var tabla = document.getElementById("tb_dsubsidiado");
        var num_fila = parseInt(contarTablaFila(tabla));
        num_fila = num_fila - 1;	
	
        num_dia_subsidiado = 0;
	
        var total = document.getElementById('ds_total');
        var num_max_dia_local = num_max_dia;
	

        for(var i= 1; i <= num_fila; i++){ console.log("ciclo iiii="+ i);
	
            var cbo = document.getElementById('cbo_ds_tipo_suspension-'+i);
            var dia = document.getElementById('ds_cantidad_dia-'+i);		
		
            //	
            var diaint = parseInt( (dia.value == '' || dia.value < 0) ? 0 : dia.value );
            //rpta
            num_dia_subsidiado = num_dia_subsidiado + (diaint);	
		
		
            if( i == 1 ){
                num_max_dia_local = num_max_dia_local - diaint;	
            }else {
                //num_max_dia_local = num_max_dia_local - diaint;
            }		
		
            if(num_max_dia_local<0){
                alert("El maximo numero de dias esS: "+ num_max_dia);
                dia.value = '';			
                diaint = 0;
                break;
            }		

            if(num_dia_subsidiado > num_max_dia){
                alert("El maximo numero de dias es: "+ num_max_dia_local);
                dia.value = '';
                num_dia_subsidiado = num_dia_subsidiado - (diaint);
                diaint = 0;
			
            }
		
            //rpta
            //num_dia_subsidiado = num_dia_subsidiado + (diaint);
            dia.value = (diaint == 0) ? '' : diaint;		
		
        }

	
        total.value = (num_dia_subsidiado>num_max_dia) ? '' : num_dia_subsidiado;
        //alert("DIA TOTAL "+ num_dia);	
    }

	
 //----------------------------------------------------------
 // FINAL DIAS SUBSIDIADOS	
 //----------------------------------------------------------











 //----------------------------------------------------------
 // INICIO DIAS NO-SUBSIDIADOS	
 //----------------------------------------------------------
	//01
	var nosubs = new Array(
	<?php $counteo = count($suspencion2); 
	for($i=0;$i<$counteo;$i++): ?>	
	<?php
		if($i == $counteo-1){ 
			echo "{id:'".$suspencion2[$i]['cod_tipo_suspen_relacion_laboral']."', descripcion:'".$suspencion2[$i]['cod_tipo_suspen_relacion_laboral']." - ".$suspencion2[$i]['descripcion_abreviada']."' }"; 
		}else{
			echo "{id:'".$suspencion2[$i]['cod_tipo_suspen_relacion_laboral']."', descripcion:'".$suspencion2[$i]['cod_tipo_suspen_relacion_laboral']." - ".$suspencion2[$i]['descripcion_abreviada']."' },"; 
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
	
	if(num_dia_subsidiado == num_max_dia){
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

var input_cdia = '<input name="dn_cantidad_dia[]" type="text" id="dn_cantidad_dia-'+COD_DETALLE_CONCEPTO+'" size="7" />';
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
	
$.ajax({
   type: "post",
   url: "sunat_planilla/view-plame/modal/dia_nolaborado.php",
   data: "id_ptrabajador="+id,
   async:true,
   success: function(datos){
    $('#editarDiaNoLaborado').html(datos);
    
    $('#dialog-dia-noLaborado').dialog('open');
   }
});

	
}

 //----------------------------------------------------------
 // FINAL DIAS SUBSIDIADOS	
 //----------------------------------------------------------












</script>

<div class="ptrabajador">

<div class="section">
	<div class="article fila1">
    <div class="ocultar">
    id_pjornada_laboral:<input name="id_pjornada_laboral" id="id_pjornada_laboral" 
    type="text" readonly="readonly" value="<?php echo $PjornadaLaboral->getId_pjornada_laboral(); ?>" />
    </div>
	  <h3>Dias de Jornada</h3>
        <hr />
	  <p>
	    <label for="dia_laborado">Laborados</label>
	    <input name="dia_laborado" type="text" id="dia_laborado" size="4" readonly="readonly"
        value="<?php echo $PjornadaLaboral->getDia_laborado(); ?>" />
       
        
        
	  </p>
	  <p>
	    <label for="dia_subsidiado">Subsidiados</label>
	    <input name="dia_subsidiado" type="text" id="dia_subsidiado" 
        value="<?php echo $PjornadaLaboral->getDia_subsidiado();?>" size="4" readonly="readonly" />
       <span>
       <a href="javascript:editarDiaSubcidiado( '<?php echo $PjornadaLaboral->getId_pjornada_laboral(); ?>')">
       <img src="images/edit.png"></a></span>
	  </p>
	  <p>
	    <label for="dia_nosubsidiado">No laborados y no subsidiados:</label>
	    <input name="dia_nosubsidiado" type="text" id="dia_nosubsidiado" 
        value="<?php echo $PjornadaLaboral->getDia_nosubsidiado(); ?>" size="4" readonly="readonly" />
         <span >
         <a href='javascript:editarDiaNoLaborado("<?php echo $PjornadaLaboral->getId_pjornada_laboral();?>")'>
         <img src="images/edit.png"></a></span>
	  </p>
      <h3>TOTAL: 
        <label for="dia_total"></label>
        <input name="dia_total" type="text" id="dia_total" size="4" readonly="readonly"
         value="<?php  echo $PjornadaLaboral->getDia_total(); ?>" />
        </h3>
      <p>&nbsp;</p>
	</div>
	<div class="article fila2">
			<h3>Horas Laboradas</h3>
              <hr />
		<p>        
          <label for="hora_ordinaria_hh">Ordinarias (HHHH:MM)</label>
          <input name="hora_ordinaria_hh" type="text" id="hora_ordinaria_hh" size="5" />
          :
          <input name="hora_ordinaria_mm" type="text" id="hora_ordinaria_mm" size="5" />
		</p>
	  <p>
	    <label for="hora_sobretiempo_hh">Sobretiempo(HHH:MM)</label>
		  <input name="hora_sobretiempo_hh" type="text" id="hora_sobretiempo_hh" size="5" />
	    :
	    <input name="hora_sobretiempo_mm" type="text" id="hora_sobretiempo_mm" size="5" />
		</p>
			<h3>TOTAL HORAS:
			  <label for="total_hora_hh"></label>
			  <input name="total_hora_hh" type="text" id="total_hora_hh" size="5" readonly="readonly" />
			:
			<label for="total_hora_mm"></label>
			<input name="total_hora_mm" type="text" id="total_hora_mm" size="5" readonly="readonly" />
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
