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

//-- INCLUDES 02
require_once('../../dao/EmpleadorDao.php');
require_once('../../dao/TrabajadorDao.php');
require_once('../../controller/CategoriaTrabajadorController.php');


//--------------- sub detalle_3 -> sub detalle_4buscar Empleador Seleeccionado
require_once '../../controller/EmpleadorDestaqueYourselfController.php';
require_once '../../dao/EmpleadorMaestroDao.php';
// Ojo Establecimientos del Empleador Maestro
require_once '../../dao/EmpleadorDestaqueYourSelfDao.php';

/*
 * Empleador Destaque Controller 
 * Busqueda de Establecimiento por Defecto 
 */
require_once '../../controller/EmpleadorDestaqueController.php';
require_once '../../dao/EmpleadorDestaqueDao.php';
require_once '../../dao/EmpleadorDao.php';


//------------------------------------------------------------------------------

require_once '../../model/PersonaTercero.php';
require_once '../../dao/PersonaTerceroDao.php';
require_once '../../controller/PersonaTerceroController.php';

// sub 01 - Destaque Periodo
require_once '../../model/PeriodoDestaque.php';
require_once '../../dao/PeriodoDestaqueDao.php';
require_once '../../controller/PeriodoDestaqueController.php';//Controller

// sub 02 - Lugar Destaque
require_once '../../model/LugarDestaque.php';
require_once '../../dao/LugarDestaqueDao.php';
require_once '../../controller/LugarDestaqueController.php';

// sub 03 - Cobertura Destaque
require_once '../../model/CoberturaSalud.php';
require_once '../../dao/CoberturaSaludDao.php';
require_once '../../controller/CoberturaSaludController.php';

//-----------------------------------------------------------------------------
//############################################################################
/**
 * ******** BUSQUEDA 01 EDIT = TRA-bajador por ID importante
 */
$ID_PERSONA = $_REQUEST['id_persona'];
// ---- Busqueda Trabajador
$objPT = new PersonaTercero();
//-- funcion Controlador Trabajador
$objPT = buscarPTerceroPorIdPersona($ID_PERSONA);

//--- sub 01 Periodo Destaque
$objPTDetalle_1 = new PeriodoDestaque();
$objPTDetalle_1 = buscarPeriodoDestaquePtercero( $objPT->getId_personal_tercero() );

//-- sub 02 Lugar Destaque
$objPTDetalle_2 = new LugarDestaque();
$objPTDetalle_2 = buscarLugarDestaquePtercero($objPT->getId_personal_tercero());

//-- sub 03 Cobertura Salud
$objPTDetalle_3 = new CoberturaSalud();
$objPTDetalle_3 = buscarCoberturaSaludPtercero($objPT->getId_personal_tercero());



//echo "<pre>";
//var_dump($objPT);
//echo "</pre>";


?>
   <!--  
  	------------------------------------------- CATEGORIAS --------------------------------------------
    -->
<?php

/*
* Listado De Empleador Destaques () y empleador maestro
* IMPORTANTE ALERT = usa Recurso SESSION
*/

// ## (01)-> Listar Empleadores
$lista_empleador_destaque_yourself = listarEmpleadorDestaqueYourself();

// ## (02) 
//$id_empleador_select = buscarID_EMP_EmpleadorDestaquePorTrabajador( $objTRADetalle_3->getId_trabajador(), $objTRADetalle_3->getId_detalle_establecimiento() );

//combo 03
$lista_establecimientos = listarEstablecimientoDestaque($id_empleador=null);
//############################################################################

$COD_LOCAL = 0;
$arreglo3 = array();		  
foreach ($lista_establecimientos as $indice) {	
	$arreglo3 = preg_split("/[|]/",$indice['id']);	
	//	$arreglo[0 - 2] =  // id_establecimiento
	if( $arreglo3[0] == $objPTDetalle_2->getId_establecimiento()){
		$COD_LOCAL = $arreglo3[2];
		break; 		
	}
}


//echo "<pre>";
//print_r($lista_empleador_destaque_yourself);
//echo "</pre>";
?>
<div id="categoria-tabs-5">
          <p>05
            <br>
        Información Complementaria '' <span class="style2" dojoattachpoint="containerNode" unselectable="on">Personal   de Terceros</span> '' </p>
          
<form action="validar"  method="post" name="form_persona_de_terceros" id="form_persona_de_terceros">
          <p>id_personal_tercero
            <input type="text" name="id_personal_tercero" id="id_personal_tercero" 
          value="<?php echo $objPT->getId_personal_tercero(); ?>" />
            <br />
oper
<label for="oper"></label>
<input type="text" name="oper" id="oper" value="edit" />
          </p>
          <table width="750
		  " border="1">
            <tr>
              <td colspan="2"><label> RUC de la empresa que destaca o desplaza</label> 
              <!--onchange="cargarEstablecimientoLocalesYourself(this)"--></td>
              <td colspan="2">
              <select name="cbo_empleadores_destaques_yourself" id="cbo_empleadores_destaques_yourself" 
              style="width:150px;">
                <option value="0">-</option>
                <?php
			  
