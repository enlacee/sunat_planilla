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
		/*$("#fv_programado").datepicker({ 
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm/yy',
			minDate: <?php echo "-".$dia_calc; ?>
		});		
		* Siempre Toleracia 11 meses . OK
		*/
	 });
		 
		 function cambiarCheck(obj){		 	
			//alert estadoCheckGlobal(obj);			
		 }
		 
		 
<?php 
//$data_fecha['fecha_calc'] = "2011-02-05";
 $date_anio = getFechaPatron($data_fecha['fecha_calc'],"Y");
 $date_mes  = getFechaPatron($data_fecha['fecha_calc'],"m");
 $date_mes_js = $date_mes - 1;
 $date_dia  = getFechaPatron($data_fecha['fecha_calc'],"d");
 
 $date_inicio = $date_anio.','.$date_mes_js.','.$date_dia;
 
 $fecha_final =  crearFecha($data_fecha['fecha_calc'],0,11,0);

 //echoo($date_fin);
 
 $date_anio_f = getFechaPatron($fecha_final,'Y');
 $date_mes_f = getFechaPatron($fecha_final,'m');
 $date_mes_f_js = $date_mes_f - 1;
 $date_dia_f = getFechaPatron($fecha_final,'d');
 
 $date_fin = $date_anio_f.','.$date_mes_f_js.','.$date_dia_f;
 
 
?>
//alert("fin es : <?php echo $date_inicio; ?>"); 
			//.calendar
		 $( "#fv_programado" ).datepicker({
			changeMonth: true,
			changeYear: true, 
		 	firstDay:0,
			dateFormat: 'dd/mm/yy',			
			numberOfMonths: 2, //16/08/2012
			minDate: new Date(<?php echo $date_inicio; ?>),
			maxDate: new Date(<?php echo $date_fin; ?>),
			showOn: 'both',
			buttonText: 'Selecciona una fecha',
			buttonImageOnly:true,
			buttonImage: 'images/calendar.gif',
			//minDate: new Date(2010, 10, 1),
			//maxDate: new Date(2010, 10, 15), 
/*			showOn: 'button',
			buttonText: "Seleccionar",			
			
			yearRange: '2010:2012',*/
		 
		 });

		 
		 
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

<table width="500" border="1" class="tabla_gris">
  <thead>
    <tr>
      <th width="16">#</th>
      <th width="197">F. Calculado(12 m)</th>
      <th width="194">F. Programada</th>
      <th width="65">Estado</th>
    </tr>
  </thead>
  <tfoot>
  </tfoot>
  <tbody>
  </tbody>
</table>
<p><strong> Proxima Vacacion:</strong></p>

<form id="FrmVacacion" name="FrmVacacion" method="post" action="">

<table width="500" border="1" class="tabla_gris">
  <tfoot>
  </tfoot>
  <tbody>
    <tr>
<td width="9">#</td>
      <td width="204"><input name="fv_calculado" type="text" id="fv_calculado" size="15" 
      value="<?php echo getFechaPatron($data_fecha['fecha_calc'],"d/m/Y");?>"  readonly="readonly" /></td>
      <td width="188"><input name="fv_programado" type="text" id="fv_programado" size="15" 
       <?php echo  ($estado == 'anio mayor') ? ' readonly="readonly"' : ''; ?>
       />
        <!--<input type="checkbox" name="chk_estado" id="chk_estado" onclick="cambiarCheck(this)"  />-->
      <label for="chk_estado"><br />
        <input type="radio" name="tipo_vacacion" value="1" checked="checked" />
        mes[30]
        <input type="radio" name="tipo_vacacion" value="2" />
        dias[15]     </label></td>
      <td width="71">
      <input type="button" name="btnAprovar" id="btnAprovar" value="Aprobar"
      class "red"
      onclick="guardarVacacionProgramada()" <?php echo  ($estado == 'anio mayor') ? ' disabled="disabled"' : ''; ?> /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="hidden" name="textfield" id="textfield"  class="calendar" /></td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>
</form>


<p><strong>Historial:</strong></p>
<table width="500" border="1" class="tabla_gris">
  <?php for($i=0;$i<count($data);$i++): ?>
  <tr>
    <td width="27"><input name="id_vacacion" type="hidden" id="id_vacacion" size="4"
    value="<?php echo $data[$i]['id_vacacion'];?>"
     />
      #</td>
    <td width="183"><input name="fecha_i_pasada" type="text" id="fecha_i_pasada"
    value="<?php echo getFechaPatron($data[$i]['fecha'],"d/m/Y"); ?>" size="15" readonly="readonly"
     /></td>
    <td width="189"> inicio: <span class="blue">[<?php echo getFechaPatron($data[$i]['fecha_programada'],"d/m/Y");?>]</span>
      <br />
       final:    
      [<?php echo getFechaPatron($data[$i]['fecha_programada_fin'],"d/m/Y");?>]<br />
    tipo : 
    [<?php echo ($data[$i]['tipo_vacacion'] == 1)? 'Mes 30' : 'Dias 15'; ?></td>
    <td width="73">
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
    <td><label for="textfield"></label></td>
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
