<?php
//session_start();
//*******************************************************************//
require_once('../ide2.php');
//*******************************************************************//
//require_once('../../dao/AbstractDao.php');
require_once('../../controller/ideController2.php');
//--------------combo CATEGORIAS
require_once('../../util/funciones.php');

require_once('../../dao/PersonaDao.php');

require_once('../../dao/ComboCategoriaDao.php');
require_once('../../controller/ComboCategoriaController.php');
//echoo($_REQUEST);
$ID_PERSONA = $_REQUEST['id_persona'];
//echo "ID_EMPLEADOR_MAESTRO = ".ID_EMPLEADOR_MAESTRO;

$data = listarPersonaConPeriosdoLaborales(ID_EMPLEADOR_MAESTRO,$ID_PERSONA);

//echoo($data);

//combo 01x
$cbo_motivo_baja_registro_cat_trabajador = comboMotivoBajaRegistroCatTrabajador();
?>
<script type="text/javascript">
//disableForm('formAddTrabajador');

 $(document).ready(function(){
	
	$('#btnCancelar').click(function(){
		console.log("close modal");
		$("#dialog-addTrabajador").dialog('close');	
	});
	 

  });



</script>


<div class="ayuda">Genera un nuevo periodo de trabajo, para la persona:</div>


<form id="formAddTrabajador" name="formAddTrabajador" method="post" action="">

<div class="ocultar">
    <p>oper      
      <input type="text" name="oper" id="oper" value="add"/>
      <br />
      id_persona		
      <input name="id_persona" type="text" value="<?php echo $ID_PERSONA; ?>"/>
      
      
      <br />
    </p>
  </div>
  
<?php 

  $bandera_situacion = false; // SITUACION BAJA Y PUEDES REGISTRAR:
  for($a=0;$a<count($data);$a++){
  	
	if($data[$a]['cod_situacion'] != 0){ // 0 = baja
		$bandera_situacion = true; // SITUACION ACTIVO:
		break;
	}
  }//endfor
  
//echo "<br>----------------<br>";
//echoo($bandera_situacion);
//var_dump($bandera_situacion);
//echo "<br>----------------<br>";

  // INICIO  IF
  if($bandera_situacion==false):
  if(count($data)>0): // logica para no mostrar tabla
?>
  
<table width="507" border="1">
  <tr>
    <td width="57">ID</td>
    <td width="103">fecha inicio</td>
    <td width="94">fecha fin</td>
    <td width="106">Descripcion</td>
    <td width="113">Motivo</td>
  </tr>
  <tr>  
<?php 
  endif;	
  for($i=0;$i<count($data);$i++):   
  ?>
  
  
    <td>
      
      <input name="id[]" type="text"
      value="<?php echo $data[$i]['id_detalle_periodo_laboral']; ?>" size="3"/>
      
      <input name="id_trabajador[]" type="hidden" size="5" 
      value="<?php echo $data[$i]['id_trabajador']; ?>" />
      
      </td>
    <td>
    <input name="f_inicio[]" type="text"  size="11"
    value="<?php echo getFechaPatron($data[$i]['fecha_inicio'],"d/m/Y");?>"
     />
    </td>
    
    <td><input name="f_fin[]" type="text"  size="11" 
    value="<?php echo getFechaPatron($data[$i]['fecha_fin'],"d/m/Y"); ?>"
    />
    </td>
    <td>
      <input name="cod_situacion[]" type="hidden" size="3" 
      value="<?php echo $data[$i]['cod_situacion'];?>" />
      
      <?php echo $data[$i]['descripcion_abreviada'];?></td>
    <td><label for="cbo[]"></label>
      <select name="cbo[]" id="cbo[]">
      
      <?php 
$cod_motivo_baja = $data[$i]['cod_motivo_baja_registro'];

foreach ($cbo_motivo_baja_registro_cat_trabajador as $indice) {
	if($indice['cod_motivo_baja_registro'] =="0" ){
		
		$html = '<option value="0"  >-</option>';
	
	}else if( $indice['cod_motivo_baja_registro'] == $cod_motivo_baja ){

		$html = '<option value="'. $indice['cod_motivo_baja_registro'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';

	}else {
		$html = '<option value="'. $indice['cod_motivo_baja_registro'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
	}
	echo $html;
} 
?>
      
      </select></td>
    
<?php //FIN FOR
endfor; ?>      
    
    
  </tr>
</table>

  <p>&nbsp;</p>
  
  <input type="button" name="btnNuevo"  value="nuevo Trabajador" class="submit-go" 
  onclick="nuevoAddTrabajador('<?php echo $ID_PERSONA;?>')" />
  
<!--<input type="button" name="btnCancelar" id="btnCancelar"  value="Cancelar"  class="submit-cancelar"/> -->


<?php 
//FIN IF
else:

echo "<h3>Trabajador ACTIVO</h3>";

endif;

?>  
  
</form>
