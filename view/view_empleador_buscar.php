<?php
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//


?>


<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
//-----------------------------------------------------------


function buscarEmpleador(){
	/*
	* HERE CONDICIONAR si es DD1 O DD2
	* y ENVIAR todo por ajax THIS es controlador
	* OKKK
	*
	*/
	//formBuscarEmpleador

	var ruc_buscar = $('input#txt_ruc_buscar').val();	
	var ruc_maestro  = $('input#ruc_maestro').val();
	
	
	if(ruc_maestro ==ruc_buscar){
		alert ("Debe Ingresar un RUC distinto al del propio\n Empleador");		
	}else if(ruc_buscar.length != 11){
		alert ("Numero de Ruc debe tener 11 digitos");
		
	}else if(isNaN(ruc_buscar)){
		alert ("Solo se permiten Datos Numericos");
	
	}else{
		
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: 'sunat_planilla/controller/EmpleadorController.php',
		data: {oper: 'existe-empleador',ruc: ruc_buscar},
		success: function(data){
			//----------------
			if(data == true){				
			//inicio accion
				var tipo_emp = $('#tipo_emp').val();
				
				if(tipo_emp == "emp-dd2"){			
					javascript:cargar_pagina('sunat_planilla/view/new_empleador_dd2.php?ruc_empleador_subordinado='+ruc_buscar+'','#CapaContenedorFormulario'); 
					
				}else if(tipo_emp == "emp-dd1"){
					javascript:cargar_pagina('sunat_planilla/view/new_empleador_dd1.php?ruc_empleador_subordinado='+ruc_buscar+'','#CapaContenedorFormulario'); 
				}else{
					alert("Alert error Script Else");				
				}
			//finalizo accion				
			
			}else{
				alert("No se encontro Empleador registrado \Ncon este Numero  de RUC : "+ruc_buscar);
			}
			
			
			//----------------			
		}
	});

		
		
		
		
		
		
		//-----------------------------------------------------	
		/*$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'sunat_planilla/controller/ComboController.php',
			data: {id_departamento: cbo_depa.options[cbo_depa.selectedIndex].value, oper: 'listar_provincias'},
			success: function(json){
				LlenarCombo(json, cbo_provin);
				$("#cbo_provincia").removeAttr('disabled');
				$("#cbo_distrito").removeAttr('disabled');
				
			}
		});*/
		//-----------------------------------------------------	
	}
	
}//ENDFN


//----
function existeEmpleadorBD(ruc){
var estado;	
	alert("exisss  estado  "+estado);
return estado;

}

</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Buscar  Empleador</a></li>			

        </ul>
        
        
      <div id="tabs-1">
<form action="sunat_planilla/controller/EmpleadorDDController.php" method="post" name="formBuscarEmpleador" id="formBuscarEmpleador" >
    <div class="ocultar">
      <p>
        <label for="oper"></label>
        oper 
        <input name="oper" type="text" id="oper" value="buscar-empleador" />
        <br />
        
        
        tipo_emp
        <input type="text" name="tipo_emp" id="tipo_emp" value="<?php echo $_REQUEST['tipo_emp'];?>" />
        <br />
        <label> ruc SUNAT empleador</label>
        <input type="text" name="ruc_maestro" id="ruc_maestro" value="<?php echo $_SESSION['sunat_empleador']['ruc']; ?>" />
      </p>
    </div>
    
      RUC:<input name="txt_ruc_buscar" type="text"   id="txt_ruc_buscar" value="14725836914" size="11" maxlength="11" />
      <input type="button" name="btn_validar_ruc" id="btn_validar_ruc" value="Validar RUC" 
    onclick="buscarEmpleador()" />
      <br />


</form>
        
      </div>
</div>