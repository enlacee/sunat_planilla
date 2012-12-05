<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
	
	var periodo = document.getElementById('periodo').value;
	var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
	
	cargarTablaParaTiFamilia(id_pdeclaracion,periodo);
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
            <li><a href="#tabs-1">PARA TI FAMILIA</a></li>			

        </ul>
        <div id="tabs-1"><span class="div_opciones"><br />
        
          <input id="btnnuevo" name="btnnuevo" value="Nuevo Registro" class="submit-nuevo" 
onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/new_cparatifamilia.php?id_declaracion=<?php echo $_REQUEST['id_declaracion']; ?>&periodo=<?php echo $_REQUEST['periodo']; ?>','#CapaContenedorFormulario')" type="button" />

<!-- Boton cancelar-->
<input type="button" 
onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/view_registro_concepto_e.php?id_declaracion=<?php echo $_REQUEST['id_declaracion']; ?>&periodo=<?php echo $_REQUEST['periodo']; ?>','#CapaContenedorFormulario')" 
class="submit-cancelar" value="Cancelar">

        </span><br />
        <br>
        
        
        
<table id="list">
</table>
<div id="pager">

          
        </div>
</div>

</div>

