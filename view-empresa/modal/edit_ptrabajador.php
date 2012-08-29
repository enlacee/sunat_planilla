<?php
require_once('../../util/funciones.php');
require_once('../../dao/AbstractDao.php');

require_once('../../model/Ptrabajador.php');
require_once('../../dao/PtrabajadorDao.php');
require_once('../../controller/PlameTrabajadorController.php');

//---
require_once('../../dao/TrabajadorPdeclaracionDao.php');

$_ID_TRABAJADOR = $_REQUEST['id_trabajador'];

$ID_PTRABAJADOR = existeID_TrabajadorPoPtrabajador($_ID_TRABAJADOR);

$obj_ptrabajador = buscar_ID_Ptrabajador($ID_PTRABAJADOR);
/*
echo"<pre>";
print_r($obj_ptrabajador);
echo "</pre>";
*/
// Busca Datos Segun Caracteristicas 
// - TIPO DE REGIMEN DE SALUD
// - Tipo de Regimen Pensionario
$dao = new TrabajadorPdeclaracionDao();
$DATA_TRA = $dao->buscar_ID_trabajador($_ID_TRABAJADOR);


?>
<script type="text/javascript">

verDetallePTF();

	function verDetallePTF(){ //obj esta Sobrando
	
	
		var frm = document.frmPtrabajador;
		
		var bandera = false;
		var counteo = frm.rbtn_ptf.length;
			
		for(var i=0;i<counteo;i++){
			if(frm.rbtn_ptf[i].value == 1 && frm.rbtn_ptf[i].checked){			
				bandera = true;
				//alert ("sss"+frm.rbtn_remype[i].value);
			}
		}//ENDFOR
		
		var remype_true = document.getElementById('tipo_ptf');
		//var hijo_tipo_empresa = frm.rbtn_remype_tipo_empresa;
		console.log (bandera);
		if(bandera==true){
			remype_true.style.display='block';
			//hijo_tipo_empresa[0].checked=true;
		}else{
			remype_true.style.display='none';
			//For No Nedd
			//hijo_tipo_empresa[0].checked = null;
			//hijo_tipo_empresa[1].checked = null;
		}
	
	
	//---------------------------------------------------------------
		/*var remype_true = document.getElementById('tipo_ptf');
		var hijo_tipo_empresa = document.frmPtrabajador.rbtn_tipo_ptf;
		//console.dir(hijo_tipo_empresa);
		if(obj.value==1){
			remype_true.style.display='block';
			//hijo_tipo_empresa[0].checked=true;
		}else{
			remype_true.style.display='none';
			//For No Nedd
			hijo_tipo_empresa[0].checked = null;
			hijo_tipo_empresa[1].checked = null;
		}*/

	
	}

</script>
<h2>Otras condiciones del Trabajador</h2>
<form action="" method="post" name="frmPtrabajador" id="frmPtrabajador">
<p class="ocultar">
id_ptrabajador
  <input name="id_ptrabajador"  id="id_ptrabajador" type="text"  
value="<?php echo $obj_ptrabajador->getId_ptrabajador();?>" />
  <br />
  oper 
  <input name="oper" type="text" id="oper" value="edit" />
</p>
<h2 class="">Configuracion Sueldo</h2>
Adelanto %
<label for="adelanto"></label>
<input type="text" name="adelanto" id="adelanto" 
value="<?php echo $obj_ptrabajador->getAdelanto(); ?>" />
<h2 >Configuracion Empresa</h2>
<p>Asignacion familiar: 
  <input type="radio" name="rbtn_afamiliar" id="" value="1"
 <?php echo ($obj_ptrabajador->getAsignacion_familiar()=="1") ? ' checked="checked"': ''; ?>         
         />
  SI
  <input type="radio" name="rbtn_afamiliar" id="" value="0"
<?php echo ($obj_ptrabajador->getAsignacion_familiar()=="0") ? ' checked="checked"': ''; ?>           
         />
  NO</p>
<p> Seguro &quot;para ti familia&quot;: 
  <input type="radio" name="rbtn_ptf" id="" value="1" onclick="verDetallePTF()"
  <?php echo ($obj_ptrabajador->getPara_ti_familia()=="1") ? ' checked="checked"': ''; ?>
         />
  SI	
  <input type="radio" name="rbtn_ptf" id="" value="0" onclick="verDetallePTF()"
  <?php echo ($obj_ptrabajador->getPara_ti_familia()=="0") ? ' checked="checked"': ''; ?>         
         />
  NO</p>
<div id="tipo_ptf">
  <p>tipo:
    <input type="radio" name="rbtn_tipo_ptf" id="rbtn_tipo_ptf" value="1"
  <?php echo ($obj_ptrabajador->getPara_ti_familia_op()=="1") ? ' checked="checked"': ''; ?>           
           />
    Personal
    <input type="radio" name="rbtn_tipo_ptf" id="rbtn_tipo_ptf2" value="2"
<?php echo ($obj_ptrabajador->getPara_ti_familia_op()=="2") ? ' checked="checked"': ''; ?>            
           />
    Familiar </p>
</div>

<h2 >Configuracion Trabajador</h2>
<p>
  <label for="rbtn_apension">Aporta a Es Salud + Vida</label>
  <input name="rbtn_essaludvida" type="radio" value="1"
<?php 
echo ($obj_ptrabajador->getAporta_essalud_vida() == 1) ? ' checked="checked"' : '';
?> />
  SI
  <input name="rbtn_essaludvida" type="radio" value="0" 
<?php 
echo ($obj_ptrabajador->getAporta_essalud_vida() == 0) ? ' checked="checked"' : '';
?>

 />
  NO</p>
<p>
  <label for="rbtn_apension">Aporta a Asegura tu Pensi&oacute;n</label>
  <input name="rbtn_apension" type="radio" value="1" 
<?php 
if($DATA_TRA['cod_regimen_pensionario']=='02'){ //DIFERENTE DE ONP

echo ($obj_ptrabajador->getAporta_asegura_tu_pension()== 1)? ' checked="checked"' : '';

}else{
echo ' disabled="disabled"'; 
}
?>
  />
  SI
  <input name="rbtn_apension" type="radio" value="0" 
<?php 
if($DATA_TRA['cod_regimen_pensionario']=='02'){ //DIFERENTE DE ONP

echo ($obj_ptrabajador->getAporta_asegura_tu_pension()==0)? ' checked="checked"' : '';

}else{
echo ' disabled="disabled"'; 
echo ' checked="checked"';
}
?>


/>
  NO</p>
<div class="ocultar">
  <p><b>DATOS TRIBUTARIOS</b></p>
  <p>
    <label for="rbtn_pt_domiciliado">Condici&oacute;n de <strong>domicilio</strong> seg&uacute;n impuesto a la renta</label>
    <input name="rbtn_pt_domiciliado" type="radio" value="1" 
<?php echo ($obj_ptrabajador->getDomiciliado()==1) ? ' checked="checked"' : ''; ?> />
    SI
    <input name="rbtn_pt_domiciliado" type="radio" value="0" 
<?php echo ($obj_ptrabajador->getDomiciliado()==0) ? ' checked="checked"' : ''; ?>        />
    NO </p>
</div>
</form>
