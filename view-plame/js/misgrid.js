// JavaScript Document

//-----------------------------------------------------------------------------------------
    // GRID 2
    function cargarTablaTrabajadoresPorPeriodo(periodo){
		
        $("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PlameController.php?oper=trabajador_por_periodo&periodo='+periodo,
            datatype: 'json',
            colNames:['ID','Tipo_doc','Numero Doc','APaterno',
                'AMaterno', 'Nombres',
				'F inicio','F fin','Opciones'],
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
                    name:'cod_tipo_documento',
                    index:'cod_tipo_documento',
                    search:false, 
                    editable:false,
                    width:70, 
                    align:'center' 
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    search:false,
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    editable:false,
                    width:80,
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
                    editable:false,
                    width:90,
                    align:'center'
                },
				{
                    name:'fecha_inicio', 
                    index:'fecha_inicio',
                    editable:false,
                    width:90,
                    align:'center'
                },
				 {
                    name:'fecha_fin', 
                    index:'fecha_fin',
                    editable:false,
                    width:90,
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
			heigth:'250px',
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_trabajador',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //caption: 'Trabajadores Activos',
            toolbar: [true,"top"],
            //multiselect: true,
            hiddengrid: false,

			
        });
		
		
        //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

	
    }
	
	
	
    function cargarTablaPTrabajadores(periodo){ alert("cargarTablaPTrabajadores periodo "+periodo);
		
        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PlameTrabajadorController.php?oper=cargar_tabla&periodo='+periodo,
            datatype: 'json',
            colNames:['ID','Tipo_doc','Numero Doc','APaterno',
                'AMaterno', 'Nombres','dias L.','Editar','Est'],
            colModel :[
                {
                    name:'id_ptrabajador', 
                    editable:false, 
                    index:'id_ptrabajador',
                    search:false,
                    width:20,
                    align:'center'
                },		
                {
                    name:'cod_tipo_documento',
                    index:'cod_tipo_documento',
                    search:false, 
                    editable:false,
                    width:70, 
                    align:'center' 
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    search:false,
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    editable:false,
                    width:80,
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
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'dia_laborado',
                    index:'dia_laborado',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                },
				
                {
                    name:'opciones',
                    index:'opciones',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                },
                {
                    name:'estado',
                    index:'estado',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                }											


            ],
            pager: '#pager',
			heigth:'250px',
            rowNum:9,
            rowList:[9,18,36],
            sortname: 'id_ptrabajador',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //caption: 'Trabajadores Activos',
            toolbar: [true,"top"],
            //multiselect: true,
            hiddengrid: false,

			
        });
		
		
        //--- PIE GRID
//	jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

	
    }





//------------------------------------------------------------


function crearDialogoPTperiodoLaboral(){
//alert('crearDialogoPersonaDireccion');
	$("#dialog-pt-periodo-laboral").dialog({ 
           
			autoOpen: false,
			height: 170,
			width: 250,
			modal: true,                        
			/*buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {	
				}
                                
			},*/			
			open: function() {},
			close: function() {}
	});
}


//----------
function editarPTperiodoLaboral(id_ptrabajador){ 
	crearDialogoPTperiodoLaboral();

    $.ajax({
   type: "POST",
   url: "sunat_planilla/view-plame/modal/periodo_laboral.php",
   data: {id_ptrabajador : id_ptrabajador},
   async:true,
   success: function(datos){
    $('#editarPTperiodoLaboral').html(datos);
    
    $('#dialog-pt-periodo-laboral').dialog('open');
   }
   }); 
}








//----------------------------------------------------
//----------------------------------------------------
//funciuones

//-------------------------------
function registrarDeclaracion(){ alert("..");

var data = $("#formPago").serialize();
var id_declaracion = document.getElementById('id_declaracion').value;
var declaracionRectificadora = document.getElementById('rbtn_declaracionRectificadora').value;
var periodo = document.getElementById('txt_periodo_tributario').value;

   $.ajax({
   type: "POST",
   url: "sunat_planilla/controller/PlameDeclaracionController.php",
	   data: {oper : 'add-data-ptrabajadores',
	   id_declaracion : id_declaracion,
	   declaracionRectificadora: declaracionRectificadora,
	   periodo : periodo
   },//Enviando a ediatarProducto.php vareiable=id_producto
   async:true,
   success: function(datos){
	
	
	if(datos){
		alert("Se guardo Correctamente los datos");
	}else{
		alert("Error");
	}
	
	
   }
   }); 



}

