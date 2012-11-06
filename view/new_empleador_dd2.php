<?php
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//

require_once('../util/funciones.php');
require_once('../dao/AbstractDao.php');
require_once('../dao/ComboCategoriaDao.php');
require_once('../controller/ComboCategoriaController.php');

// IMPORTANTE :: Buscar Id_empleador_maestro
require_once('../dao/EmpleadorMaestroDao.php');
require_once('../controller/EmpleadorMaestroController.php');

//Empleador DAO
require_once('../dao/EmpleadorDao.php');

// (01) PRIMERO busacar Empleador subordinado
//$_REQUEST['ruc_empleador_subordinado']; //DATO de EmpleadorDDController.php
//---------------------------------------------------------------

// Lista de Detalle Servicio Prestado
// (01) Busca Id empleador Destaque
require_once '../dao/EmpleadorDestaqueYourSelfDao.php';
//require_once '../controller/EmpleadorDestaqueYourselfController.php';
//
// (02) Lista los Servicicios
require_once '../dao/ServicioPrestadoYourselfDao.php';
require_once '../controller/ServicioPrestadoYourselfController.php';

//require_once('../dao/DetalleServicioPrestado2Dao.php');
//require_once('../controller/DetalleServicioPrestadoController2.php');

$cbo_tipo_actividad = comboTipoActividad();


// Acce
$dao = new EmpleadorDao();
$DATA_EMP_SUBORDINADO = $dao->buscaEmpleadorPorRuc($_REQUEST['ruc_empleador_subordinado']);


//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";



// Empleador Que Ingreso al Sistema ()
//----- PARAMETRO (01)
$ID_EMPLEADOR = $_SESSION['sunat_empleador']['id_empleador'];
$DATA_EMPLEADOR_MAESTRO = buscarIdEmpleadorMaestroPorIDEMPLEADOR($ID_EMPLEADOR);

//----- PARAMETRO (02)
$ID_EMPLEADOR_MAESTRO = $DATA_EMPLEADOR_MAESTRO['id_empleador_maestro'];


//----### Busqueda id_empleadorDestaqueYourSelf ###

//// Lista de Detalle Servicio Prestado
//$DATA_DETALLE_DD2 = listarDetalleServicioPrestado2($DATA_EMPLEADOR_MAESTRO['id_empleador_maestro'], $DATA_EMP_SUBORDINADO['id_empleador']);
$DATA_DETALLE_DD2 = listarServiciosPrestadosYourself($ID_EMPLEADOR_MAESTRO, $DATA_EMP_SUBORDINADO['id_empleador']);



?>
<style type="text/css">
<!--
#cesta-productos{
	text-align:center;
	width:660px;
	/*margin-top:5px;*/
	
}
.celda{
	float:left;
	min-height:22px;
	padding:5px 0 5px 0;
	margin-right:1px;
}
.negrita .celda{
	min-height:45px;
	background-color:#6FF;
	font: 14px/12px inherit;	
	
}


.eliminar{
	width:20px;
	background-color:#fcac36;
}

.producto{
	width:90px;
	background-color:#fcac36;
}


.cantidad{
	width:270px;
	background-color:#fcac36;
}
.precio,.sub-total{
	width:130px;
	background-color:#fcac36;

}


-->
</style>
<script>
//INICIO HISTORIAL
$(document).ready(function(){                  
        $( "#tabs").tabs();		
//-------------------------------------------------------------------------------------

});	
//-----------------------------------

function seleccionarTipoDeServicio(obj){
	/*
	* Posicion Fija:
	* Esta funcion buscar en el combo el valor que se ingresa en 
	* el INPUT
	*/
	var cadena = obj.id; //alert(cadena);
	console.dir(obj);
	console.dir("--------------------");
	var aguja = obj.value;
	//Posicion Fija
	var id = cadena.charAt(11,-1);
	

	
	var eCombo = document.getElementById('cbo_tipo_servicio_prestado-'+id);
	
	//if(eCombo.selectedIndex <0 ){ //SI NO ESTA SELECCIONADO
	
	var counteo = eCombo.options.length;	
	
	var bandera= false;
	for(i=0;i<counteo;i++){
		if(aguja == eCombo.options[i].value){
			//alert("uno")
			eCombo.options[i].selected = true;
			bandera=true;
			break;
		}else{				
			//obj_combo.options[0].selected = true;
			//alert("No se encontro este codigo");
		}		
	}//end for
	//alert(bandera);
	if(bandera==false){
		alert("Codigo No existe");
		 //var indice = eCombo.selectedIndex;
   		 eCombo.options[0].selected=true;
	}
	
	//}else{
		//alert("ddd")
	///}
	
	
}//ENDFN