foreach ($lista_empleador_destaque_yourself as $indice) {

 if($indice['id_empleador_destaque_yoursef'] == $objPT->getId_empleador_destaque_yoursef()  ){		
		$html = '<option value="'. $indice['id_empleador_destaque_yoursef'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	} else {
		$html = '<option value="'. $indice['id_empleador_destaque_yoursef'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
}

?>
              </select></td>
              <td width="2">&nbsp;</td>
              <td width="90">&nbsp;</td>
              <td width="87"><em>codigo</em></td>
              <td width="160"><em>local</em></td>
              <td width="44">&nbsp;</td>
            </tr>
            <tr>
              <td width="78">&nbsp;</td>
              <td width="84"><em>Fecha Inicio</em> </td>
              <td width="102"><em>Fecha Fin</em> </td>
              <td width="45">&nbsp;</td>
              <td class="style1">&nbsp;</td>
              <td> Lugar de destaque o 
                <label for="id_lugar_destaque"></label>
              desplace: 
              <input name="id_lugar_destaque" type="text" id="id_lugar_destaque"
               value="<?php echo $objPTDetalle_2->getId_lugar_destaque(); ?>"
               size="2" /></td>
              <td><input name="txt_codigo_local3" type="text" id="txt_codigo_local3" size="6"
              value="<?php echo $COD_LOCAL; ?>"
               /></td>
              <td>
              <select name="cbo_establecimiento_local_yourself" id="cbo_establecimiento_local_yourself" style="width:160px;"
              onchange="seleccionarLocalDinamico3(this)" >
                    <option value="0">-</option>
<?php			  
foreach ($lista_establecimientos as $indice) {
	$arreglo3 = preg_split("/[|]/",$indice['id']);
 if(/*$indice['id']*/ $arreglo3[0] ==  $objPTDetalle_2->getId_establecimiento() ){	 		
		$html = '<option value="'. $indice['id'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';	
	} else {		
		$html = '<option value="'. $indice['id'] .'" >' . $indice['descripcion'] . '</option>';		
	}
	echo $html;
}

?>

              
              </select>
              <br />
              <input type="text" name="txt_id_establecimiento3" id="txt_id_establecimiento3" 
              value="<?php echo $objPTDetalle_2->getId_establecimiento(); ?>" 
               /></td>
              <td><a href="#">detalle</a></td>
            </tr>
            <tr>
              <td><label>periodos de destaque</label> <label for="id_periodo_destaque"></label>
              <input name="id_periodo_destaque" type="text" id="id_periodo_destaque" 
              value="<?php echo $objPTDetalle_1->getId_periodo_destaque(); ?>" size="2" 
              
               /></td>
              <td><input name="txt_pdestaque_finicio_base" type="text" id="txt_pdestaque_finicio_base" 
              value="<?php  echo getFechaPatron ($objPTDetalle_1->getFecha_inicio(), "d/m/Y"); ?>" size="14" 
              
               ></td>
              <td><input name="txt_pdestaque_ffin_base" type="text" id="txt_pdestaque_ffin_base"
              value="<?php echo getFechaPatron ($objPTDetalle_1->getFecha_fin(), "d/m/Y"); ?>" size="14" 
              
               ></td>
              <td><a href="#">detalle</a></td>
              <td class="style1">&nbsp;</td>
              <td><label> Cobertura Pensión: </label></td>
              <td><input name="rbtn_cobertura_pension" type="radio" value="onp" 
              <?php              
			  echo ($objPT->getCobertura_pension()=="onp") ? ' checked="checked"' : '';
			  ?>
              >
                ONP</td>
              <td><input name="rbtn_cobertura_pension" type="radio" value="seguro-privado"
              <?php              
			  echo ($objPT->getCobertura_pension()=="seguro-privado") ? ' checked="checked"' : '';
			  ?>
                >
              Seguro Privado </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><p>&nbsp;</p>              </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td class="style1">&nbsp;</td>
              <td> <label>Cobertura Salud: </label>
                <label for="id_cobertura_salud"></label>
              <input name="id_cobertura_salud" type="text" id="id_cobertura_salud"
               value="<?php echo $objPTDetalle_3->getId_cobertura_salud(); ?>"
                size="2" /></td>
              <td><input name="rbtn_cobertura_salud" type="radio" value="essalud"  
              <?php  echo ($objPTDetalle_3->getNombre_cobertura() == "essalud") ? ' checked="checked"' : ''?> 
              >
EsSalud</td>
              <td><input name="rbtn_cobertura_salud" type="radio" value="eps" 
              <?php  echo ($objPTDetalle_3->getNombre_cobertura() == "eps") ? ' checked="checked"' : ''?> 
               >
              EPS </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><label>situacion</label></td>
              <td><input name="txt_situacion3" type="text" id="txt_situacion3" size="14" readonly="readonly"
              value="<?php echo $objPT->getCod_situacion(); ?>"
               ></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td class="style1">&nbsp;</td>
              <td>&nbsp;</td>
              <td><em>Fecha de inicio <br>
              dd/mm/aaaa</em></td>
              <td><em>Fecha de Fin <br>
                dd/mm/aaaa</em></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td class="style1">&nbsp;</td>
              <td>&nbsp;</td>
              <td><input name="txt_csalud_finicio_base" type="text" id="txt_csalud_finicio_base"
               value="<?php echo getFechaPatron ($objPTDetalle_3->getFecha_inicio(), "d/m/Y" ); ?>"
                size="14"></td>
              <td><input name="txt_csalud_ffin_base" type="text" id="txt_csalud_ffin_base" 
              value="<?php echo getFechaPatron($objPTDetalle_3->getFecha_fin() , "d/m/Y");?>"
               size="14"></td>
              <td><a href="#">detalle</a></td>
            </tr>
          </table>
          <br />
    <input name="btn_aceptar" type="button" id="btn_aceptar" value="Aceptar(Validar)" 
          onclick="validarFormularioPersonaDeTerceros()" />
</form>          
          <p>&nbsp;</p>
</div>
