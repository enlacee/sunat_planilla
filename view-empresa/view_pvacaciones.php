<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
		//$("#fv_inicio").timepicker();
		
		
		cargarTablaPVacaciones();
		
	});
	
	//----------------- jqgrid
	
	
    // GRID 2
    function cargarTablaPVacaciones(){
		console.log("cargarTablaPVacaciones");

        //$("#list-2").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/VacacionController.php?oper=cargar_tabla_trabajador&estado=1',
            datatype: 'json',
            colNames:['id','Ttipo_doc','Numero Doc','Apellido Paterno',
                'Apellido Materno','Nombres','F Inicio','Fecha Vacacion'
                ,'Opciones'],
            colModel :[
                {
                    name:'id_trabajador', 
                    editable:false, 
                    index:'id_trabajador',
                    search:false,
                    width:20,
                    align:'center'
                },		
                {
                    name:'nombre_tipo_documento', 
                    index:'nombre_tipo_documento',
                    search:false,
                    editable:false,
                    width:50,
                    align:'center'
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    editable:false,
                    width:80,
                    align:'center'
                },
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'nombres', 
                    index:'nombres',
                    editable:true,
                    width:90,
                    align:'center'
                },
				{
                    name:'fecha_inicio',
                    index:'fecha_inicio',
					hidden :false,
                    editable:true,
                    search:false,
                    width:100, 
                    align:'center'
                },
				
                {
                    name:'fecha_vacacion_proxima',
                    index:'fecha_vacacion_proxima',
                    editable:true,
                    search:false,
                    width:100, 
                    align:'center',
					formatter:'date'
                },
                {
                    name:'opciones',
                    index:'opciones',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                }							


            ],
            pager: '#pager-2',
            //autowidth: true,
			//width: '',
			height:380,
            rowNum:10,
            rowList:[15,30,45],
            sortname: 'id_trabajador',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //caption: 'Lista de Trabajadores Activos',
            //toolbar: [true,"top"],
            //multiselect: true,
            hiddengrid: false,
            //onSelectRow: function(rowid, selected) {}
			//---
			gridComplete    : function(){  //alert("grid okD");
		
				var ids = $("#list").getDataIDs();
				console.log("-ids-");
				console.dir(ids);
				console.log("-ids-");
				var act;
				var name_space = '';
				for(var i=0;i<ids.length;i++){
					//Obteniendo Fila X Fila
					var data = $("#list").getRowData(ids[i]);
					if (data.nombre_ubigeo_reniec == "" && data.estado_direccion == "Primera") {
						name_space = data.num_documento+"  "+data.apellido_paterno+" "+data.apellido_materno+" "+data.nombres;
						//act =' <b class="red">Debe Ingresar La Primera Direccion es Obligatorio!. </b>';
						//$("#list").setRowData(ids[i],{nombre_ubigeo_reniec: act });
					}
				}//ENDFOR
			}			
			
			//---
			
			
			
			
        	});
		
		
        //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

	
    }
	
	
	
	
	//----
	
	
	
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Vacaciones calculadas</a></li>			

        </ul>
        <div id="tabs-1">
          <h2>Lista de Trabajadores con vacaciones proximas</h2>
          <table id="list">
          </table>
<div id="pager"></div>
<p></p>
<p>&nbsp;</p> 





          
          
        </div>
</div>

</div>






<!-- DIALOG -->

<div id="dialog_view_vacacion" title="Vacaciones">
    <div id="view_vacacion" align="left"></div>
</div>