// 

function crearElementoTipoDeServicio(){
	//
	var id = counteoElementoTipoDeServicio();	
	//alert("id counteo"+id);	
	// Creando Elementos
	
	crearInputTipoDeServicio(id);	
	
	
	
}//ENDFN


function counteoElementoTipoDeServicio(){
	var form = document.form_new_empleador_dd2;
	//console.dir(form);
	var counteo = form.length;	
	
	var counteoSelect = 0;
	for(var i=0;i<counteo;i++){
		if(form.elements[i].nodeName=='SELECT'){
			counteoSelect++;	
		}	
	}//ENDFOR
	return counteoSelect;
}//ENDFN


function crearInputTipoDeServicio(id){
	var id = id + 1;
	
	//INICIO div
	var div =document.createElement('div');
	var div_input_id_servicio =document.createElement('div');	 
	var div_input_codigo = document.createElement('div');
	var div_select_tservicio = document.createElement('div');	
	var div_input_finicio = document.createElement('div');	
	var div_input_ffin = document.createElement('div');	


	div_input_id_servicio.setAttribute('class','celda eliminar');
	div_input_codigo.setAttribute('class','celda producto');
	div_select_tservicio.setAttribute('class','celda cantidad');
	div_input_finicio.setAttribute('class','celda precio');
	div_input_ffin.setAttribute('class','celda sub-total');


	
	// INICIO input
	var input_id_servicio =document.createElement('input');	 
	var input_codigo = document.createElement('input');
	var select_tservicio = document.createElement('select');	
	var input_finicio = document.createElement('input');	
	var input_ffin = document.createElement('input');	


	input_id_servicio.setAttribute('name','id_detalle_servicio_prestado2[]');
	input_id_servicio.setAttribute('id','id_detalle_servicio_prestado2-'+id);
	input_id_servicio.setAttribute('type','text');
	input_id_servicio.setAttribute('size','5');
	input_id_servicio.setAttribute('value','0');
	//
	input_codigo.setAttribute('name','txt_codigo[]');
	input_codigo.setAttribute('id','txt_codigo-'+id);
	input_codigo.setAttribute('type','text');
	input_codigo.setAttribute('size','6');
	//input_codigo.addEventListener('onblur','seleccionarTipoDeServicio',true);
	input_codigo.setAttribute('onblur', "seleccionarTipoDeServicio(this)");
	//input_codigo.setAttribute('onblur','seleccionarTipoDeServicio(this)');
	
	//seleccionarTipoDeServicio(this)
	//
	select_tservicio.setAttribute('name','cbo_tipo_servicio_prestado[]');
	select_tservicio.setAttribute('id','cbo_tipo_servicio_prestado-'+id);
	select_tservicio.style.width="250px";
	//input_codigo.setAttribute('style','text');
	
	//width
	// llenar Combo
	//llenarCombo(select_tservicio);
	//select_tservicio.setAttribute('type','select');
	//
	input_finicio.setAttribute('name','txt_fecha_inicio[]');
	input_finicio.setAttribute('id','txt_fecha_inicio-'+id);
	input_finicio.setAttribute('type','text');
	input_finicio.setAttribute('size','12');
	//
	input_ffin.setAttribute('name','txt_fecha_fin[]');
	input_ffin.setAttribute('id','txt_fecha_fin-'+id);
	input_ffin.setAttribute('type','text');
	input_ffin.setAttribute('size','12');
	
	
	
	//Capa Contenedora
	capa = document.getElementById('contenedor_producto');	
	//
	capa.appendChild(div); //PRINCIPAL		
	//-------
	div.appendChild(div_input_id_servicio);
	div_input_id_servicio.appendChild(input_id_servicio);
	//-------
	div.appendChild(div_input_codigo);
	div_input_codigo.appendChild(input_codigo);
	//-------
	div.appendChild(div_select_tservicio);
	div_select_tservicio.appendChild(select_tservicio);
	llenarComboTipoServicio(select_tservicio);
	//-------
	div.appendChild(div_input_finicio);
	div_input_finicio.appendChild(input_finicio);
	//-------
	div.appendChild(div_input_ffin);
	div_input_ffin.appendChild(input_ffin);
	
	/*
	capa.appendChild(input_id_servicio);	
	capa.appendChild(input_codigo);	
	capa.appendChild(select_tservicio);	
	llenarComboTipoServicio(select_tservicio);
	capa.appendChild(input_finicio);	
	capa.appendChild(input_ffin);	
*/
	
}//ENDFN

