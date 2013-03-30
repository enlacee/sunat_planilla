<?php
require_once '../util/funciones.php';

require_once '../dao/AbstractDao.php';
require_once '../dao/PrestamoDao.php';
require_once '../dao/PrestamoCuotaDao.php';
require_once '../model/PrestamoCuota.php';
//prestamo
require_once '../controller/PrestamoController.php';
//prestamo cuota
require_once '../controller/PrestamoCuotaController.php';


//$_REQUEST['id_prestamo'];
//$_REQUEST['id_trabajador'];
$ID_PRESTAMO = $_REQUEST['id_prestamo'];
$PERIODO = $_REQUEST['periodo'];

$data = buscar_IdPrestamo($ID_PRESTAMO);
//CUOTAS

$data_obj  = listaCuotas($ID_PRESTAMO);

//echoo($data_obj);
?>
<script type="text/javascript">


    $(document).ready(function(){
		$( "#tabs").tabs();
		
//------------------------------------
/*
	$( ".fecha_calc" ).datepicker({
		showButtonPanel: true,		
		changeMonth: true,
		changeYear: true,
		dateFormat: '01/mm/yy',
		minDate: new Date(data_mes.getFullYear(),data_mes.getMonth(),data_mes.getDay()),
		//maxDate: "+11M +0D"		
		
	});
*/

	});



function guardarPrestamoCuota(id){	
	if(true){
  //periodo
	  var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
	  var periodo = document.getElementById('periodo').value;
  

	var periodo = document.getElementById('periodo').value;
	var id_prestamo = document.getElementById('id_prestamo').value;
	var monto_prestamo = document.getElementById('monto_prestamo').value;
	var num_cuota = document.getElementById('num_cuota').value;
	
	//prestamo cuota
	var montoc = document.getElementById('montoc-'+id).value;
	var montoc_variable = document.getElementById('montoc_variable-'+id).value;
	var fecha_calc = document.getElementById('fecha_calc-'+id).value;	
	
	
	// ajax 
	$.ajax({
	   type: "POST",
	   dataType: 'json',
	   async:true,
	   url: "sunat_planilla/controller/PrestamoCuotaController.php",
	   data: {
			oper : 'edit',
			monto_prestamo : monto_prestamo,
			num_cuota : num_cuota,
			fecha_calc : fecha_calc,
			montoc : montoc,		   
			montoc_variable : montoc_variable,		   
			id_prestamo : id_prestamo,
			id_prestamo_cuota : id,
			periodo : periodo	   
		   },	   
	   success: function(data){
		   if(data==true){
				alert("Se Guardo Correctamente.");
        var paramentro = 'id_declaracion='+id_pdeclaracion+'&periodo='+periodo;
        cargar_pagina('sunat_planilla/view-empresa/view_cprestamo.php?'+paramentro,'#CapaContenedorFormulario')
		   }else{
				alert("Ocurrio un error.");  
		   }
	   }
	   }); 	
	   
	}
			
}	
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
            <li><a href="#tabs-1">Ver Detalle  Prestamo</a></li>			

        </ul>
        <div id="tabs-1">
        <form id="FrmPrestamo" name="FrmPrestamo" method="post" action="">
          
          <div class="ocultar">
              id_prestamo
                <input type="text" name="id_prestamo" id="id_prestamo" 
              value="<?php echo $data['id_prestamo'];?>" />
            <br />
            id_trabajador
<input type="text" name="id_trabajador" id="id_trabajador" 
              value="<?php echo $data['id_trabajador'];?>" />
            <br />
            oper
            <label for="oper"></label>
            <input type="text" name="oper" id="oper" value="edit" />
          </div>  
            <br />            
<!-- Boton cancelar-->
<input type="button" 
onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/view_cprestamo.php?id_declaracion=<?php echo $_REQUEST['id_declaracion']; ?>&periodo=<?php echo $_REQUEST['periodo']; ?>','#CapaContenedorFormulario')" 
class="submit-cancelar" value="Cancelar">
    <br />
    <br />  
<table width="510" border="0">
  <tr>
    <td width="139"><label>Num doc:</label></td>
    <td width="355"><?php echo $data['num_documento']; ?></td>
    </tr>
  <tr>
    <td><label>Nombres:</label></td>
    <td><?php echo $data['apellido_paterno'].' '.$data['apellido_materno'].' '.$data['nombres']?></td>
    </tr>
  <tr>
    <td><label>Monto:</label></td>
    <td><?php echo $data['valor']; ?>      
      <input name="monto_prestamo" type="hidden" id="monto_prestamo" value="<?php echo $data['valor']; ?>" size="7" /></td>
  </tr>
  <tr>
    <td><label>Numero Cuotas:</label></td>
    <td><?php echo $data['num_cuota']; ?>
      <input name="num_cuota" type="hidden" id="num_cuota" size="7" 
      value="<?php echo $data['num_cuota']; ?>" /></td>
  </tr>
  <tr>
    <td><label>fecha inicio del Prestamo:</label></td>
    <td><?php echo getFechaPatron($data['fecha_inicio'], "m/Y"); ?></td>
  </tr>
</table>
<h3>Prestamo Cuotas:</h3>              
              
              <table width="530" border="1" class="tabla_gris">
                <tr>
                  <th width="17">id</th>
                  <th width="55">Monto</th>
                  <th width="62">Monto variable</th>
                  <th width="90">Fecha pago</th>
                  <th width="74">&nbsp;</th>
                </tr>                
      <?php foreach($data_obj as $obj): 
	  $id = $obj->getId_prestamo_cutoa();
	  $flag = false;
	  if($obj->getFecha_pago()):	  	
	  else:
		$flag = ($obj->getFecha_calc() == $PERIODO) ? true : false;
	  endif;
	  ?>        
                <tr <?php echo ($flag) ? ' class = "borde_rojo"': '';?> >
                  <td><?php echo $obj->getId_prestamo_cutoa();?></td>                  
                  <td>                  
				  <?php echo $obj->getMonto();?>                                       
                  <input name="montoc"
                  type="hidden" 
                  id="montoc-<?php echo $id;?>" size="7" 
                  value="<?php echo $obj->getMonto();?>" />
              	  </td>
                                    
                  <td>                  
                  <input name="montoc_variable[]"
                  type="text" <?php echo ($flag) ? '' : 'disabled="disabled"';?>
                  id="montoc_variable-<?php echo $id;?>"
                  value="<?php echo $obj->getMonto_variable();?>" size="7"/>
              	  </td>
				  <td>             
					  <?php echo $obj->getFecha_calc();?>                   
					  <input name="fecha_calc" 
					  type="hidden"  
					  class="fecha_calc" 
					  value="<?php echo getFechaPatron($obj->getFecha_calc(), 'd/m/Y');?>"
					  id="fecha_calc-<?php echo $id;?>" size="12" />
				  </td>                  
                  <td>
                  <?php if($flag):?>
                  <input type="button" name="btnGuardar"  value="Guardar"  class="submit-go"
                  id="" onclick="guardarPrestamoCuota('<?php echo $id;?>')" />
                  <?php endif; ?>
                  </td>
                </tr>                
	<?php endforeach; ?>                
</table>
        <p>&nbsp;</p>
        </form>       
        </div>
</div>
</div>