<?php
//*******************************************************************//
require_once('../../view/ide2.php');
//*******************************************************************//
//require_once('../../dao/AbstractDao.php');
//--------------combo CATEGORIAS
require_once('../../util/funciones.php');

//ACTUALIZAR
require_once '../../model/PjornadaLaboral.php';
require_once '../../dao/PjoranadaLaboralDao.php';
require_once '../../controller/PlameJornadaLaboralController.php';

$id_ptrabajador = $_REQUEST['id_ptrabajador'];

$PjornadaLaboral = new PjornadaLaboral();
$PjornadaLaboral = buscarPjornadaLaboral_IdPtrabajdor($id_ptrabajador);

//echo "<pre> $id_ptrabajador";
//echo print_r($PjornadaLaboral);
//echo "</pre>";

?>
<div class="ptrabajador">

<div class="section">
	<div class="article fila1">
    <div class="ocultar">
    id_pjornada_laboral:<input name="id_pjornada_laboral" id="id_pjornada_laboral" 
    type="text" readonly="readonly" value="<?php echo $PjornadaLaboral->getId_pjornada_laboral(); ?>" />
    </div>
	  <h3>Dias de Jornada</h3>
        <hr />
	  <p>
	    <label for="dia_laborado">Laborados</label>
	    <input name="dia_laborado" type="text" id="dia_laborado" size="4" readonly="readonly"
        value="<?php echo $PjornadaLaboral->getDia_laborado(); ?>" />
       
        
        
	  </p>
	  <p>
	    <label for="dia_subsidiado">Subsidiados</label>
	    <input name="dia_subsidiado" type="text" id="dia_subsidiado" 
        value="<?php echo $PjornadaLaboral->getDia_subsidiado();?>" size="4" readonly="readonly" />
       <span>
       <a href="javascript:editarDiaSubcidiado( '<?php echo $PjornadaLaboral->getId_pjornada_laboral(); ?>')">
       <img src="images/edit.png"></a></span>
	  </p>
	  <p>
	    <label for="dia_nosubsidiado">No laborados y no subsidiados:</label>
	    <input name="dia_nosubsidiado" type="text" id="dia_nosubsidiado" 
        value="<?php echo $PjornadaLaboral->getDia_nosubsidiado(); ?>" size="4" readonly="readonly" />
         <span >
         <a href='javascript:editarDiaNoLaborado("<?php echo $PjornadaLaboral->getId_pjornada_laboral();?>")'>
         <img src="images/edit.png"></a></span>
	  </p>
      <h3>TOTAL: 
        <label for="dia_total"></label>
        <input name="dia_total" type="text" id="dia_total" size="4" readonly="readonly"
         value="<?php  echo $PjornadaLaboral->getDia_total(); ?>" />
        </h3>
      <p>&nbsp;</p>
	</div>
	<div class="article fila2">
			<h3>Horas Laboradas</h3>
              <hr />
		<p>        
          <label for="hora_ordinaria_hh">Ordinarias (HHHH:MM)</label>
          <input name="hora_ordinaria_hh" type="text" id="hora_ordinaria_hh" size="5" />
          :
          <input name="hora_ordinaria_mm" type="text" id="hora_ordinaria_mm" size="5" />
		</p>
	  <p>
	    <label for="hora_sobretiempo_hh">Sobretiempo(HHH:MM)</label>
		  <input name="hora_sobretiempo_hh" type="text" id="hora_sobretiempo_hh" size="5" />
	    :
	    <input name="hora_sobretiempo_mm" type="text" id="hora_sobretiempo_mm" size="5" />
		</p>
			<h3>TOTAL HORAS:
			  <label for="total_hora_hh"></label>
			  <input name="total_hora_hh" type="text" id="total_hora_hh" size="5" readonly="readonly" />
			:
			<label for="total_hora_mm"></label>
			<input name="total_hora_mm" type="text" id="total_hora_mm" size="5" readonly="readonly" />
			</h3>
	  <p>&nbsp;</p>
	  </div>
	</div>
    
    
</div>   



<!---  DIALOG -->
<div id="dialog-dia-subsidiado">

<div id="editarDiaSubsidiado"> </div>

</div> 


<!---  DIALOG -->
<div id="dialog-dia-noLaborado">

<div id="editarDiaNoLaborado"> </div>

</div> 
