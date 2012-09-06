<?php
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
*/
?>
<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
	cargarTablaConceptosRPC();
	
	
    function cargarTablaConceptosRPC(){

        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PlameConceptoController.php?oper=cargar_registro_por_concepto',
            datatype: 'json',
            colNames:['Id','Codigo','Concepto','Opciones'],
            colModel :[
                {
                    name:'id_detalle_concepto_empleador_maestro', 
                    editable:false, 
					hidden:true,
                    index:'id_detalle_concepto_empleador_maestro',
                    search:false,
                    width:30,
                    align:'center'
                },		
                {
                    name:'cod_detalle_concepto',
                    index:'cod_detalle_concepto',
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
            rowNum:25,
            rowList:[25,50,75],
            sortname: 'id_detalle_concepto_empleador_maestro',
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