// JavaScript Document
    // GRID 2
    function cargarTablaMod_1(){

        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/EmpresaModulo_01Controller.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['ID','Categoria','Ttipo_doc','Numero Doc','Apellido Paterno',
                'Apellido Materno','Nombres','Fecha Nacimiento','Sexo','Estado'
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
                    name:'categoria',
                    index:'categoria',
                    search:false, 
                    editable:false,
                    width:70, 
                    align:'center' 
                },
                {
                    name:'nombre_tipo_documento', 
                    index:'nombre_tipo_documento',
                    search:false,
                    editable:false,
                    width:90,
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
                    name:'fecha_nacimiento',
                    index:'fecha_nacimiento',
                    editable:true,
                    width: 90, 
                    align:'center'
                },
                {
                    name:'sexo',
                    index:'sexo',
                    editable:true,
                    search:false,
                    width:40, 
                    align:'center'
                },
                {
                    name:'estado',
                    index:'estado',
                    editable:true,
                    search:false,
                    width:50, 
                    align:'center'
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
            pager: '#pager',
			heigth:'200px',
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_trabajador',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            caption: 'Trabajadores Activos',
            toolbar: [true,"top"],
            //multiselect: true,
            hiddengrid: false,

			
        });
		
		
        //--- PIE GRID
	//jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

	
    }


//-------------------------------------------------------------------

    function cargarTablaPdeclaracionEmpresa(){ 
		//var d = new Date();
		//var n = d.getFullYear(); 
		var anio = document.getElementById('anio').value;

        $("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PlameDeclaracionController.php?oper=cargar_tabla_empresa&anio='+anio,
            datatype: 'json',
            colNames:['Id','Periodo','Add','Edit','SUNAT','SUNAT'],
            colModel :[
                {
                    name:'id_pdeclaracion', 
                    editable:false, 
                    index:'id_pdeclaracion',
                    search:false,
                    width:20,
                    align:'center'
                },		
                {
                    name:'periodo',
                    index:'periodo',
                    search:false, 
                    editable:false,
                    width:70, 
                    align:'center' 
                },
                {
                    name:'add', 
                    index:'add',
                    search:false,
                    editable:false,
                    width:100,
                    align:'center'
                },
                {
                    name:'edit', 
                    index:'edit',
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'sunatAdd', 
                    index:'sunatAdd',
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'sunatEdit', 
                    index:'sunatEdit',
                    editable:false,
                    width:90,
                    align:'center'
                }

            ],
            pager: '#pager',
			height:'250px',
            //width:'800px',
            //autowidth: true,
            rowNum:12,
            rowList:[12,24,36],
            sortname: 'id_pdeclaracion',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            caption: 'Lista de Periodos',
           // toolbar: [true,"top"],
            //multiselect: true,
            hiddengrid: false
	

        });
        //--- PIE GRID
  jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
	//add
	//----
/*
var input = '<select name="anio" id="anio" onchange="cargarTablaPdeclaracionEmpresa()">';
	input+= '<option value="2011">2011</option>';
	input+= '<option value="2012">2012</option>';
    input+= '</select>';
		
		$("#t_list").append(input);
		$("input","#t_list").click(function(){
		
		});
*/



  

}
