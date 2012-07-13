<?php
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//
?>
<?php
//require_once('../dao/PersonaDao.php');
//require_once('../controller/PersonaController.php')


$dao_persona = new PersonaDao();
$data_persona = $dao_persona->buscarPersonaPorId($_REQUEST['id_persona'], $DATA_EMPLEADOR['id_empleador']);

//var_dump($data_persona);
?>

<script>

//INICIO HISTORIAL
$(document).ready(function(){						   
//demoApp = new Historial();
$( "#tabs").tabs();


var id_persona = document.getElementById('id_persona').value;
var id_empleador = document.getElementById('id_empleador').value;
				 
cargarTablaDerechoHabiente(id_persona,id_empleador);					 

});


</script>

            
			
			
<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro de Derechohabientes</a></li>			
			
        </ul>
      <div id="tabs-1">
        <h3>Identificación del Titular </h3>
        <table cellspacing="0" cellpadding="0">
          <tr class="ocultar">
            <td height="0">id_persona</td>
            <td height="0"><input name="id_persona" type="text" id="id_persona" 
			value="<?php echo $_REQUEST['id_persona']; ?>" readonly=""></td>
          </tr>
          <tr class="ocultar">
            <td  height="0">id_empleador</td>
            <td height="0"><input name="id_empleador" type="text" id="id_empleador" 
			value="<?php  //echo $DATA_EMPLEADOR['id_empleador']; ?>" readonly="readonly"></td>
          </tr>
          <tr>
            <td height="0" width="29%">Tipo y Número de Documento de Identidad:</td>
            <td height="0" width="71%"><input name="textfield" type="text" 
			value="<?php echo $data_persona['documento']; ?>" size="50" readonly="readonly"></td>
          </tr>
          <tr>
            <td height="0">Apellidos y Nombres:</td>
            <td height="0"><input name="textfield2" type="text" 
			value="<?php echo $data_persona['apellido_paterno']." ".$data_persona['apellido_materno']." ".$data_persona['nombres'] ?>" size="50" readonly="readonly"></td>
          </tr>
        </table>
        <h3>Identificación del Titular </h3>
        <p><div class="ocultar">
          <input type="checkbox" name="checkbox" id="checkbox" />
          <label for="checkbox"></label>
          Ver también derechohabientes dados de baja</div>
        <br />
        <input type="button" name="Submit" value="Nuevo DerechoHabiente"
			 onclick="javascript:cargar_pagina('sunat_planilla/view/new_derechohabiente.php?id_persona=<?php echo $data_persona['id_persona']; ?>','#CapaContenedorFormulario')" />
		
        </p>
<table id="list"></table>
            <div id="pager"></div>
      </div>

        
    </div>
</div>

