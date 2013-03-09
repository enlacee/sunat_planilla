<?php
/*
 * 	 ALERT()::
 * 	Primero debe Ejecutars Dia Subsidiado 
 * 	para tener las varibales defaul bajo control	
 *
 */
require_once '../../util/funciones.php';
require_once '../../dao/AbstractDao.php';
require_once '../../dao/ComboCategoriaDao.php';
require_once '../../controller/ComboCategoriaController.php';

//datos
require_once '../../controller/DiaNoSubsidiadoController.php';
require_once '../../model/DiaNoSubsidiado.php';
require_once '../../dao/DiaNoSubsidiadoDao.php';


$suspencion2 = comboSuspensionLaboral_2();
$id_pago = $_REQUEST['id_tpd'];
//DATOS
$data = buscarDiaNoSPor_IdTrabajadorPdeclaracion($id_pago);



//echoo($data);



?>



<script type="text/javascript">

//FUNCION INICIO
//calcDiaSubsidiado();  
$(document).ready(function(){
//Obteniendo periodo:
	var periodo = $("#txt_periodo_tributario").val();
	var new_periodo;
	var arreglo;
	var data_mes;

	periodo = periodo.replace("/","-");  	//var arreglo = periodo.split("/");
	new_periodo = '01-'+periodo;
	
	arreglo = new_periodo.split("-");	
	data_mes = new Date( parseInt(arreglo[2]),parseInt(arreglo[1])-1,parseInt(arreglo[0]) );	
		 $( ".f_inicio, .f_fin" ).datepicker({
			changeMonth: true,
			changeYear: true, 
		 	firstDay:0,
			dateFormat: 'dd/mm/yy',
			//dateFormat: 'DD MM yy',			
			numberOfMonths: 1,
			//minDate: new Date(data_mes.getFullYear(),data_mes.getMonth(),data_mes.getDay()),
			//maxDate: new Date(data_mes.getFullYear(),data_mes.getMonth()+1,0),
			
            //showOn: 'both',
			//buttonText: 'Selecciona una fecha',
			//buttonImageOnly:true,
			//buttonImage: 'images/calendar.gif',
		 
		 });
});


//--
calcDiaNoSubsidiado();




</script>



<form action="" method="get" name="formDiaNoSubsidiado" id="formDiaNoSubsidiado">

  <div class="ocultar">oper
    <input name="oper" type="text" value="dual" />
      <br />
    id_tpd
    <input type="text" name="id_pago" id="" value="<?php echo $id_pago; ?>" />
  </div>

<table width="443" border="1" id="tb_dnolaborado" class="tabla_gris">
      <tr>
            <td width="170">tipo desuspens&oacute;n</td>
            <td width="65">cantidad de dias</td>
            <td width="71">fecha inicio</td>
            <td width="55">fecha fin</td>
            <td width="48">Eliminar</td>


        </tr>



        <?php       
        $ID = 0;
        for ($i = 0; $i < count($data); $i++):
        $ID++;
        ?>

       
        <tr id="dia_nosubsidiado-<?php echo $ID;?>">

            <td>
                <input size="4" id="id_pdia_nosubsidiado-<?php echo $ID; ?>" name="id_pdia_nosubsidiado[]" 
                value="<?php echo $data[$i]->getId_dia_nosubsidiado(); ?>" type="hidden">
                <input size="4" id="estado-<?php echo $ID; ?>" name="estado[]"  type="hidden" 
                value="<?php echo ($data[$i]->getId_dia_nosubsidiado()) ? 1 : 0; ?>"/>
                
              <input type="hidden" name="txt_cbo_dns_tipo_suspension[]" 
                 value="<?php echo $data[$i]->getCod_tipo_suspen_relacion_laboral(); ?>"/>  

                
                
          <select name="cbo_dns_tipo_suspension[]" id="cbo_dns_tipo_suspension-<?php echo $ID; ?>" style="width:150px;" onchange=""
<?php  //echo ($data[$i]->getEstado() == '1' ) ? ' disabled="disabled"' : '';?> 
 > 
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
                </select>           </td>
            <td>
                <input name="dns_cantidad_dia[]" id="dns_cantidad_dia-<?php echo $ID; ?>" size="7" type="text"
<?php  echo ($data[$i]->getEstado() == '1' ) ? ' readonly="readonly"' : '';?>                 
                
                value="<?php echo $data[$i]->getCantidad_dia();?>"/>            </td>
            <td>
<input type="text" name="dns_finicio[]" id="dns_finicio-<?php echo $ID; ?>" class="f_inicio" 
value="<?php echo getFechaPatron($data[$i]->getFecha_inicio(),"d/m/Y");?> " size="11" >
            </td>
            
            
            <td>
<input type="text" name="dns_fin[]" id="dns_fin-<?php echo $ID; ?>" class="f_fin" 
size="11" value="<?php echo getFechaPatron($data[$i]->getFecha_fin(),"d/m/Y");?>" >
            </td>
          <td>
            <?php if($data[$i]->getEstado() != '1' ): ?>
            <span title="editar">
            <a href="javascript:eliminar_dns_0('dia_nosubsidiado-<?php echo $ID; ?>',<?php echo $data[$i]->getId_dia_nosubsidiado(); ?>)">
            <img src="images/cancelar.png"/></a>
            </span>
            <?php endif; ?>
              
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
