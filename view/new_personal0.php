<?php
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//

require_once('../util/funciones.php');
require_once('../dao/AbstractDao.php');
require_once('../dao/ComboDao.php');
require_once('../controller/ComboController.php');

//require_once('../dao/PersonaDao.php');

//require_once('../controller/PersonaController.php');


// COMBO 01
$cbo_tipo_documento = comboTipoDocumento();

?>
<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
	
	
	
	
	function existePersonaRegistrada(){

	//get data
	var cbo_tipo_documento = document.getElementById('cbo_tipo_documento').value;
	var num_documento = document.getElementById('txt_num_documento').value;
		
	
	//-----------------------		
		//ENVIA A OTR VISTA SI EXISTE ID_PERSONA registrada.
		
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'sunat_planilla/controller/PersonaController.php',
		data: {oper: 'buscar_persona', tipo_documento : cbo_tipo_documento, num_documento : num_documento  },
		success: function(data){
			console.log("javascript  = "+data);
			console.dir(data);
			var rpta = data.rpta;
			var id =data.id_persona;
			var id2 = data.id_trabajador;
			
			if(rpta == false){
				alert("Prestador de Servicio ya fue registrado.\n"+data.mensaje);
				//cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona='+id+'&id_trabajador='+id2+'&cod_situacion=1','#CapaContenedorFormulario')
					
			}else if(rpta == true){//True Reg new Trabajador
				console.log("se acaba de crear un nuevo trabajador...");
				console.log("Debe llenarse los datos... erros else");
				alert("Registre los nuevos datos del Trabajador")
				cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona='+id+'&id_trabajador='+id2+'&cod_situacion=1','#CapaContenedorFormulario')
				
			}else if(rpta == "otro"){
				alert("Registre un Nuevo Prestador de Servicio.");
				var param = '?tipo_documento='+cbo_tipo_documento+'&num_documento='+num_documento;
				cargar_pagina('sunat_planilla/view/new_personal.php'+param,'#CapaContenedorFormulario')
				
			}
			
		//	var cbo_base = document.getElementById('cbo_establecimiento');
		//	cargar_pagina('sunat_planilla/view/edit_personal.php?id_persona='+id+'&cod_situacion=1','#CapaContenedorFormulario')
			
			
			
		}
	});	
//--------------------

	
	
};
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Editar Empleador</a></li>			

        </ul>
        <div id="tabs-1">
          <div class="fila_input">
            <label>Tipo de Documento: </label>
            <select name="cbo_tipo_documento" id="cbo_tipo_documento" onchange="eventoKeyComboPeruPersonal(this)" >
              <option value="" selected="selected">-</option>
              <?php
foreach ($cbo_tipo_documento as $indice) {
	
	if ($indice['cod_tipo_documento'] == '0'/*$obj_banco_liqui->getId_banco()*/ ) {
		
		//$html = '<option value="'. $indice['cod_tipo_documento'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
	} else {
		$html = '<option value="'. $indice['cod_tipo_documento'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
}
?>
            </select>
          </div>
          <div class="fila_input" >
            <label>Numero Documento </label>
            <input name="txt_num_documento" type="text" id="txt_num_documento" />
            <input type="button" name="btn_existePersona" id="btn_existePersona" value="Buscar Persona"
                    onclick="existePersonaRegistrada()" />
            <label for="id_persona_existe"></label>
            <input name="id_persona_existe" type="hidden" id="id_persona_existe" size="4" />
          </div>
        </div>
</div>

</div>