<?php
require_once '../util/funciones.php';
require_once '../dao/AbstractDao.php';
require_once '../dao/ComboCategoriaDao.php';

require_once '../controller/ComboCategoriaController.php';


$data = comboTipoParaTiFamilia();

//echoo($data);

?>
<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
//-----------------------
		var periodo = $("#periodo").val();	
		var data_mes = cadenaFecha(periodo);
		
		$("#fecha_inicio").datepicker({ 
		showButtonPanel: true,							  
		changeMonth: true,
		changeYear: true,
		dateFormat: '01/mm/yy',
		minDate: new Date(data_mes.getFullYear(),data_mes.getMonth(),data_mes.getDate())
		//maxDate: "+11M +0D"
		
	});

		
	});


	$("#btn_cancelar").click(function(){
		cargar_pagina('sunat_planilla/view-empresa/view_cparatifamilia.php','#CapaContenedorFormulario')	
	})

	
	//-----------------------------
	
</script>


<div class="demo" align="left">

<div class="ocultar">
id_pdeclaracion
<input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
value="<?php echo $_REQUEST['id_declaracion']; ?>"/><br />
periodo
<input type="text" name="periodo" id="periodo"
value="<?php echo $_REQUEST['periodo']; ?>" />
</div>



    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Para Ti Familia</a></li>			

        </ul>
        <div id="tabs-1">
<form name="FrmParaTiFamilia" id="FrmParaTiFamilia" method="post" action="">

<div class="ocultar">
        <p>   
              id_para ti familia
          <input type="text" name="id" id="id">
              <br>
              id_trabajador
              <label for="id_trabajador"></label>
              <input type="text" name="id_trabajador" id="id_trabajador">
<br>
              oper
              <label for="oper"></label>
              <input name="oper" type="text" id="oper" value="add">
          </p>
        </div>
<div class="fila_input">
  <label>Num doc</label>
  <input name="dni" type="dni" id="textfield" size="12" readonly="readonly">
  <input name="nombre" type="text" id="nombre" size="40" readonly="readonly">
  <a onclick="javascript:modalshow_anb('sunat_planilla/view-empresa/modal/new_cparatifamilia_grid.php')" 
tabindex="-10000" href="#"><img alt="Buscar" src="images/search.png"></a></div>
<br>
<div class="fila_input">
          <label>Tipo Para ti Familia
            
          </label><select name="cbo_tipo_para_tifamilia" id="cbo_tipo_para_tifamilia" style="width:180px">
            
            <?php for($i=0; $i<count($data); $i++): ?>
            <option value="<?php echo $data[$i]['id_tipo_para_ti_familia']; ?>">	
            <?php echo $data[$i]['descripcion'].' ['.$data[$i]['valor'].']'; ?>		
            </option>
            <?php endfor; ?>
          </select>
</div>

 <div class="fila_input">
    <label>fecha Inicio</label>
    <input name="fecha_inicio" type="text" id="fecha_inicio" 
            value=""  readonly="readonly"/>
    (mm/aaaa)
   </div>


  <br />
  <input type="button" name="btn_aceptar" id="btn_aceptar" value="Guardar"
            class="submit-go"
            onclick="grabarParaTiFamilia(this)" />
            
<!-- Boton cancelar-->
           
  <input type="button" name="btn_cancelar" id="btn_cancelar" 
            class="submit-cancelar" value="Cancelar"  
 onclick="cargar_pagina('sunat_planilla/view-empresa/view_cparatifamilia.php?id_declaracion=<?php echo $_REQUEST['id_declaracion']; ?>&periodo=<?php echo $_REQUEST['periodo']; ?>','#CapaContenedorFormulario')" />


          
        <br />
</form>
          <p><br>
          </p>
          
        </div>
</div>

</div>

