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
                  
       // $( "#tabs").tabs();
		
	});
</script>


<div id="">



          <h2>Datos Basicos de la Declaracion:</h2>

  <form id="frmNuevaDeclaracion" name="frmNuevaDeclaracion" method="post" action="">
          
		RUC:		
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
<input name="txt_periodo_tributario" type="text" id="txt_periodo_tributario" value="01/2012" />

          
    </form>
        
        
</div>