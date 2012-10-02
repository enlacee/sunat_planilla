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
		
		//$( "#fv_programado" )datepicker.({ minDate: -20, maxDate: "+1M +10D" });
		$("#fv_programado").datepicker({ 
			changeMonth: true,
			changeYear: true,
			minDate: <?php echo "-".$dia_calc; ?>
			//maxDate: "+11M +0D"
			
		});
		
		/*
		* Siempre Toleracia 11 meses . OK
		*/
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

<table width="450" border="1" class="tabla_gris">
  <thead>
    <tr>
      <th width="14">#</th>
      <th width="177">F. Calculado(12 m)</th>
      <th width="141">F. Programada</th>
      <th width="90">Estado</th>
    </tr>
  </thead>
  <tfoot>
  </tfoot>
  <tbody>
  </tbody>
</table>
<p><strong> Proxima Vacacion:</strong></p>

<table width="450" border="1" class="tabla_gris">
  <tfoot>
  </tfoot>
  <tbody>
    <tr>
<td width="8">#</td>
      <td width="197"><input name="fv_calculado" type="text" id="fv_calculado" size="15" 
      value="<?php echo getFechaPatron($data_fecha['fecha_calc'],"d/m/Y");?>"  readonly="readonly" /></td>
      <td width="131"><input name="fv_programado" type="text" id="fv_programado" size="15"
       <?php echo  ($estado == 'anio mayor') ? ' disabled="disabled"' : ''; ?>
       />
        <!--<input type="checkbox" name="chk_estado" id="chk_estado" onclick="cambiarCheck(this)"  />-->
      <label for="chk_estado"></label></td>
      <td width="86">
      <input type="button" name="btnAprovar" id="btnAprovar" value="Aprobar"
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
<p><strong>Historial:</strong></p>
<table width="450" border="1" class="tabla_gris">
  <?php for($i=0;$i<count($data);$i++): ?>
  <tr>
    <td width="23"><input name="id_vacacion" type="text" id="id_vacacion" size="4"
    value="<?php echo $data[$i]['id_vacacion'];?>"
     />
      #</td>
    <td width="182"><input name="fecha_i_pasada" type="text" id="fecha_i_pasada"
    value="<?php echo getFechaPatron($data[$i]['fecha'],"d/m/Y"); ?>" size="15" readonly="readonly"
     /></td>
    <td width="131"><input name="fecha_f_pasada" type="text" id="fecha_f_pasada" 
    value="<?php echo getFechaPatron($data[$i]['fecha_programada'],"d/m/Y");?>" size="15" readonly="readonly" /></td>
    <td width="86">
    <span title="Editar" class="ocultar">
		<a class="divEditar" 
        href="javascript:editarVacacion()">
        </a>
	</span>
    <span title="Eliminar" >
		<a class="divEliminar" 
        href="javascript:eliminarVacacion(<?php echo $data[$i]['id_vacacion'];?>)">
        </a>
	</span>


    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php endfor; ?>
</table>
<p></p>
<p>. </p>
<table id="list">
</table>
<div id="pager"></div>