function llenarComboTipoServicio(objCombo){
	

	//console.log(test);
	var test = new Array(
	<?php $counteo = count($cbo_tipo_actividad); 
	for($i=0;$i<$counteo;$i++): ?>	
	<?php
		if($i == $counteo-1){ 
			echo "{id:'".$cbo_tipo_actividad[$i]['cod_tipo_actividad']."', descripcion:'".$cbo_tipo_actividad[$i]['descripcion']."' }"; 
		}else{
			echo "{id:'".$cbo_tipo_actividad[$i]['cod_tipo_actividad']."', descripcion:'".$cbo_tipo_actividad[$i]['descripcion']."' },"; 
		}
	?>
	<?php endfor; ?>
	);//end array

	var counteo = 	test.length;		
	//objCombo.options[0] = new Option('-', '');
	for(var i=0;i<counteo;i++){
		objCombo.options[i] = new Option(test[i].descripcion, test[i].id);
	}

}//ENDFN





//----




function validarCampoFecha(obj){
	var valor_txt = obj.value;
	var bandera = true;
	
	expresion_regular_fecha =/^\d{2}\/\d{2}\/\d{4}$/;
	var bandera = expresion_regular_fecha.test(valor_txt);
	
	if(bandera==false){
		//alert("Formato de fecha es incorrecto\n Utilize este formato: dia/mes");
		obj.value="";
		obj.style.border="thin solid #ff0000";
	}else{
		//alert("bien");
		console.log("Fecha Correcta");
		with (obj.style) {
		 borderColor=null;
		 //borderWidth = "3px";
		 //borderStyle = "solid";
		}
		return bandera;
	}

}//ENDFN

//-----------------------------------------------------------------------------


     function validarFormulario(){

		var estado = errorEnComboyFecha_dd2();
		
		var counteo = counteoElementoTipoDeServicio();
		console.log(estado);
		if(estado && counteo>=1){ alert ("true ajax");
			
			//-----------------------------------------------------------------------				
				var from_data =  $("#form_new_empleador_dd2").serialize();
				$.getJSON('sunat_planilla/controller/EmpleadorDestaqueYourselfController.php?'+from_data,
					function(data){
						if(data){
							alert ('Se Guardo Correctamente.');
							cargar_pagina('sunat_planilla/view/view_empleador.php','#CapaContenedorFormulario');
						}else{
							alert("Ocurrio un error");
						}
					}); 
			//-----------------------------------------------------------------------
		}else{
			//counteo=0
			alert ("Debe Ingresarse Por lo menos 1 tipo de servicio ajax_false");
		
		}

		return true;
				 

}//ENDVAlidarformulario 

//---------------------------------------------------------

// ----------------------- REGLAS DE FORMULARIO ----------
function contarCombosFieldSet_1_dd2(){

	var form = document.form_new_empleador_dd2;
	//console.dir(form);
	var counteo = form.length;	
	
	var counteoSelect = 0;
	for(var i=0;i<counteo;i++){
		if(form.elements[i].nodeName=='FIELDSET' && form.elements[i].id=="fieldset-1"){
			//alert("FIELDSET");
			
			var fieldsetx = form.elements[i];
			var counteoFielset = fieldsetx.elements.length;	
					
			//CONTANDO LOS SELECT DENTRO DEL fielset
			for(var j=0; j<counteoFielset;j++){
						
				if(fieldsetx.elements[j].nodeName=='SELECT'){
					counteoSelect++;
					//alert("SELECT");
				}				
			}//ENDFOR
			
		
		}//ENDIF
		//console.log(counteoSelect);
		
	}//ENDFOR
	return counteoSelect;
}//endfunction




