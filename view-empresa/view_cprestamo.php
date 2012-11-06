<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
			//cargarTablaPrestamo();
		
	});
	
cargarTablaPrestamo();
	
	

	
</script>


<div class="demo" align="left">

  <div id="tabs">
    
    <ul>
            <li><a href="#tabs-1">PRESTAMO EMPRESA</a></li>			

        </ul>
        <div id="tabs-1">  

          <br />
          <input id="btnnuevo" name="btnnuevo" value="Nuevo Prestamo" class="submit-nuevo" 
onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/new_cprestamo.php','#CapaContenedorFormulario')" type="button">
          <input type="button"  class="submit-cancelar"
          value="Cancelar" 
          onclick="cargar_pagina('sunat_planilla/view-empresa/view_registro_concepto_e.php','#CapaContenedorFormulario')"
          />
          <br />
          <br />

        <table id="list">
        </table>
<div id="pager">
        
        
        
        
    
        
      </div>
</div>

</div>