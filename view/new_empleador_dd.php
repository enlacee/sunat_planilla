<?php
//session_start();
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//

require_once('../util/funciones.php');
require_once('../dao/AbstractDao.php');
require_once('../dao/ComboCategoriaDao.php');
require_once('../controller/ComboCategoriaController.php');

// IMPORTANTE :: Buscar Id_empleador_maestro
require_once('../dao/EmpleadorMaestroDao.php');
require_once('../controller/EmpleadorMaestroController.php');

// Empleador Que Ingreso al Sistema ()
$ID_EMPLEADOR = $_SESSION['sunat_empleador']['id_empleador'];
//Busca ID
$DATA_EMPLEADOR_MAESTRO = buscarIdEmpleadorMaestroPorIDEMPLEADOR($ID_EMPLEADOR);




// COMBO 01
$cbo_tipo_actividad = comboTipoActividad();

//echo "<pre>";
//print_r($cbo_tipo_actividad);
//echo "<pre>";


?>
<style type="text/css">
<!--
#cesta-productos{
	text-align:center;
	width:660px;
	/*margin-top:5px;*/
	
}
.celda{
	float:left;
	min-height:22px;
	padding:5px 0 5px 0;
	margin-right:1px;
}
.negrita .celda{
	min-height:45px;
	background-color:#6FF;
	font: 14px/12px inherit;	
	
}


.eliminar{
	width:20px;
	background-color:#fcac36;
}

.producto{
	width:90px;
	background-color:#fcac36;
}


.cantidad{
	width:270px;
	background-color:#fcac36;
}
.precio,.sub-total{
	width:130px;
	background-color:#fcac36;

}


-->
</style>
<script>
     function validarFormulario(){
		var cbo = $('select');
		
		console.log("----------");
		console.log(cbo[0].value);
		console.log(cbo.length);
		console.log("----------");
		var num_combos = cbo.length;
		var vacio =0;
		for(var i=0;i < num_combos; i++ ){			
			
			if(cbo[i].value ==""){
				vacio++;
				console.log(cbo[i].value);
			}
				
		}
		console.log(vacio);
		if(vacio<=1){ alert ("vacio "+vacio);
			
			//-----------------------------------------------------------------------				
				var from_data =  $("#form_new_empleador_dd2").serialize();
				$.getJSON('sunat_planilla/controller/EmpleadorDD2Controller.php?'+from_data,
					function(data){
						if(data){
							alert ('Se Guardo Correctamente.');
							//cargar_pagina('sunat_planilla/view/view_empleador.php','#CapaContenedorFormulario');
						}else{
							alert("Ocurrio un error");
						}
					}); 
			//-----------------------------------------------------------------------
		}else{
			alert ("Debe seleccionar todos los combo "+ vacio);
		
		}
		//alert(cbo);
		//console.dir(cbo);
		//console.log(vacio);
		return true;
				 

}//ENDVAlidarformulario 

    //INICIO HISTORIAL
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
    //-------------------------------------------------------------
    });

//-------------------------------------------------------------

	$("#btn_validar_ruc").click(function(){	

		var ruc_maestro  = $('input#ruc').val();
		var ruc_buscar = $('input#txt_ruc_buscar').val();
		

			
			
	  if(ruc_maestro.length != 11){
	   	alert ("Numero de Ruc debe tener 11 digitos");
	   }else if(ruc_buscar.length != 11){
		 alert ("Numero de Ruc debe tener 11 digitos");  
	   }else if(ruc_maestro == ruc_buscar){
		alert ("Debe Ingresar un RUC distinto al del propio\n Empleador");
	   }else{

	//------
			var param = 'oper=buscar_empleador&txt_ruc='+ruc_buscar;
			$.getJSON(
				'sunat_planilla/controller/EmpleadorController.php?'+param,
				function(data){ 
					if(data){
						//jQuery("#list").trigger("reloadGrid");
						alert("Se encontro Registro");
						//Cargar Datos:
						$('#id_empleador').val(data.id_empleador);
						$('#txt_razon_social').val(data.razon_social_concatenado);
						$("#btn_validar_ruc").attr('disabled',true);
						$("#txt_ruc_buscar").attr('readonly',true);	
						$("#datos-empleador2-ajax").show();	
						
						//USAR LOAD PHP SCRIP 
						alert("ddd");
						var id_empleador_maestro = $('input#id_empleador_maestro').val();
						var id_empleador = $('input#id_empleador').val();						
						var url = "sunat_planilla/view/load/load_empleador_dd2_1.php";
						alert(url);
						$('#contenedor_producto').load(url,{id_empleador_maestro: id_empleador_maestro,id_empleador : id_empleador});
						
						//END LOAS js PHP
						
						
						
						
						
						
						
																
						
											
					}else{
						alert("No existe Empleador registrado en el sistema");						
					}
				}
			);	
	//----	

		} 
			


					
});
		
