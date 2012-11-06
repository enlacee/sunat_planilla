<script type="text/javascript">


$(document).ready(function(){
	$( "#tabs").tabs();
	
//-----------------------		
	$("#fecha_inicio").datepicker({ 
		changeMonth: true,
		changeYear: true,
		dateFormat: 'mm/yy',
		//minDate: 0,
		//maxDate: "+11M +0D"
		
	});



});



//-----------------------------------
//-----------------------------------
function validarFormatoMesAnio(value){
	
	var valor_txt = value;
	var bandera = true;
	// format = dd/mm
	var arreglo = valor_txt.split("/",2);
	console.log("ver arreglo");
	console.dir(arreglo);
	var dia = arreglo[0];
	var mes = arreglo[1];

	expresion_regular_vf_mes =/^\d{2}\/\d{4}$/;
	var bandera = expresion_regular_vf_mes.test(valor_txt);
	
	if(bandera==false){
		alert("Formato de fecha es incorrecto\n Utilize este formato: mm/aaaa");
	}else{
		//alert("bien");
		console.log("Fecha Correcta");
	}
	return bandera;
	
}

	
	
function grabarPrestamo(obj){
	
var from_data =  $("#FrmPrestamo").serialize();

var id_trabajador = document.getElementById('id_trabajador').value;
var fecha_inicio =  document.getElementById('fecha_inicio').value;


if(id_trabajador != ''){

if(validarFormatoMesAnio(fecha_inicio)){
		//-----	
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: 'sunat_planilla/controller/PrestamoController.php?'+from_data,
			data: {},
		    success: function(data){
							
				if(data){
					alert("Se registro correctamente");
					cargar_pagina('sunat_planilla/view-empresa/view_cprestamo.php','#CapaContenedorFormulario')
				}else{
					alert("Ocurrio un error.");
				}
				
				
			}
		});
		//-------
}else{

}

}else{
	alert("Ingrese datos validos");		
}


}
	
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Nuevo Prestamo</a></li>			

        </ul>
        <div id="tabs-1">
        <form id="FrmPrestamo" name="FrmPrestamo" method="post" action="">
          
          <p>
          <div class="ocultar">
              id_prestamo
                <input type="text" name="id" id="id" />
            <br />
            id_trabajador
<input type="text" name="id_trabajador" id="id_trabajador" />
            <br />
            oper
            <label for="oper"></label>
            <input type="text" name="oper" id="oper" value="add" />
          </div>  
            
            <div class="fila_input">
              <label>Num doc:</label>
              <input name="dni" type="text" id="textfield" size="12" readonly="readonly">
              
              <input name="nombre" type="text" id="nombre" size="40" readonly="readonly">
              <a onclick="javascript:modalshow_anb('sunat_planilla/view-empresa/modal/new_cprestamo_grid.php')" 
tabindex="-10000" href="#"><img alt="Buscar" src="images/search.png"></a></div>        
            
            <div class="fila_input">
              <label>Monto:</label>            
              <input type="text" name="valor" id="valor" onkeydown="soloNumeros(event)" />
            soles</div>
            
            <div class="fila_input">
              <label>Numero Cuotas:</label>
              
              <input type="text" name="num_cuota" id="num_cuota"  onkeydown="soloNumeros(event)"/>
            ejem: 1,2,3</div>
            
            <div class="fila_input">
          <label>fecha inicio del Prestamo:</label>
            
          <input name="fecha_inicio" type="text" id="fecha_inicio" readonly="readonly" />
            (mm/aaaa)</div>
<p>
          
          <input type="button" name="btn_grabar" id="btn_grabar" value="Guardar" 
                class="submit-go" onclick="grabarPrestamo(this)" />
                <input type="button" name="btn_cancelar" id="btn_cancelar" 
                class="submit-cancelar" value="Cancelar" 
                onclick="cargar_pagina('sunat_planilla/view-empresa/view_cprestamo.php','#CapaContenedorFormulario');" />
              </p>
          <p>&nbsp;</p>
        </form>       
        </div>
</div>

</div>