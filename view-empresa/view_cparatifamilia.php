<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
	cargarTablaParaTiFamilia();
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">PARA TI FAMILIA</a></li>			

        </ul>
        <div id="tabs-1"><span class="div_opciones">
          <input id="btnnuevo" name="btnnuevo" value="Nuevo Registro" class="submit-nuevo" 
onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/new_cparatifamilia.php','#CapaContenedorFormulario')" type="button" />
        </span><br>
        
        
        
<table id="list">
</table>
<div id="pager">

          
        </div>
</div>

</div>

