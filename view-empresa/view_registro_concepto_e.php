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

        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/Concepto_E_EmpleadorController.php?oper=cargar_tabla',
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
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registros por Conceptos</a></li>			

        </ul>
        <div id="tabs-1">
        
Lista de conceptos utilizados        
<table id="list">
</table>
<div id="pager"></div>        
        
        </div>
</div>

</div>