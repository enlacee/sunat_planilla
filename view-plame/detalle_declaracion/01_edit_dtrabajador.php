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


//--------------- sub detalle_1
require_once('../../model/DetallePeriodoLaboral.php');
require_once('../../dao/DetallePeriodoLaboralDao.php');
require_once('../../controller/DetallePeriodoLaboralController.php');

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

//data - trabajador
$trabajador = new Trabajador();
$trabajador = buscar_IDTrabajador($ptrabajador->getId_trabajador());

//data - persona
$persona = new Persona();
$persona = buscarPersonaPorId($trabajador->getId_persona()); 

/*
echo "<pre>";
print_r($ptrabajador);
echo "<hr>";
print_r($trabajador);
echo "<hr>";
print_r($persona);


echo "</pre>";
*/
//----------------------------------------------------------------
// IMPORTANT  id_tipo_empleador = 01->privado 02->publico 03->otros
$id_tipo_empleador = $_SESSION['sunat_empleador']['id_tipo_empleador'];

//$remype = $_SESSION['sunat_empleador']['remype'];


//combo 03x
$cbo_tipo_trabajador = comboTipoTrabajadorPorIdTipoEmpleador($id_tipo_empleador); 


//COMBO 07
$combo_regimen_salud = comboRegimenSalud();

// COMBO 08
$combo_regimen_pensionario = comboRegimenPensionario();

//combo 10
$estado = ($trabajador->getCod_situacion()==2) ? 0 : $trabajador->getCod_situacion();
$combo_situacion = comboSituacion($estado);




//----------------------------------------------------------------
//--- sub 1 Periodo Laboral

$objTRADetalle_1 = new DetallePeriodoLaboral();
$objTRADetalle_1 = buscarDetallePeriodoLaboral( $ptrabajador->getId_trabajador() );

//--- sub 2 Tipo Trabajador
$objTRADetalle_2 = new DetalleTipoTrabajador();
$objTRADetalle_2 = buscarDetalleTipoTrabajador( $ptrabajador->getId_trabajador() );

//--- sub 4 Regimen Salud
$objTRADetalle_4 = new DetalleRegimenSalud();
$objTRADetalle_4 = buscarDetalleRegimenSalud( $trabajador->getId_trabajador());

//--- sub 5 Regimen Pensionario
$objTRADetalle_5 = new DetalleRegimenPensionario();
$objTRADetalle_5 = buscarDetalleRegimenPensionario ( $trabajador->getId_trabajador() );


?>
<div class="ptrabajador">

<label for="pt_apaterno">Apellido Paterno</label>
<input type="text" name="pt_apaterno" id="pt_apaterno"
 value="<?php echo $persona->getApellido_paterno(); ?>"
 />

<label for="pt_apaterno">Apellido Materno</label>
<input type="text" name="pt_materno" id="pt_materno" 
  value="<?php echo $persona->getApellido_materno(); ?>"
/>

<label for="pt_nombres">Nombres</label>
<input type="text" name="pt_nombres" id="pt_nombres" 
 value="<?php echo $persona->getNombres(); ?>"
/>


<h1>DATOS LABORALES Y DE SEGURIDAD SOCIAL</h1>

<p><b>Periodo Laboral</b>
  
</p>

  <p>
    <label for="pt_fecha_inicio">Fecha de inicio</label>
    <input type="text" name="pt_fecha_inicio" id="pt_fecha_inicio"
    value="<?php echo  getFechaPatron($objTRADetalle_1->getFecha_inicio(), "d/m/Y");?>" />
    
    
    <label for="pt_fecha_fin">Fecha de fin</label>
    <input type="text" name="pt_fecha_fin" id="pt_fecha_fin" ç
    value="<?php echo  getFechaPatron($objTRADetalle_1->getFecha_fin(), "d/m/Y");?>" />
    
    <a href="#">ver detalle</a></p>
  
<p>
  <label for="pt_tipo_trabajador">Tipo de trabajador</label>
  <select name="cbo_ttrabajador_base" id="cbo_ttrabajador_base" style="width:180px;" onchange="comboVinculadosTipoTrabajadorConCategoriaOcupacional(this)" >
    <!--<option value="0" >-</option>-->
