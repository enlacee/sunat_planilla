<?php
require_once '../util/funciones.php';

require_once '../dao/AbstractDao.php';
require_once '../dao/PrestamoDao.php';
require_once '../controller/PrestamoController.php';


//$_REQUEST['id_prestamo'];
//$_REQUEST['id_trabajador'];

$data = buscar_IdPrestamo($_REQUEST['id_prestamo']);


?>
<script type="text/javascript">


    $(document).ready(function(){
		$( "#tabs").tabs();
		
//-----------------------		
/*		$("#fecha_inicio").datepicker({ 
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm/yy',
			//minDate: 0,
			//maxDate: "+11M +0D"
			
		});
*/


	});
	
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Ver Detalle  Prestamo</a></li>			

        </ul>
        <div id="tabs-1">
        <form id="FrmPrestamo" name="FrmPrestamo" method="post" action="">
          
          <p>
          <div class="ocultar">
              id_prestamo
                <input type="text" name="id" id="id" 
              value="<?php echo $data['id_prestamo'];?>" />
            <br />
            id_trabajador
<input type="text" name="id_trabajador" id="id_trabajador" 
              value="<?php echo $data['id_trabajador'];?>" />
            <br />
            oper
            <label for="oper"></label>
            <input type="text" name="oper" id="oper" value="add" />
          </div>  
            <br />
            Num Doc:
<input name="dni" type="dni" id="textfield" size="12" value="<?php echo $data['num_documento']; ?>" 
readonly="readonly">
                
            <input name="nombre" type="text" id="nombre" size="40" readonly="readonly" 
            value="<?php echo $data['apellido_paterno'].' '.$data['apellido_materno'].' '.$data['nombres']?>" >
            <br />
            Monto
            
            <input name="valor" type="text" id="valor" value="<?php echo $data['valor']; ?>" readonly="readonly" />
            <br />
            Numero Cuotas
            
          <input type="text" name="num_cuota" id="num_cuota"
          readonly="readonly"
            value="<?php echo $data['num_cuota']; ?>" />
            <br />
            fecha inicio del Prestamo
            
            <input name="fecha_inicio" type="text" id="fecha_inicio" readonly="readonly" 
            value="<?php echo getFechaPatron($data['fecha_inicio'], "m/Y"); ?>" />
            </p>(mm/aaaa)

              <p>&nbsp;</p>
              <h3>Historial de Pagos:</h3>
              <table width="380" border="1" class="tabla_gris">
                <tr>
                  <td width="11">id</td>
                  <td width="128">fecha calculado</td>
                  <td width="75">fecha pago</td>
                  <td width="66">valor</td>
                  <td width="66">estado</td>
                </tr>
                
                <?php 
				$fecha_variable = $data['fecha_inicio'];
				
				for($i=0; $i<$data['num_cuota'];$i++):?>
                <tr>                
                  <td>				  </td>
                  <td>
				  <?php
				  $fecha_variablee = crearFecha($fecha_variable, $day = 0, $month = (0+$i), $year = 0);
				  echo getFechaPatron($fecha_variablee,'m/Y');
				  ?>                  </td>
                  <td>&nbsp;</td>
                  <td><?php echo number_format($data['valor']/$data['num_cuota'],2); ?></td>
                  <td>&nbsp;</td>
                </tr>
                <?php endfor;?>
</table>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
        </form>       
        </div>
</div>

</div>