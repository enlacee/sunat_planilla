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



      <?php foreach($data_obj as $obj): 
	  		$idd = $obj->getId_prestamo_cutoa();
	  ?>	  
	$( "#fecha_calc-<?php echo $idd;?>" ).datepicker({
		showButtonPanel: true,		
		changeMonth: true,
		changeYear: true,
		dateFormat: '01/mm/yy',
		minDate: new Date(<?php echo getFechaPatron($obj->getFecha_calc(), "Y,m,d");?>),
		//maxDate: "+11M +0D"		
		
	});	  
	  <?php endforeach; ?>
			
/*		//.is(':checked')	
$('.cubodin').bind('click', function() {
	//$(this).toggleClass('entered');	
	//console.log( $('.cubodin') );	
	var $chk = $('.cubodin');
	
	console.log($chk);
	//var id_numero = null;
	for(var i=0;i<$chk.length;i++){
		//console.log(i);
		console.log( $chk[i].checked );	
		//console.log( $chk[i].id );
		var id_numero = $chk[i].id;			
		var cadena = id_numero.split("-");	
		var obj_chek = document.getElementById('montoc_variable-'+cadena[1]);
		
		if($chk[i].checked){
			//console.log(cadena);					
			obj_chek.value = '';
			$chk[i].value = 1;
			break;
		}else{
			$chk[i].value = 0;
		}
	}
});*/
		
//-----------------------		
/*		$("#fecha_inicio").datepicker({ 
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm/yy',
			//minDate: 0,
			//maxDate: "+11M +0D"
			
		});
*/
	});


// funciones Cuota Prestamo

function validarFechas(){
	
	// PRIMERA ETAPA
	var $chk = $('.estado');
	
	var fechas = new Array();
	var bandera = false;
	for(var i=0;i<$chk.length;i++){
		console.log('i='+i);			
		var id_numero = $chk[i].id;	
		var cadena = id_numero.split("-");	
		var fecha = document.getElementById('fecha_calc-'+cadena[1]).value				
		
		fechas[i] = {'id':cadena[1], 'fecha':fecha}

	}
	
	
	//console.log(fechas);
	//console.log(fechas.length);
	
	// SEGUNDA ETAPA
	for(var j=0;j<$chk.length;j++){
	
		for(var k=0;k<fechas.length;k++){
			
			var id_numero = $chk[j].id;	
			var cadena = id_numero.split("-");	
			var fecha = document.getElementById('fecha_calc-'+cadena[1]).value			
			
			//console.log(fecha+'=='+fechas[k].fecha);
			//console.log(cadena[1]+"=="+fechas[j].id );
							
			if(cadena[1] != fechas[k].id){	
				//console.log('diferentes');		
				if(fechas[k].fecha == fecha){
					bandera= true;
					break;
				}			
			}
		}
		
				
		
	}

	
	return bandera;
		
}





function validarPrestamoCuota(){
	
	// Variables locales
	var monto_prestamo = parseFloat(document.getElementById('monto_prestamo').value);
	var num_cuota = parseInt(document.getElementById('num_cuota').value);
	
	var $chk = $('.estado');
	var sum_montoc = 0; 
	var sum_montoc_variable = 0;
	
	var sum_monto_pagado = 0;
	var sum_monto_no_pagado = 0;
	
	//
	var maximo = 0;
	var minimo = 0;
		
	for(var i=0;i<$chk.length;i++){	
	console.log(i);			
		var id_numero = $chk[i].id;			
		var cadena = id_numero.split("-");		
		
		var nombre_banderin = document.getElementById('estado-'+cadena[1]).className;
		var bandera = nombre_banderin.split(" ");	
		
		console.log(bandera);
		var estado = parseInt(document.getElementById('estado-'+cadena[1]).value);
		
		
		var mtoc = document.getElementById('montoc-'+cadena[1]).value;
		var mtoc_variable = document.getElementById('montoc_variable-'+cadena[1]).value;
	

		var montoc_variable = (mtoc_variable=='') ? 0 : parseFloat(mtoc_variable);
		var montoc = (mtoc=='') ? 0 : parseFloat(mtoc);
		
		
		sum_montoc_variable = sum_montoc_variable + montoc_variable;
		
		sum_monto_pagado = sum_monto_pagado + montoc_pagado;
		
		
		//break
		console.log('bandera[1] = '+bandera[1]);
		if(bandera[1]=='bandera'){
			
			if(num_cuota == (i+1) ){ //ultima cuota a pagar
				maximo = (monto_prestamo - sum_monto_pagado);
				minimo = (monto_prestamo - sum_monto_pagado);
			}else{ //numero de cuota aun queda a pagar
				var numero_cuota_queda = (num_cuota - (i+1));
				maximo = (monto_prestamo - sum_monto_pagado) - numero_cuota_queda;
				minimo = (monto_prestamo - sum_monto_pagado) - numero_cuota_queda;			
			}
			
			console.log("BREAK SALIOOO");
			break;				
		}

		
		
	}
	
	console.log('monto_prestamo = '+monto_prestamo);
	
	console.log('sum_montoc_variable = '+sum_montoc_variable);	

	console.log("sum_monto_pagado = "+sum_monto_pagado);
	
	
	alert("maximo Monto variable "+maximo+"\nminimo = "+minimo);


	
	

	
}

function guardarPrestamoCuota(id){
	var estado = validarFechas();
	
	if(!estado){
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
	   
	}else{alert("Fecha de Calc o Fecha de Pago Corriga fechas duplicadas!")}
			
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
                  <th width="62" class="ocultar">Monto variable</th>
                  <th width="90">Fecha calc</th>
                  <th width="90">Fecha pago</th>
                  <th width="96">Estado</th>
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
                  type="<?php echo ($flag) ? 'text' : 'text'; ?>" 
                  id="montoc-<?php echo $id;?>" size="7" 
                  value="<?php echo $obj->getMonto();?>" />                  </td>
                                    
                  <td class="ocultar">
                  <?php echo $obj->getMonto_variable();?>                  
                                  
                  <input name="montoc_variable[]"
                  type="<?php echo ($flag) ? 'text' : 'text'; ?>" 
                  id="montoc_variable-<?php echo $id;?>"
                  value="<?php echo $obj->getMonto_variable();?>" size="7"
                  readonly="readonly" />                  </td>
<td>

                  
              <?php echo $obj->getFecha_calc();?>
                   
              <input name="fecha_calc" 
                  type="<?php echo ($flag) ? 'text' : 'text'; ?>"  
                  class="fecha_calc" 
                  value="<?php echo getFechaPatron($obj->getFecha_calc(), 'd/m/Y');?>"
                  id="fecha_calc-<?php echo $id;?>" size="12" />              </td>
                  
                  <td>				  
				  <?php echo $obj->getFecha_pago();?>                  </td>
                  
                  <td>
                  <input name="estado"  
                  type="<?php echo ($flag) ? 'text' : 'text'; //hidden?>"
                  size="7"
                  value="<?php echo $obj->getEstado();?>"
                  class="estado <?php echo ($flag) ? 'bandera' : '';?>"
                  id="estado-<?php echo $id;?>" />
				  <?php echo $obj->getEstado();?>                  </td>
                  
                  <td>
                  <?php if($flag):?>
                  <input type="button" name="btnGuardar"  value="Guardar"  class="submit-go"
                  id="" onclick="guardarPrestamoCuota('<?php echo $id;?>')" />
                  <?php endif; ?>                  </td>
                </tr>
                
	<?php endforeach; ?>                
</table>
          <p>&nbsp;</p>
        </form>       
        </div>
</div>

</div>