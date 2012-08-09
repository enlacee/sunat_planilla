<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Lista de  Periodos</a></li>			

        </ul>
        <div id="tabs-1">
            <input type="button" name="button" id="button" value="Nuevo Periodo"
            onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/new_periodo.php','#CapaContenedorFormulario')" />

          <h2>lista de periodos JQGRID</h2>
            <h3>01-2011</h3>
          <h3>02-2011</h3>

        
        </div>
</div>

</div>

















