// JavaScript Document

//Variables Global
var FECHAPC = new Date();




function llenarComboGlobal(data, objCombo){ //console.log(json);
	objCombo.options[0] = new Option('-', '0');
	for(var i=0;i<(data.length);i++){
		objCombo.options[i+1] = new Option(data[i].descripcion, data[i].id);
	}
}

/*************************
* TRABAJADORES
*************************/
function validarFormtrabajador(){

	//esFechaValida
	var estado = false;
	var form = document.form_trabajador;
	
	//---------
	var f1 = form.txt_plaboral_fecha_inicio_base.value;
	var f2 = form.txt_plaboral_fecha_fin_base.value;
	
	var f3 = form.txt_ttrabajador_fecha_inicio_base.value;
	var f4 = form.txt_ttrabajador_fecha_fin_base.value;
	
	var f5 = form.txt_rsalud_fecha_inicio_base.value;
	var f6 = form.txt_rsalud_fecha_fin_base.value;
	
	var f7 = form.txt_rpensionario_fecha_inicio_base.value;
	var f8 = form.txt_rpensionario_fecha_fin_base.value;



//---------------------------------------------------
	var fecha_estado = null;
	//alert ("1"+ fecha_estado);

//PASO 1
	if( fecha_estado == null ){
		//alert("entro "+fecha_estado);
		if(f1 == ""){
			alert("Debe Ingresar Fecha inicio del Periodo Laboral");
			fecha_estado = false;
		}else if(esFechaValida(f1)==false){
			alert("Formato de Fecha inicio del Periodo Laboral es Incorrecto");
			fecha_estado = false;
		}else{
			fecha_estado = validarFechaInicio_Formtrabajador(f1);		
		}
		
		//FECHA FIN

			
	}//ENDIF
	
//PASO 1.2	
var estado_f2=false;
if( fecha_estado == true ){

		if(f2 != ""){
			
			if(esFechaValida(f2)==false){
				alert("Error en Formato de Fecha Fin \ndel Periodo Laboral");
				fecha_estado = false;
			}else{
				fecha_estado = validarFechaFin_Formtrabajador(f2);
				estado_f2 = fecha_estado;
			}			
		}
		
		

}//ENDFN
//---PASO 1.3
if(estado_f2 ==true){
	if(form.cbo_plaboral_motivo_baja_base.value == 0 || form.cbo_plaboral_motivo_baja_base.value == ""){
		alert ("Debe seleccionar  Motivo de baja del Periodo Laboral");
		fecha_estado =false;
	}
}



	
	
	
	
//PASO 2 
//alert("2 " +fecha_estado );	
	if(fecha_estado ==true){
		
		if(f3 == ""){
			alert("Debe Ingresar Fecha Inicio de Tipo de Trabajador");
			fecha_estado = false;
		}else if(esFechaValida(f3)==false){
			alert("Error en Formato de Fecha Inicio de Tipo de Trabajador es Incorrecto");
			fecha_estado = false;
		}else{
			fecha_estado = validarFechaInicio_Formtrabajador(f3);		
		}
	}//ENDIF
	
//PASO 2.2	
if( fecha_estado == true ){

		if(f4 != ""){
			
			if(esFechaValida(f4)==false){
				alert("Error en Formato de Fecha Fin \ndel Tipo de Trabajador");
				fecha_estado = false;
			}else{
				fecha_estado = validarFechaFin_Formtrabajador(f4);		
			}
		}

}

	
	
	
//PASO 3
	if(fecha_estado ==true){
		
		if(f5 == ""){
			alert("Debe Fecha Inicio de Regimen de salud");
			fecha_estado = false;
		}else if(esFechaValida(f5)==false){
			alert("Error en Formato de Fecha Inicio de Regimen de salud");
			fecha_estado = false;
		}else{
			fecha_estado = validarFechaInicio_Formtrabajador(f5);		
		}
	}//ENDIF
	
//PASO 3.2	
if( fecha_estado == true ){

		if(f6 != ""){
			
			if(esFechaValida(f6)==false){
				alert("Error en Formato de Fecha Fin \ndel  Regimen de salud");
				fecha_estado = false;
			}else{
				fecha_estado = validarFechaFin_Formtrabajador(f6);		
			}
		}

}
	
	


//PASO 4
	if(fecha_estado ==true){
		
		if(f7 == ""){
			alert("Debe Fecha Inicio de Regimen Pensionario");
			fecha_estado = false;
		}else if(esFechaValida(f7)==false){
			alert("Error en Formato de Fecha Inicio de Regimen Pensionario");
			fecha_estado = false;
		}else{
			fecha_estado = validarFechaInicio_Formtrabajador(f7);		
		}
	}//ENDIF
	
//PASO 4.2	
if( fecha_estado == true ){

		if(f8 != ""){
			
			if(esFechaValida(f8)==false){
				alert("Error en Formato de Fecha Fin \ndel  Regimen Pensionario");
				fecha_estado = false;
			}else{
				fecha_estado = validarFechaFin_Formtrabajador(f8);		
			}
		}

}


		
		
		
		

if( fecha_estado==true){ alert ("entroodosadasdasdasd");
	fecha_estado = validarRelacionPeriodoConResto();
	
}
				
		
		
		
/*	
	}else if(f2 != ""){
		if(esFechaValida(f2)==false){
			alert("Formato de Fecha Fin del Periodo Laboral es Incorrecto");
			fecha_estado = false;
		}else{
			fecha_estado = validarFechaFin_Formtrabajador(f1);
		}
	
	
	}
*/
	
	
	//alert ("validando okkk "+fecha_estado);
	
	
	return fecha_estado;
}










