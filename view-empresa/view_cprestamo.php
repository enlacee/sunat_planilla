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
        <h2>Prestamos</h2>
 <div class="div_opciones">
<input id="btnnuevo" name="btnnuevo" value="Nuevo Prestamo" class="submit-nuevo" 
onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/new_cprestamo.php','#CapaContenedorFormulario')" type="button">            
</div>
 <br>
<table id="list">
</table>
<div id="pager">
        
        
        
        
    
        
      </div>
</div>

</div>