<script type="text/javascript">
	var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
	var periodo = document.getElementById('periodo').value;
    $(document).ready(function(){
        $( "#tabs").tabs();	


        $("#vacacion").click(function(event){
        	
        	$(this).attr("disabled","disabled");
		   $.ajax({
				type: "POST",
				url: "sunat_planilla/controller/TrabajadorVacacionController.php",
				data: { oper: 'generar',id_pdeclaracion : id_pdeclaracion,periodo : periodo},
				async:true,
				dataType: 'json',
				success: function(data){			
					if(data.rpta==true){				
						alert(data.mensaje);   
					}else{
						alert("Ocurrio un error");
					}		
					//cargar_pagina('sunat_planilla/view-empresa/view_periodo.php','#CapaContenedorFormulario')
			   }
			});

        });


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
            <li><a href="#tabs-1">Procesar Vacaciones</a></li>			
        </ul>
        <div id="tabs-1">
<p>
  <!-- Boton cancelar-->
  <input type="button" onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/new_etapaPago.php?id_declaracion=<?php echo $_REQUEST['id_declaracion']; ?>&periodo=<?php echo $_REQUEST['periodo']; ?>','#CapaContenedorFormulario')" class="submit-cancelar" value="Cancelar" name="Retornar ">
</p>
<p>&nbsp;</p>
<p>
<h2>  <input type="button" name="vacacion" id="vacacion" value="Procesar Vacacion" /></h2>

</p>

</div>
</div>
</div>

<!-- DIALOG -->

<div id="dialog_view_vacacion" title="Vacaciones">
    <div id="view_vacacion" align="left"></div>
</div>
