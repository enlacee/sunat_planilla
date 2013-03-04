<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
$id_pdeclaracion = ($_SESSION['sunat_empleador']['config']['id_pdeclaracion']) ? $_SESSION['sunat_empleador']['config']['id_pdeclaracion'] : 'null';

?>
<script type="text/javascript">

	$(document).ready(function(){                  
		$( "#tabs").tabs();		
	});

	var id_pdeclaracion = <?php echo $id_pdeclaracion;?>;	
	function cargarPagina(){
		if(id_pdeclaracion){
			cargar_pagina('sunat_planilla/view-empresa/view_periodo.php','#CapaContenedorFormulario');
		}
	}	
	cargarPagina();
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Configurar Planilla</a></li>			

        </ul>
        <div id="tabs-1">
          <p>Ingresar al periodo:</p>
          <table width="200" border="1">
            <tr>
              <td>mes</td>
              <td>a&ntilde;o</td>
            </tr>
            <tr>
              <td><input name="month" type="text" id="month" size="5" /></td>
              <td><input name="year" type="text" id="year" size="10" /></td>
            </tr>
          </table>
          <p><label>
            <input type="button" name="entrar" id="entrar" value="Entrar" onclick="validarPlanilla(this)" />
          </label></p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p><br>
          </p>
      </div>
</div>

</div>

