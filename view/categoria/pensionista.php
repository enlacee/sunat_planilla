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

//---------------trabajador
require_once('../../model/Pensionista.php');
require_once('../../dao/PensionistaDao.php');
require_once('../../controller/CategoriaPensionistaControlller.php');

//--------------- sub detalle_1
require_once('../../model/DetallePeriodoLaboral.php');
require_once('../../model/DetallePeriodoLaboralPensionista.php'); //herencia
require_once('../../dao/DetallePeriodoLaboralPensionistaDao.php');
require_once('../../controller/DetallePeriodoLaboralPensionistaController.php');


//############################################################################
/**
 * ******** BUSQUEDA 01 EDIT = TRA-bajador por ID importante
 */
$ID_PERSONA = $_REQUEST['id_persona'];
// ---- Busqueda Trabajador
$objPEN = new Pensionista();
//-- funcion Controlador Trabajador
$objPEN = buscarPensionistaPorIdPersona($ID_PERSONA);

//--- sub 1 Periodo Laboral
$objPENDetalle_1 = new DetallePeriodoLaboralPensionista();
$objPENDetalle_1 = buscarDetallePeriodoLaboralPensionista( $objPEN->getId_pensionista() );

//echo "<pre>";
//var_dump($objPEN);
//echo "</pre>";

?>
<!--  
     ------------------------------------------- CATEGORIAS --------------------------------------------
 -->
<?php
//combo 01x
$cbo_motivo_baja_registro_cat_pensionista = comboMotivoBajaRegistroCatPensionista();

//combo 02
$cbo_regimen_pensionario = comboRegimenPensionarioCatP();

//combo 03
//$cbo_tipo_pago = comboTipoTrabajadorPorIdTipoEmpleadorCatPensionista($id_tipo_empleador); 
//COMBO 08
$cbo_tipo_pago = comboTipoPago();
?>
<div id="categoria-tabs-3">
    <p>03</p>

    <h2>Información Complementaria '<span class="style2">Form Pensionista</span>' </h2>

    <form action="validar"  method="post" name="form_pensionista" id="form_pensionista">

        <p>
          <label for="id_pensionista"></label>
          id_pensionista
          <input type="text" name="id_pensionista" id="id_pensionista" 
          value="<?php echo $objPEN->getId_pensionista(); ?>" />
          <br />
          oper
          <label for="oper"></label>
          <input type="text" name="oper" id="oper" value="edit" />
        </p>
        <table width="524" height="239" border="1" style="background-color:#F3FDD5">
            <tr>
                <td width="82"><label> Tipo de Trabajador Pensionista: </label></td>
                <td colspan="2"><select name="cbo_tipo_pensionista" id="cbo_tipo_pensionista" style="width:170px;">
                        <option value="0" >-</option>
                        
                        <option value="24" 
						<?php echo ($objPEN->getCod_tipo_trabajador() ==24) ? 'selected="selected"' : ""; ?>
                         >PENSIONISTA O CESANTE</option>
						
                    </select>              </td>
                <td width="204">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td width="106"><em>Fecha de Inicio </em></td>
                <td width="84"><em>Fecha de Fin </em></td>
                <td>Motivo de Fin o suspension </td>
            </tr>
            <tr>
                <td><label>Periodo</label>
                    <label for="id_detalle_periodo_laboral_pensionista"></label>
                    <input name="id_detalle_periodo_laboral_pensionista" type="text" id="id_detalle_periodo_laboral_pensionista" size="1"
                           value="<?php echo $objPENDetalle_1->getId_detalle_periodo_laboral_pensionista(); ?>" /></td>
                <td><input name="txt_plaboral_pensionista_finicio_base" type="text" id="txt_plaboral_pensionista_finicio_base" size="14"
                           value="<?php echo getFechaPatron($objPENDetalle_1->getFecha_inicio(), "d/m/Y" ); ?>" ></td>
                <td><input name="txt_plaboral_pensionista_ffin_base" type="text" id="txt_plaboral_pensionista_ffin_base" size="14" 
                           value="<?php echo getFechaPatron($objPENDetalle_1->getFecha_fin(),"d/m/Y"); ?>"></td>
                <td><select name="cbo_plaboral_pensionista_motivo_baja_base"  id="cbo_plaboral_pensionista_motivo_baja_base" style="width:150px;">
                        <?php
                        foreach ($cbo_motivo_baja_registro_cat_pensionista as $indice) {

                            if ($indice['cod_motivo_baja_registro'] == $objPENDetalle_1->getCod_motivo_baja_registro()) {

                                $html = '<option value="' . $indice['cod_motivo_baja_registro'] . '" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
                            } else {
                                $html = '<option value="' . $indice['cod_motivo_baja_registro'] . '" >' . $indice['descripcion_abreviada'] . '</option>';
                            }
                            echo $html;
                        }
                        ?>			  
                    </select> 
                    <a href="#">detalle</a></td>
            </tr>
            <tr>
                <td><label>Regimen Pensionario </label></td>
                <td colspan="2"><select name="cbo_regimen_pensionario" id="cbo_regimen_pensionario" style="width:170px;" >
                        <?php
                        foreach ($cbo_regimen_pensionario as $indice) {
                            if ($indice['cod_regimen_pensionario'] == $objPEN->getCod_regimen_pensionario()) {
                                $html = '<option value="' . $indice['cod_regimen_pensionario'] . '" selected="selected" >' . $indice['descripcion_abreviada'] . '</option>';
                            } else {
                                $html = '<option value="' . $indice['cod_regimen_pensionario'] . '" >' . $indice['descripcion_abreviada'] . '</option>';
                            }
                            echo $html;
                        }
                        ?>
                    </select>
                  <br /></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
              <td>cuspp</td>
              <td colspan="2"><input type="text" name="txt_CUSPP2" id="txt_CUSPP2" 
              value="<?php echo $objPEN->getCuspp(); ?>" /></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
                <td><label>Tipo de Pago </label></td>
                <td colspan="2"><select name="cbo_tipo_pago" id="cbo_tipo_pago" style="width:150px;" >
                        <?php
                        foreach ($cbo_tipo_pago as $indice) {
                            if ($indice['cod_tipo_pago'] == $objPEN->getCod_tipo_pago()) {
                                $html = '<option value="' . $indice['cod_tipo_pago'] . '" selected="selected" >' . $indice['descripcion'] . '</option>';
                            } else {
                                $html = '<option value="' . $indice['cod_tipo_pago'] . '" >' . $indice['descripcion'] . '</option>';
                            }
                            echo $html;
                        }
                        ?>
                    </select></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><label>Situacion</label></td>
                <td colspan="2"><label for="cbo_situacion"></label>
                    <input name="txt_situacion" type="text" id="txt_situacion" value="<?php echo $objPEN->getCod_situacion(); ?>" readonly=""></td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <p>
            <input name="btn_aceptar" type="button" id="btn_aceptar" value="Aceptar(Validar)" 
                   onclick="validarFormularioPensionista()" />
      </p>

    </form>
    <p>&nbsp;</p>
</div>