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
            <li><a href="#tabs-1">Operacion Mensual</a>
              </li></ul><div id="tabs-1">
              
              <div class="ocultar">
              id_pdeclaracion<input name="id_pdeclaracion" id="id_pdeclaracion" type="text" 
               value="<?php echo $ID_PDECLARACION; ?>"/>
              
               </div>
              


           <h2>Mensual:</h2>
           <div class="help ayuda"><strong>01 Mensual Individual :</strong><br />
             Genera la planilla mensual de los trabajadores seleccionados.
               <p><strong>02 Mensual Total :</strong><br />
               Genera la planilla mensual de todos los trabajadores  comprendidos en el mes. </p>
           </div>
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
-Esta operacion puede tardar segun el numero de trabajadores que tenga en la (Lista).</p>
</div>
              </div>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      </div>
</div>


<!-- -->
<div id="dialog-form-editarPtrabajador">

<div id="editarPtrabajador"> </div>

</div>