//-------------------------------------
function errorEnComboyFecha_dd2(){

	var bandera_select = 0;
	var bandera_fechai = 0;
	var bandera_fechaf = 0;
	var bandera_dias = 0;
	var counteo = contarCombosFieldSet_1_dd2();

for(var i=0;i<counteo; i++){
	
	var id = i+1;
	console.log("idddddddddddddddd"+id);
	//<<< Validando SELECT				   
	var cboTipo = document.getElementById('cbo_tipo_servicio_prestado-'+id);
	var inputFechaInicio = document.getElementById('txt_fecha_inicio-'+id);
	console.log('txt_fecha_inicio-'+id);
	var inputFechaFin = document.getElementById('txt_fecha_fin-'+id);
	
//----------------------------

	if(validarCombo(cboTipo)==false){
		bandera_select++;
	}else if(validarFechaInicio(inputFechaInicio)==false){
		//alert(validarFechaInicio(inputFechaInicio));//txt_fecha_inicio-3
		bandera_fechai++;
	}else if(validarFechaFin(inputFechaFin)==false){
		bandera_fechaf++;
	}else{
		//alert("else combo validarComb .?o");		
	}
	
	//Condicion de Dias
	//alert("OKKKKKKKK");
	/*
	* Fecha de Inicio es Obligatorio 
	* Fecha de Fin NO 
	* Y si estan establecidos las 2 fechas recien se validan # dias
	*/
	if( inputFechaFin.value != ""  && (inputFechaInicio.value != inputFechaFin.value)){ //error CUANDO SON IGUALES devuelde 31!!!
		if(validarFechaInicio(inputFechaInicio)==true && validarFechaFin(inputFechaFin)==true){		
			var dias = obtenerDiasTranscurridos(inputFechaInicio,inputFechaFin);
			console.log("dias ? = "+dias);
			if(dias>=1 && dias<=5){
				console.log("dias true 1-5 = "+dias);
			}else{ //Errror					
				bandera_dias++;
			}
		}//
	}//ENDIF


}//ENDFOR
console.log("bandera_select ="+bandera_select);
console.log("bandera_fechai ="+bandera_fechai);
console.log("bandera_fechaf ="+bandera_fechaf);
console.log("bandera_dias ="+bandera_dias);


if(bandera_select>0){
	alert("Debe seleccionar combo!");
	return false;
}else if(bandera_fechai>0){
	alert("Error fecha inicio!");
	return false;
}else if(bandera_fechaf>0){
	alert("Error fecha fin!");
	return false;
}else if(bandera_dias>0){
	alert("La transaccion del Trabajador solo entre:  1 a 5 dias.\Revise las fechas")
	//return	
}else{
    return true;
}

//return true;

}//ENDFUNCTION

//----------------------------------------
function validarCombo(cbo){
	
	if(cbo.value==0 || cbo.value=="-" ){
		return false;
	}else{
		return true;
	}
}

function validarFechaInicio(fechaInicio){
	//alert("validar f iniico");
	if(fechaInicio.value==""){ //fecha de inicio NO Permite Vacio		
		return false;	
	}else{
		return 	esFechaValida(fechaInicio);
	}
	
}

function validarFechaFin(fechaFin){
	if(fechaFin.value==""){ alert("fecha fin vaciooo");
		return true;
	}else if(fechaFin.value!=""){
		return 	esFechaValida(fechaFin);
	}
	
}

