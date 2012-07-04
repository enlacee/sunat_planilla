// JavaScript Document
function validarRadioMarcado(form_radio){
	var counteo = form_radio.length
	var bandera = false;
	for(var i =0; i<counteo; i++){
		if(form_radio[i].checked){
			bandera = true;
		}
	}
	return bandera;	
}



function disableForm(form){
	var frm = document.getElementById(form);
	for (i=0;i<frm.elements.length;i++)
	{
		frm.elements[i].disabled = true;

	}

}


function disableForm2(form){
	var frm = document.getElementById(form);
	for (i=0;i<frm.elements.length;i++)
	{
		frm.elements[i].disabled = false;

	}

}

//-----------------------------------
//-----------------------------------
function validarvfMesConcepcion(obj){
	
	var valor_txt = obj.value;
	var bandera = true;
	// format = dd/mm
	var arreglo = valor_txt.split("/",2);
	
	var dia = arreglo[0];
	var mes = arreglo[1];
	//bandera = (parseInt(dia) >= 1 && parseInt(dia) <= 31 )? true :false;
	//bandera = (parseInt(mes) >= 1 && parseInt(mes) <= 12)? true : false;
	
	expresion_regular_vf_mes =/^\d{2}\/\d{2}$/;
	var bandera = expresion_regular_vf_mes.test(valor_txt);
	
	if(bandera==false){
		alert("Formato de fecha es incorrecto\n Utilize este formato: dia/mes");
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
		
	}
	
}
//-----------------------------------
//-----------------------------------
	function eventoKeyComboPeruDerechoHabiente(obj){

	//alert(obj.value);

	var indice = obj.selectedIndex;
	
	var padre_txt = obj.options[indice].text
	
	pasis_emidoc_esclavo = document.getElementById('cbo_pais_emisor_documento');
	pasis_emidoc_esclavo.options[0].selected = true;
	//
	if(padre_txt =='DNI'){

	
	console.log("Objeto combo 111");
	console.dir(pasis_emidoc_esclavo);	
	console.log("Objeto combo 222 ");

	var counteo = pasis_emidoc_esclavo.options.length;	
	var bandera=false;
	for(i=0;i<counteo;i++){
		//alert ("padre = "+padre_txt+"\nentro for = hijo"+pasis_emidoc_esclavo.options[i].text);
		if(pasis_emidoc_esclavo.options[i].text == 'PERU'/**/){ 
			//alert("txtx  de hijo es "+pasis_emidoc_esclavo.options[i].text);
			//alert("uno")
			pasis_emidoc_esclavo.options[i].selected = true;	
			bandera=true		
			break;
		}else{		
		}		
	}//end for

	if(bandera==false){
		pasis_emidoc_esclavo.options[0].selected = true;	
	}
	
	
	//END
	//console.log("estado del boootn");
	//console.dir(nacionalidad_esclavo.disabled);
	
	//console.log("enddddd");
}
}
//-----------------------------------
//-----------------------------------
	function eventoKeyComboPeruPersonal(obj){

	//alert(obj.value);

	var indice = obj.selectedIndex;
	
	var padre_txt = obj.options[indice].text
	var padre_val = obj.value;
	
	pasis_emidoc_esclavo = document.getElementById('cbo_pais_emisor_documento');
	pasis_emidoc_esclavo.options[0].selected = true;
	//
	nacionalidad_esclavo = document.getElementById('cbo_Nacionalidad');
	nacionalidad_esclavo.options[0].selected = true;
	nacionalidad_esclavo.disabled=false;	
	
	//ojo recursivo
	validarPaisEmisorDocumento();
	
	
	if(padre_val =='01' /*|| padre_val =='04'*/ || padre_val =='11' ){

	
	console.log("Objeto combo 111");
	console.dir(pasis_emidoc_esclavo);	
	console.log("Objeto combo 222 ");

	var counteo = pasis_emidoc_esclavo.options.length;	
	var bandera=false;
	for(i=0;i<counteo;i++){
		//alert ("padre = "+padre_txt+"\nentro for = hijo"+pasis_emidoc_esclavo.options[i].text);
		if(pasis_emidoc_esclavo.options[i].text == 'PERU'/**/){ 
			//alert("txtx  de hijo es "+pasis_emidoc_esclavo.options[i].text);
			//alert("uno")
			pasis_emidoc_esclavo.options[i].selected = true;	
			bandera=true		
			break;
		}else{		
		}		
	}//end for

	if(bandera==false){
		pasis_emidoc_esclavo.options[0].selected = true;	
	}
	
/*	
	//-------- SEG 2ESCLAVO
	var counteo = nacionalidad_esclavo.options.length;	
		for(i=0;i<counteo;i++){
		//alert ("padre = "+padre_txt+"\nentro for = hijo"+pasis_emidoc_esclavo.options[i].text);
		if(nacionalidad_esclavo.options[i].text == 'PERU'){ 
			//alert("txtx  de hijo es "+pasis_emidoc_esclavo.options[i].text);
			//alert("uno")
			nacionalidad_esclavo.options[i].selected = true;
			nacionalidad_esclavo.readOnly=true;
								
			break;
		}else{		
		}		
	}//end for
*/	
	//END
	console.log("estado del boootn");
	console.dir(nacionalidad_esclavo.disabled);	
	console.log("enddddd");
	
}



if(padre_val =='01' || padre_val=='06' || padre_val=='11'){
// Segunda Condicion
	//-------- SEG 2ESCLAVO
	var counteo = nacionalidad_esclavo.options.length;	
		for(i=0;i<counteo;i++){
		//alert ("padre = "+padre_txt+"\nentro for = hijo"+pasis_emidoc_esclavo.options[i].text);
		if(nacionalidad_esclavo.options[i].text == 'PERU'){ 
			//alert("txtx  de hijo es "+pasis_emidoc_esclavo.options[i].text);
			//alert("uno")
			nacionalidad_esclavo.options[i].selected = true;
			nacionalidad_esclavo.readOnly=true;
								
			break;
		}else{		
		}		
	}//end for

}else{
	nacionalidad_esclavo.options[0].selected = true;	
}


}
//-----------------------------------
//-----------------------------------
function validarPaisEmisorDocumento(){
	
	var cboTipoDoc = document.getElementById('cbo_tipo_documento');
	
	var cboPaisEmisorDoc =document.getElementById('cbo_pais_emisor_documento');	
	
	if( cboTipoDoc.value == '07' && cboPaisEmisorDoc.value =='604' ){
		alert("El tipo de Documento  'Pasaporte'\n No tiene relacion con Peru.");
		cboPaisEmisorDoc.options[0].selected = true;
	}	
	
}







