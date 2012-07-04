<?php
//session_start();
//*******************************************************************//
require_once('../ide2.php');
//*******************************************************************//
//require_once('../../dao/AbstractDao.php');

//--------------combo CATEGORIAS
require_once('../../util/funciones.php');
require_once('../../dao/ComboCategoriaDao.php');
require_once('../../controller/ComboCategoriaController.php');

/*
 * Empleador Destaque Controller 
 * Busqueda de Establecimiento por Defecto 
 */
require_once '../../controller/EmpleadorDestaqueController.php';
require_once '../../dao/EmpleadorDestaqueDao.php';
require_once '../../dao/EmpleadorDao.php';

//------------------------------------------------------------------------------
// OBJECT Persona Formacion laboral
require_once '../../controller/CategoriaPFormacionController.php';//Controller
require_once '../../model/PersonaFormacionLaboral.php';
require_once '../../dao/PersonaFormacionLaboralDao.php';

//sub 1 Periodo Formativo
require_once '../../model/DetallePeriodoFormativo.php';
require_once '../../dao/DetallePeriodoFormativoDao.php';
require_once '../../controller/DetallePeriodoFormativoController.php';//Controller

//sub 2 Establecimiento Formacion
require_once '../../model/DetalleEstablecimientoFormacion.php';
require_once '../../dao/DetalleEstablecimientoFormacionDao.php';
require_once '../../controller/DetalleEstablecimientoFormacionController.php';//Controller

//############################################################################

$ID_PERSONA = $_REQUEST['id_persona'];

$objPERF = new PersonaFormacionLaboral();

$objPERF = buscaPersonaFormacionPorIdPersona($ID_PERSONA);

//--- sub 1 Periodo Formativo
$objPERFDetalle_1 = new DetallePeriodoFormativo();
$objPERFDetalle_1 = buscarDetallePeriodoFormativo( $objPERF->getId_personal_formacion_laboral() );


//--- sub 2 Establecimiento de Formacion
$objPERFDetalle_2 = new DetalleEstablecimientoFormacion();
$objPERFDetalle_2 = buscarDetalleEstablecimientoFormacion( $objPERF->getId_personal_formacion_laboral() );


//echo "<pre>";
//var_dump($objPERF->getId_personal_formacion_laboral());
//echo "</pre>";

echo "<pre>oo";
var_dump($objPERFDetalle_1);
echo "</pre>";

//#############################################################################
?>
   <!--  
  	------------------------------------------- CATEGORIAS --------------------------------------------
    -->
<?php //comboNivelEducativo

//combo 00
$cbo_modalidad_formativa = comboModalidadFormativa();

//combo 02
$cbo_nivel_educativo =  comboNivelEducativo();

//COMBO 03 
$cbo_ocupaciones =  comboOcupacionALLPformacion();

//combo 04
$lista_establecimientos = listarEstablecimientoDestaque($id_empleador=null);


$COD_LOCAL = 0;
$arreglo3 = array();		  
foreach ($lista_establecimientos as $indice) {	
	$arreglo3 = preg_split("/[|]/",$indice['id']);	
	//	$arreglo[0 - 2] =  // id_establecimiento
	if( $arreglo3[0] == $objPERFDetalle_2->getId_establecimiento()){
		$COD_LOCAL = $arreglo3[2];
		break; 		
	}
}


//echo "<pre>";
//print_r($lista_establecimientos);
//echo "</pre>";


?>
<script type="text/javascript" language="javascript">
 $(document).ready(function(){
	//cargarOcupacionPersonaFormacionLaboral();	 
	});




function cargarOcupacionPersonaFormacionLaboral(){ //alert("dddd");
	//01
	var test = new Array(
	<?php $counteo = count($cbo_ocupaciones); 
	for($i=0;$i<$counteo;$i++): ?>	
	<?php
		if($i == $counteo-1){ 
			echo "{id:'".$cbo_ocupaciones[$i]['id']."', descripcion:'".$cbo_ocupaciones[$i]['descripcion']."' }"; 
		}else{
			echo "{id:'".$cbo_ocupaciones[$i]['id']."', descripcion:'".$cbo_ocupaciones[$i]['descripcion']."' },"; 
		}
	?>
	<?php endfor; ?>
	
	);//end array
	//console.log(test);
	objCombo = document.getElementById('cbo_ocupacion2');
	var counteo = 	test.length;		
	objCombo.options[0] = new Option('-', '0');
	for(var i=0;i<counteo;i++){
		objCombo.options[i+1] = new Option(test[i].descripcion, test[i].id);
	}
}//ENDFN