//--
function obtenerDiasTranscurridos(fecha_incio, fecha_fin){
	
//--
		var dia  =  parseInt(fecha_incio.value.substring(0,2),10);
		var mes  =  parseInt(fecha_incio.value.substring(3,5),10);
		var anio =  parseInt(fecha_incio.value.substring(6),10);
		
		var f_inicio = new Date();
		f_inicio.setDate(dia);
		f_inicio.setMonth(mes);
		f_inicio.setYear(anio);
		f_inicio.setHours(00);
		f_inicio.setMinutes(00);
		f_inicio.setSeconds(00);					
		//Fecha Fin	
		var diax  =  parseInt(fecha_fin.value.substring(0,2),10);
		var mesx  =  parseInt(fecha_fin.value.substring(3,5),10);
		var aniox =  parseInt(fecha_fin.value.substring(6),10);
		
		var f_fin = new Date();
		f_fin.setDate(diax);
		f_fin.setMonth(mesx);
		f_fin.setYear(aniox);
		f_fin.setHours(00);
		f_fin.setMinutes(00);
		f_fin.setSeconds(00);
				
		var total = f_fin.getTime() - f_inicio.getTime();
		//alert("resta segundos  = "+total);
		var nueva_fecha =new Date();			 
		nueva_fecha.setTime(total);		
		
		//alert("DIA TRANSCURRIDO ES "+nueva_fecha.getDate());
		var dias = nueva_fecha.getDate(); 
		return dias;
//--
}


</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro del Empleador </a></li>			

        </ul>
        <div id="tabs-1">
		
          <h1><strong>Empleador   que me destaca Personal</strong></h1>
          <form action="sunat_planilla/controller/EmpleadorController.php" method="POST" name="form_new_empleador_dd2" id="form_new_empleador_dd2" >
            <div class="ocultar" style="display:block" >
              <div></div>
</div>
            <fieldset>

  <legend><strong>Empleador que me destaca o desplaza personal  </strong></legend>
  <div class="ocultar">
    <div style="">
      <label>id_empleador_maestro</label>
      <label for="textfield"></label>
      <input type="text" name="id_empleador_maestro" id="id_empleador_maestro" readonly="readonly"
                  value="<?php echo ( empty($DATA_EMPLEADOR_MAESTRO['id_empleador_maestro']))? "ERROR NO Es Empleador Maestro" : $DATA_EMPLEADOR_MAESTRO['id_empleador_maestro']; ?>" />
      <label> <br />
        </label>
    </div>
    <div class="fila_input">
      <label>id_empleador_subordinado:</label>
      <input name="id_empleador" type="text" class=""  id="id_empleador" readonly="readonly"
                  value="<?php echo $DATA_EMP_SUBORDINADO['id_empleador']; ?>" />
    </div>
  </div>
  <div class="">

                   RUC:
                     <input name="txt_ruc_buscar" type="text"  id="txt_ruc_buscar" size="15" maxlength="11"
                value="<?php  echo $DATA_EMP_SUBORDINADO['ruc']; ?>" />

  </div>
    <div class="fila_input">
      Nombre / Razón Social:
      <input name="txt_razon_social" type="text" id="txt_razon_social" readonly="readonly"
                  value="<?php echo $DATA_EMP_SUBORDINADO['razon_social_concatenado']; ?>" />

      <div class="ocultar">
        <div><label>oper</label>
          <label for="oper"></label>
          <input type="text" name="oper" id="oper" value="add"/>
        </div>
        <div>id_empleador_destaque_yourself                  
          <input type="text" name="id_empleador_destaque_yourself" id="id_empleador_destaque_yourself" />
        </div>
      </div>
      <br />
      <br />
  </div>
 

			  
<div id="datos-empleador2-ajax" style=" border:1px solid #999; background-color:#FF9; display:block" >
  <fieldset id="fieldset-1">
  <legend><strong>Servicios Recibidos</strong></legend>
<p>
  <input name="btn_agregar" type="button" id="btn_agregar" value="Agregar" 
  class="submit-nuevo" onclick="crearElementoTipoDeServicio()" />

  
  <br />
  <br />
Fecha de periodo a perido max 1 mes


<!-- INI AJAX cesta de productos -->
<div id="cesta-productos">
    <div class="negrita" >
				  <div class="celda eliminar"></div>
				  <div class="celda producto">Codigo</div>
			    <div class="celda cantidad">Servicio Recibido</div>
					<div class="precio celda">Fecha de Inicio<br />
					  (dd/mm/aaaa))
					</div>
				  <div class="celda sub-total">Fecha de Fin.</div>
                  <div class="clear"></div>
