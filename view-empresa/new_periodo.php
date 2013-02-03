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
	console.log("NEW_PERIODO.PHP");
	console.log(id_pdeclaracion);
	console.log(periodo);
			
			
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
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


          <h2>Datos Basicos del Periodo Mensual:</h2>

          <form id="formNewDeclaracion" name="formNewDeclaracion" method="post" action="">
          
		    <label>RUC:</label>
              <label for="ruc"></label>
            <input type="text" name="ruc" id="ruc" 
            value="<?php  echo $data['ruc']; ?>" />
            <br />
            <br />
          <label>Nombre/Razon Social:</label>	
          <label for="razon_social"></label>
          <input type="text" name="razon_social" id="razon_social"  
          value="<?php echo $data['razon_social_concatenado']; ?>" />
          <br />
          <br />
          <label>Periodo Tributario:</label>           
          <input type="text" name="txt_periodo_tributario" id="txt_periodo_tributario"  />
      (mm/aaaa)		    <br />
		    <br />
<input type="button" name="btnValidar"  value="Guardar" 
class="submit-go" onclick="validarNewDeclaracionPeriodo()"/>
		    
            <input type="button"  value="Cancelar" class="submit-cancelar"
            onclick="cargar_pagina('sunat_planilla/view-empresa/view_periodo.php','#CapaContenedorFormulario')" />
		   
		    
		    <table id="list">
	        </table>
        <div id="pager"></div>
        <div class="ocultar">Lista de Trabajadores que se registraran en el periodo (mes/a&ntilde;o)
          <sbr />
          <p>estado
            <input type="text" name="estado" id="estado" />
            <br />
            periodo inicio
              
            <input type="text" name="mes_inicio" id="mes_inicio" />
            <br />
            periodo fin 
            <input type="text" name="mes_fin" id="mes_fin" />
            <br />
            <br />
                </p>
        </div>
  </p>
          </form>
        
      </div><!-- tabs-1 -->
        
        
        <!--<div id="tabs-2">ass</div>-->
        
        
    <!--<div id="tabs-3">ass</div>-->
        
        
</div><!-- tasb-->

</div>