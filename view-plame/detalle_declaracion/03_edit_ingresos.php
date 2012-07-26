<?php
//*******************************************************************//
require_once '../../view/ide2.php';
//*******************************************************************//
//require_once('../../util/funciones.php');
require_once('../../dao/AbstractDao.php');
//require_once('../../controller/ideController2.php');

require_once('../../model/Dcem_Pingreso.php');
require_once('../../dao/Dcem_PingresoDao.php');
require_once('../../controller/PlameDcem_PingresoController.php');


$ID_PTRABAJADOR = $_REQUEST['id_ptrabajador'];

$datas = new Dcem_Pingreso();
//$data_cantidad = cantidadDetalleConceptoEM( $cod_concepto, ID_EMPLEADOR_MAESTRO );
$pingreso = array();
$pingreso = listarDcem_Pingreso($ID_PTRABAJADOR);

//echo "<pre>";
//print_r($pingreso);
//echo "</pre>";
?>


<div class="ptrabajador">
    <h3>Ingresos:  </h3>
    <hr />
    <table width="670" border="1">
        <tr>
            <td width="10">&nbsp;</td>
            <td width="55">C&oacute;digo</td>
            <td width="202">Concepto</td>
            <td width="96">Devengado(S/.)</td>
            <td width="134">Pagado(S/.)</td>
        </tr>



        <?php
        if (count($pingreso) >= 1):

            for ($i = 0; $i < count($pingreso); $i++):
                ?>  



                <tr>
                    <td> <?php echo $pingreso[$i]['id_dcem_pingreso']; ?></td>
                    <td><label for="pt_codigo"></label>
                        <input name="pt_codigo" type="text" id="pt_codigo" size="5" 
                               value="<?php echo $pingreso[$i]['cod_detalle_concepto']; //echo $pingreso[$i]['id_ptrabajador'];   ?>"      />

                    </td>
                    <td><?php echo $pingreso[$i]['descripcion']; ?></td>
                    <td><label for="pt_devengado"></label>
                        <input name="pt_devengado" type="text" id="pt_devengado" size="8"
                               value="<?php echo $pingreso[$i]['devengado']; ?>"/>
                    </td>
                    <td><input name="pt_pagado" type="text" id="pt_pagado" size="8"
                               value="<?php echo $pingreso[$i]['pagado']; ?>" />
                    </td>
                </tr>

                <?php
            endfor;
        endif;
        ?>   











    </table>
    <p>&nbsp;</p>



</div>

















<table width="670" border="1">
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>TOTAL INGRESOS:</td>
        <td><label for="pt_total_devengado"></label>
            <input name="pt_total_devengado" type="text" id="pt_total_devengado" value="0.00" size="8" /></td>
        <td><input name="pt_total_pagado" type="text" id="pt_total_pagado" value="0.00" size="8" /></td>
    </tr>
</table>
<p>&nbsp;</p>