//-------------------------
//NUEVO ELEMNTO
var i=0;
function nuevoElemento(id_elem_clonar,id_posicion_ver){
if(i<3){
	//Capa Contenedora
	capa = document.getElementById('contenedor_producto');	
	//crear elemento
	patron = document.getElementById('producto_base');
	//ubicar hijo
//	patron.setAttribute('id',' ');	
	
	//console.dir(hijo_patron);
	//console.dir(hijo_patron);
	clonado = patron.cloneNode(true);	
	clonado.removeAttribute('id');
	clonado.removeAttribute('style');
	capa.appendChild(clonado);
}else{
	alert('Servicios Limitados a 3');
}
	i++;
}//ENDnuevoElemento
//-------------------------------	

var rucEmpleador = function(){
	var length_ruc = 11;
	var ruc_maestro  = $('input#ruc').val();
	var ruc_buscar = $('input#txt_ruc_buscar').val();
	rucEmpleadorMaestro : ruc_maestro
	rucEmpleadorComun : ruc_buscar
	console.log("wwwwww");
	alert(ruc_buscar);
//	sayHello : function(){
	  if(ruc_maestro.length == length_ruc && ruc_buscar.length == length_ruc ){console.log("ddddddaaddddd");
	   	alert ("Numero de Ruc debe tener "+length_ruc+" digitos");
	   }else if(ruc_maestro == ruc_buscar){ console.log("dddddddd");
		alert ("Debe Ingresar un RUC distinto al del propio\n Empleador");
	   }else{
		alert ("hel");
		} 
//	};
	
	
};


// SCRIPT DE PRUEBA //

function verLoad(){
	//USAR LOAD PHP SCRIP 
var id_empleador_maestro = 1
var id_empleador = 17;						
var url = "sunat_planilla/view/load/load_empleador_dd2_1.php?id_empleador_maestro="+id_empleador_maestro+"&id_empleador="+id_empleador+"";
alert(url);
$('#contenedor_producto').load(url);

}

</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro del Empleador </a></li>			

        </ul>
        <div id="tabs-1">
		
          <h1><strong>Empleador   a quien destaco o desplazo personal 	</strong>	
          </h1>
          <form action="sunat_planilla/controller/EmpleadorController.php" method="POST" name="form_new_empleador_dd2" id="form_new_empleador_dd2" >
            <div class="ocultar" style="display:block" >
              <div>
                <label>oper </label>
                <input name="oper" type="text" value="add-dd2" />
              </div>
              <div class="fila_input">
                <label>id_empleador_maestro</label>
                <input type="text" name="id_empleador_maestro" id="id_empleador_maestro"
              value="<?php echo $DATA_EMPLEADOR_MAESTRO['id_empleador_maestro']; ?>" />
              </div>
              <div class="fila_input">
                <label>nombre_empleador_maestro </label>
                <input type="text" name="empleador_maestro" id="empleador_maestro"  
              value="<?php echo $DATA_EMPLEADOR_MAESTRO['razon_social']; ?> "/>
              </div>
              <div class="fila_input">
                <label> ruc </label>
                <input type="text" name="ruc" id="ruc" value="<?php echo $DATA_EMPLEADOR_MAESTRO['ruc']; ?>" />
              </div>
            </div>
            <fieldset>
              <legend><strong>Empleador que me destaca o desplaza personal  </strong></legend>
            <div>
                <label>RUC:</label>
                <input name="txt_ruc_buscar" type="text" class="required error"  id="txt_ruc_buscar" size="11" maxlength="11" />
                <input type="button" name="btn_validar_ruc" id="btn_validar_ruc" value="Validar RUC" />
                <br />
              <div class="fila_input">
                  <label>Nombre / Razón Social:</label>
                  <input name="txt_razon_social" type="text" class="required error"  id="txt_razon_social" readonly="readonly" />
                  <br />
                  <div class="fila_input">
                    <label>id_empleador:</label>
                    <input name="id_empleador" type="text" class=""  id="id_empleador" readonly="readonly" />
                  </div>
                  <br />
                  <br />
              </div>
            </div>
			  
