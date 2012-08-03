<?php
//session_start();
//*******************************************************************//
require_once('../../view/ide2.php');
//*******************************************************************//
//require_once('../../dao/AbstractDao.php');
//--------------combo CATEGORIAS
require_once('../../util/funciones.php');
require_once('../../dao/ComboCategoriaDao.php');
require_once('../../controller/ComboCategoriaController.php');

//COMBO BASICO
require_once('../../dao/ComboDao.php');
require_once('../../controller/ComboController.php');

require_once('../../util/funciones.php');
require_once('../../dao/ComboCategoriaDao.php');
require_once('../../controller/ComboCategoriaController.php');


//############################################################################
//------- Ptrabajador
require_once('../../model/PTrabajador.php');
require_once('../../dao/PtrabajadorDao.php');
require_once('../../controller/PlameTrabajadorController.php');

//------- Persona
require_once('../../model/Persona.php');
require_once('../../dao/PersonaDao.php');
require_once('../../controller/PersonaController.php');

//------- Trabajador
require_once('../../model/Trabajador.php');
require_once('../../dao/TrabajadorDao.php');
require_once('../../controller/CategoriaTrabajadorController.php');


//--------------- PLAME periodoLaboral
require_once('../../model/PperiodoLaboral.php');
require_once('../../dao/PperiodoLaboralDao.php');
require_once('../../controller/PlamePeriodosLaboralesController.php');

//--------------- sub detalle_2
require_once('../../model/DetalleTipoTrabajador.php');
require_once('../../dao/DetalleTipoTrabajadorDao.php');
require_once('../../controller/DetalleTipoTrabajadorController.php');

//--------------- sub detalle_4
require_once('../../model/DetalleRegimenSalud.php');
require_once('../../dao/DetalleRegimenSaludDao.php');
require_once('../../controller/DetalleRegimenSaludController.php');

//--------------- sub detalle_5
require_once('../../model/DetalleRegimenPensionario.php');
require_once('../../dao/DetalleRegimenPensionarioDao.php');
require_once('../../controller/DetalleRegimenPensionarioController.php');

/**
********* BUSQUEDA 01 EDIT = TRA-bajador por ID importante
*/
$ID_PTRABAJADOR = $_REQUEST['id_ptrabajador'];
/*
echo"<pre>ID_PTRABAJADOR..";
print_r($ID_PTRABAJADOR);
echo"</pre>";
*/
//data - ptrabajador
$ptrabajador = new PTrabajador();
$ptrabajador = buscar_IDPtrabajador($ID_PTRABAJADOR);

//echo "<pre>";
//print_r($ptrabajador);
//echo "</pre>";

$trabajador = new Trabajador();
$trabajador = buscar_IDTrabajador($ptrabajador->getId_trabajador());


//data - persona
$persona = new Persona();
$persona = buscarPersonaPorId($trabajador->getId_persona()); 


//echo "<pre>";
//print_r($ptrabajador);
//echo "</pre>";

//----------------------------------------------------------------
// IMPORTANT  id_tipo_empleador = 01->privado 02->publico 03->otros
$id_tipo_empleador = $_SESSION['sunat_empleador']['id_tipo_empleador'];

//$remype = $_SESSION['sunat_empleador']['remype'];

// COMBO 01
$cbo_tipo_documento = comboTipoDocumento(); //var_dump($cbo_tipo_documento);

//combo 03x
$cbo_tipo_trabajador = comboTipoTrabajadorPorIdTipoEmpleador($id_tipo_empleador); 


//COMBO 07
$combo_regimen_salud = comboRegimenSalud();

// COMBO 08
$combo_regimen_pensionario = comboRegimenPensionario();

//combo 10
$combo_situacion = comboSituacion($estado);




//----------------------------------------------------------------
//--- sub 1 Plame Periodo Laboral

$objTRADetalle_1 = new PperiodoLaboral();
$dataObj = array();
$dataObj = buscarPLPorIdPtrabajador( $ID_PTRABAJADOR );

//echo "<pre>ID_PTRABAJADOR";
//print_r($dataObj);
//echo "</pre>";

$objTRADetalle_1 = $dataObj[0];