</div>
<!-- END AJAX cesta de productos -->









<div id="contenedor_producto" style="border:1px solid red;">
<!-- inicio ..aaa -->
<?php

//Control de datos del Empleador que me destaca personal
//01 IF
$DATA = $DATA_DETALLE_DD2;
$counteo = count($DATA);
//echo "<h1>Numero de Servicios Registrados :</h1>".$counteo;


if ($counteo >= 1):
for($i=0;$i<$counteo;$i++):

$id = $i+1;

?>
<!-- INICIO HTML -->

<div id="for-each" style="display:block; border:1px solid green" >
<div class="celda eliminar">
<input name="id_detalle_servicio_prestado2[]"  id="id_detalle_servicio_prestado2-<?php  echo $id;?>" 
type="text"  size="1" 
value="<?php echo $DATA[$i]['id_servicio_prestado_yoursef']; ?>" />
</div>
<div class="celda producto">
<input name="txt_codigo[]" type="text" size="9" id="txt_codigo-<?php echo $id;?>" 
value="<?php  echo $DATA[$i]['cod_tipo_actividad']; ?>" onblur="seleccionarTipoDeServicio(this)"/>
</div>
<div class="celda cantidad">

<select name="cbo_tipo_servicio_prestado[]" id="cbo_tipo_servicio_prestado-<?php echo $id;?>"
 style="width:250px;" title ="combo-php" >
<!-- <option value="" >-</option> -->
<?php
foreach ($cbo_tipo_actividad as $indice):
if ($indice['cod_tipo_actividad'] == $DATA[$i]['cod_tipo_actividad'] ) {

$html = '<option value="'. $indice['cod_tipo_actividad'] .'" selected="selected" > ' . $indice['descripcion'] . '</option>';

} else {
$html = '<option value="'. $indice['cod_tipo_actividad'] .'" >' . $indice['descripcion'] . '</option>';
}
echo $html;
endforeach;

?>

</select>


</div>
<div class="precio celda">
<input name="txt_fecha_inicio[]" id="txt_fecha_inicio-<?php echo $id;?>"
class="required error" type="text"  size="12"
value="<?php echo getFechaPatron($DATA[$i]['fecha_inicio'],"d/m/Y")?>" />
</div>
<div class="celda sub-total">
<input name="txt_fecha_fin[]" id="txt_fecha_fin-<?php echo $id;?>"
type="text"  size="12" 
value = "<?php  echo getFechaPatron($DATA[$i]['fecha_fin'],"d/m/Y");  ?>" />
</div>
<div class="clear"></div>
</div>

<!--FINAL HTML -->




<?php 
//02 IF
endfor;
else:
echo "ELSEEEEE!!";
  ?>
 
 
 
 
 
 

<?php
//03 IF
endif;
?>









  
  
  
  

<div style="clear:both">
</div>



<!-- final ...-->


  </div>










<!-- END AJAX cesta de productos -->
		  </div>
<!-- END AJAX cesta de productos -->




</fieldset>
<br />
</div>			  
			  
			  
			  
			  
			  
			  
			  
            </fieldset>

            <p><!-- DIRECCION 2-->
			<input name="btn_grabar" type="button" id="btn_grabar" value="Guardar" 
            class="submit-go" onclick="validarFormulario()"  >

                <input name="btnRetornar" type="button" id="btnRetornar" 
                value="Cancelar"  class="submit-cancelar"
                onclick="javascript:cargar_pagina('sunat_planilla/view/edit_empleador.php?id_empleador=<?php echo $ID_EMPLEADOR; ?>','#CapaContenedorFormulario');" />
              <br />
            </p>
          </form>

          <p>
            <!--
	FORMULARIO DIRECCION 2
-->
          </p>
          <div class="ayuda">Para mayor detalle respecto a las opciones e información solicitada, sírvase   ingresar al siguiente enlace: <a id="aAyuda" href="http://www.sunat.gob.pe/ayuda/tributos/tregistro-E-P/T-RegistroPrivado-H02.html" target="_new">Ayuda del T-Registro</a>              </div>
      </div>
    </div>
</div>




