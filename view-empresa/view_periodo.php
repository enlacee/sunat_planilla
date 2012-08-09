<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
		cargarTablaPdeclaracionEmpresa();
		

		
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
            
            <h2>lista de periodos JQGRID
              <label for="anio"></label>
              <select name="anio" id="anio" onchange="cargarTablaPdeclaracionEmpresa()">
                <option value="2011">2011</option>
                <option value="2012" selected="selected">2012</option>
              </select>
            </h2>
          
            <table id="list">
            </table>
            <div id="pager"></div>

        
        </div>
</div>

</div>

















