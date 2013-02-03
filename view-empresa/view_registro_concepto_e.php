<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
?>
<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
	cargarTablaConceptosRPCE();

    function cargarTablaConceptosRPCE(){
		var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
		var periodo = document.getElementById('periodo').value;
		var parametro = 'id_pdeclaracion='+id_pdeclaracion+'&periodo='+periodo;
        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/Concepto_E_EmpleadorController.php?oper=cargar_tabla&'+parametro,
            datatype: 'json',
            colNames:['Id','Codigo','Concepto','Opciones'],
            colModel :[
                {
                    name:'id_concepto_e_empleador', 
                    editable:false, 
					hidden:true,
                    index:'id_concepto_e_empleador',
                    search:false,
                    width:30,
                    align:'center'
                },		
                {
                    name:'id_concepto_e',
                    index:'id_concepto_e',
                    search:true, 
                    editable:false,
                    width:70, 
                    align:'center' 
                },
                {
                    name:'descripcion', 
                    index:'descripcion',
                    search:false,
                    editable:false,
                    width:500,
                    align:'center'
                },
                {
                    name:'opciones',
                    index:'opciones',
                    search:false,
                    editable:false,
                    width:100,
                    align:'center'
                }							
            ],
            pager: '#pager',
			height:350,
            rowNum:15,
            rowList:[15,30,45],
            sortname: 'id_concepto_e_empleador',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //caption: 'Trabajadores Activos',
            //toolbar: [true,"top"],
            //multiselect: true,
            hiddengrid: false,
        });
        //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
    }
</script>
<div class="demo" align="left"> 
<div class="ocultar">
  id_pdeclaracion
  <input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
  value="<?php echo $_REQUEST['id_declaracion']; ?>"/><br />
  periodo
  <input type="text" name="periodo" id="periodo"
  value="<?php echo $_REQUEST['periodo']; ?>" />
  </div>  
  
  
<div id="tabs">
      <ul>
            <li><a href="#tabs-1">Registros por Conceptos</a></li>			
    </ul>
        <div id="tabs-1">   
    <!-- Boton cancelar-->
<input type="button" onclick="javascript:cargar_pagina('sunat_planilla/view-empresa/view_periodo.php?id_pdeclaracion=<?php echo $_REQUEST['id_declaracion']; ?>','#CapaContenedorFormulario')" class="submit-cancelar" value="Cancelar" name="Retornar ">           
<br /><br />

<table id="list">
</table>
<br />

<div id="pager"></div>        
        
    </div>
</div>

</div>