</script>











<div id="categoria-tabs-4">
          <p>04          </p>
          <h2>Información Complementaria  <span dojoattachpoint="containerNode" unselectable="on">''' <span class="style2">Personal   en formación laboral</span></span> '''          </h2>
       <p class="style2">here no registra Persona por default exepcion!</p>
          
<form action="validar.php" method="post" name="form_personal_en_formacion" id="form_personal_en_formacion" > 
        <p>
          <label for="id_persona_formacion"></label>
          id_persona_formacion
          <input type="text" name="id_persona_formacion" id="id_persona_formacion" 
          value="<?php echo $objPERF->getId_personal_formacion_laboral(); ?>" />
          <br />
          oper
          <label for="oper"></label>
          <input type="text" name="oper" id="oper" value="edit" />
        </p>
          
          <table width="726" border="1">
            <tr>
              <td width="96"><label> Tipo de modalidad formativa: </label></td>
              <td width="215">
              <select name="cbo_tipo_modalidad_formativa" id="cbo_tipo_modalidad_formativa" style="width:180px;" >


<?php  
foreach ($cbo_modalidad_formativa as $indice) {
	
 if($indice['id_modalidad_formativa'] == $objPERF->getId_modalidad_formativa() ){
		
	$html = '<option value="'. $indice['id_modalidad_formativa'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';		
}else {
	$html = '<option value="'. $indice['id_modalidad_formativa'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
}
	echo $html;
}
?>	              
              </select>              </td>
              <td width="3">&nbsp;</td>
              <td width="141">&nbsp;</td>
              <td width="91"><p>Fecha de Inicio <br>
              dd/mm/aaaa</p>              </td>
              <td width="214"><p>Fecha de Inicio <br>
              dd/mm/aaaa</p>              </td>
            </tr>
            <tr>
              <td ><label> Seguro médico: </label></td>
              <td><input name="rbtn_seguro_medico" type="radio"   value="essalud"
              <?php echo ($objPERF->getSeguro_medico()=="essalud" ) ? 'checked="checked"' : '';?>
               >
EsSalud
              <input name="rbtn_seguro_medico" type="radio" value="compania-privada"
              <?php echo ($objPERF->getSeguro_medico()=="compania-privada") ? ' checked="checked"' : '';?>
              >
Compañía Privada </td>
              <td>&nbsp;</td>
              <td><label>Período formativo:</label>
              <input name="id_detalle_perido_formativo" type="text" id="id_detalle_perido_formativo" size="1" 
              value="<?php echo $objPERFDetalle_1->getId_detalle_periodo_formativo(); ?>"
              /></td>
              <td><input name="txt_pformativo_fecha_inicio_base" type="text" id="txt_pformativo_fecha_inicio_base" size="14"
              value="<?php echo getFechaPatron( $objPERFDetalle_1->getFecha_inicio() , "d/m/Y" ); ?>"
              ></td>
              <td><input name="txt_pformativo_fecha_fin_base" type="text" id="txt_pformativo_fecha_fin_base" size="14"
              value="<?php echo  getFechaPatron( $objPERFDetalle_1->getFecha_fin() , "d/m/Y" ); ?>"
              > 
                <a href="#">detalle</a></td>
            </tr>
            <tr>
              <td rowspan="2"><label> Nivel educativo: </label></td>
              <td rowspan="2">
              <select name="cbo_nivel_educativo" id="select" style="width:180px;">
             <option value="0">-</option>
<?php  
foreach ($cbo_nivel_educativo as $indice) {
	
 if($indice['cod_nivel_educativo'] == $objPERF->getCod_nivel_educativo() ){
		
	$html = '<option value="'. $indice['cod_nivel_educativo'] .'" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';		
}else {
	$html = '<option value="'. $indice['cod_nivel_educativo'] .'" >' . $indice['descripcion_abreviada'] . '</option>';
}
	echo $html;
}
?>	
              </select></td>
              <td rowspan="2">&nbsp;</td>
              <td rowspan="2"><label>Establecimiento en el que se forma:</label>
              <input name="id_detalle_establecimiento_formacion" type="text" id="id_detalle_establecimiento_formacion" size="1" 
              value="<?php echo $objPERFDetalle_2->getId_detalle_establecimiento_formacion();  ?>" /></td>
              <td>Código</td>
              <td>Local</td>
            </tr>
            <tr>
              <td><input name="txt_codigo_local2" type="text" id="txt_codigo_local2" size="6" 
              value="<?php echo $COD_LOCAL; ?>" />
                <br />
              <input name="txt_id_establecimiento2" type="text" id="txt_id_establecimiento2" size="15" 
              value="<?php echo $objPERFDetalle_2->getId_establecimiento(); ?>" /></td>
              <td>
                <select name="cboLocal2" id="cboLocal2" style="width:180px"
                onchange="seleccionarLocalDinamico2(this)" >
                    <option value="0">-</option>
<?php			  
foreach ($lista_establecimientos as $indice) {
	$arreglo3 = preg_split("/[|]/",$indice['id']);
	
 if(/*$indice['id']*/$arreglo3[0] == $objPERFDetalle_2->getId_establecimiento() ){	 		
		$html = '<option value="'. $indice['id'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';	
	} else {		
		$html = '<option value="'. $indice['id'] .'" >' . $indice['descripcion'] . '</option>';		
	}
	echo $html;
}

?>
                
              </select>
              <br />
              <a href="#">detalle</a></td>
            </tr>
            <tr>
              <td> Ocupación: </td>
              <td><select name="cbo_ocupacion2" id="cbo_ocupacion2"  style="width:180px" >
<?php 
foreach ($cbo_ocupaciones as $indice) {

 if($indice['id'] == $objPERF->getId_ocupacion_2()  ){	 		
		$html = '<option value="'. $indice['id'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';	
	} else {		
		$html = '<option value="'. $indice['id'] .'" >' . $indice['descripcion'] . '</option>';		
	}
	echo $html;
}
?>                  
                  </select> 
              <span class="style2">HAY FILTRO</span></td>
              <td>&nbsp;</td>
              <td><label>¿ Presenta Discapacidad ?:</label></td>
              <td><input name="rbtn_pdiscapacidad" type="radio" value="1"
              <?php echo ($objPERF->getPresenta_discapacidad()=="1")? ' checked="checked"' : '';?>
              >
                SI
                  <input name="rbtn_pdiscapacidad" type="radio" value="0" 
                  <?php echo ($objPERF->getPresenta_discapacidad()=="0")? ' checked="checked"' : '';?>
                  >
              No</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><label> Centro de Formación: </label></td>
              <td><input name="rbtn_cformacion" type="radio" value="centro-educativo"
              <?php echo ($objPERF->getCentro_formacion()=="centro-educativo")? ' checked="checked"' : '';?>
              >
Centro Educativo                <br>
              <input name="rbtn_cformacion" type="radio" value="universidad" 
              <?php echo ($objPERF->getCentro_formacion()=="universidad")? ' checked="checked"' : '';?>
               >
Universidad <br>
<input name="rbtn_cformacion" type="radio" value="instituto"
<?php echo ($objPERF->getCentro_formacion()=="instituto")? ' checked="checked"' : '';?>
 >
Instituto <br>
<input name="rbtn_cformacion" type="radio" value="otros"
<?php echo ($objPERF->getCentro_formacion()=="otros")? ' checked="checked"' : '';?>
>
Otros </td>
              <td>&nbsp;</td>
              <td><label>¿ Horario Nocturno ?: </label></td>
              <td><input name="rbtn_hnocturno" type="radio" value="1" 
<?php echo ($objPERF->getHorario_nocturno()=="1")? ' checked="checked"' : '';?>              
               >
SI
  <input name="rbtn_hnocturno" type="radio" value="0" 
  <?php echo ($objPERF->getHorario_nocturno()=="0")? ' checked="checked"' : '';?>  
   >
No</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td><label>Situación: </label></td>
              <td><input name="txt_situacion2" type="text" id="txt_situacion2" size="15"
               value ="<?php echo $objPERF->getCod_situacion(); ?>" ></td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <p>
            <input name="btn_aceptar" type="button" id="btn_aceptar" value="Aceptar(Validar)" 
          onclick="validarFormularioPersonaFormacion()" />
          </p>
</form>  
          
</div>
       