<?php
require_once('../../dao/AbstractDao.php');
require_once('../../controller/ideController2.php');
require_once('../../dao/EmpleadorDao.php');

require_once('../../controller/EmpleadorDestaqueController.php');

//require_once('../');
require_once('../../controller/EmpresaCentroCostoController.php');
require_once('../../dao/EmpresaCentroCostoDao.php');

//EmpleadorDao


$estableciento = listarEstablecimientoDestaque($id_empleador);

//echo "<pre>";
//print_r($estableciento);
//echo "</pre>";

//$CCosto = listarCentroCosto($objTRA->getId_establecimiento(),"all");


?>

<script type="text/javascript">

function X2(oCombo){ console.log("usando ....");
	console.log("combo x padre");

	
	var aguja = oCombo.value;

	var partes = aguja.split("|");	
	
	var id_establecimiento = partes[0];	
	var codigo_establecimiento = partes[2];


	//cargarEstablecimientoLocalesCCostoX(id_establecimiento );
	
var objComboox= document.getElementById('cboCentroCosto');

	//-----

	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'sunat_planilla/controller/EmpresaCentroCostoController.php',
		data: {id_establecimiento: id_establecimiento, oper: 'lista_centrocosto'},		
		success: function(json){			
			//console.log(json);
			if(json == null || json.length<1 ){
				var mensaje = "No Existen Establecimientos Registrados\n";
				mensaje += "Registe los establecimientos correspondientes para el Empleador";
				
				//limpiarComboGlobal(objCombo);
				objComboox.disabled =true;
				alert(mensaje);	
			}else{
				console.log("entro ah llenar combo  x2222");
				
				objComboox.disabled =false;
				llenarComboDinamico(json,objComboox);
			}
		}
	});
	//-----	




	
}

</script>
<h2>Planilla Mensual:</h2>
<div class="ocultar">
  <p>
    <label for="id_pdeclaracion"></label>
    id_pdeclaracion
    <input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
	value="<?php echo $_REQUEST['id_pdeclaracion']; ?>" />
    <br />
    <label for="id_etapa_pago"></label>
    id_etapa_pago
    <input type="text" name="id_etapa_pago" id="id_etapa_pago" 
    value="<?php echo $_REQUEST['id_etapa_pago']; ?>"
     />
  </p>
</div>
<p>
  Establecimientos
  <select name="id_establecimientos" id="id_establecimientos" style="width:180px;" 
  onchange="X2(this)"  >
    <option value="0" selected="selected">-</option>
 <?php for($i=0;$i<count($estableciento);$i++):
	
	echo '<option value="'.$estableciento[$i]['id'].'">'.$estableciento[$i]['descripcion'].'</option>';
  
 endfor;
 ?>
  
  </select>
</p>
<p>Centro de Costo 
  <select name="cboCentroCosto" id="cboCentroCosto" style="width:180px;">
  <option value="0" selected="selected">-</option>
 <?php for($i=0;$i<count($CCosto);$i++):
	
	echo '<option value="'.$CCosto[$i]['id_empresa_centro_costo'].'">'.$estableciento[$i]['descripcion'].'</option>';
  
 endfor;
 ?>
  
  
  
  </select>
</p>
