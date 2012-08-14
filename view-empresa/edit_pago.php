<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
require_once "../util/funciones.php";
require_once '../controller/ideController.php';

//NEW
require_once("../controller/PlameDeclaracionController.php");
require_once("../dao/PlameDeclaracionDao.php");
require_once ("../model/Pdeclaracion.php");

// EtAPA PAGO
require_once "../model/EtapaPago.php";
require_once "../dao/EtapaPagoDao.php";
require_once "../controller/EtapaPagoController.php";

echo "ID_EMPLEADOR_MAESTRO = ".ID_EMPLEADOR_MAESTRO;
// -- Carga de COMBOS
//require_once('../dao/ComboCategoriaDao.php');
//require_once('../controller/ComboCategoriaController.php');
//Combo 01
//$cbo_tipo_empleador = comboTipoEmpleador();

$data = $_SESSION['sunat_empleador'];



//require_once('../controller/ideController.php');
$ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];
$ID_ETAPA_PAGO = $_REQUEST['id_etapa_pago'];

$pdeclaracion = new Pdeclaracion();
$pdeclaracion = buscar_ID_Pdeclaracion($ID_PDECLARACION);

echo "<pre>";
//print_r($pdeclaracion);
echo "</pre>";



//ETAPA DE PAGO
$obj_estapa_pago = buscar_ID_EtapaPago($ID_ETAPA_PAGO);

echo "<pre>";
//print_r($obj_estapa_pago);
echo "</pre>";
?>

<script type="text/javascript">
//VARIABLES GLOBALES:


    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
		//$( "#tabs2").tabs();
		
	});
//---------------------------------

	cargar_pagina('sunat_planilla/view-empresa/edit_pago_tab2.php?periodo=' ,'#tabs-2');
	cargar_pagina('sunat_planilla/view-empresa/edit_pago_tab3.php?periodo=' ,'#tabs-3');



//functiones GRID

//	cargarTablaPTrabajadores(PERIODO);

	
</script>

<div align="left">

<div id="tabs">

<div class="ocultarr">id_declaracion 
<input type="text" name="id_pdeclaracion" id="id_pdeclaracion"
value="<?php echo $ID_PDECLARACION; ?>" readonly="true" >
<br>
id_etapa_pago 
<input name="id_etapa_pago" id="id_etapa_pago" type="text" readonly="true" 
value="<?php echo $ID_ETAPA_PAGO; ?>">
</div>



  <p>Empresa :<span class="red"><?php echo $data['ruc']." - ".$data['razon_social_concatenado']; ?></span>
    Declaracion: <span class="red"><?php echo getFechaPatron($pdeclaracion->getPeriodo(),"m/Y"); ?></span>  </p>
    
<ul>
            <li><a href="#tabs-1">Informacion General</a></li>	
            <li><a href="#tabs-2">Detalle de Declaraci&oacute;n</a></li>
            <li><a href="#tabs-3">Determinacion de Deuda</a></li>	            		

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
             value="<?php echo getFechaPatron($pdeclaracion->getPeriodo(),"m/Y"); ?>" />
          <br />
              </p>
              <h4> detalle</h4>
          La declaracion se ebabor&oacute;
          <input name="dfcreacion" type="text" id="dfcreacion" value="<?php echo getFechaPatron($pdeclaracion->getFecha_creacion(),"d/m/Y");?>" readonly="readonly" />
          <br>
		    Ultima fecha de modificacion 
		    <input name="fecha_modificacion" type="text" id="fecha_modificacion"
                           value="<?php echo getFechaPatron($pdeclaracion->getFecha_modificacion(),"d/m/Y"); ?>" readonly="readonly" />
            </div>
            
                <h2>Titulo: <span class="blue"><?php echo $obj_estapa_pago->getGlosa(); ?></span></h2>
              Fecha inicio 
              <input type="text" name="finicio" id="finicio" 
              value="<?php echo getFechaPatron($obj_estapa_pago->getFecha_inicio(),"d/m/Y"); ?>" />
              <br>
              Fecha fin
              <input type="text" name="ffin" id="ffin" 
                     value="<?php echo getFechaPatron($obj_estapa_pago->getFecha_fin(),"d/m/Y"); ?>" />
<p>&nbsp;</p>
		    
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
            
            
            		    
          </form>
        
      </div><!-- tabs-1 -->
        
        
        <div id="tabs-2">
          <p>&nbsp;</p>
          <p>ass2 </p>
</div>
        
        
        <div id="tabs-3">
        ass
        
        3</div>
        
        
</div>