/**
* Validar la relación del campo Período con el resto de conceptos que 
* registran periodicidad. 
*/

function validarRelacionPeriodoConResto(){
	
	var estado =true;
	
	var fecha_tope = new Date(2011,7,1);
	//fecha_tope.setMonth((fecha_tope.getMonth) - 1);
	
	var form = document.form_trabajador;
	
	//---------
	var f1x = form.txt_plaboral_fecha_inicio_base.value;
	
	var f3x = form.txt_ttrabajador_fecha_inicio_base.value;
	
	var f5x = form.txt_rsalud_fecha_inicio_base.value;
	
	var f7x = form.txt_rpensionario_fecha_inicio_base.value;

	
	var f1 = new Date(cargarFecha(f1x));
	var f3 = new Date(cargarFecha(f3x));
	var f5 = new Date(cargarFecha(f5x));
	var f7 = new Date(cargarFecha(f7x));
	
	console.log("fecha tope es "+ fecha_tope);
	
console.log("---f1");
	console.log(f1);
		console.log("---f3");
	console.log(f3);
		console.log("---f5");
	console.log(f5);
		console.log("---f7");
	console.log(f7);

	
	console.log("verificando fechassssssss ssss s");
	console.log("fecha tope  = "+ fecha_tope);
	
	if( f1 >= fecha_tope){ //MAYOR A FECHA TOPE CONDICION
		console.log("fecha f1 es MAYOR =  A fecha tope ");
		console.log("consicion");
	
		if( ( f1.getTime() == f3.getTime() ) && (f1.getTime() == f5.getTime() ) && ( f1.getTime()==f7.getTime()) ){ 
					console.log("f1 y f3 == consicion");
			alert ("correcto11111");
			estado = true;
			
			//return true;
		}else{		console.log("else direrentes f1 y f3 ");
			var mensaje = "- la fecha de inicio TT tiene que ser Igual F Inicio del Periodo Laboral\n";
			mensaje += "- La fecha de inicio Regimen de Salud  debe ser Igual F Inicio del Periodo Laboral\n";
			mensaje += "- La fecha de inicio Regimen de Pensionario  debe ser Igual F Inicio del Periodo Laboral";
			
			alert (mensaje);
			estado =  false;
			
		}
		
		
	
	}else{ //menor A FECHA TOPE CONDICION
		console.log("no entro");
			if( ( f3.getTime()>= f1.getTime() ) && (f5.getTime()>=f1.getTime() ) && ( f7.getTime()>=f1.getTime()) ){ 
				//CORRECTO
				alert("correcto2222");
				estado = true;
				
			}else{
				var mensaje = "Fechas de Inicio \nTipo Trabajador, Regimen de Salud, Regimen Pensionario";
				mensaje +="\n No pueden Ser menor A la Fecha de Inicio del \nPeriodo Laboral";
				alert (mensaje);
				estado =false;	
			}

		
	}
	
	return estado;
	


}






