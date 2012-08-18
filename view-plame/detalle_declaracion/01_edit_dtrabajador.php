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
require_once('../../model/Ptrabajador.php');
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
$ID_PAGO = $_REQUEST['id_pago'];
/*
echo"<pre>ID_PTRABAJADOR..";
print_r($ID_PTRABAJADOR);
echo"</pre>";
*/
//data - ptrabajador
$obj_pago = new Pago();
$obj_pago = buscar_IDPago($ID_PAGO);

//echo "<pre>";
//print_r($obj_pago);
//echo "</pre>";

$trabajador = new Trabajador();
$trabajador = buscar_IDTrabajador($obj_pago->getId_trabajador());


//data - persona
$persona = new Persona();
$persona = buscarPersonaPorId($trabajador->getId_persona()); 


//echo "<pre>";
//print_r($obj_pago);
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
<div class="ocultar">
id_pago

</div>
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


<h1>DATOS LABORALES</h1>

<p>
  <label for="cboCentroCosto">Centro de costo</label>
    <select name="cboCentroCosto" disabled="disabled" style="width:180px;">
      <?php
foreach ($cbo_ccosto as $indice) {
	
	if ( $indice['id_empresa_centro_costo'] == $obj_pago->getId_empresa_centro_costo()) {
		
		$html = '<option value="'. $indice['id_empresa_centro_costo'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';		
	} else {
		$html = '<option value="'. $indice['id_empresa_centro_costo'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}
?>
    </select>
</p>
<p><img src="images/icons/candado_1.png" width="72" height="72" /></p>
</div>



<!---  DIALOG -->
<div id="dialog-pt-periodo-laboral">

<div id="editarPTperiodoLaboral"> </div>

</div>

