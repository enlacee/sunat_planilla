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

// Lista de  Servicio Prestado ok
require_once('../dao/EmpleadorDestaqueDao.php');

require_once('../dao/ServicioPrestadoDao.php');
require_once('../controller/ServicioPrestadoController.php');



// Lista de Detalle Servicio Prestado
require_once('../dao/EstablecimientoVinculadoDao.php');
require_once('../controller/EstablecimientoVinculadoController.php');

/*
//breakkkk
// Lista de Detalle Servicio Prestado
// (01) Busca Id empleador Destaque
require_once '../dao/EmpleadorDestaqueYourSelfDao.php';
require_once '../controller/EmpleadorDestaqueYourselfController.php';
// (02) Lista los Servicicios
require_once '../dao/ServicioPrestadoYourselfDao.php';
require_once '../controller/ServicioPrestadoYourselfController.php';

*/

//COMBO <<<<<<<<<<((01))
$cbo_tipo_actividad = comboTipoActividad();


// Acce
$dao = new EmpleadorDao();
$DATA_EMP_SUBORDINADO = $dao->buscaEmpleadorPorRuc($_REQUEST['ruc_empleador_subordinado']);

//COMBO <<<<<<<<<<((02))
$cbo_combo_establecimiento = comboEstablecimientoLineal($DATA_EMP_SUBORDINADO['id_empleador']);

//echo "<pre> hereeeee eee e";
//var_dump($DATA_EMP_SUBORDINADO);
//echo "</pre>";



// Empleador Que Ingreso al Sistema ()
//----- PARAMETRO (01)
$ID_EMPLEADOR = $_SESSION['sunat_empleador']['id_empleador'];
$DATA_EMPLEADOR_MAESTRO = buscarIdEmpleadorMaestroPorIDEMPLEADOR($ID_EMPLEADOR);

//----- PARAMETRO (02)
$ID_EMPLEADOR_MAESTRO = $DATA_EMPLEADOR_MAESTRO['id_empleador_maestro'];


// Lista de Detalle Servicio Prestado
$DATA_DETALLE_DD1 = listarServiciosPrestados($ID_EMPLEADOR_MAESTRO , $DATA_EMP_SUBORDINADO['id_empleador']);

//echo "<pre> akkakak";
//var_dump($DATA_DETALLE_DD1);
//echo "<pre>";

// Lista de Detalle Establecimiento Vinculado
$DATA_DETALLE_EV = listarDetalleEstablecimientoVinculado($DATA_EMPLEADOR_MAESTRO['id_empleador_maestro'], $DATA_EMP_SUBORDINADO['id_empleador']);

//echo "<pre> here e :Dt";
//var_dump($DATA_DETALLE_EV);
//echo "</pre>";


?>
<style type="text/css">
<!--
.cesta-productos{
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
	background-color:#E1E1E1;
	font: 14px/12px inherit;	
	
}


.eliminar{
	width:20px;

}

.producto{
	width:90px;

}


.cantidad{
	width:270px;

}
.precio,.sub-total{
	width:130px;
	

}


-->
</style>
<script>
    //INICIO HISTORIAL
    $(document).ready(function(){
                  
        $( "#tabs").tabs();	

    });
//-------------------------------------------------------------
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
		//alert("Codigo No existe");
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
	var id = counteoElementoTipoDeServicio_FS1();	
	//alert("id counteo"+id);	
	// Creando Elementos
	
	crearInputTipoDeServicio(id);	
	
	
	
}//ENDFN