//----------------------------------------------------------------------------
function cargarFecha(fecha){
	var dia  =  parseInt(fecha.substring(0,2),10);
	var mes  =  parseInt(fecha.substring(3,5),10);
	var anio =  parseInt(fecha.substring(6),10);		
	var fecha = new Date(anio,mes - 1 ,dia/*, 0,0,0*/);
	return fecha;
}


function validarFechaInicio_Formtrabajador(fecha){// alert("hola"+fecha);

		//fecha entrada:
		/**
		* Condicion Fecha no Puede ser Mayor a 30 dias de FECHA actual
 		*/
		var fecha = cargarFecha(fecha);
		
		var fecha_mas_30 = new Date(FECHAPC);		
		fecha_mas_30.setDate((fecha_mas_30.getDate() + 30));
		
		console.log(fecha);
		console.log("fecha + 30");
		console.log(fecha_mas_30);
		
		if(fecha > fecha_mas_30){
			alert("fechas de inicio\n no pueden ser mayor en 30 dias a la fecha actua");
			return false
		}else{
			return true;
		}		
		
			

	
}//ENDFN


function validarFechaFin_Formtrabajador(fecha){
		//fecha entrada:
		/**
		* Condicion Fecha no Puede ser Mayor a 5 dias de FECHA actual
 		*/
		var fecha = cargarFecha(fecha);
		
		var fecha_mas = new Date(FECHAPC);	

		fecha_mas.setDate((fecha_mas.getDate() + 5));

		console.log(fecha_mas);
		
		if(fecha > fecha_mas){
			alert(" fecha de fin\n no pueden ser mayor en 5 dias a la fecha actual ");
			return false
		}else{
			return true;
		}
		
}






//----------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------
//form_trabajador
     function validarFormtrabajadorPrincipal(){

		var estado = validarFormtrabajador();
		
		var id_persona = document.getElementById('id_persona_categoria').value;
			
			
		if(/*false*/estado){ //alert("ajax true");			
			//-----------------------------------------------------------------------				
				var from_data =  $("#form_trabajador").serialize();
				$.getJSON('sunat_planilla/controller/CategoriaTrabajadorController.php?id_persona='+id_persona+'&'+from_data,
					function(data){
						if(data){
							alert ('Se Guardo Correctamente Trabajador.');
							//cargar_pagina('sunat_planilla/view/view_empleador.php','#CapaContenedorFormulario');
							cargar_pagina('sunat_planilla/view/view_personal.php','#CapaContenedorFormulario')
						}else{
							alert("Ocurrio un error");
						}
					}); 
			//-----------------------------------------------------------------------
		}else{
		//	alert ("ajax false ");
		
		}
		return true;
}//ENDVAlidarformulario 



