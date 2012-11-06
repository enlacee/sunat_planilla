<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//

require_once '../controller/ideController.php';

//--- Inicio Carga de Conceptos
require_once '../dao/PlameDetalleConceptoEmpleadorMaestroDao.php';


require_once('../controller/PlameDetalleConceptoEmpleadorMaestroController.php');

registrarDetalleConceptoEM(ID_EMPLEADOR_MAESTRO);

//Table_detalle_conceptos_afectaciones();
//--- Finall Carga de Conceptos



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
		//---------------------
		disableForm('formPlameEmpleador');
		cargarTablaConceptos();
		//---- Creando Dialogo
		crearDialogoAfectacion();
		
	});
	
	
</script>


<div class="demo" align="left">
    <div id="tabs">
      <ul>
            <li><a href="#tabs-1">Indetificacion del Empleador</a></li>			
			<li><a href="#tabs-2">Mantenimiento de Conceptos</a></li>
			<li><a href="#tabs-3">Convenios</a></li>	            	
      </ul>
        
      <div id="tabs-1">
        <div class="titulo">Identificación del Empleador </div>
          
          
          <form name="formPlameEmpleador" id="formPlameEmpleador" method="post" action="">

<p>Datos Generales:</p>
          <p>RUC:		
            <label for="ruc"></label>
            <input type="text" name="ruc" id="ruc" 
            value="<?php  echo $data['ruc']; ?>" />
            <br />
          Nombre/Razon Social:	
          <label for="razon_social"></label>
          <input type="text" name="razon_social" id="razon_social"  
          value="<?php echo $data['razon_social_concatenado']; ?>" />
          </p>
          <hr />
          Tipo de Empleador:
        <select name="select" id="select" style="width:150px;" disabled="disabled" >
                          <option value="" >-</option>
<?php
foreach ($cbo_tipo_empleador as $indice) {
	
	if ($indice['id_tipo_empleador'] == $data['id_tipo_empleador'] ) {
		$html = '<option value="'. $indice['cod_tipo_documento'] .'"  selected="selected"  >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['id_tipo_empleador'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>

          </select>
          <br />
          Es una Microempresa
        inscrita en la REMYPE? 
        <input type="radio" name="rbtn_remype"   value="1"  disabled="disabled"
          <?php echo ($data['remype'] == 1) ? ' checked="checked"' : ''; ?> />
          SI
        <input type="radio" name="rbtn_remype"  value="0" disabled="disabled"
          <?php echo ($data['remype'] == 0) ? ' checked="checked"' : ''; ?> />
          NO<br />
        Realiza actividad por las que aporta al SENATI?
        
        <input type="radio" name="rbtn_senati"  value="1" 
        <?php echo ($data['senati'] == 1) ? ' checked="checked"' : ''; ?>
        />
        SI
        <label for="radio3"></label>
        <input type="radio" name="rbtn_senati"  value="0" 
        <?php echo ($data['senati'] == 0) ? ' checked="checked"' : ''; ?>
        />
        NO
        <label for="radio4"></label>
        <hr />
        <p>Tiene convenio de estabilidad y/o exoneracion? 
          <input type="radio" name="rbtn_convenio"  value="radio" />
          SI
          <input type="radio" name="rbtn_convenio"  value="radio2" />
          NO
        </p>


          </form>        
        
        </div>
        <!--tabs-1-->

       
      <div id="tabs-2">
        <div class="titulo">Mantenimiento de Conceptos</div>
        <br />
        RUC: <?php echo $data['ruc']." - ".$data['razon_social_concatenado']; ?>
        <br />
        <br />

<div id="detalle_concepto">
        <table id="list">
        </table>
        <div id="pager">
        </div>
</div>        
      </div>
      
      
      <!--tabs-2-->  
        
        
              
      <div id="tabs-3">
        
      </div>        
</div>







<!-- -->

<div id="dialog-form-editarAfectacion" title="Editar Afectacion">
    <div id="editarAfectacion" align="left"></div>
</div>