<div id="datos-empleador2-ajax" style=" border:1px solid #999; background-color:#FF9; display:block" >
  <fieldset>
  <legend><strong>Servicios Recibidos</strong></legend>
<p>
  <input name="btn_agregar" type="button" id="btn_agregar" value="Agregar" onclick="nuevoElemento()" />

  <input type="button" name="load" value="ver load" onclick="verLoad()"/>
  <br />
  
  <p>&nbsp;</p>
  
<!--  
  <div id="cesta-productos_ANTES">
    <div class="negrita" >
				  <div class="celda eliminar">E</div>
				  <div class="celda producto">Codigo</div>
			    <div class="celda cantidad">Servicio Recibido</div>
					<div class="precio celda">Fecha de Inicio<br />
					  (dd/mm/aaaa))
					</div>
				  <div class="celda sub-total">Fecha de Fin.</div>
</div>




<div style="clear:both"></div>
		  </div>
-->  
        
<p>Fecha de periodo a perido max 1 mes</p>


<!-- INI AJAX cesta de productos -->
<div id="cesta-productos">
    <div class="negrita" >
				  <div class="celda eliminar"></div>
				  <div class="celda producto">Codigo</div>
			    <div class="celda cantidad">Servicio Recibido</div>
					<div class="precio celda">Fecha de Inicio<br />
					  (dd/mm/aaaa))
					</div>
				  <div class="celda sub-total">Fecha de Fin.</div>
                  <div class="clear"></div>
</div>
<!-- END AJAX cesta de productos -->













<div id="contenedor_producto" style="border:1px solid red;">xxaa ...load_empleador_dd2_1.php

  </div>










<!-- END AJAX cesta de productos -->
		  </div>
<!-- END AJAX cesta de productos -->




</fieldset>
<br />
</div>			  
			  
			  
			  
			  
			  
			  
			  
            </fieldset>

            <p><!-- DIRECCION 2-->
			<input name="btn_grabar" type="button" id="btn_grabar" value="Grabar" onclick="validarFormulario()"  >

                <input name="btnRetornar" type="button" id="btnRetornar" value="Retornar" 
                onclick="javascript:cargar_pagina('sunat_planilla/view/view_empleador_dd2.php','#CapaContenedorFormulario');" />
          </p>
          </form>

          <p>
            <!--
	FORMULARIO DIRECCION 2
-->
          </p>
          <div class="ayuda">Para mayor detalle respecto a las opciones e información solicitada, sírvase   ingresar al siguiente enlace: <a id="aAyuda" href="http://www.sunat.gob.pe/ayuda/tributos/tregistro-E-P/T-RegistroPrivado-H02.html" target="_new">Ayuda del T-Registro</a>              </div>
      </div>
    </div>
</div>

<!-- -->

<!-- -->

<div id="dialog-form-editarDireccion" title="Editar Direccion">
    <div id="editarPersonaDireccion" align="left"></div>
</div>











<div id="producto_base" style="display:block; border:3px solid red;" >
<div class="celda eliminar">
<input name="id_detalle_servicio_prestado2[]" type="text" id="id_detalle_servicio_prestado2" 
value="0" /></div>
<div class="celda producto">
<input name="txt_codigo[]" type="text" size="9" />
</div>
<div class="celda cantidad">
<select name="cbo_tipo_servicio_prestado[]"  style="width:250px;" title="combo-js">
<option value="" >-</option>
<?php
foreach ($cbo_tipo_actividad as $indice) {

if ($indice['cod_pais_emisor_documento'] == 0/*$obj_persona->getCod_pais_emisor_documentos()*/ ) {

$html = '<option value="'. $indice['cod_tipo_actividad'] .'"  >' . $indice['descripcion'] . '</option>';
} else {
$html = '<option value="'. $indice['cod_tipo_actividad'] .'" >' . $indice['descripcion'] . '</option>';
}
echo $html;
}

?>

</select>
</div>
<div class="precio celda">
<input name="txt_fecha_inicio[]" class="required error" type="text"  size="12" />
</div>
<div class="celda sub-total">
<input name="txt_fecha_fin[]" type="text"  size="12" />
</div>
<div class="clear"></div>
</div>