<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
require_once '../controller/ideController.php';


// -- Carga de COMBOS
require_once('../dao/ComboCategoriaDao.php');
require_once('../controller/ComboCategoriaController.php');

//Combo 01
$cbo_tipo_empleador = comboTipoEmpleador();

$data = $_SESSION['sunat_empleador'];

//echo "<pre>";
//print_r($data);
//echo "</pre>";

//require_once('../controller/ideController.php');


?>

<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
//------------------------------------------------------------------------------
function validarNewDeclaracion(){ //Registrar Periodo
	var periodo = document.getElementById('txt_periodo_tributario').value;
	var input_estado = document.getElementById('estado');
	var input_inicio = document.getElementById('mes_inicio');
	var input_fin = document.getElementById('mes_fin');
	
	if(validarPeriodo(periodo)==true){
		//-----	
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: 'sunat_planilla/controller/PlameDeclaracionController.php',
			data: {oper: 'add', periodo : periodo },
		    success: function(data){
				// FALSE = YA SE REGISTRO PERIODO
				// TRUE  = NO EXISTE PERIODO REGISTRADO				
				input_estado.value = data.rows[0]['estado'];				
				input_inicio.value =  data.rows[0]['data_mes']['mes_inicio'];
				input_fin.value = data.rows[0]['data_mes']['mes_fin'];
								
				//---------
				alert(input_estado.value);
				
				if(input_estado.value == 'true'){
					cargar_pagina('sunat_planilla/view-plame/edit_declaracion.php?periodo='+periodo,'#CapaContenedorFormulario');
				}else if(input_estado.value == 'false'){
					alert("FALSE Periodo Ya se encuentra Registrado!\n O no existe ningun trabajador en el periodo: ."+input_inicio.value);
				}
				
				
				
			}
		});
		//-------
	}//ENDIF
	

}

//-----------------------------------
function verPeriodo(){
	var periodo = document.getElementById('txt_periodo_tributario').value;
	//alert(periodo);
	if(validarPeriodo(periodo) == true){
		cargarTablaTrabajadoresPorPeriodo(periodo);	
	}
}
//----------------------------------
function validarPeriodo(periodo){
	var estado = false;
	//-------------
	
	//-------------
	var expresion =/^\d{2}\/\d{4}$/;
	var bandera = expresion.test(periodo);
	
	if(bandera==false){
		alert("Formato del Periodo es incorrecto\n Utilize el formato: dia/mes");
		obj.value="";
		obj.style.border="thin solid #ff0000";
	}else{
		
	//----------------			
	//var fecha = new Date();
	var dia = 01;
	var mes  =  parseInt(periodo.substring(0,2),10);
	var anio  =  parseInt(periodo.substring(3,7),10);
	
	//alert("mes.."+mes);
	//alert("anio.."+anio);	
		
	var fecha_form = new Date(anio,mes - 1 ,dia/*, 0,0,0*/);
	var fecha_local = new Date();
	
	if(fecha_form > fecha_local){
		alert("Fecha No puede Ser mayor que la fecha local: \n"+ fecha_local);
		//cargarTablaTrabajadoresPorPeriodo("01/1970")
	}else{
		//cargarTablaTrabajadoresPorPeriodo(periodo);	
		estado = true;
	}	
	//----------------
		
	}//ENDELSE
	
	return estado;

}


	
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Informacion General</a></li>	
            <!--<li><a href="#tabs-2">Detalle de Declaraci&oacute;n</a></li>
            <li><a href="#tabs-3">Determinacion de Deuda</a></li>	-->            		

        </ul>
        <div id="tabs-1">


          <h2>Datos Basicos de la Declaracion:</h2>

          <form id="formNewDeclaracion" name="formNewDeclaracion" method="post" action="">
          
		    <p>RUC:
              <label for="ruc"></label>
            <input type="text" name="ruc" id="ruc" 
            value="<?php  echo $data['ruc']; ?>" />
            <br />
          Nombre/Razon Social:	
          <label for="razon_social"></label>
          <input type="text" name="razon_social" id="razon_social"  
          value="<?php echo $data['razon_social_concatenado']; ?>" />
          <br />
          Periodo Tributario (mm/aaaa)          
      <input type="text" name="txt_periodo_tributario" id="txt_periodo_tributario"  />
      <input type="button" name="ver periodo" id="ver periodo" value="Ver lista de Trabajadores del  periodo"
      onclick="verPeriodo()" />
		    </p>
            <span class="ocultar">
		    <p>Declaracion Sustitutoria o Rectificadora
              <input type="radio" name="rbtn_declaracionRectificadora" id="rbtn_declaracionRectificadora" value="1" />
              Si
              <input type="radio" name="rbtn_declaracionRectificadora" id="rbtn_declaracionRectificadora" value="0" />
No  </p>
Limpia datos Edit</span>

            <p>Sincronizar datos: Actualizar Prestadores de Servicios:
              <input type="checkbox" name="chk_actualizar_declaracion" id="chk_actualizar_declaracion" />
              <input type="button" name="btnEjecutar" id="btnEjecutar" value="Ejecutar" />
            </p>
            
            
            
            
            
		    <p>            
	        <table id="list">
            </table>
        <div id="pager"></div>
              
Lista de Trabajadores que se registraran en el periodo (mes/a&ntilde;o)<sbr />
<p><br />
  estado
  <input type="text" name="estado" id="estado" />
  <br />
  periodo inicio

  <input type="text" name="mes_inicio" id="mes_inicio" />
  <br />
  periodo fin 
  <input type="text" name="mes_fin" id="mes_fin" />
  <br />
  <br />
  
  <input type="button" name="btnValidar"  value="Validar Y Regiatrar"  onclick="validarNewDeclaracion()"/>
</p>
</p>
          </form>
        
      </div><!-- tabs-1 -->
        
        
        <!--<div id="tabs-2">ass</div>-->
        
        
    <!--<div id="tabs-3">ass</div>-->
        
        
</div><!-- tasb-->

</div>