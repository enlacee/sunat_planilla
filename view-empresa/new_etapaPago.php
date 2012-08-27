<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
require_once("../util/funciones.php");


require_once("../dao/ComboCategoriaDao.php");

require_once("../controller/ComboCategoriaController.php");

$data = comboPeriodoRemuneracion();
$cod_periodo_remuneracion = $_REQUEST['cod_periodo_remuneracion'];
//var_dump($periodoR);

$periodo = $_REQUEST['periodo'];
$ID_DECLARACION = $_REQUEST['id_declaracion'];

$mes = getFechaPatron($periodo,"m");
$anio = getFechaPatron($periodo,"Y");

?>

<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
//--------------------------------------------------

function adelanteEtapa01(){
	var cod_periodo_remuneracion = document.getElementById('cboPeriodoRemunerativo').value;
	cod_periodo_remuneracion = parseInt(cod_periodo_remuneracion);
	var periodo = document.getElementById('periodo').value;		
	
	var id_declaracion = document.getElementById('id_declaracion').value;
	
	var url = "sunat_planilla/view-empresa/new_etapaPago2.php";
	url+="?periodo="+periodo+"&cod_periodo_remuneracion="+cod_periodo_remuneracion+"&id_declaracion="+id_declaracion;
	
	if(cod_periodo_remuneracion==2){		
		cargar_pagina(url,'#CapaContenedorFormulario');
		
	}else if(cod_periodo_remuneracion==1){ //MENSUAL
	
	cargar_pagina('sunat_planilla/view-empresa/edit_declaracion.php?id_pdeclaracion='+id_declaracion,'#CapaContenedorFormulario')
		//cargar_pagina(url,'#CapaContenedorFormulario');
		
	}else{
		alert("No se permite el adelanto "+ cod_periodo_remuneracion);
	}
	
}
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Etapa Declaracion</a></li>			

        </ul>
        <div id="tabs-1">
          <h2>01 Declaracion</h2>
          <p>
          <div class="ocultar">
          id_declaracion
          <input name="id_declaracion" id="id_declaracion" type="text" value="<?php echo $ID_DECLARACION; ?>">
          <br />
          periodo
          <input type="text" name="periodo" id="periodo" value="<?php echo $periodo; ?>" />
          </div>
          <br>
Periodo o Declaracion 
<input name="mes" type="text" id="mes" value="<?php echo $mes;?>" size="4" readonly="readonly">
            <input name="anio" type="text" id="anio" value="<?php echo $anio;?>" size="7" readonly="readonly" >
          </p>
          <h2>02 Seleccione:</h2>
          <h2>
            Operacion
              <select name="cboPeriodoRemunerativo" id="cboPeriodoRemunerativo">
              <option value="2">- Primera Quincena -</option>
              <option value="1">- Mensual -</option>
      
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
          </h2>
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