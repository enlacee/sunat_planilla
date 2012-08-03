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



//--------------------------
    function cargarTablaPDeclaracion(){ 		
        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PlameDeclaracionController.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['ID','Periodo','Ultima Fecha Actualizaci&oacute;n.','Estado',
                'Modificar', 'Eliminar','Archivo Envio'],
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
                    width:90, 
                    align:'center' 
                },
                {
                    name:'fecha_modificacion', 
                    index:'fecha_modificacion',
                    search:false,
                    editable:false,
                    width:100,
                    align:'center'
                },
                {
                    name:'estado', 
                    index:'estado',
                    editable:false,
                    width:80,
                    align:'center'
                },
                {
                    name:'modificar', 
                    index:'modificar',
                    editable:false,
                    width:90,
                    align:'center'
                },
				
                {
                    name:'archivo',
                    index:'archivo',
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
			heigth:'450px',
            rowNum:12,
            rowList:[12,24,36],
            sortname: 'id_pdeclaracion',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //caption: 'Trabajadores Activos',
            //toolbar: [true,"top"],
            //multiselect: true,
            hiddengrid: false,

			
        });
		
		
        //--- PIE GRID
//	jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

	
    }


///------------------------------------------------------------


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
function editarPTperiodoLaboral(id){ 
	crearDialogoPTperiodoLaboral();

    $.ajax({
   type: "POST",
   url: "sunat_planilla/view-plame/modal/periodo_laboral.php",
   data: "id_ptrabajador="+id,
   async:true,
   success: function(datos){
    $('#editarPTperiodoLaboral').html(datos);
    
    $('#dialog-pt-periodo-laboral').dialog('open');
   }
   }); 
}


//---------------------------------------------------
function editarDiaSubcidiado(id){
	crearDialogoDiaSubsidiado();
	id = 0;
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view-plame/modal/dia_subsidiado.php",
   data: "id_ptrabajador="+id,
   async:true,
   success: function(datos){
    $('#editarDiaSubsidiado').html(datos);
    
    $('#dialog-dia-subsidiado').dialog('open');
   }
   }); 

	

}


function crearDialogoDiaSubsidiado(){
//alert('crearDialogoPersonaDireccion');
	$("#dialog-dia-subsidiado").dialog({ 
           
			autoOpen: false,
			height: 250,
			width: 490,
			modal: true,
			title: "Dias Subsidiados"
			/*                      
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {	
				}
                                
			},			
			open: function() {},
			close: function() {}
			*/
	});
}


//---------------------------------------------------
function editarDiaNoLaborado(){
	crearDialogoDiaNoLaborado();
	id = 0;
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view-plame/modal/dia_nolaborado.php",
   data: "id_ptrabajador="+id,
   async:true,
   success: function(datos){
    $('#editarDiaNoLaborado').html(datos);
    
    $('#dialog-dia-noLaborado').dialog('open');
   }
   }); 

}

function crearDialogoDiaNoLaborado(){
//alert('crearDialogoPersonaDireccion');
	$("#dialog-dia-noLaborado").dialog({ 
           
			autoOpen: false,
			height: 250,
			width: 490,
			modal: true,
			title: "Dias no laborados y no Subsidiados" 
			/*                      
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {	
				}
                                
			},			
			open: function() {},
			close: function() {}
			*/
	});
}

