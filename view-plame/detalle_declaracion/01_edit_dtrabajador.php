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
/*
foreach ($cbo_tipo_documento as $indice) {
	
	if ( $indice['cod_tipo_documento'] == $persona->getCod_tipo_documento() ) {
		
		$html = '<option value="'. $indice['cod_tipo_documento'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';		
	} else {
		$html = '<option value="'. $indice['cod_tipo_documento'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
}*/
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