//-----------------------------------------------------------------------------
//----------------------Validar formato de fecha con Javascript--------------//
//-----------------------------------------------------------------------------
    function esFechaValida(fecha){

            if (!/^\d{2}\/\d{2}\/\d{4}$/.test(fecha) ){
               // alert("formato de fecha no válido (dd/mm/aaaa)f"+fecha);
                return false;
            }
            var dia  =  parseInt(fecha.substring(0,2),10);
            var mes  =  parseInt(fecha.substring(3,5),10);
            var anio =  parseInt(fecha.substring(6),10);
     
        switch(mes){
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                numDias=31;
                break;
            case 4: case 6: case 9: case 11:
                numDias=30;
                break;
            case 2:
                if (comprobarSiBisisesto(anio)){ numDias=29 }else{ numDias=28};
                break;
            default:
               // alert("Fecha introducida erroneam");
                return false;
        }
     
            if (dia>numDias || dia==0){
               // alert("Fecha introducida erronead");
                return false;
            }
            return true;

    }//ENDFN




    function comprobarSiBisisesto(anio){
    if ( ( anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0))) {
        return true;
        }
    else {
        return false;
        }
    }




//-------------------------------------------------------
// FUNCION ADICIONAL UTILIZADA!
function limpiarComboGlobalID(idCombo){
	combo = document.getElementById(idCombo);
	var counteo = combo.length; //console.log("limpiar   "+combo.length);
	for(var i=0;i<counteo;i++){
		combo.options[i] = null;
	}
}//ENDFN

function limpiarComboGlobal(objCombo){
	var counteo = objCombo.length; //console.log("limpiar   "+combo.length);
	for(var i=0;i<counteo;i++){
		objCombo.options[i] = null;
	}

}


///-------------------------------------
function validarCampoVacio(dojoField,message){
	if(esnulo(dojoField.getValue())){
		alert(message);
		dojoField.focus();
		return false;
	}
	return true;
}



//----------------------------
function hosEs(){
	var fecha = new Date();

		
	return 	fecha.getDate()+"/"+(fecha.getMonth() + 1)+"/"+fecha.getFullYear();
	
}

//-----------------------------
function getFechaActualEnter(e,obj){
var tecla=(document.all) ? e.keyCode : e.which;	
	if (tecla==13) { 
		obj.value = hosEs();	
	}//ENDIF
}


