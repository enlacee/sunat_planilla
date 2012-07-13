<?php 
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//
?>
<script>
	//INICIO HISTORIAL
	$(document).ready(function(){						   
		//demoApp = new Historial();
	 $( "#tabs" ).tabs();
	cargarTablaEmpleador();


	});



</script>

            

			
			

<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro del Empleador</a></li>			
			
        </ul>
        <div id="tabs-1">

            <input type ="button" 
            onclick="javascript:cargar_pagina('sunat_planilla/view/new_empleador.php','#CapaContenedorFormulario')"
             value="Nuevo Empleador "/>
             <br />
<table id="list">
</table>
<div id="pager"></div>

		

        </div>

        
    </div>
</div>

