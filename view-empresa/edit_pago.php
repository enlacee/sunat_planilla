<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//

require_once "../util/funciones.php";
require_once('../dao/AbstractDao.php');
require_once '../controller/ideController.php';


$data = $_SESSION['sunat_empleador'];

//require_once('../controller/ideController.php');
$ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
$periodo = $_REQUEST['periodo'];

?>

<script type="text/javascript">
//VARIABLES GLOBALES:


    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
		//$( "#tabs2").tabs();
		
	});
//---------------------------------

	cargar_pagina('sunat_planilla/view-empresa/edit_pago_tab2.php' ,'#tabs-2');
	//cargar_pagina('sunat_planilla/view-empresa/edit_pago_tab3.php?periodo=' ,'#tabs-3');

	
</script>

<div align="left">

<div id="tabs">

<div class="ocultar">id_declaracion 
<input type="text" name="id_pdeclaracion" id="id_pdeclaracion"
value="<?php echo $ID_PDECLARACION; ?>" readonly="true" >
<br>
id_etapa_pago 
<input name="periodo" id="periodo" type="text" readonly="true" 
value="<?php echo $periodo; ?>">
</div>



  <p>EMPRESA: <span class="red"><?php echo $data['ruc']." - ".$data['razon_social_concatenado']; ?></span>
    DECLARACION: <span class="red"><?php echo $periodo; ?></span>  </p>
    
<ul>
            <li><a href="#tabs-1">Informacion General</a></li>	
            <li><a href="#tabs-2">Detalle de Declaraci&oacute;n</a></li>
            <!--<li><a href="#tabs-3">Determinacion de Deuda</a></li>-->	            		

  </ul>
        
        
        <div id="tabs-1">
          <form id="frmNuevaDeclaracion" name="frmNuevaDeclaracion" method="post" action="">
            <div class="ocultar">
              <h2>Datos Basicos Declaracion Etapa:</h2>
              <p>RUC:
                <label for="ruc"></label>
                <input type="text" name="ruc" id="ruc"  readonly="readonly"
            value="<?php  echo $data['ruc']; ?>" />
                <br />
          Nombre/Razon Social:	
          <label for="razon_social"></label>
          <input type="text" name="razon_social" id="razon_social"   readonly="readonly"
          value="<?php echo $data['razon_social_concatenado']; ?>" />
          <br />
          Periodo Tributario (mm/aaaa)          
          <input type="text" name="txt_periodo_tributario" id="txt_periodo_tributario"  readonly="readonly"
             value="<?php echo getFechaPatron($periodo,'m/Y'); ?>" />
          <br />
              </p>
            </div>
            
	    
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
            
            
            		    
          </form>
        
      </div><!-- tabs-1 -->
        
        
        <div id="tabs-2">
          <p>&nbsp;</p>
          <p>ass2 </p>
</div>
        
        
        <!--<div id="tabs-3">
        ass3</div>-->
        
        
</div>