?>
<div class="ptrabajador">

<label for="pt_tipo_documento">Tipo documento: 
  <select name="pt_tipo_documento" id="pt_tipo_documento" disabled="disabled" 
					  style="width:70px">
						<option value="">-</option>
<?php
foreach ($cbo_tipo_documento as $indice) {
	
	if ( $indice['cod_tipo_documento'] == $persona->getCod_tipo_documento() ) {
		
		$html = '<option value="'. $indice['cod_tipo_documento'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';		
	} else {
		$html = '<option value="'. $indice['cod_tipo_documento'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
}
?>


</select>
                      
                      
  Nro. documento:</label>
<label for="pt_num_documento"></label>
<input name="pt_num_documento" type="text" disabled="disabled" class="input_0" id="pt_num_documento"
value="<?php echo $persona->getNum_documento(); ?>" />
<label for="pt_tipo_documento"><br />
  <br />
  Apellido Paterno</label>
<input name="pt_apaterno" type="text" disabled="disabled" class="input_0" id="pt_apaterno"
 value="<?php echo $persona->getApellido_paterno(); ?>"
 />

<label for="pt_apaterno">Apellido Materno</label>
<input name="pt_materno" type="text" disabled="disabled" class="input_0" id="pt_materno" 
  value="<?php echo $persona->getApellido_materno(); ?>"
/>

<label for="pt_nombres">Nombres</label>
<input name="pt_nombres" type="text" disabled="disabled" class="input_0" id="pt_nombres"
 value="<?php echo $persona->getNombres(); ?>"
/>


<h1>DATOS LABORALES Y DE SEGURIDAD SOCIAL</h1>

<p><b>Periodo Laboral</b>
  
</p>

  <p>
    <label for="pt_fecha_inicio">Fecha de inicio</label>
    <input name="pt_fecha_inicio" type="text" disabled="disabled" id="pt_fecha_inicio"
    value="<?php echo  getFechaPatron($objTRADetalle_1->getFecha_inicio(), "d/m/Y");?>" />
    
    
    <label for="pt_fecha_fin">Fecha de fin</label>
    <input name="pt_fecha_fin" type="text" disabled="disabled" id="pt_fecha_fin"
           value="<?php echo  getFechaPatron($objTRADetalle_1->getFecha_fin(), "d/m/Y");?>" ç />
    
           <a href="javascript:editarPTperiodoLaboral('<?php echo $ptrabajador->getId_ptrabajador();?>')">ver detalle</a></p>
  
<p>
  <label for="pt_tipo_trabajador">Tipo de trabajador</label>
  <select name="pt_ttrabajador" id="pt_ttrabajador" style="width:180px;" disabled="disabled" >
    <!--<option value="0" >-</option>-->
<?php
foreach ($cbo_tipo_trabajador as $indice) {
	//----
	if($indice['cod_tipo_trabajador'] =="0" ){		
		$html = '<option value="0"  >-</option>';	
	}else if( $indice['cod_tipo_trabajador'] ==  $ptrabajador->getCod_tipo_trabajador()){
		$html = '<option value="' . $indice['cod_tipo_trabajador'] .'" selected="selected" >'.$indice['cod_tipo_trabajador'].'-'.$indice['descripcion'].'</option>';
	}else {
		$html = '<option value="'. $indice['cod_tipo_trabajador'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
} 
?>
</select>  
  
<label for="pt_situacion">Situaci&oacute;n</label>
              <select name="pt_situacion" id="pt_situacion" style="width:160px"  disabled="disabled"
              
               >
            <!--<option value="" >-</option>-->
            <?php              
            foreach ($combo_situacion as $indice) {
            
            if($indice['cod_situacion']=== $ptrabajador->getCod_situacion() ){
            
            $html = '<option value="'.$indice['cod_situacion'].'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';	
            
            } else {
				
            $html = '<option value="'. $indice['cod_situacion'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
            
			}
            echo $html;
            }
            ?>
              </select>
</p>

<p>
  <label for="pt_regimen_salud">R&eacute;gimen de salud</label>
  
<select name="pt_regimen_salud" id="pt_regimen_salud" style="width:210px;" disabled="disabled">
                <!--<option value="" >-</option>-->
<?php              
foreach ($combo_regimen_salud as $indice) {	
	if($indice['cod_regimen_aseguramiento_salud']=== $ptrabajador->getCod_regimen_aseguramiento_salud()){		
		$html = '<option value="'.$indice['cod_regimen_aseguramiento_salud'].'" selected="selected" >' . $indice['descripcion'] . '</option>';			
	} else {
		$html = '<option value="'. $indice['cod_regimen_aseguramiento_salud'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>  
</select>

</p>
<p>
  <label for="pt_regimen_pensionario">R&eacute;gimen de pensionario</label>
          <select name="pt_regimen_pensionario" id="pt_regimen_pensionario" style="width:180px" 
          disabled="disabled">
                <!--<option value="">-</option>-->
<?php 
foreach ($combo_regimen_pensionario  as $indice) {
	
	if ($indice['cod_regimen_pensionario']==0 ) {		
		$html = '<option value="0" >-</option>';
		
	}else if($indice['cod_regimen_pensionario'] == $ptrabajador->getCod_regimen_pensionario() ){
		
		$html = '<option value="'. $indice['cod_regimen_pensionario'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';

	} else {
		
		$html = '<option value="'. $indice['cod_regimen_pensionario'] .'" >' . $indice['descripcion'] . '</option>';

	}
	echo $html;
}
?>
</select>
</p>






<p>
  <label for="rbtn_pt_sctr">Aporta a Es Salud - SCTR</label>
  <input name="rbtn_pt_sctr" type="radio" value="1" disabled="disabled" />SI
  
  <input name="rbtn_pt_sctr" type="radio" value="0" checked="checked"  disabled="disabled" />NO
</p>
<p>
  <label for="rbtn_pt_pension">Aporta a Es Salud + Vida</label>
  <input name="rbtn_pt_evida" type="radio" value="1"  
  <?php echo ($ptrabajador->getAporta_essalud_vida() == '1') ? ' checked="checked"' : ''; ?>/>SI
<input name="rbtn_pt_evida" type="radio" value="0" 
  <?php echo ($ptrabajador->getAporta_essalud_vida() == '0') ? ' checked="checked"' : ''; ?>
/>NO</p>
<p>
  <label for="rbtn_pt_pension">Aporta a Asegura tu Pensi&oacute;n</label>
  <input name="rbtn_pt_pension" type="radio" value="1" 
    <?php echo ($ptrabajador->getAporta_asegura_tu_pension() == '1') ? ' checked="checked"' : ''; ?>
   />SI
<input name="rbtn_pt_pension" type="radio" value="0" 
<?php echo ($ptrabajador->getAporta_asegura_tu_pension() == '0') ? ' checked="checked"' : ''; ?>
/>NO</p>
<p> <b>DATOS TRIBUTARIOS</b></p>
<p>
  
  
  
  <label for="rbtn_pt_dociliado">Condici&oacute;n de domicilio seg&uacute;n impuesto a la renta</label>
  <input name="rbtn_pt_dociliado" type="radio" value="1"  
<?php echo ($ptrabajador->getDomiciliado() == '1') ? ' checked="checked"' : ''; ?>  
  />
  Domiciliado
  <input name="rbtn_pt_dociliado" type="radio" value="0" 
 <?php echo ($ptrabajador->getDomiciliado() == '0') ? ' checked="checked"' : ''; ?> 
  />
  No Domiciliado</p>
<p>¿Tiene otros ingresos de 5ta Categoria? 
  <input type="radio" name="radio" id="rbtn_pt_ingreso5ta_categoria" value="rbtn_pt_ingreso5ta_categoria" />
  SI
  <input name="radio" type="radio" id="rbtn_pt_ingreso5ta_categoria2" value="rbtn_pt_ingreso5ta_categoria" checked="checked" />
  NO
  <label for="rbtn_pt_ingreso5ta_categoria"></label>
</p>
<p>&nbsp;</p>

</div>



<!---  DIALOG -->
<div id="dialog-pt-periodo-laboral">

<div id="editarPTperiodoLaboral"> </div>

</div>