<?php
foreach ($cbo_tipo_trabajador as $indice) {
	//----
	if($indice['cod_tipo_trabajador'] =="0" ){		
		$html = '<option value="0"  >-</option>';	
	}else if( $indice['cod_tipo_trabajador'] ==  $objTRADetalle_2->getCod_tipo_trabajador()){
		$html = '<option value="' . $indice['cod_tipo_trabajador'] .'" selected="selected" >'.$indice['cod_tipo_trabajador'].'-'.$indice['descripcion'].'</option>';
	}else {
		$html = '<option value="'. $indice['cod_tipo_trabajador'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
} 
?>
</select>  
  
  <label for="pt_situacion">Situaci&oacute;n</label>
              <select name="cbo_situacion" id="cbo_situacion" style="width:160px" 
              <?php //echo ($trabajador->getCod_situacion()== '1') ? ' disabled="disabled"' : ''; ?>
               >
            <!--<option value="" >-</option>-->
            <?php              
            foreach ($combo_situacion as $indice) {
            
            if($indice['cod_situacion']=== $trabajador->getCod_situacion()){
            
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
  
<select name="cbo_regimen_salud_base" id="cbo_regimen_salud_base" style="width:210px;">
                <!--<option value="" >-</option>-->
<?php              
foreach ($combo_regimen_salud as $indice) {	
	if($indice['cod_regimen_aseguramiento_salud']=== $objTRADetalle_4->getCod_regimen_aseguramiento_salud()){		
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
          <select name="cbo_regimen_pensionario_base" id="cbo_regimen_pensionario_base" style="width:180px" onchange="havilitarCUSPP(this)">
                <!--<option value="">-</option>-->
<?php 
foreach ($combo_regimen_pensionario  as $indice) {
	
	if ($indice['cod_regimen_pensionario']==0 ) {		
		$html = '<option value="0" >-</option>';
		
	}else if($indice['cod_regimen_pensionario'] == $objTRADetalle_5->getCod_regimen_pensionario() ){
		
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
  <input name="rbtn_pt_sctr" type="radio" value="1"  />SI
  
  <input name="rbtn_pt_sctr" type="radio" value="0" checked="checked"/>NO
</p>
<p>
  <label for="rbtn_pt_evida">Aporta a Es Salud + Vida</label>
  <input name="rbtn_pt_evida" type="radio" value="1"  
  <?php echo ($ptrabajador->getAporta_essalud_vida() == '1') ? ' checked="checked"' : ''; ?>/>SI
<input name="rbtn_pt_evida" type="radio" value="0" 
  <?php echo ($ptrabajador->getAporta_essalud_vida() == '0') ? ' checked="checked"' : ''; ?>
/>NO</p>
<p>
  <label for="rbtn_pt_evida">Aporta a Asegura tu Pensi&oacute;n</label>
  <input name="rbtn_pt_evida" type="radio" value="1" 
    <?php echo ($ptrabajador->getAporta_asegura_tu_pension() == '1') ? ' checked="checked"' : ''; ?>
   />SI
<input name="rbtn_pt_evida" type="radio" value="0" 
<?php echo ($ptrabajador->getAporta_asegura_tu_pension() == '0') ? ' checked="checked"' : ''; ?>
/>NO</p>
<p> <b>DATOS TRIBUTARIOS</b></p>
<p>
  
  
  
  <label for="rbtn_pt_docimiliado">Condici&oacute;n de domicilio seg&uacute;n impuesto a la renta</label>
  <input name="rbtn_pt_docimiliado" type="radio" value="1" 
<?php echo ($ptrabajador->getDomiciliado() == '1') ? ' checked="checked"' : ''; ?>  
  />SI
  <input name="rbtn_pt_docimiliado" type="radio" value="0" 
 <?php echo ($ptrabajador->getDomiciliado() == '0') ? ' checked="checked"' : ''; ?> 
  />NO
</p>
<p>&nbsp;</p>

</div>