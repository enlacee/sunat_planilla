<script type="text/javascript">
	//var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
	//var periodo = document.getElementById('periodo').value;
$(document).ready(function(){
        $( "#tabs").tabs();	
		//--
		
		$('#gratifiacion').click(function(){
			console.log("GRATIFIACION");
			console.log(id_pdeclaracion);
			console.log(periodo);
			//----			
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: 'sunat_planilla/controller/TrabajadorGratificacionController.php',
				data: {
					id_pdeclaracion : id_pdeclaracion,
					periodo:periodo,
					oper:'gratificacion'
					},
				success: function(data){								
					if(data){
						var parametro = 'id_declaracion='+id_pdeclaracion+'&periodo='+periodo;
						alert("Se registro correctamente");
						//javascript:cargar_pagina('sunat_planilla/view-empresa/view_periodo.php','#CapaContenedorFormulario')
					}else{
						alert("Ocurrio un error.");
					}		
				}
			});
			//-------
		});//end gratifiacion
		
		
		
});
//---------------------------------------------------------------
	
</script>
<div class="demo" align="left">
<div class="ocultar">
id_pdeclaracion
<input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
value="<?php echo $_REQUEST['id_declaracion']; ?>"/><br />
periodo
<input type="text" name="periodo" id="periodo"
value="<?php echo $_REQUEST['periodo']; ?>" />
</div>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Procesar Gratificacion</a></li>			
        </ul>
        <div id="tabs-1">
<p>
  <!-- Boton cancelar-->
  <input type="button" onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/new_etapaPago.php?id_declaracion=<?php echo $_REQUEST['id_declaracion']; ?>&periodo=<?php echo $_REQUEST['periodo']; ?>','#CapaContenedorFormulario')" class="submit-cancelar" value="Cancelar" name="Retornar ">
</p>
<p>&nbsp;</p>
<p>
<h2>  <input type="button" name="gratificacion" id="gratificacion" value="Procesar Gratificacion" /></h2>

</p>

</div>
</div>
</div>
