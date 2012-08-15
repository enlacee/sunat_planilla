<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
?>



<!--<link rel="stylesheet" href="sunat_planilla/view/ui/themes/ui-lightness/jquery.ui.all.css">-->

<!--<link rel="stylesheet" type="text/css" media="screen" href="sunat_planilla/view/css/ui.jqgrid.css" />

<link rel="stylesheet" href="sunat_planilla/view/css/ui.jqgrid.css">
<link rel="stylesheet" href="sunat_planilla/view/css/ui-lightness/jquery-ui-1.8.20.custom.css">

-->
<!--<script src="sunat_planilla/view/js/jquery-1.7_min.js" type="text/javascript"></script>-->

<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		//cargarTablaLiquidaciones();
		cargarTablaPdeclaracionEmpresa();
		

		
	});
	
//-----------------------------------------------------------------


function edit_15(){}

function add_15(id_declaracion,periodo,num15){ //QUINCENAL
   alert("15");
   $.ajax({
   type: "POST",
   url: "sunat_planilla/controller/AdelantoController.php",
   data: {id_declaracion : id_declaracion, periodo : periodo ,num15: num15 ,oper : 'add_15'},
   async:true,
   success: function(datos){
	//alert("datos ::? "+datos);

   }
   }); 
   
   
}
	

	
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Lista de  Periodos</a></li>			

        </ul>
        <div id="tabs-1">
            <input type="button" name="button" id="button" value="Nuevo Periodo"
            onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/new_periodo.php','#CapaContenedorFormulario')" />
            
            <h2>lista de periodos JQGRID
              <label for="anio"></label>
              <select name="anio" id="anio" onchange="cargarTablaPdeclaracionEmpresa()">
                <option value="2011">2011</option>
                <option value="2012" selected="selected">2012</option>
              </select>
            </h2>
          
            <table id="list">
            </table>
            <div id="pager"></div>
            
            
            
            <table id="list10_d">
            </table>
            <div id="list10_d"></div>
            

        
        </div>
</div>

</div>

















