<?php 
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//

$ID_EMPLEADOR = $_REQUEST['id_empleador'];
?>

<script>
	//INICIO HISTORIAL
	$(document).ready(function(){	
	
	var id_empleador = document.getElementById('id_empleador').value;					   
	cargarTablaEstablecimientoEmpleador(id_empleador);
	 $( "#tabs" ).tabs();
	 
	 //---------------------------------------
	 crearDialogoEditEmpresaCentroCosto();
	 crearDialogoNewEmpresaCentroCosto();
	 
	});


	//FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
</script>
            
			

<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro del Establecimientos</a></li>			
			
        </ul>
        <div id="tabs-1">
          <input type="hidden" name="id_empleador" id="id_empleador"  value="<?php echo $ID_EMPLEADOR;?>"/>
          <br />
          <input type ="button" 
            onclick="javascript:cargar_pagina('sunat_planilla/view/new_establecimiento.php?id_empleador=<?php echo $ID_EMPLEADOR; ?>','#CapaContenedorFormulario')"
             value="Nuevo Establecimiento "/>

      <table id="list"><tr><td/></tr></table>
            <div id="pager"></div>

		

        </div>

        
    </div>
</div>




<!-- -->
<div id="dialog-form-editarCentroCosto" title="Editar Centro de Costo">
    <div id="editarCentroCosto" align=""></div>
</div>


<!-- -->
<div id="dialog-form-newCentroCosto" title="Nuevo Centro de Costo">
    <div id="newCentroCosto" align=""></div>
</div>