function counteoElementoTipoDeServicio_FS1(){
	var form = document.form_new_empleador_dd1;
	//console.log("hola\n\nhola\n\n");
	//console.dir(form);
	var counteo = form.length;	
	
	var counteoSelect = 0;
	for(var i=0;i<counteo;i++){
		if(form.elements[i].nodeName=='FIELDSET'){
			//alert("FIELDSET");
			
			var fieldsetx = form.elements[i];
			var counteoFielset = fieldsetx.elements.length;	
					
			//CONTANDO LOS SELECT DENTRO DEL fielset
			for(var j=0; j<counteoFielset;j++){
						
				if(fieldsetx.elements[j].nodeName=='SELECT' && fieldsetx.id=="fieldset-1"){
					counteoSelect++;
					//alert("SELECT");
				}				
			}//ENDFOR
			
		
		}//ENDIF
		//console.log(counteoSelect);
		
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


	input_id_servicio.setAttribute('name','id_detalle_servicio_prestado[]');
	input_id_servicio.setAttribute('id','id_detalle_servicio_prestado-'+id);
	input_id_servicio.setAttribute('type','hidden');
	input_id_servicio.setAttribute('size','5');
	input_id_servicio.setAttribute('value','0');
	//
	input_codigo.setAttribute('name','txt_codigo[]');
	input_codigo.setAttribute('id','txt_codigo-'+id);
	input_codigo.setAttribute('type','text');
	input_codigo.setAttribute('size','6');
	input_codigo.setAttribute('onkeyup', "seleccionarTipoDeServicio(this)");

	
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
	input_ffin.setAttribute('name','txt_fecha_inicio[]');
	input_ffin.setAttribute('id','txt_fecha_inicio-'+id);
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



//----------------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------

function crearElementoEstablecimiento(){
	
	//--inicio
	var id = contarCantidadEstablecimientos_FS2();	
	console.log("id counteo"+id);	
	// Creando Elementos	
	crearInputEstablecimientos(id);	
	//--final	

	
	
}//ENDFN

function contarCantidadEstablecimientos_FS2(){

	var form = document.form_new_empleador_dd1;
	//console.dir(form);
	var counteo = form.length;	
	
	var counteoSelect = 0;
	for(var i=0;i<counteo;i++){
		if(form.elements[i].nodeName=='FIELDSET' && form.elements[i].id=="fieldset-2"){
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

// counteoElementoTipoDeServicio_FS1




//-----------------------

function crearInputEstablecimientos(id){
	var id = id + 1;
	
	//INICIO div
	var div =document.createElement('div');
	div.setAttribute('id','establecimiento-'+id);
	
	//Capa Contenedora
	capa = document.getElementById('contenedor_establecimiento');	
	//
	capa.appendChild(div); //PRINCIPAL	
	
	//inicio html
	
var html ='';
var cerrarDiv = '<\/div>';


html +='	<div class="celda eliminar">';	
html +='    <input name="id_detalle_establecimiento[]" type="hidden" value="0" size="6" ';
html +='    id="id_detalle_establecimiento-'+id+'" />';
html += cerrarDiv;

html +='	<div class="celda producto">';    
html +='	<input name="txt_codigo_est" type="text" value="" id="txt_codigo_est-'+id+'"  ';    
html +='	 size="8" onkeyup="return buscarCodigoEstablecimiento(event,this.value,'+id+')" />';
html += cerrarDiv;     

html +='	<div class="celda cantidad">';
html +='      <select name="cbo_establecimiento[]" id="cbo_establecimiento-'+id+'"  ';
html +='	  style="width:250px;"  onchange="actualizarCodigoEstablecimientoSeleccionado('+id+',this.value)" >';
html +='      </select>';
html += cerrarDiv;

html +='	<div class="celda precio">';     
html +='    <input name="rbtn_realiza_actividad_riesgo-'+id+'" type="radio" value="1" onclick="actualizarEstadoRie(event,this.value,'+id+')" />Si';
html +='    <input name="rbtn_realiza_actividad_riesgo-'+id+'" type="radio" value="0" onclick="actualizarEstadoRie(event,this.value,'+id+')" />No';
html +='	<input type="text" name="esCenTraRieEstab[]" id="esCenTraRieEstab-'+id+'" size ="2"/>';
html += cerrarDiv;

html +='	<div class="celda sub-total">';
html +='    <a href="javascript:eliminarFilaTabEstablecimientos('+id+')" id="eliminar_elemento-'+id+'" >delete</a>';
html += cerrarDiv;

//html +='	<div>';    

//html += cerrarDiv;
//alert(html);
//finale html
//agregar en Div	
div.innerHTML=html;
cbo = document.getElementById('cbo_establecimiento-'+id);
//console.dir(cbo);
llenarComboEstablecimientos(cbo);
	
	
	
	
}//ENDFN


//------------------------------------------------
//ARRIBA FULL
//---
/**
**  ARRAY ESTABLECIMIENTOS;
**
**
**/
	var establecimientos = new Array(
	<?php $counteo = count($cbo_combo_establecimiento); 
	for($i=0;$i<$counteo;$i++): ?>	
	<?php
		if($i == $counteo-1){ 
			echo "{id:'".$cbo_combo_establecimiento[$i]['id_establecimiento']."', descripcion:'".$cbo_combo_establecimiento[$i]['descripcion']."' }"; 
		}else{
			echo "{id:'".$cbo_combo_establecimiento[$i]['id_establecimiento']."', descripcion:'".$cbo_combo_establecimiento[$i]['descripcion']."' },"; 
		}
	?>
	<?php endfor; ?>
	);
function getNumeroEstablecimiento(){
	return establecimientos.length;
}



function llenarComboEstablecimientos(objCombo){
	
	//$cbo_combo_establecimiento
	
	var test = new Array();
	test = establecimientos;

	var counteo = 	test.length;
	//_numEstablecimientosdeCombo = 	test.length;  // VARIABLE GLOBAL
		
	objCombo.options[0] = new Option('-', '');
	for(var i=0;i<counteo;i++){
		objCombo.options[i+1] = new Option(test[i].descripcion, test[i].id);
	}

}//ENDFN






//########################################################################################
// ------------- SUNAT ---- NEW FUNCIONES VALIDACION
//########################################################################################

function verificarSeleccionEstablecimiento(valor,id) {
	alert("verificar("+valor+","+id+")");
	var seleccionado=false;
	var counteo = contarCantidadEstablecimientos_FS2();
	
	for (i=1;i<=counteo;i++) {
	elem=document.getElementById("txt_codigo_est-"+i);
	if (elem!=null) {
		if(valor!=""){
			if (elem.value==valor && i!=id) {
				seleccionado=true;
			}
		}
	}
	}//ENDFOR
	return seleccionado;
} 

//-----------------------------------------------
function actualizarCodigoEstablecimientoSeleccionado(id,valor) {
	//alert(valor.substring(0,valor.indexOf('|')));
	var seleccionado=verificarSeleccionEstablecimiento(valor.substring(0,valor.indexOf('|')),id);
	//alert("seleccionado : "+seleccionado);
	if (seleccionado) {
	alert("Sólo puede seleccionarse el establecimiento una vez. El código ingresado ya se encuentra seleccionado.");
	document.getElementById("cbo_establecimiento-"+id).selectedIndex=0;
	document.getElementById("txt_codigo_est-"+id).value="";
	}
	else {
	document.getElementById("txt_codigo_est-"+id).value=valor.substring(0,valor.indexOf('|'));
	//document.getElementById("tipEstab"+id).value=valor.substring(valor.indexOf('|')+1);	//document.getElementById("desEstab"+id).value=document.getElementById("cbo_establecimiento-"+id).options[document.getElementById("selDesEst"+id).selectedIndex].text;
	}
} 



//--------------------------------------------------------
//return buscarCodigoEstablecimiento(event,this.value,'+id+')
function buscarCodigoEstablecimiento(e,valor,id) {
	tecla=(document.all) ? e.keyCode : e.which;
	if (tecla==13) {
		while (valor.length<4 && valor!="") {
		valor="0"+valor;
		}
		document.getElementById("txt_codigo_est-"+id).value=valor;
		var seleccionado=false;
		if (valor!="") {
		seleccionado=verificarSeleccionEstablecimiento(valor,id);
		}
		if (!seleccionado) {
			var select = document.getElementById('cbo_establecimiento-'+id);
			var hallado=false;
			for (i=0;i<select.length;i++) {
				if (select.options[i].value.substring(0,select.options[i].value.indexOf('|'))==valor) {
					select.selectedIndex=i;
					codEst = select.options[i].value.substring(0,select.options[i].value.indexOf('|'));
					//tipestab=select.options[i].value.substring(select.options[i].value.indexOf('|')+1);
					hallado=true;
				}
			}//ENDFOR
			if (hallado) {
			document.getElementById("txt_codigo_est-"+id).value=codEst;
			//document.getElementById("tipEstab"+id).value=tipestab;
			//document.getElementById("desEstab"+id).value=select.value;
			//document.getElementById("desEstab"+id).value=select.options[document.getElementById("selDesEst"+id).selectedIndex].text;
			//alert("hallado = true");
			}
			else {
			alert("El código ingresado es incorrecto. Verifique el código ingresado e inténtelo nuevamente.");
			select.selectedIndex=0;
			actualizarCodigoEstablecimientoSeleccionado(id,"");
			}
		}
		else {
			alert("Sólo puede seleccionarse el establecimiento una vez. El código ingresado ya se encuentra seleccionado.");
			document.getElementById("txt_codigo_est-"+id).value="";
			select.selectedIndex=0;
		}
	}
} 

//---------------------------------------------------------------
//actualizarCodigoEstablecimientoSeleccionado

function actualizarEstadoRie(e,valor,codestab){
	document.getElementById("esCenTraRieEstab-"+codestab).value=valor;
} 



///----------------
//ARRIBA FULL>>> go
//-----------------


function agregarNuevaFilaTabEstablecimientos() {
//alert("getNumeroEstablecimiento = "getNumeroEstablecimiento()+"\n contarCantidadEstablecimientos_FS2"+ contarCantidadEstablecimientos_FS2());
	if (verificarEstablecimientosSinInformacion()==false ) {
	if ( getNumeroEstablecimiento() > contarCantidadEstablecimientos_FS2() ) {
	//insertarFilaTabEstablecimientos('','','','1','','-','0'); //crearrrr
	crearElementoEstablecimiento();
	//document.getElementById("codEstab"+idxfilastabestab).focus();
	}
	else {
	alert("No se pueden agregar más establecimientos de los que tiene este empleador.");
	}
	}
	else {
	alert("Hay un establecimiento al que no se le ha asignado un código o indicador de riesgo.");
	}


} 


function verificarEstablecimientosSinInformacion() {
	var cantsininfo=0;
	counteo = contarCantidadEstablecimientos_FS2();//CONTADOR SELECT!
	for (i=1;i<=counteo;i++) {
	if (document.getElementById("txt_codigo_est-"+i)!=null) {
	if (document.getElementById("txt_codigo_est-"+i).value=="") {
	cantsininfo++;
	}
	}
	}
	if (cantsininfo>0) {
	return true;
	}
	else {
	return false;
	}
} 

//--------
function verificarEstablecimientosSinIndRiesgo() {
	var cantsininfo=0;
	counteo = contarCantidadEstablecimientos_FS2();//CONTADOR SELECT!
	for (i=1;i<=counteo;i++) {
	if (document.getElementById("esCenTraRieEstab-"+i)==null) {
	//cantsininfo++;
	}else{
	if (document.getElementById("esCenTraRieEstab-"+i).value=="") {
	cantsininfo++;
	}
	}
	}
	if (cantsininfo>0) {
	return true;
	}
	else {
	return false;
	}
} 

//------
function verificarServiciosSinInformacion() {
var cantsininfo=0;
var counteo = counteoElementoTipoDeServicio_FS1();
for (i=1;i<=counteo;i++) {
if (document.getElementById("cbo_tipo_servicio_prestado-"+i)!=null) {
if (document.getElementById("cbo_tipo_servicio_prestado-"+i).value=="0") {
cantsininfo++;
}
}
}
if (cantsininfo>0) {
return true;
}
else {
return false;
}
} 


//---------------------------

function eliminarFilaTabEstablecimientos(id){
	
	//var div_padre = document.getElementById('contenedor_establecimiento');
	//div_padre.removeChild(document.getElementById('eliminar_elemento-'+id));
		
}
//################################################################
//################  GRABAR
//################################################################


function grabar(){

//alert("cantida de servicios "+contarCantidadEstablecimientos_FS2();

	if (counteoElementoTipoDeServicio_FS1()==0){
	alert("Debe ingresar al menos un servicio.");
	}
	else if (verificarServiciosSinInformacion()==true) {
	alert("Hay un servicio al que no se le ha asignado un código o fecha inicial.");
	}/*
	else if (verificarServiciosSinDescripcion()==true) {
	alert("Hay un servicio que no tiene una descripción válida.");
	}*/
	else if (contarCantidadEstablecimientos_FS2()==0) {
	alert("Debe ingresar al menos un establecimiento a donde destacó personal.");
	}
	else if (verificarEstablecimientosSinInformacion()==true) {
	//alert("Hay un establecimiento al que no se le ha asignado un código o indicador de riesgo.");
	alert("Hay un establecimiento al que no se le ha asignado un código.")
	}else if (verificarEstablecimientosSinIndRiesgo()==true) {
	alert("Hay un establecimiento al que no se le ha asignado indicador de riesgo.");	
	
	}
	else{//document.form_new_empleador_dd1.action="empleadores.htm?accion=validarDatosEmpleadorTercero&desdehacia=2&errorvalidacion="+errorvalidacion+"&estabiniciales="+estabiniciales+"&estabeliminados="+estabeliminados+"&estabfinales="+estabfinales;
		return true;
		//if(confirm("Seguro que quiere guardar Datos")){
			//document.form_new_empleador_dd1.submit(); 			
		//}
	}


}

//###############################################################
// ultima vALIDACION
//###############################################################


     function validarFormulario(){

//		var estado = errorEnComboyFecha_dd2();
		var estado = grabar();
		
		console.log(estado);
		if(estado){ //alert ("true ajax");			
			//-----------------------------------------------------------------------				
				var from_data =  $("#form_new_empleador_dd1").serialize();
				$.getJSON('sunat_planilla/controller/EmpleadorDestaqueController.php?'+from_data,
					function(data){
						if(data){
							alert ('Se Guardo Correctamente.');
							cargar_pagina('sunat_planilla/view/edit_empleador.php?id_empleador=<?php echo $ID_EMPLEADOR; ?>','#CapaContenedorFormulario');
						}else{
							alert("Ocurrio un error");
						}
					}); 
			//-----------------------------------------------------------------------
		}else{
			alert ("ajax false ");
		
		}

		return true;
				 

}//ENDVAlidarformulario 

</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro del Empleador </a></li>			

        </ul>
        <div id="tabs-1">
		
          <h1 class="subtitulo_sunat">Empleador   a quien destaco o desplazo personal	
          </h1>
          <form action="sunat_planilla/controller/EmpleadorController.php" method="POST" name="form_new_empleador_dd1" id="form_new_empleador_dd1" >
<div class="ocultar">id_empleador_maestro
  <input type="text" name="id_empleador_maestro" id="id_empleador_maestro" readonly="readonly"
                  value="<?php echo ( empty($DATA_EMPLEADOR_MAESTRO['id_empleador_maestro']))? "ERROR NO Es Empleador Maestro" : $DATA_EMPLEADOR_MAESTRO['id_empleador_maestro'];?>" />
  <br />
  
  
  id_empleador_subordinado:
  <input name="id_empleador" type="text" class=""  id="id_empleador" readonly="readonly"
                  value="<?php echo $DATA_EMP_SUBORDINADO['id_empleador']; ?>" />
  <br />
  
  RUC_subordinado:
  <input name="txt_ruc_buscar" type="text" class="required error"  id="txt_ruc_buscar" size="15" maxlength="11"
                value="<?php echo $DATA_EMP_SUBORDINADO['ruc']; ?>" />
  <br />
  
  
  Nombre / Razón Social:
  <input name="txt_razon_social" type="text" class="required error"  id="txt_razon_social" readonly="readonly"
                  value="<?php echo $DATA_EMP_SUBORDINADO['razon_social_concatenado']; ?>" />
  <br />
  
  
  oper
  <input type="text" name="oper" id="oper" value="add"/>
</div>
<div id="datos-empleador2-ajax" style="max-width:750px;" >
  <fieldset id="fieldset-1">
  <legend><strong>Servicios Recibidos</strong></legend>
<p>
  <input name="btn_agregar" type="button" id="btn_agregar" value="Agregar" onclick="crearElementoTipoDeServicio()" />

  Fecha de periodo a perido max 1 mes


<!-- INI AJAX cesta de productos -->
(Servicio Prestado no se pueden duplicar con la misma fecha de inicio O un mismo servicio no puede contener la misma fecha inicio)<div class="cesta-productos">
    <div class="negrita" >
				  <div class="celda eliminar">E</div>
				  <div class="celda producto">Codigo</div>
			    <div class="celda cantidad">Servicio Prestado</div>
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
$DATA = $DATA_DETALLE_DD1;
$counteo = count($DATA);
//echo "<h1>Numero de Servicios Registrados :</h1>".$counteo;
if ($counteo >= 1):
for($i=0;$i<$counteo;$i++):

$id = $i+1;

?>
<!-- INICIO HTML -->

<div id="for-each"  >
<div class="celda eliminar">
  <input name="id_detalle_servicio_prestado[]"  id="id_detalle_servicio_prestado-<?php echo $id;?>" 
type="hidden"  size="2" 
value="<?php echo $DATA[$i]['id_servicio_prestado']; ?>" />
</div>
<div class="celda producto">
<input name="txt_codigo[]" type="text" size="9" id="txt_codigo-<?php echo $id;?>" 
value="<?php echo $DATA[$i]['cod_tipo_actividad']; ?>" onblur="seleccionarTipoDeServicio(this)"/>
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
value="<?php  echo getFechaPatron($DATA[$i]['fecha_inicio'],"d/m/Y"); ?>" />
</div>
<div class="celda sub-total">
<input name="txt_fecha_fin[]" id="txt_fecha_fin-<?php echo $id;?>"
type="text"  size="12" 
value = "<?php echo getFechaPatron($DATA[$i]['fecha_fin'],"d/m/Y");  ?>" />
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
<!-- END AJAX cesta de productos --><br />
    
    Establecimientos donde destaco o desplazo personal
    
  <br />
  <input name="btn_agregar2" type="button" id="btn_agregar2" value="Agregar"
    onclick="agregarNuevaFilaTabEstablecimientos()" />
  </fieldset>
  <fieldset id="fieldset-2">
    <legend>lista de Establecimientos</legend>
    <div class="cesta-productos">
      <div class="negrita" >
				  <div class="celda eliminar"></div>
				  <div class="celda producto">Codigo</div>
			    <div class="celda cantidad">Establecimiento</div>
					<div class="precio celda">¿Realizaran trabajao de riesgo?
					</div>
				  <div class="celda sub-total">quitar.</div>
                  <div class="clear"></div>
</div>
<!-- END AJAX cesta de productos -->









<div id="contenedor_establecimiento" >
  
  
<!-- Inicio HTML 2-->

<div id="for-each-2" style="display:block; border:1px solid blue" >
<?php 
//Control de datos del Empleador que me destaca personal
//01 IF
$DATA = array();
$DATA = $DATA_DETALLE_EV;
$counteo = count($DATA);
//echo "<h1>Numero de Servicios Registrados :</h1>".$counteo;
if ($counteo >= 1):
for($i=0;$i<$counteo;$i++):

$id = $i+1;

?>

        <div id="establecimiento-1<?php echo $id;?>">
            <div class="celda eliminar"> 
                <input type="hidden" id="id_detalle_establecimiento-<?php echo $id;?>" size="2" name="id_detalle_establecimiento[]"
                value="<?php  echo $DATA[$i]['id_establecimiento_vinculado']; ?>" >
            </div>	
            <div class="celda producto">
                <input type="text" onkeyup="return buscarCodigoEstablecimiento(event,this.value,1)" size="8" id="txt_codigo_est-<?php echo $id;?>" name="txt_codigo_est"
                value="<?php echo $DATA[$i]['cod_establecimiento']; ?>" >
            </div>	
            <div class="celda cantidad">       
                <select onchange="actualizarCodigoEstablecimientoSeleccionado(1,this.value)" style="width:250px;" id="cbo_establecimiento-<?php echo $id;?>" name="cbo_establecimiento[]">

                <option value="" >-</option>
                <?php 
                foreach ($cbo_combo_establecimiento as $indice):
                if ($indice['id_establecimiento'] == $DATA[$i]['concat_id_establecimiento'] ) {
                
                $html = '<option value="'. $indice['id_establecimiento'] .'" selected="selected" > ' . $indice['descripcion'] . '</option>';
                
                } else {
                $html = '<option value="'. $indice['id_establecimiento'] .'" >' . $indice['descripcion'] . '</option>';
                }
                echo $html;
                endforeach;
                
                ?>


                </select>
            </div>	
            <div class="celda precio">
                <input type="radio" onclick="actualizarEstadoRie(event,this.value,<?php echo $id;?>)" <?php if($DATA[$i]['realizan_trabajo_de_riesgo'] == 1){ ?> checked="checked" <?php } ?>  value="1" name="rbtn_realiza_actividad_riesgo-<?php echo $id;?>">Si    
                    <input type="radio" onclick="actualizarEstadoRie(event,this.value,<?php echo $id;?>)" <?php if($DATA[$i]['realizan_trabajo_de_riesgo'] == 0){ ?> checked="checked" <?php  }?>  value="0"  name="rbtn_realiza_actividad_riesgo-<?php echo $id;?>">No
                       
                        <input type="text" size="2" id="esCenTraRieEstab-<?php echo $id;?>" name="esCenTraRieEstab[]" value="<?php echo $DATA[$i]['realizan_trabajo_de_riesgo']; ?>"></div>	<div class="celda sub-total"> 
                                <a id="eliminar_elemento-1<?php echo $id;?>" href="javascript:eliminarFilaTabEstablecimientos(1)">delete</a>
                            </div>
                  </div><!-- html fin-->

<?php

//02 IF
endfor;
else:
echo "ELSEEEEE-X2!!";

 ?>
 
 
 

<?php
//03 IF
endif;
?>











<div class="clear"></div>
</div>

<!-- fin HTML 2-->



  </div>










<!-- END AJAX cesta de productos -->
		  </div>

    
    
    

</fieldset>
  



</div>			  
			  
			  
			  
			  
			  
			  
			  


            <p><!-- DIRECCION 2-->
			<input name="btn_grabar" type="button" id="btn_grabar" value="Grabar" 
            onclick="validarFormulario()"  >

                <input name="btnRetornar" type="button" id="btnRetornar" value="Retornar" 
                onclick="javascript:cargar_pagina('sunat_planilla/view/view_empleador_dd1.php','#CapaContenedorFormulario');" />
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

<!-- -->

<!-- -->

<div id="dialog-form-editarDireccion" title="Editar Direccion">
    <div id="editarPersonaDireccion" align="left"></div>
</div>