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
//-------------------------------

function validarNewDeclaracion(){
	cargar_pagina('sunat_planilla/view-plame/edit_declaracion.php','#CapaContenedorFormulario');
	
	//var pagina = "sunat_planilla/view-plame/edit_declaracion.php";
	//document.location.href=pagina;


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
      <input type="text" name="txt_periodo_tributario" id="txt_periodo_tributario" />

          
            </p>
		    <p>
		      <input type="button" name="btnValidar"  value="Validar"  onclick="validarNewDeclaracion()"/>
		    </p>
          </form>
        
      </div><!-- tabs-1 -->
        
        
        <!--<div id="tabs-2">ass</div>-->
        
        
    <!--<div id="tabs-3">ass</div>-->
        
        
</div><!-- tasb-->

</div>