/*************************
* PENSIONISTAS 
*************************/

     function validarFormularioPensionista(){

		var estado = true;//grabar();
		var id = document.getElementById('id_pensionista').value;
		alert(estado);	
		if(estado){ alert("ajax true");			
			//-----------------------------------------------------------------------				
				var from_data =  $("#form_pensionista").serialize();
				$.getJSON('sunat_planilla/controller/CategoriaPensionistaControlller.php?'+from_data,
					function(data){
						if(data){
							alert ('Se Guardo Correctamente Pensionista.');
							//cargar_pagina('sunat_planilla/view/view_empleador.php','#CapaContenedorFormulario');
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

/*************************
* PERSONA EN FORMACION 
*************************/

     function validarFormularioPersonaFormacion(){

		var estado = true;//grabar();
		//var id = document.getElementById('id_pensionista').value;
		alert(estado);	
		if(estado){ alert("ajax true");			
			//-----------------------------------------------------------------------				
				var from_data =  $("#form_personal_en_formacion").serialize();
				$.getJSON('sunat_planilla/controller/CategoriaPFormacionController.php?'+from_data,
					function(data){
						if(data){
							alert ('Se Guardo Correctamente Persona en Formacion.');
							//cargar_pagina('sunat_planilla/view/view_empleador.php','#CapaContenedorFormulario');
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
//--------------------------------------


/*************************
* PERSONA EN FORMACION 
*************************/

     function validarFormularioPersonaDeTerceros(){

		var estado = true;//grabar();
		//var id = document.getElementById('id_pensionista').value;
		alert(estado);	
		if(estado){ alert("ajax true");			
			//-----------------------------------------------------------------------				
				var from_data =  $("#form_persona_de_terceros").serialize();
				$.getJSON('sunat_planilla/controller/CategoriaPTercerosController.php?'+from_data,
					function(data){
						if(data){
							alert ('Se Guardo Correctamente Personal de Terceros.');
							//cargar_pagina('sunat_planilla/view/view_empleador.php','#CapaContenedorFormulario');
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
//--------------------------------------





/**
* Cargar Establecimientos BASE
*
**/
function cargarEmpleadoresBase(){
	//alert("cargarEmpleadoresBase ?? LOFA ....");
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'sunat_planilla/controller/EmpleadorDestaqueController.php',
		data: {oper: 'lista_emp_dest'},
		beforeSend: function(objeto){ /*alert("Adiós, me voy a ejecutar");*/ },
        complete: function(objeto, exito){ /*alert("Me acabo de completar");
			if(exito=="success"){alert("Y con éxito");}*/
        },
		success: function(data){
			//console.log(data);
			var cbo_base = document.getElementById('cbo_establecimiento');
			llenarComboGlobal(data,cbo_base);	
		}
	});	

}


function cargarEstablecimientosBase(){
	
	alert("cargarEstablecimientosBase ?? LOFA ....");
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'sunat_planilla/controller/EmpleadorDestaqueController.php',
		data: {oper: 'lista_establecimiento'},
		beforeSend: function(objeto){ /*alert("Adiós, me voy a ejecutar");*/ },
        complete: function(objeto, exito){ /*alert("Me acabo de completar");
			if(exito=="success"){alert("Y con éxito");}*/
        },
		success: function(data){
			console.log(data);
			var cbo_base = document.getElementById('cbo_establecimiento_local');
			llenarComboGlobal(data,cbo_base);	
		}
	});	
	
}





//-----------------------------------------
function cargarEstablecimientoLocales(idComboPadre){
	
	var valor = idComboPadre.value;
	//alert("id_empleador "+ valor);
	var objCombo = document.getElementById('cbo_establecimiento_local');
	//cbo_depa.options[cbo_depa.selectedIndex].value
	if(valor=='0'){
		//objCombo.disabled = true;
		alert("Debe Selecionar Un Establecimiento Correcto");
		limpiarComboGlobal(objCombo);
	}else{
		//limpiarComboGlobal(objCombo);
		//objCombo.disabled = false;
	//-----

	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'sunat_planilla/controller/EmpleadorDestaqueController.php',
		data: {id_empleador: valor, oper: 'lista_establecimiento'},
		beforeSend: function(objeto){ /*alert("Adiós, me voy a ejecutar");*/ },
        complete: function(objeto, exito){ /*alert("Me acabo de completar");
			if(exito=="success"){alert("Y con éxito");}*/
        },
		success: function(json){
			//console.log(json);
			if(json == null || json.length<1 ){
				var mensaje = "No Existen Establecimientos Registrados\n";
				mensaje += "Registe los establecimientos correspondientes para el Empleador\n";
				mensaje += "O el problema es aun Mayor"; 
				alert(mensaje);	
			}else{				
				llenarComboDinamico(json,objCombo);
			}
		}
	});
	//-----		
	}
	

}//ENDIF
//-----------------------
//-----------------------------------------
/*
function cargarEstablecimientoLocalesYourself(idComboPadre){
	
	var valor = idComboPadre.value;
	//alert("id_empleador "+ valor);
	var objCombo = document.getElementById('cbo_establecimiento_local_yourself');
	//cbo_depa.options[cbo_depa.selectedIndex].value
	if(valor=='0'){
		//objCombo.disabled = true;
		alert("Debe Selecionar Un Establecimiento Correcto");
		//limpiarComboGlobal(objCombo);
	}else{
		//limpiarComboGlobal(objCombo);
		//objCombo.disabled = false;
	//-----
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'sunat_planilla/controller/EmpleadorDestaqueYourselfController.php',
		data: {id_empleador: valor, oper: 'lista_establecimiento_yourserlf'},
        },
		success: function(json){
			//console.log(json);
			limpiarComboGlobal(objCombo);
			llenarComboGlobal(json,objCombo);
			//llenarComboDinamico(json,objCombo);	
		}
	});
	//-----		
	}
	

}//ENDIF
*/

//-----------------------
function llenarComboDinamico(test,objCombo){

	var counteo = objCombo.length;
	for(var i=0;i<counteo;i++){
		objCombo.options[i] = null;
	}
	//console.log("fin limpiado");	
	
	var counteo = 	test.length;		
	objCombo.options[0] = new Option('-', '0');
	for(var i=0;i<counteo;i++){
		objCombo.options[i+1] = new Option(test[i].descripcion, test[i].id);
	}


}

//----------------------

//javascript:cargar_pagina('sunat_planilla/view/view_derechohabiente.php','#tabs-2')
//setTimeout(cargar_pagina('sunat_planilla/view/view_derechohabiente.php','#tabs-2'), 5000);






/*******************************************************************************************************
** ----------------------------- DETALLE 01 ------------------------------------------------------------
 [trabajador.php]= Periodos Laborales
********************************************************************************************************/

function contarDetalle_1(){

	var form = document.frm_detalle_1;
	var counteoSelect=0;

	for(var i=0;i<form.elements.length;i++){		
		if(form.elements[i].nodeName=='SELECT'){
			counteoSelect++;
		}
	}
	//alert("#"+counteoSelect);
	return counteoSelect;	
}//endfunction


function buscarCodigoDeCombo(codigo,idCombo){
		
	//var obj =document.getElementById(idInput);
	var aguja = codigo;	
	var eCombo = document.getElementById(idCombo);
	var counteo = eCombo.options.length;
	
	var encontro = false
	
	for(i=0;i<counteo;i++){
		if(aguja == eCombo.options[i].value){			
			eCombo.options[i].selected = true;
			encontro = true;
			break;
		}			
	}//end for	
	if(encontro==false){
		eCombo.options[0].selected = true;
		//obj.value="";
	}
}//ENDFN
//--------------------------------------------------


function editarDialogoDetalle_1(){  alert ("...");
	crearDialogoDetalle_1();
	$('#dialog_detalle_1').dialog('open');
	var form = document.form_trabajador;
	var f_inicio = form.txt_plaboral_fecha_inicio_base.value;
	var f_fin = form.txt_plaboral_fecha_fin_base.value;
	var cbo_motivo_baja = form.cbo_plaboral_motivo_baja_base.value;
	alert("hola 2..."+cbo_motivo_baja);
	
	if(contarDetalle_1()==1){
	//POP UP -- pasar datos
	document.getElementById('txt_plaboral_fecha_inicio-1').value= f_inicio;
	document.getElementById('txt_plaboral_fecha_fin-1').value = f_fin
	//function para cagar combo con ID

	buscarCodigoDeCombo(cbo_motivo_baja,'cbo_motivo_baja-1');

//	document.getElementById('cbo_motivo_baja-1').value = "hola cbo";
	}

}
function guardarDialogoDetalle_1(){
	// DATOS DE POP UP a  form principal	
	var id = contarDetalle_1(); alert(id);
	
	
	var popup_fecha1 = document.getElementById('txt_plaboral_fecha_inicio-'+id).value;
	var popup_fecha2 = document.getElementById('txt_plaboral_fecha_fin-'+id).value;
	var popup_cbo_motivo= document.getElementById('cbo_motivo_baja-'+id).value;
	
	var form = document.form_trabajador;
	form.txt_plaboral_fecha_inicio_base.value=popup_fecha1;
	form.txt_plaboral_fecha_fin_base.value=popup_fecha2;
	//var codigo = form.cbo_plaboral_motivo_baja_base.value;
	
	buscarCodigoDeCombo(popup_cbo_motivo,'cbo_plaboral_motivo_baja_base');
	
	if(id == 1){
		form.txt_plaboral_fecha_inicio_base.disabled=false;
		form.txt_plaboral_fecha_fin_base.disabled=false;
		form.cbo_plaboral_motivo_baja_base.disabled=false;
	}else if(id>=2){//validado field llenos
		form.txt_plaboral_fecha_inicio_base.disabled=true;
		form.txt_plaboral_fecha_fin_base.disabled=true;
		form.cbo_plaboral_motivo_baja_base.disabled=true;
	}

}

//----------------------
function crearDialogoDetalle_1(){
alert('crearDialogoDetalle_1');
	$("#dialog_detalle_1").dialog({         
			autoOpen: false,
			height: 310,
			width: 720,
			modal: false,                        
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {					
					//---	VALIDACION ECHA EN 	modal/detalle_persona_direccion.php	
					guardarDialogoDetalle_1();
					$(this).dialog('close');
					//$('#dialog_detalle_1').dialog('close');
				}//btn guardar
                                
			},//button
			open: function() {},
			close: function() {}
	});
}
//---------------------------------------
function crearElementoDetalle_1(){	
	//--inicio
	var id = contarDetalle_1();	
	console.log("id counteo"+id);	
	// Creando Elementos	
	crearInputDetalle_1(id);	
	//--final
}//ENDFN
//-----------------------
function crearInputDetalle_1(id){
	var id = id + 1;	
	//INICIO div
	var div =document.createElement('div');
	div.setAttribute('id','plaboral-row-'+id);	
	//Capa Contenedora
	capa = document.getElementById('contenedor_plaboral');
	capa.appendChild(div); //PRINCIPAL		
	//inicio html
	
var html ='';
var cerrarDiv = '<\/div>';


html +='	<div class="celda eliminar">';	
html +='    <input name="id_detalle_plaboral[]" type="text" value="0" size="6" ';
html +='    id="id_detalle_plaboral-'+id+'" />';
html += cerrarDiv;

html +='	<div class="celda producto">';    
html +='	<input name="txt_plaboral_fecha_inicio[]" type="text" value="" id="txt_plaboral_fecha_inicio-'+id+'"  ';    
html +='	 size="15"  />';
html += cerrarDiv; 

html +='	<div class="celda precio">';
html +='    <input  type="text" id="txt_plaboral_fecha_fin-'+id+'"  name="txt_plaboral_fecha_fin[]"size="15" />';      
html += cerrarDiv;    

html +='	<div class="celda cantidad">';
html +='      <select name="cbo_motivo_baja[]" id="cbo_motivo_baja-'+id+'"  ';
html +='	  style="width:250px;"  onchange="" >';
html +='      </select>';
html += cerrarDiv;


html +='	<div class="celda sub-total">';
html +='    <a href="javascript:eliminarElemento( document.getElementById( \'plaboral-row-'+id+'\' ) )" > delete </a>';
html += cerrarDiv;   

div.innerHTML=html;
cbo = document.getElementById('cbo_motivo_baja-'+id);
//console.dir(cbo);
llenarComboDetalle_1(cbo);	
}//ENDFN





/*******************************************************************************************************
** ----------------------------- DETALLE 02 ------------------------------------------------------------
 Tipo Trabajador
********************************************************************************************************/

function contarDetalle_2(){

	var form = document.frm_detalle_2;
	var counteoSelect=0;

	for(var i=0;i<form.elements.length;i++){		
		if(form.elements[i].nodeName=='SELECT'){
			counteoSelect++;
		}
	}
	//alert("#"+counteoSelect);
	return counteoSelect;	
}//endfunction



function editarDialogoDetalle_2(){  //alert (".");
	crearDialogoDetalle_2();
	$('#dialog_detalle_2').dialog('open');

	var form = document.form_trabajador;
	var f_inicio = form.txt_ttrabajador_fecha_inicio_base.value;
	var f_fin = form.txt_ttrabajador_fecha_fin_base.value;
	var cbo_motivo_baja = form.cbo_ttrabajador_base.value;
	alert(cbo_motivo_baja);
	
	if(contarDetalle_1()==1){
	//POP UP -- pasar datos
	document.getElementById('txt_ttrabajador_fecha_inicio-1').value= f_inicio;
	document.getElementById('txt_ttrabajador_fecha_fin-1').value = f_fin
	//function para cagar combo con ID
	buscarCodigoDeCombo(cbo_motivo_baja,'cbo_ttrabajador-1');
//	document.getElementById('cbo_motivo_baja-1').value = "hola cbo";
	}
}

//------------
function guardarDialogoDetalle_2(){
	// DATOS DE POP UP a  form principal	
	var id = contarDetalle_1(); alert(id);
	
	
	var popup_fecha1 = document.getElementById('txt_ttrabajador_fecha_inicio-'+id).value;
	var popup_fecha2 = document.getElementById('txt_ttrabajador_fecha_fin-'+id).value;
	var popup_cbo_motivo= document.getElementById('cbo_ttrabajador-'+id).value;
	
	var form = document.form_trabajador;
	form.txt_ttrabajador_fecha_inicio_base.value=popup_fecha1;
	form.txt_ttrabajador_fecha_fin_base.value=popup_fecha2;
	
	buscarCodigoDeCombo(popup_cbo_motivo,'cbo_ttrabajador_base');
	
	if(id>=2){//validado field llenos
		form.txt_ttrabajador_fecha_inicio_base.readOnly=true;
		form.txt_plaboral_fecha_fin_base.readOnly=true;
		//form.cbo_ttrabajador_base.disabled=true;
	}

}

//----------------------
function crearDialogoDetalle_2(){
alert('crearDialogoDetalle_2');
	$("#dialog_detalle_2").dialog({         
			autoOpen: false,
			height: 310,
			width: 720,
			modal: false,                        
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {					
					//---	VALIDACION ECHA EN 	modal/detalle_persona_direccion.php	
					guardarDialogoDetalle_2();
					$(this).dialog('close');
					$('#dialog_detalle_2').dialog('close');
				}//btn guardar
                                
			},//button
			open: function() {},
			close: function() {}
	});
}
//---------------------------------------
function crearElementoDetalle_2(){	
	//--inicio
	var id = contarDetalle_2();	
	console.log("id counteo"+id);	
	// Creando Elementos	
	crearInputDetalle_2(id);	
	//--final
}//ENDFN
//-----------------------
function crearInputDetalle_2(id){
	var id = id + 1;	
	//INICIO div
	var div =document.createElement('div');
	div.setAttribute('id','ttrabajador-row-'+id);	
	//Capa Contenedora
	capa = document.getElementById('contenedor_ttrabajador');
	capa.appendChild(div); //PRINCIPAL		
	//inicio html
	
var html ='';
var cerrarDiv = '<\/div>';

html +='	<div class="celda eliminar">';
html +='	<input type="hidden" name="id_ttrabajador[]" id="id_ttrabajador-'+id+'" />'	
html +='    <input id="txt_ttcodigo-1" name="txt_ttcodigo[]" type="text" value="0" size="5" readonly="readonly"';
html +='    id="id_detalle_plaboral-'+id+'" />';
html += cerrarDiv;

html +='	<div class="celda cantidad">';
html +='      <select name="cbo_ttrabajador[]" id="cbo_ttrabajador-'+id+'"  ';
html +='	  style="width:250px;"  onchange="" >';
html +='      </select>';
html += cerrarDiv;

html +='	<div class="celda producto">';    
html +='	<input name="txt_ttrabajador_fecha_inicio[]" type="text" value="" id="txt_ttrabajador_fecha_inicio-'+id+'"  ';    
html +='	 size="15"  />';
html += cerrarDiv; 

html +='	<div class="celda precio">';
html +='    <input  type="text" id="txt_ttrabajador_fecha_fin-'+id+'"  name="txt_ttrabajador_fecha_fin[]"size="15" />';      
html += cerrarDiv;    


html +='	<div class="celda sub-total">';
html +='    <a href="javascript:eliminarElemento(document.getElementById( \'ttrabajador-row-'+id+'\' ) )" >delete</a>';
html += cerrarDiv;   

div.innerHTML=html;
cbo = document.getElementById('cbo_ttrabajador-'+id);
//console.dir(cbo);
llenarComboDetalle_2(cbo);	
}//ENDFN





//--------------------------------
/*
* oJO SOLO SE MOSTRARA COMBO seleccionadoç
---- TIPO DE TRABAJADOR ----- CBO PADRE
19=EJECUTIVO
20=OBRERO
21=EMPLEADO

----- CATEGORIA OCUPACIONAL
01=EJECUTIVO
02=OBRERO
03=EMPLEADO
**/
function comboVinculadosTipoTrabajadorConCategoriaOcupacional(cbo){
	//alert("entro comboVinculados "+cbo.value);	
	var oper = cbo.value;
	var comboDestino = document.getElementById('cbo_categoria_ocupacional');
	
	limpiarComboGlobalID('cbo_categoria_ocupacional');
	limpiarcomboVinculadosTipoTrabajadorConCategoriaOcupacional();
	
	comboDestino.options[0] = new Option('-', '0');	
	//alert("oper = "+oper);
	if(oper == '19'){	
	
	for(var i=0;i<1;i++){ comboDestino.options[i+1] = new Option('EJECUTIVO', '01'); }
		
	}else if(oper == '20'){		

	for(var i=0;i<1;i++){ comboDestino.options[i+1] = new Option('OBRERO', '02'); }

	}else if(oper == '21'){
		
	for(var i=0;i<1;i++){ comboDestino.options[i+1] = new Option('EMPLEADO', '03'); }	

	}else{
		comboDestino.options[1] = new Option('EJECUTIVO','01');
		comboDestino.options[2] = new Option('OBRERO','02');
		comboDestino.options[3] = new Option('EMPLEADO','03');			
	}
	
	

}//ENDFN


function limpiarcomboVinculadosTipoTrabajadorConCategoriaOcupacional(){
	document.getElementById('txt_ocupacion_codigo').value="";
	var cboOcupacion = document.getElementById('cbo_categoria_ocupacional');
	cboOcupacion[0].selected;	
}

/*******************************************************************************************************
** -----------------------------  ------------------------------------------------------------

********************************************************************************************************/
function seleccionarLocalDinamico(oCombo){ //alert(oCombo.value);
	var oInput = document.getElementById('txt_codigo_local');
	var oInput2 = document.getElementById('txt_id_establecimiento');
	
	var aguja = oCombo.value;

	var partes = aguja.split("|");	
	
	var id_establecimiento = partes[0];	
	var codigo_establecimiento = partes[2];
	
	oInput.value = codigo_establecimiento;
	oInput2.value = id_establecimiento;

	//seleccionarComboCodigoAinput(oCombo,oInput);
	
}

//-----------------
function seleccionarLocalDinamico2(oCombo){ //alert(oCombo.value);

	var oInput = document.getElementById('txt_codigo_local2');
	var oInput2 = document.getElementById('txt_id_establecimiento2');
	
	var aguja = oCombo.value;

	var partes = aguja.split("|");	
	
	var id_establecimiento = partes[0];	
	var codigo_establecimiento = partes[2];
	
	oInput.value = codigo_establecimiento;
	oInput2.value = id_establecimiento;

	//seleccionarComboCodigoAinput(oCombo,oInput);
	
}

//---------------
//-----------------

function seleccionarLocalDinamico3(oCombo){ //alert(oCombo.value);

	var oInput = document.getElementById('txt_codigo_local3');
	var oInput2 = document.getElementById('txt_id_establecimiento3');
	
	var aguja = oCombo.value;

	var partes = aguja.split("|");	
	
	var id_establecimiento = partes[0];	
	var codigo_establecimiento = partes[2];
	
	oInput.value = codigo_establecimiento;
	oInput2.value = id_establecimiento;

	//seleccionarComboCodigoAinput(oCombo,oInput);
	
}



function seleccionarComboCodigoAinput(objCombo,objInput){
	objInput.value = objCombo.value;
}




//--------------------------------------------------------------------------
//--------------------------------------------------------------------------
//  intranet intranet intranet intranet intranet intranet intranet intranet
// PersonaDireccion { modal : detalle_persona_direccion}
//----- 
function validarFormDireccion(){
	var estado = false;
	var form = document.form_direccion;

    if(form.cbo_departamento.value=="" || form.cbo_departamento.value==0){
		estado =("Debe seleccionar Departamento");
	}else if(form.cbo_provincia.value=="" || form.cbo_provincia.value==0){
		estado = ("Debe seleccionar Provincia");		
	}else if(form.cbo_distrito.value=="" || form.cbo_distrito.value==0){
		estado = ("Debe Seleccionar Distrito");
	}else if ( form.cbo_tipo_via.value==0 && form.cbo_tipo_zona.value==0 ){		
		estado  = ("Debe seleccionar Tipo de Via  O Zona");	

	}else if((form.cbo_tipo_via.value > 0) &&  (form.txt_nombre_via.value=="") ){
		estado = ("Debe Ingresar un Nombre de via");
		
	}else if( (form.cbo_tipo_zona.value > 0) && (form.txt_zona.value=="") ){
		estado = ("Debe Ingresar un Nombre de Zona");
		
	}else if(  validarRadioMarcado(form.rbtn_ref_essalud ) == false ){
		estado = ("Debe seleccionar Si o No.\nReferente para Centro Asistencia EsSalud: ");		
					
	}else{	
		estado = true;
	}	
	return estado;
	
}


