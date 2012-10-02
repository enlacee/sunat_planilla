<?php
require_once '../../dao/AbstractDao.php';
require_once '../../dao/ComboCategoriaDao.php';
require_once '../../controller/ComboCategoriaController.php';

//datos
require_once '../../model/DiaSubsidiado.php';
require_once '../../dao/DiaSubsidiadoDao.php';
require_once '../../controller/DiaSubsidiadoController.php';

$suspencion1 = comboSuspensionLaboral_1();
$id_pago = $_REQUEST['id_tpd'];

//DATOS
$data = buscarDiaSPor_IdTrabajadorPdeclaracion($id_pago);
/*
echo "<pre>";
print_r($data);
echo "</pre>";*/
?>

<script type="text/javascript">
//FUNCION INICIO
//calcDiaSubsidiado();    

</script>
<form action="" method="get" id="formDiaSubsidiado" name="formDiaSubsidiado">

    <div class="" style="width:450px;" >
<div class="ocultar">oper
        <input name="oper" type="text" value="dual" />
        <br />
        id_pago
        <label for="id_pago"></label>
        <input type="text" name="id_pago" id="" value="<?php echo $id_pago; ?>" />
</div>
<table width="394" border="1" id="tb_dsubsidiado" class="tabla_gris">
            <tr>
                <td width="217">tipo desuspens&oacute;n</td>
                <td width="81">cantidad de dias</td>
<td width="74">Eliminar</td>
            </tr>





            <?php
            $SELECCIONADO = array();
            $ID = 0;
            for ($i = 0; $i < count($data); $i++):
                $ID++;
                ?>
                <!-- -->


                <tr id="dia_subsidiado-<?php echo $ID;?>">
                    <td>
                        <input size="4" id="pdia_subsidiado-<?php echo $ID; ?>" name="pdia_subsidiado[]" 
                               value="<?php echo $data[$i]->getId_dia_subsidiado(); ?>" 
                               type="hidden"> 
                        <input size="4" id="estado-<?php echo $ID; ?>" name="estado[]" 
                               value="<?php echo ($data[$i]->getId_dia_subsidiado()) ? 1 : 0; ?>"
                               type="hidden">
                               
                             <input type="hidden" name="txt_cbo_ds_tipo_suspension[]" 
                             value="<?php echo $data[$i]->getCod_tipo_suspen_relacion_laboral(); ?>"/>  
                             
                        <select name="cbo_ds_tipo_suspension[]" id="cbo_ds_tipo_suspension-<?php echo $ID; ?>" 
                        style="width:150px;" onchange=""   >
                            <?php
                            foreach ($suspencion1 as $indice) {
             

                                if ($indice['cod_tipo_suspen_relacion_laboral'] == $data[$i]->getCod_tipo_suspen_relacion_laboral()) {                              
                                    $html = '<option value="' . $indice['cod_tipo_suspen_relacion_laboral'] . '" selected="selected" >' . $indice['cod_tipo_suspen_relacion_laboral'] . " - " . $indice['descripcion_abreviada'] . '</option>';
                                } else {
                                    $html = '<option value="' . $indice['cod_tipo_suspen_relacion_laboral'] . '" >' . $indice['cod_tipo_suspen_relacion_laboral'] . " - " . $indice['descripcion_abreviada'] . '</option>';
                                }
                                
                                
                                echo $html;
                            }
                            ?>
                        </select>                    </td>
                    <td>
                        <input name="ds_cantidad_dia[]" id="ds_cantidad_dia-<?php echo $ID; ?>" size="7" onblur="calcDiaSubsidiado()" type="text"
                               value="<?php echo $data[$i]->getCantidad_dia(); ?>" >                    </td>
<td>
            <span title="editar">
                <a href="javascript:eliminar_ds_0( 'dia_subsidiado-<?php echo $ID; ?>',<?php echo $data[$i]->getId_dia_subsidiado(); ?> )">
                    <img src="images/cancelar.png"></a></span>          </td>
                </tr>

                <!-- -->

            <?php endfor; ?>
        </table>
    </div>
    <br>
    <div style="width:150px; margin:0 0 0 174px;">
        <label for="ds_total">TOTAL</label>
        <input name="ds_total" type="text" id="ds_total" size="7" readonly="readonly">
    </div>
    <p>
        <input type="button" name="btnGrabar" id="btnGrabar" value="Grabar" onclick="grabarDiaSubsidiado()" />
        <input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onclick="validarNuevoInput()" />
        <!--<input type="button" name="demo" id="demo" value="Button"  onclick="getIdsCombos()"/>-->
    </p>
</form>