<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
//---------------------------------

function generarArchivosTregistro(){
	
		var estado = true;//grabar();
		if(estado){ alert("ajax true");			
			//-----------------------------------------------------------------------				
				var from_data =  $("#form-generarArchivos").serialize();
				$.getJSON('sunat_planilla/controller/Estructura_01TrabajadorController.php?oper=t-registro&'+from_data,
					function(data){
						if(data){
							alert ('Se Genero Correctamente Los Archivos.');
							//cargar_pagina('sunat_planilla/view/view_empleador.php','#CapaContenedorFormulario');
						}else{
							alert("Ocurrio un error");
						}
					}); 
			//-----------------------------------------------------------------------
		}else{
			alert ("ajax false ");		
		}
		return true;
		
}

</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">PARA LA IMPORTACIÓN EN T-REGISTRO</a></li>
            
        </ul>
      <div id="tabs-1">
<form name= "form-generarArchivos" id="form-generarArchivos" >
<table width="831" border="1">
<thead>
  <tr>
    <td width="3">&nbsp;</td>
    <td width="703"><strong>ESTRUCTURAS</strong></td>
    <td width="35">&nbsp;</td>
    <td width="62">&nbsp;</td>
  </tr>
</thead>
  <tr>
    <td>&nbsp;</td>
    <td>Establecimientos Propios del Empleador". Identifique establecimientos de riesgo SCTR.</td>
    <td><a href="sunat_planilla/controller/Estructura-01Controller.php?oper=1">1.esp</a></td>
    <td><input type="checkbox" name="t-registro[]" id="t-registro-1" value="1" />
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Empleadores a quienes destaco o desplazo personal.</td>
    <td><a href="#">2.edd.ldd</a></td>
    <td><input name="t-registro[]" type="checkbox" id="t-registro-2" value="2" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Empleadores que me destacan o desplazan personal.</td>
    <td><a href="#">3.med</a></td>
    <td><input name="t-registro[]" type="checkbox" id="t-registro-3" value="3" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Datos personales del trabajador, pensionista, personal en formación - modalidad formativa laboral y otros y personal de terceros.</td>
    <td><a href="#">4.ide</a></td>
    <td><input name="t-registro[]" type="checkbox" id="t-registro-4" value="4" checked="checked" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Datos del trabajador.</td>
    <td><a href="#">5.tra</a></td>
    <td><input name="t-registro[]" type="checkbox" id="t-registro-5" value="5" checked="checked" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Datos del pensionista.</td>
    <td><a href="#">6.pen</a></td>
    <td><input name="t-registro" type="checkbox" id="t-registro-6" value="6" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Datos del personal en formación -  modalidad formativa laboral y otros.</td>
    <td><a href="#">9.pfl</a></td>
    <td><input name="t-registro[]" type="checkbox" id="t-registro-7" value="9" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Datos del personal de terceros.</td>
    <td><a href="#">10.ter</a></td>
    <td><input name="t-registro[]" type="checkbox" id="t-registro-8" value="10" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Períodos.</td>
    <td><a href="#">11.per</a></td>
    <td><input name="t-registro[]" type="checkbox" id="t-registro-9" value="11a" />
    Alta 
      <br />
      <input name="t-registro[]" type="checkbox" id="t-registro-14" value="11b" />
      Baja</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Datos de los establecimientos donde labora el trabajador.</td>
    <td><a href="#">17.est</a></td>
    <td><input name="t-registro[]" type="checkbox" id="t-registro-10" value="17" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Lugar de formación de Personal en formación - modalidad formativa laboral y otros y de destaque del Personal de Terceros</td>
    <td><a href="#tabs-1">23.lug</a></td>
    <td><input name="t-registro[]" type="checkbox" id="t-registro-11" value="23" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><strong>DE USO EXCLUSIVO EN EL REGISTRO DE DERECHOHABIENTES</strong></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Datos de derechohabientes - ALTAS</td>
    <td><a href="#">13</a></td>
    <td><input name="t-registro[]" type="checkbox" id="t-registro-12" value="13" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Baja de derechohabientes - BAJAS</td>
    <td><a href="#">24</a></td>
    <td><input name="t-registro[]" type="checkbox" id="t-registro-13" value="24" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="button" name="btn-generar" id="btn-generar" value="Generar" 
    onclick="generarArchivosTregistro()" /></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
        
        
      </div>
                
</div>

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
 
?>
