<script>


	
	function eventoKeyCombo(obj){

	alert(obj.value);

	var indice = obj.selectedIndex;
	
	var padre_txt = obj.options[indice].text
	
	//alert("selectedIndex "+padre_txt);
	
	cadena = obj.id;
	
	var palabras = cadena.split("-");
	
	var tx = palabras[0];
	var id = palabras[1];
	
	//Buscar valoresdel hijo
	//alert("id ess"+id);
	hijo_combo = document.getElementById('comboHijo-'+id);
	
	console.log("Objeto combo 111");
	console.dir(hijo_combo);	
	console.log("Objeto combo 222 ");

	var counteo = hijo_combo.options.length;	
	var bandera=false;
	for(i=0;i<counteo;i++){
		//alert ("padre = "+padre_txt+"\nentro for = hijo"+hijo_combo.options[i].text);
		if(padre_txt == hijo_combo.options[i].text){ 
			//alert("txtx  de hijo es "+hijo_combo.options[i].text);
			//alert("uno")
			hijo_combo.options[i].selected = true;	
			bandera=true		
			break;
		}else{		
		}		
	}//end for

	if(bandera==false){
		hijo_combo.options[0].selected = true;	
	}
	
	console.log("enddddd");
	
	}


</script>

<form id="form1" name="form1" method="post" action="">
  <p>
    Padre
    <label for="codigo"></label>
    <label for="combo"></label>
    <select name="comboPadre-1" id="comboPadre-1" style="width:200px;" onchange="eventoKeyCombo(this)">
      <option value="0">-</option>
      <option value="1">UNO</option>
      <option value="2">DOS</option>
      <option value="3">TRES</option>
      <option value="4">PERU</option>
      <option value="5">DNI</option>
    </select>
    <br />
    <br />
    Hijo
    <label for="comboHijo-1"></label>
    <select name="comboHijo-1" id="comboHijo-1" style="width:200px;">
      <option value="0">-</option>
      <option value="1">1</option>
      <option value="2">2</option>
     <option value="3">PERU</option>
      <option value="4">3</option>
      <option value="5">4</option>
    
    </select>
  </p>
  <p>&nbsp;</p>
</form>
