<?php 
require_once("../../util/funciones.php");
require_once("../../dao/AbstractDao.php");
require_once("../../dao/VacacionDao.php");
require_once("../../dao/DetallePeriodoLaboralDao.php");
require_once ('../../controller/VacacionController.php');



$ID_TRABAJADOR = $_REQUEST['id_trabajador'];

$dao_vacacion = new VacacionDao();
$data = $dao_vacacion->listar($ID_TRABAJADOR);


//echo "id =".$ID_TRABAJADOR;
//var_dump($data);
//echoo($data);

$data_fecha = getFechaVacacionCalc($ID_TRABAJADOR);

$year_1 = getFechaPatron($data_fecha['fecha_calc'],"Y");
$year_2 = date("Y");//2012


if($year_1 > $year_2){
$estado = "anio mayor";
}else{
$estado = "anio menor";
}
//var_dump($estado);


//echo "estado es ";
//echoo($estado);



$now_dia = getDayThatYear(date("Y-m-d"));


//echoo($data_fecha);


$dia =  getDayThatYear($data_fecha['fecha_calc']); // getFechaPatron($fv_calculado,"d");

$dia_calc = $now_dia - $dia;

//echo "now_dia =".$now_dia;
//echo "\ndia = ".$dia;

?>
<script type="text/javascript">
    $(document).ready(function(){
		//$("#fv_calculado").datepicker();
		//$("#fv_programado").datepicker();
		
		//$( "#fv_programado" )datepicker.({ minDate: -20, maxDate: "+1M +10D" });
		$( "#fv_programado" ).datepicker({ minDate: <?php echo "-".$dia_calc; ?>/*, maxDate: "0M +5D"*/ });
		
		//(new Date(2009, 1 - 1, 26))
		
		 });
		 
		 function cambiarCheck(obj){
		 	
			//alert estadoCheckGlobal(obj);
			
		 }
		 
		 
</script>

<div class="ocultar">id_trabajador 
  <input name="id_trabajador" id="id_trabajador"  type="text"
  value="<?php echo $ID_TRABAJADOR;?>" >
  
</div>
<div class="back">
  <p><?php echo strToUpper($_REQUEST['name']); ?></p>
  <p>Fecha de ingreso : <?php echo getFechaPatron($data_fecha['fecha_inicio'],"d/m/Y"); ?></p>
</div>
<br/>

<table width="400" border="1" class="tabla_gris">
  <thead>
    <tr>
      <th width="">#</th>
      <th width="">F. Calculado(12 m)</th>
      <th width="">F. Programada</th>
      <th width="">Estado</th>
    </tr>
  </thead>
  <tfoot>
  </tfoot>
  <tbody>
  </tbody>
</table>
<p><strong>Fecha de Vacacion Proxima...</strong></p>

<table width="400" border="1" class="tabla_gris">
  <tfoot>
  </tfoot>
  <tbody>
    <tr>
      <td width="">&nbsp;</td>
      <td width=""><input name="fv_calculado" type="text" id="fv_calculado" size="15" 
      value="<?php echo getFechaPatron($data_fecha['fecha_calc'],"d/m/Y");?>"  readonly="readonly" /></td>
      <td width=""><input name="fv_programado" type="text" id="fv_programado" size="15"
       <?php echo  ($estado == 'anio mayor') ? ' disabled="disabled"' : ''; ?>
       />
        <!--<input type="checkbox" name="chk_estado" id="chk_estado" onclick="cambiarCheck(this)"  />-->
      <label for="chk_estado"></label></td>
      <td width="">
      <input type="button" name="btnAprovar" id="btnAprovar" value="Aprobar Fecha"
      class "red"
      onclick="guardarVacacionProgramada()" <?php echo  ($estado == 'anio mayor') ? ' disabled="disabled"' : ''; ?> /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
<p><strong>Ver vacaciones pasadas... </strong></p>






<table width="400" border="1" class="tabla_gris">
<?php for($i=0;$i<count($data);$i++): ?>
  <tr>
    <td width="">
    <input name="id_vacacion" type="hidden" id="id_vacacion" size="4"
    value="<?php echo $data[$i]['id_vacacion'];?>"
     /></td>
    <td width="">    
    <input name="fecha_i_pasada" type="text" id="fecha_i_pasada"
    value="<?php echo getFechaPatron($data[$i]['fecha'],"d/m/Y"); ?>" size="15" readonly="readonly"
     /></td>
    <td width=""><input name="fecha_f_pasada" type="text" id="fecha_f_pasada" 
    value="<?php echo getFechaPatron($data[$i]['fecha_programada'],"d/m/Y");?>" size="15" readonly="readonly" /></td>
    <td width="92">&nbsp;</td>
  </tr>
<?php endfor; ?>  
  
</table>






<p></p>
<p>. </p>
<table id="list">
</table>
<div id="pager"></div>
