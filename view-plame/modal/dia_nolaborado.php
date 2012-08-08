<?php
/*
 * 	 ALERT()::
 * 	Primero debe Ejecutars Dia Subsidiado 
 * 	para tener las varibales defaul bajo control	
 *
 */

require_once '../../dao/AbstractDao.php';
require_once '../../dao/ComboCategoriaDao.php';
require_once '../../controller/ComboCategoriaController.php';

//datos
require_once '../../controller/PlameDiaNoSubsidiadoController.php';
require_once '../../model/PdiaNoSubsidiado.php';
require_once '../../dao/PdiaNoSubsidiadoDao.php';


$suspencion2 = comboSuspensionLaboral_2();
$id_pjoranada_laboral = $_REQUEST['id_pjoranada_laboral'];

//DATOS
$data = buscarDiaNoSPor_IdPjornadaLaboral($id_pjoranada_laboral);

//echo "<pre>";
//print_r($data);
//echo "</pre>";


?>


<script type="text/javascript">
//--
calcDiaNoSubsidiado();
</script>



<form action="" method="get" name="formDiaNoSubsidiado" id="formDiaNoSubsidiado">

    oper
    <input name="oper" type="text" value="dual" />
    <br />
    id_pjoranada_laboral
    <label for="id_pjoranada_laboral"></label>
    <input type="text" name="id_pjornada_laboral" id="" value="<?php echo $id_pjoranada_laboral; ?>" />
    <table width="450" border="1" id="tb_dnolaborado">
        <tr>
            <td width="217">tipo desuspens&oacute;n</td>
            <td width="81">cantidad de dias</td>
            <td width="88">Modificar</td>
            <td width="74">Eliminar</td>
        </tr>



        <?php       
        $ID = 0;
        for ($i = 0; $i < count($data); $i++):
        $ID++;
        ?>

       
        <tr id="dia_nosubsidiado-<?php echo $ID;?>">

            <td>
                <input size="4" id="id_pdia_nosubsidiado-1" name="id_pdia_nosubsidiado[]" value="" type="hidden">
                <input size="4" id="estado-1" name="estado[]" value="0" type="hidden">
                <select name="cbo_dns_tipo_suspension[]" id="cbo_dns_tipo_suspension-1" style="width:150px;" onchange=""> 
                    <?php
                    foreach ($suspencion2 as $indice) {


                    if ($indice['cod_tipo_suspen_relacion_laboral'] == $data[$i]->getCod_tipo_suspen_relacion_laboral()) {
                    $html = '<option value="' . $indice['cod_tipo_suspen_relacion_laboral'] . '" selected="selected" >' . $indice['cod_tipo_suspen_relacion_laboral'] . " - " . $indice['descripcion_abreviada'] . '</option>';
                    } else {
                    $html = '<option value="' . $indice['cod_tipo_suspen_relacion_laboral'] . '" >' . $indice['cod_tipo_suspen_relacion_laboral'] . " - " . $indice['descripcion_abreviada'] . '</option>';
                    }


                    echo $html;
                    }
                    ?>


                </select>

            </td>
            <td>
                <input name="dns_cantidad_dia[]" id="dns_cantidad_dia-1" size="7" type="text"
                value="<?php echo $data[$i]->getCantidad_dia();?>"/>
            </td>
            <td>
                <span title="editar"><a href="javascript:editar_dns('1')"><img src="images/edit.png"></a></span>
            </td>
            <td>
                <span title="editar">
                    <a href="javascript:eliminar_dns('dia_nosubsidiado-1')">
                        <img src="images/cancelar.png"/></a></span>
            </td>

        </tr>
         


        <?php endfor; ?>










    </table>
    <br>
    <div style="width:150px; margin:0 0 0 174px;">
      <label for="dns_total">TOTAL</label>
        <input name="dns_total" type="text" id="dns_total" size="7" readonly="readonly">
    </div>
    <p>
        <input type="button" name="btnGrabar" id="btnGrabar" value="Grabar" onclick="grabarDiaNoSubsidiado()" />
        <input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onclick="validarNuevoInput_2()" />
    </p>
</form>
