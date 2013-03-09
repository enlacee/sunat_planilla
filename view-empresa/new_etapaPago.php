<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
require_once("../util/funciones.php");
require_once("../dao/ComboCategoriaDao.php");
require_once("../controller/ComboCategoriaController.php");

//$data = comboPeriodoRemuneracion();
$cod_periodo_remuneracion = $_REQUEST['cod_periodo_remuneracion'];
//var_dump($periodoR);

$periodo = $_REQUEST['periodo'];
$ID_DECLARACION = $_REQUEST['id_declaracion'];

$mes = getFechaPatron($periodo,"m");
$anio = getFechaPatron($periodo,"Y");
$option4 = '';
if($mes == '07'|| $mes=='12'):
	$option4 = '<option value="4">- Gratificacion-</option>';
endif;

?>
<script type="text/javascript">
// console.log('id_pdeclaracion = '+id_pdeclaracion);
// console.log('periodo = '+periodo);

$(document).ready(function(){              
    $( "#tabs").tabs();


});	
	
//--------------------------------------------------


// Funcion lista:
// -quincena
// -mensual
function adelanteEtapa01(){	
	
	var cod_periodo_remuneracion = document.getElementById('cboPeriodoRemunerativo').value;
	cod_periodo_remuneracion = parseInt(cod_periodo_remuneracion);
	var periodo = document.getElementById('periodo').value;			
	var id_declaracion = document.getElementById('id_declaracion').value;

	if(cod_periodo_remuneracion==2 || cod_periodo_remuneracion==1){
        var url = "sunat_planilla/view-empresa/new_etapaPago2.php";
        url+="?periodo="+periodo+"&cod_periodo_remuneracion="+cod_periodo_remuneracion+"&id_declaracion="+id_declaracion;            
        cargar_pagina(url,'#CapaContenedorFormulario');
	}else if(cod_periodo_remuneracion==3 ){
        var url = "sunat_planilla/view-empresa/view_pvacaciones.php";
        url+="?periodo="+periodo+"&cod_periodo_remuneracion="+cod_periodo_remuneracion+"&id_declaracion="+id_declaracion;            
        cargar_pagina(url,'#CapaContenedorFormulario');
	}else if(cod_periodo_remuneracion==4){ // gratificacion
		var url = "sunat_planilla/view-empresa/view_gratificacion.php";
		url+="?periodo="+periodo+"&cod_periodo_remuneracion="+cod_periodo_remuneracion+"&id_declaracion="+id_declaracion;            
		cargar_pagina(url,'#CapaContenedorFormulario');		
	
	}else{
		alert("La opcion que selecciono, no es valido.");
	}
}
</script>
<div class="demo" align="left">
    <div id="tabs">
<div class="ocultar">
id_declaracion
<input name="id_declaracion" id="id_declaracion" type="text" value="<?php echo $ID_DECLARACION; ?>">
<br />
periodo
<input type="text" name="periodo" id="periodo" value="<?php echo $periodo; ?>" />
</div>
      <ul>
            <li><a href="#tabs-1">Etapa Declaracion</a></li>
      </ul>
        <div id="tabs-1">
          <h2>Declaracion  <?php echo $mes ."/". $anio;?></h2>
          <p>
          <br>
          </p>
          <label>
            Tipo de Operacion
          </label>
              <select name="cboPeriodoRemunerativo" id="cboPeriodoRemunerativo">
              <option value="0">- Seleccione -</option>	
              <option value="2">- Primera Quincena -</option>
              <option value="1">- Mensual -</option>
              <option value="3">- Vacaciones-</option>
              <?php echo $option4;?>
      <?php 
/*
foreach ($data as $indice) {
	 if( $indice['cod_periodo_remuneracion'] == $cod_periodo_remuneracion){
		$html = '<option value="'. $indice['cod_periodo_remuneracion'] .'" selected="selected" >' . $indice['descripcion'] . '</option>';
	}else {
		$html = '<option value="'. $indice['cod_periodo_remuneracion'] .'" >' . $indice['descripcion'] . '</option>';
	}
	echo $html;
} 
*/
?>
            </select>
          <p>
            <input type="button" name="btnAtras"  value="&lt;&lt; Atras" disabled="disabled"
            onclick="cargar_pagina('sunat_planilla/view-empresa/view_etapaPago2.php','#CapaContenedorFormulario')" />
            <input type="button" name="btnAdelante" id="btnAdelante" value="SIGUIENTE &gt;&gt;"            
            onclick="adelanteEtapa01()" />
            </p>
</p>
</div>


</div>

</div>