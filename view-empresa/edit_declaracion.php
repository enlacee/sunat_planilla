<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
require_once '../controller/ideController.php';
$data = $_SESSION['sunat_empleador'];

$ID_PDECLARACION = $_REQUEST['id_pdeclaracion'];

?>

<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
	cargarTablaPDeclaracionEtapaPago(<?php echo $ID_PDECLARACION; ?>);
</script>


<div class="demo" align="left" >
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Declaracion</a>
              </li></ul><div id="tabs-1">
              
              <div class="ocultar">
              id_pdeclaracion<input name="id_pdeclaracion" id="id_pdeclaracion" type="text" 
               value="<?php echo $ID_PDECLARACION; ?>"/>
              
               </div>
              
<div class="blue">        
             RUC: <?php echo $data['ruc']. " - ". $data['razon_social_concatenado']; ?>
          </div>
        
        



           <h2>Paso 01 : Lista de trabajadores por etapas:</h2>
          <ol>
          <li>1ERA QUINCENA</li>
          <li>2DA QUINCENA</li>
          </ol>
          
            <input type="button" name="adelanto_01" id="adelanto_mes_01" value="01 Mensual Individual">
            <input type="button" name="adelanto_02" id="adelanto_mes_02" value="02 Mensual Total" />
          
          
          <table id="list">
        </table>
        <div id="pager" align="left">
        </div>




<div class="ayuda">
<h3>Descripcion tenga en cuenta:</h3>
<p>-Verificar si la (lista) de  trabajadores que se muestra arriba.<br />
coinciden con los trabajadores que realmente desea Declarar<br />
en Planilla Mensual. 'PDT-PLANILLA'.</p>
<p>
-Los calculos que se realizaran, se  muestran en la Cinta : 'CONFIGURACION >> Formulas'.<br />
</p>
<p>
-Esta operacion puede tardar segun el numero de trabajadores que tenga en la (Lista).
</p>
</div>
<p>
  <input type="button" name="button3" id="button3" value="GENERAR PLANILLA MENSUAL"
  onclick="generarDeclaracionPlanilla('<?php echo $ID_PDECLARACION; ?>',this)" />
</p>
                <div class="paginadores ocultar">
  <input type="button" name="button" id="button" value="&lt;&lt; ANTERIOR" />
  <input type="button" name="button2" id="button2" value="SIGUIENTE &gt;&gt;" />
        </div>

              
              
              
              
              
              
            </div>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      </div>
</div>


<!-- -->
<div id="dialog-form-editarPtrabajador">

<div id="editarPtrabajador"> </div>

</div>