<?php
require_once '../util/funciones.php';
require_once '../dao/AbstractDao.php';
require_once '../dao/ComboCategoriaDao.php';

require_once '../controller/ComboCategoriaController.php';
require_once('../controller/ParatiFamiliaController.php');

require_once '../dao/ParatiFamiliaDao.php';

$data = comboTipoParaTiFamilia();

//echoo($data);

//$_REQUEST['id_para_ti_familia'];

$obj = buscar_IdParaTiFamilia($_REQUEST['id_para_ti_familia']);

//echoo($obj);

?>
<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
			
	});

	$("#btn_cancelar").click(function(){
		cargar_pagina('sunat_planilla/view-empresa/view_cparatifamilia.php','#CapaContenedorFormulario')	
	})

	
	
	
	//-----------------------------
	
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Para Ti Familia</a></li>			

        </ul>
        <div id="tabs-1">
          <p>&nbsp;</p>
          
<form name="FrmParaTiFamilia" id="FrmParaTiFamilia" method="post" action="">

<div class="ocultar">
        <p>   
              id_para ti familia
          <input type="text" name="id" id="id" 
          value="<?php echo $obj['id_para_ti_familia']; ?>">
              <br>
              id_trabajador
              <label for="id_trabajador"></label>
              <input type="text" name="id_trabajador" id="id_trabajador" 
              value="<?php echo $obj['id_trabajador']; ?>" >
<br>
              oper
              
            <input name="oper" type="text" id="oper" value="edit">
          </p>
        </div>
          <p><label>Num Doc:</label>
            <input name="dni" type="text" id="dni" value="<?php echo $obj['num_documento']; ?>" size="15" readonly="readonly">
            <input name="nombre" type="text" id="nombre" size="40" readonly="readonly"
            value="<?php echo $obj['apellido_paterno']." ".$obj['apellido_materno']. " ".$obj['nombres']; ?>"
             >
            <br>
          <label>Tipo Para ti Familia</label>
          
<select name="cbo_tipo_para_tifamilia" id="cbo_tipo_para_tifamilia" style="width:180px" disabled="disabled" >

<?php for($i=0; $i<count($data); $i++): ?>

<?php if( $obj['id_tipo_para_ti_familia'] == $data[$i]['id_tipo_para_ti_familia'] ): ?>
    <option selected="selected" value="<?php echo $data[$i]['id_tipo_para_ti_familia']; ?>">	
    <?php echo $data[$i]['descripcion'].' ['.$data[$i]['valor'].']'; ?>		
    </option>
<?php else: ?>
    
    <option value="<?php echo $data[$i]['id_tipo_para_ti_familia']; ?>">	
    <?php echo $data[$i]['descripcion'].' ['.$data[$i]['valor'].']'; ?>		
    </option>
    
<?php endif; ?>

<?php endfor; ?>
</select>
<label for="textfield"></label>
<br />
<label>fecha Inicio</label> 
            <input name="fecha_inicio" type="text" id="fecha_inicio" 
            value="<?php echo getFechaPatron( $obj['fecha_inicio'],"m/Y");?>" readonly="readonly" />
(mm/aaaa) </p>

          <p><br />
            <input type="button" name="btn_aceptar" id="btn_aceptar" value="Guardar" disabled="disabled"
onclick="grabarParaTiFamilia(this)" />
            <input type="button" name="btn_cancelar" id="btn_cancelar" 
            class="submit-cancelar" value="Cancelar" />
          </p>

        <p>&nbsp;</p>
          
        </form>
<p><br>
</p>

        </div>
</div>

</div>

