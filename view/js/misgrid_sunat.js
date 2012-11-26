// JavaScript Document

//************************************************************//
// EMPLEADOR
//***********************************************************//
//----------------------------------------------------------								
	function eliminarEmpleador(id){
		if(confirm("Desea Eliminar el Registro seleccionado?")){		
			var data = "id="+id+"&oper=del";		
			$.getJSON(
				'sunat_planilla/controller/EmpleadorController.php?'+data,
				function(data){
					if(data){
						jQuery("#list").trigger("reloadGrid");
						alert("Registro Fue Eliminado Correctamente");						
					}else{
						alert("Ocurrio un error, intente nuevamente");						
					}
				}
			);	
		}
	}


//************************************************************//
// EMPLEADOR
//***********************************************************//
//----------------------------------------------------------								
	function eliminarDerechohabiente(id){
		if(true/*confirm("Desea Eliminar el Registro seleccionado?")*/){		
			var data = "id="+id+"&oper=del";		
			$.getJSON(
				'sunat_planilla/controller/DerechohabienteController.php?'+data,
				function(data){
					if(data){
						jQuery("#list").trigger("reloadGrid");
						alert("Registro Fue Eliminado Correctamente");						
					}else{
						alert("Ocurrio un error, intente nuevamente");						
					}
				}
			);	
		}
	}


/**
*
//************************************************************
// INICIO PERSONAS 25/05/2012
***********************************************************
*/
	function cargarTablaPersonalDireccion(id_persona){ 
			
			//OBTENER ID PERSONA
			//$("#list").jqGrid('GridUnload');+	
			$("#list").jqGrid({
				url:'sunat_planilla/controller/PersonaDireccionController.php?oper=cargar_tabla&id_persona='+id_persona,
				datatype: 'json',
				colNames:['Id','ID','Direccion','N&deg;','Opciones'],
				colModel :[
					{
						name:'id_persona', 
						editable:false, 
						index:'id_persona',
						search:false,
						hidden:true,
						width:20,
						align:'center'
					},
					{
						name:'id_persona_direccion', 
						editable:false, 
						index:'id_persona_direccion',
						search:false,
						width:20,
						align:'center'
					},		
					{
						name:'nombre_ubigeo_reniec',
						index:'nombre_ubigeo_reniec', 
						editable:false,
						width:500, 
						align:'left', 
					},
					{
						name:'estado_direccion',
						index:'estado_direccion', 
						editable:false,
						width:100, 
						align:'left', 
					},					
					{
						name:'opciones', 
						index:'opciones',
						editable:false,
						width:50,
						align:'center'
					}									
					
						

				],
				pager: '#pager',
				//autowidth: true,
				rowNum:10,
				rowList:[10,20,30],
				sortname: 'id_persona',
				sortorder: 'asc',
				viewrecords: true,
				gridview: true,
				//caption: 'Lista de Direcciones',
				onSelectRow: function(ids) {},
				height:70,
				//width:'720px',
				//grouping         : false,
				gridview          : true,
				/*footerrow        : false,
				userDataOnFooter: false,*/
				//editurl            :"grid_contenedores_acciones2.php",
				gridComplete    : function(){  //alert("grid okD");
	
		var ids = $("#list").getDataIDs();
		var act;
		for(var i=0;i<ids.length;i++){
			var data = $("#list").getRowData(ids[i]);
			if (data.nombre_ubigeo_reniec == "" && data.estado_direccion == "Primera") {
				act =' <b class="red">Debe Ingresar La Primera Direccion es Obligatorio!. </b>';
				$("#list").setRowData(ids[i],{nombre_ubigeo_reniec: act });
			}
		}//ENDFOR
	}			
				
			});

			//myGrid.jqGrid('navGrid','#mypager',{edit:true,add:true,del:true,search:true});
			
			
		}
		
	
	
	
		
		
//-----------------------------------------------------------------------------------
//  intranet intranet intranet intranet intranet intranet intranet intranet

function crearDialogoPersonaDireccion(){
	$("#dialog-form-editarDireccion").dialog({ 
           
			autoOpen: false,
			height: 310,
			width: 830,
			modal: true,
                        
			buttons: {
                'Cancelar': function() {
					$(this).dialog('close');
				}
				,
				'Guardar': function() {	
			
					var estado_form = $("#form_direccion").valid();
					if(estado_form){
						var from_data =  $("#form_direccion").serialize();						
						
						//-------
						$.getJSON(
							'sunat_planilla/controller/PersonaDireccionController.php?oper=edit&'+from_data,
							function(data){
								if(data){
									jQuery("#list").trigger("reloadGrid");
									alert("Registro Se guardo correctamente ");	
									$("#dialog-form-editarDireccion").dialog('close');				
								}else{
									alert("Ocurrio un error, intente nuevamente");						
								}
							}
						);	
						//-------		
						
					}//ENDIF
				
									
				}
                                
			},
			open: function() {},
			close: function() {}
	});
}



function editarPersonaDireccion(id_persona_direccion){
	crearDialogoPersonaDireccion();
    $('#dialog-form-editarDireccion').dialog('open');
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view/modal/detalle_persona_direccion.php",
   data: {id_persona_direccion : id_persona_direccion },
   async:true,
   success: function(datos){
    $('#editarPersonaDireccion').html(datos);
    
    
   }
   }); 
}

//-------------------------------------------------------------
//-------------------------------------------------------------
//-----------------Persona Trabajador separado ---------------
//  intranet intranet intranet intranet intranet intranet 

function addTrabajador(id_persona){
	crearDialogoAddTrabajador();
    $('#dialog-addTrabajador').dialog('open');
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view/modal/modal_add_trabajador.php",
   data: {id_persona : id_persona },
   async:true,
   success: function(datos){
    $('#edit-addTrabajador').html(datos);
    
    
   }
   }); 
}

function crearDialogoAddTrabajador(){
	$("#dialog-addTrabajador").dialog({ 
           
			autoOpen: false,
			height: 350,
			width: 600,
			modal: true,                        
			buttons: {
                /*'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {				
					//var estado_form = $("#form_direccion").valid();
				}*/
                                
			},
			open: function() {},
			close: function() {}
	});
}


function nuevoAddTrabajador(id_persona){

var from_data =  $("#formAddTrabajador").serialize();

if(id_persona!=""){
$.ajax({
   type: "POST",
   dataType: 'json',
   url: "sunat_planilla/controller/CategoriaTrabajadorController.php?"+from_data,
   /*data: {id_persona : id_persona },*/
   async:true,
   success: function(data){
   	 
   	if(data){
	   	var id_trabajador = data;
	    alert("Se Guardo Correctamente  "+id_trabajador);
	    $("#dialog-addTrabajador").dialog('close');	

	    javascript:cargar_pagina('sunat_planilla/view/edit_ptrabajador.php?id_persona='+id_persona+'&id_trabajador='+id_trabajador+'&cod_situacion=1','#CapaContenedorFormulario')
    }else{
    	alert('Ocurrio un error');
    }

   }
   }); 
}

}


//-------------------------------------------------------------
//-------------------------------------------------------------
//-----------------DERECHOHABIENTES DIRECCIONES ---------------
//  intranet intranet intranet intranet intranet intranet 

    function crearDialogoDerechohabienteDireccion(){
        $("#dialog-form-editarDireccion-Derechohabiente").dialog({           
            autoOpen: false,
            height: 400,
            width: 950,
            modal: true,
                        
            buttons: {
                'Cancelar': function() {
                    $(this).dialog('close');
                },
                'Guardar': function() { //alert(" Guardar Dh --okk");
					var estado_form = $("#form_direccion_derechohabiente").valid();
					//alert ( "valid = "+estado_form);					
					if(estado_form){
						var from_data =  $("#form_direccion_derechohabiente").serialize();
						//---------------------------
						$.getJSON(
							'sunat_planilla/controller/DerechohabienteDireccionController.php?oper=edit&'+from_data,
							function(data){
								if(data){
									jQuery("#list").trigger("reloadGrid");
									alert("Registro Se guardo correctamente "+estado_form);	
									$("#dialog-form-editarDireccion-Derechohabiente").dialog('close');				
								}else{
									alert("Ocurrio un error, intente nuevamente");						
								}
							}
						);	
						//---------------------------			
					}//ENDIF					
                }
                                
            },
            open: function() {},
            close: function() {}
        });
    }



    function editarDerechohabienteDireccion(id_persona_direccion){  
        $.ajax({
            type: "POST",
            url: "sunat_planilla/view/modal/detalle_derechohabiente_direccion.php",
            data: "id_derechohabiente_direccion="+id_persona_direccion,//Enviando a ediatarProducto.php vareiable=id_producto
            async:true,
            success: function(datos){
                $('#editarDerechohabienteDireccion').html(datos);
    
                $('#dialog-form-editarDireccion-Derechohabiente').dialog('open');
            }
        }); 
    }
	
//-------------------------------------------------------------
//-------------------DERECHOHABIENTES--------------------------
//  intranet intranet intranet intranet intranet intranet 
	
function bajaDerechohabiente(id){
	if(true/*confirm("Desea Eliminar el Registro seleccionado?")*/){		
		var data = "id="+id+"&oper=baja";		
		$.getJSON(
			'sunat_planilla/controller/DerechohabienteController.php?'+data,
			function(data){
				if(data){
					jQuery("#list").trigger("reloadGrid");
					alert("Registro Fue Eliminado Correctamente");						
				}else{
					alert("Ocurrio un error, intente nuevamente");						
				}
			}
		);	
	}
}



//-------------------------------------------------------------
//-------------------ESTABLECIMIENTOS--------------------------
//  intranet intranet intranet intranet intranet intranet 


function eliminarEstablecimiento(id){
		if(true/*confirm("Desea Eliminar el Registro seleccionado?")*/){		
			var data = "id_establecimiento="+id+"&oper=del";		
			$.getJSON(
				'sunat_planilla/controller/EstablecimientoController.php?'+data,
				function(data){
					if(data){
						jQuery("#list").trigger("reloadGrid");
						alert("Registro Fue Eliminado Correctamente");						
					}else{
						alert("Ocurrio un error, intente nuevamente");						
					}
				}
			);	
		}
	}

//----
function bajaEstablecimiento(id){
		if(true/*confirm("Desea Eliminar el Registro seleccionado?")*/){		
			var data = "id="+id+"&oper=baja";		
			$.getJSON(
				'sunat_planilla/controller/EmpleadorEstablecimientoController.php?'+data,
				function(data){
					if(data){
						jQuery("#list").trigger("reloadGrid");
						alert("Registro Fue Eliminado Correctamente");						
					}else{
						alert("Ocurrio un error, intente nuevamente");						
					}
				}
			);	
		}
	}
//----------------------------------




//************************************************************//
// EMPLEADOR DD2
//***********************************************************//
//----------------------------------------------------------								
	function editarEmpleadorDD2(RUC){
		
		javascript:cargar_pagina('sunat_planilla/view/new_empleador_dd2.php?ruc_empleador_subordinado='+RUC,'#CapaContenedorFormulario')
		
	}


    /*****************************************************/
    /***************** Derecho Habiente Direccion*******/
    /*****************************************************/
    
    //FUNNCION CARGAR_TABLA PASARELAS 10/12/2011		
    function cargarTablaDerechohabienteDireccion(id){  //alert('id_derechohabiente = '+id);			
        //OBTENER ID PERSONA
        //$("#list").jqGrid('GridUnload');+	
        $("#list").jqGrid({
            url:'sunat_planilla/controller/DerechohabienteDireccionController.php?oper=cargar_tabla&id_derechohabiente='+id,
            datatype: 'json',
            colNames:['Id','id_derechohabiente','Direccion','N&deg;','Opciones'],
            colModel :[
                {
                    name:'id_derechohabiente_direccion', 
                    editable:false, 
                    index:'id_derechohabiente_direccion',
					hidden:true,
                    search:false,
                    hidden:false,
                    width:20,
                    align:'center'
                },
                {
                    name:'id_derechohabiente', 
                    editable:false, 
                    index:'id_persona_direccion',
					search:false,
					hidden:true,

                    width:20,
                    align:'center'
                },		
                {
                    name:'nombre_ubigeo_reniec',
                    index:'nombre_ubigeo_reniec', 
                    editable:false,
                    width:500, 
                    align:'center' 
                },
                {
                    name:'estado_direccion',
                    index:'estado_direccion', 
                    editable:false,
                    width:100, 
                    align:'left', 
                },
                {
                    name:'opciones',
                    index:'opciones', 
                    editable:false,
                    width:50,
                    align:'center'
                },	
						

            ],
            pager: '#pager',
            //autowidth: true,
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'estado_direccion',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //caption: 'Direcciones',
            onSelectRow: function(ids) {},
            //height:'100px',
            //width:'700px',
			gridComplete    : function(){			
			var ids = $("#list").getDataIDs();			
				for(var i=0;i<ids.length;i++){
				var data = $("#list").getRowData(ids[i]);
					if (data.nombre_ubigeo_reniec == "" && data.estado_direccion == "Primera") {
						var act =' <b class="red">Debe Ingresar La Primera Direccion es Obligatorio!. </b>';
						$("#list").setRowData(ids[i],{nombre_ubigeo_reniec: act });
					}
				}//ENDFOR
			}				
			
			
        });
		
        	
    }		
    //-----------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------


    /*****************************************************/
    /***************** PERSONA DIRECCION ***************************/
    /*****************************************************/

    //FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
				
    function cargarTablaPersonalServicio(cod_estado){
		var arg = (typeof cod_estado == 'undefined') ? 0 : cod_estado;
	
        $("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PersonaController.php?oper=cargar_tabla&estado='+arg,
            datatype: 'json',
            colNames:['IdP','tipo_doc','Numero Doc','A.Paterno',
                'A.Materno','Nombres','Sexo','Estado','Opciones'
                ,'New'],
            colModel :[
                {
                    name:'id_persona',
                    key : true, 
                    editable:false, 
                    index:'id_persona',
                    search:false,
                    width:20,
                    align:'center',
                    hidden:false,
                },	

                {
                    name:'nombre_tipo_documento', 
                    index:'nombre_tipo_documento',
                    search:false,
                    editable:false,
                    width:80,
                    align:'center'
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    editable:false,
                    width:100,
                    align:'left',
					cellattr: function(rowId, value, rowObject, colModel, arrData) {
						return ' colspan=4';
					},
                    formatter : function(value, options, rData){4
                    	return ": "+value + " - "+rData['3']+" "+rData['4']+" "+rData['5'] ;
                    }


                },            
                
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    editable:false,
                    width:90,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    }                    
                 
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
                    editable:false,
                    width:90,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    }                    
                },
                
                {
                    name:'nombres', 
                    index:'nombres',
                    editable:true,
                    width:80,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    }
                },
                {
                    name:'sexo',
                    index:'sexo',
                    editable:true,
                    search:false,
                    width:30, 
                    align:'center'
                },
                {
                    name:'estado',
                    index:'estado',
                    editable:false,
                    search:false,
                    width:80,
                    hidden:true,
                    align:'center'
                },
                {
                    name:'opciones',
                    index:'opciones',
                    search:false,
                    editable:false,
                    width:80, 
                    align:'center'
                },
                {
                    name:'new',
                    index:'new',
                    search:false,
                    editable:false,
                    width:80,                    
                    align:'center'
                },

		
            ],
            pager: '#pager',
            mtype: "GET",
            rownumbers: true,
            //autowidth: true,
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_persona',
            sortorder: 'asc',
            viewrecords: true,
            /*gridview: true,*/
            caption: 'Lista de Personal',
            /*						multiselect: false,
                                    hiddengrid: true,*/
            onSelectRow: function(ids) {},
            height:320,
           // width:720
        });
        //--- PIE GRID
        jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
        //$("#list").remapColumns([1,3,2],true,false);
        //---- ini new otro buscador
		/*jQuery("#list").jqGrid('navGrid','#pager',{
			edit:false,
			add:false,
			del:false,
			search:false,
			refresh:false
		});
		jQuery("#list").jqGrid('navButtonAdd',"#pager",{
			caption:"Toggle",
			title:"Toggle Search Toolbar",
			buttonicon :'ui-icon-pin-s',
			onClickButton:function(){ mygrid[0].toggleToolbar() } 
		});

		jQuery("#list").jqGrid('navButtonAdd',"#pager",{
			caption:"Clear",
			title:"Clear Search",
			buttonicon :'ui-icon-refresh',
			onClickButton:function(){ mygrid[0].clearToolbar() } 
		});
		jQuery("#list").jqGrid('filterToolbar');*/
		//-----------------------------------------


        //---- ini new otro buscador


					
    }




// new  grid trabajador 09/11/2012
    function cargarTablaTrabajadorServicio(cod_estado){
    	console.log("cargarTablaTrabajadorServicio")
		var arg = (typeof cod_estado == 'undefined') ? 0 : cod_estado;
	
        $("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/CategoriaTrabajadorController.php?oper=cargar_tabla&estado='+arg,
            datatype: 'json',
            colNames:['IdP','tipo_doc','Numero Doc','A.Paterno',
                'A.Materno','Nombres','Sexo','Situacion','Opciones'
                ,'New'],
            colModel :[
                {
                    name:'id_persona',
                    key : true, 
                    editable:false, 
                    index:'id_persona',
                    search:false,
                    width:20,
                    align:'center',
                    hidden:false,
                },	

                {
                    name:'nombre_tipo_documento', 
                    index:'nombre_tipo_documento',
                    search:false,
                    editable:false,
                    width:80,
                    align:'center'
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    editable:false,
                    width:100,
                    align:'left',
					cellattr: function(rowId, value, rowObject, colModel, arrData) {
						return ' colspan=4';
					},
                    formatter : function(value, options, rData){4
                    	return ": "+value + " - "+rData['3']+" "+rData['4']+" "+rData['5'] ;
                    }


                },            
                
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
                    editable:false,
                    width:90,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    }                    
                 
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
                    editable:false,
                    width:90,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    }                    
                },
                
                {
                    name:'nombres', 
                    index:'nombres',
                    editable:true,
                    width:80,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    }
                },
                {
                    name:'sexo',
                    index:'sexo',
                    editable:true,
                    search:false,
                    width:30, 
                    align:'center'
                },
                {
                    name:'cod_situacion',
                    index:'cod_situacion',
                    editable:true,
                    search:false,
                    width:80, 
                    align:'center'
                },
                {
                    name:'opciones',
                    index:'opciones',
                    search:false,
                    editable:false,
                    width:80, 
                    align:'center'
                },
                {
                    name:'new',
                    index:'new',
                    search:false,
                    editable:false,
                    width:80,                    
                    align:'center'
                },

		
            ],
            pager: '#pager',
            rownumbers: true,
            //autowidth: true,
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_persona',
            sortorder: 'asc',
            viewrecords: true,
            /*gridview: true,*/
            caption: 'Lista de Trabajadores',
            /*						multiselect: false,
                                    hiddengrid: true,*/
            onSelectRow: function(ids) {},
            height:320,
           // width:720
        });
        //--- PIE GRID
        jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
					
    }






//-----------------------------------------------------------------------------------

                function cargarTablaDerechoHabiente(id_persona,id_empleador){
					//console.log(id_persona);
					var ids_trabajadores = new Array();
                   // $("#list").jqGrid('GridUnload');
                    $("#list").jqGrid({
                        url:'sunat_planilla/controller/DerechohabienteController.php?oper=cargar_tabla&id_persona='+id_persona+'&id_empleador='+id_empleador,
                        datatype: 'json',
                        colNames:['ID','id_persona','Doc','Num doc','Paterno',
						'Materno','Nombres','Fech Nacimiento','vinculo familiar','situacion',
						'Opciones'],
                        colModel :[
							{
                                name:'id_derechohabiente', 
                                index:'id_derechohabiente',
								search:false,
								editable:false, 
                                width:20,
                                align:'left'
                            },		
                            {
                                name:'id_persona', 
                                editable:false, 
								hidden:true,
                                index:'id_persona',
                                width:20,								
                                align:'left'
                            },		
                            {
                                name:'nombre_documento',
                                index:'nombre_documento', 
								search:false,
                                editable:true,                                
                                width:90, 
                                align:'left', 
                            },
                            {
                                name:'num_documento', 
                                index:'num_documento',
                                editable:false,
                                width:90,
                                align:'left'
                            },
                            {
                                name:'apellido_paterno', 
                                index:'apellido_paterno',
                                editable:true,
                                width:90,
                                align:'left'
                            },
                            {
                                name:'apellido_materno',
                                editable:true,
                                width:90, 
                                index:'apellido_materno', 
                                align:'left'
                            },
                            {
                                name:'nombres',
								index:'nombres',
                                editable:true,
                                width:90,                                 
                                align:'left'
                            },
							{
                                name:'fecha_nacimiento',
								index:'fecha_nacimiento',								
                                editable:true,
                                width:100,                                 
                                align:'left'
                            },
                            {
                                name:'nombre_vinculo_familiar',
								index:'nombre_vinculo_familiar',
								search:false,
                                editable:true,
                                width:150,                                 
                                align:'left' 
                            },
                            {
                                name:'nombre_situacion',
								index:'nombre_situacion',
								search:false,
                                editable:true,
                                width:90,                                 
                                align:'left'
                            },							
							{
                                name:'opciones',
                                editable:false,
								search:false,
                                width:90, 
                                index:'opciones', 
                                align:'center'
                            },
	
		
		
                        ],
                        pager: '#pager',
						//autowidth: true,
						toolbar: [true,"top"],
						multiselect: true,
                        rowNum:10,
                        rowList:[10,20,30],
                        sortname: 'id_derechohabiente',
                        sortorder: 'asc',
                        viewrecords: true,
                        gridview: true,
                        caption: 'Lista de DerechoHabientes',
                        onSelectRow: function(ids) {
                        },
                        height:'250px',
                        //width:720 
            onSelectRow: function(rowid, selected) {
					
                var counteo = ids_trabajadores.length;
		
                if( counteo != 0){
                    //console.log("MAS DE UNOO");
                    var bandera = false;					 
                    for(var i = 0; i < counteo ;i++){
                        // alert( rowid +"a igualar = " + ids_trabajadores[i]);
                        if(ids_trabajadores[i] == rowid){
                            // Ya existe rowid en array
                            bandera = true;
                            ids_trabajadores[i] =null;
                            break;
                        }
                    }//ENDFOR
		
                    if(bandera==false){
                        ids_trabajadores[counteo]=rowid;
                    }
		
                }else{
                    // console.log("UNOO");
                    ids_trabajadores[counteo]=rowid;	
                }
		
            },
            onSelectAll : function(rowids,selected) {            
                // alert("num de ids_trabajadores.length  "+ids_trabajadores.length);  
               
                for(var i=0; i<ids_trabajadores.length;i++){				
                    ids_trabajadores[i]=null;                    			
                }	
			
                if(selected){												
                    var array = new Array();
                    for(var i=0;i<rowids.length;i++){
                        //var data = jQuery("#list-1").jqGrid('getRowData',rowids[i]);
                        //array[i]=parseInt(data.id_persona);
                        array[i] = rowids[i];
                    }
                    ids_trabajadores = array;
                }//ENFIF
                
            },		

        });
		
		
//--
        //----	
        $("#t_list").append("<input type='button' value='Dar de Baja' style='height:20px;font-size:-3'/>");
        $("input","#t_list").click(function(){
            //alert("Hi! I'm added button at this toolbar");        
        	var news = new Array();
        
            var j=0;
            for(var i=0; i<ids_trabajadores.length;i++){
                if(ids_trabajadores[i]!=null){
                    news[j]=ids_trabajadores[i];
                    j++;
                }
            }

            console.log(news);

        


if(news.length >= 1){
	
	// -----arrayCadena
	var cadena='';
	for(var i=0; i < news.length;i++){	
		cadena+= "ids[]="+news[i];
		if(i != (news.length-1)){
			cadena+= "&";
		}	
	}
	//alert(cadena);
	// -----arrayCadena

	
//-----	
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'sunat_planilla/controller/DerechohabienteController.php?'+cadena,
                data: {oper: 'baja'},
                beforeSend: function(objeto){  },
                complete: function(objeto, exito){ 
                },
               success: function(data){
				   jQuery("#list").trigger("reloadGrid")
					//news = null;
					limpiarArray(ids_trabajadores);
					limpiarArray(news);
					
                }
            });	
//-------
}else{
		alert("Debe seleccionar un Registro");
}


	
	
});		
		
		
		

 //--- PIE GRID
  }
  
  //funcion basica
  
  	function limpiarArray(arreglo){
		for(var i=0; i<arreglo.length;i++){				
			arreglo[i]=null;                    			
		}	
	}

				
//---------------------------------------------------------------------------------				


                //FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
                function cargarTablaEmpleador(){ 

                   // $("#list").jqGrid('GridUnload');
                    $("#list").jqGrid({
                        url:'sunat_planilla/controller/EmpleadorController.php?oper=cargar_tabla',
                        datatype: 'json',
                        colNames:['ID','RUC','Razon Social','Nombre Comercial','Telefono','Tipo empleador','Opciones','tipo'],
                        colModel :[
                            {
                                name:'id_empleador', 
                                editable:false,
                                search:false,
                                index:'id_empleador',
                                width:20,
                                align:'center'
                            },		
                            {
                                name:'ruc',
                                index:'ruc', 
                                editable:true,
                                editrules:{
                                    required:true
                                },
                                width:100, 
                                align:'center' 
                            },
                            {
                                name:'razon_social', 
                                index:'razon_social',
                                editable:false,
                                width:170,
                                align:'center'
                            },
                            //	{name:'estado',  editable:true, width:60,  edittype:"select",editoptions:{value:"A:Activo;I:Inactivo"}, index:'estado',  sortable:false},
                            {
                                name:'nombre_comercial', 
                                index:'nombre_comercial',
                                editable:true,
                                width:170,
                                align:'center'
                            },
                            {
                                name:'telefono',
                                editable:true,
                                search:false,
                                width:90, 
                                index:'telefono', 
                                align:'center'
                            },
                            {
                                name:'nombre_tipo_empleador',
                                editable:true,
                                
                                width:120, 
                                index:'nombre_tipo_empleador', 
                                align:'center'
                            },
                            {
                                name:'opciones',
                                editable:false,
                                search:false,
                                width:90, 
                                index:'opciones', 
                                align:'center'
                            },
							 {
                                name:'tipo',
                                editable:true,
                                search:true,
                                width:90, 
                                index:'tipo', 
                                align:'center',
								hidden:true
                            }
		
		
                        ],
                        pager: '#pager',
						//autowidth: true,
                        rowNum:10,
                        rowList:[10,20,30],
                        sortname: 'id_empleador',
                        sortorder: 'asc',
                        viewrecords: true,
                        gridview: true,
                        caption: 'Lista de Empleadores',
						height:'200px',
                        //width:720

                    });

                    //--- PIE GRID
                    jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});


                }

//-----------------------------------------------------------------------------------------------------

                function cargarTablaEstablecimientoEmpleador(id_empleador){ 					
                   // $("#list").jqGrid('GridUnload');
                    $("#list").jqGrid({
                        url:'sunat_planilla/controller/EstablecimientoController.php?oper=cargar_tabla&id_empleador='+id_empleador,
                        datatype: 'json',
                        colNames:['ID','id_empleador','Codigo','RUC','nombre_establecimiento','fecha_creacion','Opciones','Centro Costo'],
                        colModel :[
                            {
                                name:'id_establecimiento', 
                                editable:false, 
                                index:'id_establecimiento',
                                width:30,
                                align:'center'
								
                            },
                            {
                                name:'id_empleador', 
                                editable:false,
								hidden : true,
                                index:'id_empleador',
                                width:20,
                                align:'center',
								hidden:true
                            },									
                            {
                                name:'nombre_establecimiento',
                                index:'nombre_establecimiento', 
                                editable:true,
                                editrules:{
                                    required:true
                                },
                                width:80, 
                                align:'center', 
                            },
                            {
                                name:'cod_establecimiento', 
                                index:'cod_establecimiento',
                                editable:false,
                                width:80,
                                align:'center'
                            },
						    {
                                name:'ruc_empleador', 
                                index:'ruc_empleador',
                                editable:false,
                                width:180,
                                align:'center'
                            },
                            //	{name:'estado',  editable:true, width:60,  edittype:"select",editoptions:{value:"A:Activo;I:Inactivo"}, index:'estado',  sortable:false},
                            {
                                name:'fecha_creacion', 
                                index:'fecha_creacion',
                                editable:true,
                                width:90,
                                align:'center'
                            },                           
							{
                                name:'opciones',
                                editable:false,
                                width:90, 
                                index:'opciones', 
                                align:'center'
                            },
							{
                                name:'opciones2',
                                editable:false,
                                width:90, 
                                index:'opciones2', 
                                align:'center'
                            }
		
		
                        ],
                        pager: '#pager',
						//autowidth: true,
                        rowNum:10,
                        rowList:[10,20,30],
                        sortname: 'id_establecimiento',
                        sortorder: 'asc',
                        viewrecords: true,
                        gridview: true,
                        caption: 'Lista de Establecimientos',
                        onSelectRow: function(ids) {
                        },
                        height:'200px',
                        //width:720 
                    });

                }




//-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------
// GRID PDT-PLAME

//FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
function cargarTablaConceptos(config){ 

//var configuracion = (typeof config == 'undefined' ) ? 23 : config;
var c = (typeof config == 'undefined' ) ? '' : 'config='+config;
//c = "config=config";
//alert(c);

    //$("#list").jqGrid('GridUnload');
	$("#list").jqGrid({
		url:'sunat_planilla/controller/PlameConceptoController.php?oper=cargar_tabla&'+c,
		datatype: 'json',
		colNames:['Codigo','Descripcion','Seleccionar'],
		colModel :[
			{
				name:'cod_concepto',
				index:'cod_concepto',
				editable:false,
				search:false,				
				width:80,
				align:'center'
			},		
			{
				name:'descripcion',
				index:'descripcion', 
				editable:false,
				width:400, 
				align:'left' 
			},
			{
				name:'seleccion', 
				index:'seleccion',
				editable:false,
				width:100,
				align:'center'
			}

		],
		pager: '#pager',
		//autowidth: true,
		rowNum:12,
		rowList:[12,24,36],
		sortname: 'cod_concepto',
		sortorder: 'asc',
		viewrecords: true,
		//caption: 'none',
		 multiselect: false,
		height:'250px',
		//width:'720px',
		
//	grouping         : false,
	gridview          : true,
/*	footerrow        : false,
	userDataOnFooter: false,*/
	editurl            :"grid_contenedores_acciones2.php",
	gridComplete    : function(){  //alert("grid ok");
		
		//$(".popup").colorbox({overlayClose: false});
	/*	
		var ids = $("#list").getDataIDs();
		for(var i=0;i<ids.length;i++){
			if (i==0) primeraFila = ids[i];
			var cl   = ids[i];
			var data = $("#list").getRowData(ids[i]);
			if (data.estado == 1) {
				act = "<img src='img/contenedor_estado_1.png' style='cursor:pointer' title='Sin revisar' />";
			}else if (data.estado == 2) {
				act = "<img src='img/contenedor_estado_2.png' style='cursor:pointer' title='Sin daños' />";
			}else{
				act = "<img src='img/contenedor_estado_3.png' style='cursor:pointer' title='Con daños' />";
			}
			$("#contenedores").setRowData(ids[i],{estado:act});
		}
		
		var idsCont = $("#list").getDataIDs();
		$("#list").setSelection(idsCont[0], true);
		*/
		
	}

	});


	//--- PIE GRID
	//jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

}

//--------------------------------------------------------------------------


//--------------------------------------------------------
//--------------------------------------------------------

function crearDialogoAfectacion(){
	$("#dialog-form-editarAfectacion").dialog({ 
           
			autoOpen: false,
			height: 470,
			width: 430,
			modal: true,
                        
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {	
				
					//---	VALIDACION ECHA EN 	modal/detalle_persona_direccion.php					
					var estado_form = true; //$("#form_direccion").valid();
					if(estado_form){
						var from_data =  $("#formAfectacion").serialize();
						//alert ("from_data = "+from_data);
						//---------------------------
						$.getJSON(
							'sunat_planilla/controller/PlameDetalleConceptoAfectacionController.php?oper=edit&'+from_data,
							function(data){
								if(data){
									//jQuery("#list").trigger("reloadGrid");
									alert("Registro Se guardo correctamente.");	
									$("#dialog-form-editarAfectacion").dialog('close');				
								}else{
									alert("Ocurrio un error, intente nuevamente");						
								}
							}
						);	
						//---------------------------			
						
					}//ENDIF
				
									
				}
                                
			},
			open: function() {},
			close: function() {}
	});
}



function editarAfectacion(id){  //alert (".");
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view-plame/modal/afectaciones.php",
   data: "id_detalle_concepto="+id,
   async:true,
   success: function(datos){
    $('#editarAfectacion').html(datos);
    
    $('#dialog-form-editarAfectacion').dialog('open');
   }
   }); 
}




//**************************************************************************************************
//-----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------
// EMPRESA 
//**************************************************************************************************



function crearDialogoEditEmpresaCentroCosto(){
	$("#dialog-form-editarCentroCosto").dialog({            
			autoOpen: false,
			height: 310,
			width: 400,
			modal: true,                        
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {					
					
					if(true){
						var from_data =  $("#formEditEmpresaCentroCosto").serialize();
						//---------------------------
						$.getJSON(
							'sunat_planilla/controller/EstablecimientoCentroCostoController.php?'+from_data,
							function(data){
								if(data){
									//jQuery("#list").trigger("reloadGrid");
									$("#dialog-form-editarCentroCosto").dialog('close');
									alert("Se Guardo Correctamente.");									
								}else{
									alert("Ocurrio un error, intente nuevamente");						
								}
							}
						);	
						//---------------------------			
						
					}//ENDIF		
				}               
			},
			open: function() {},
			close: function() {}
	});
}



function editarEmpresaCentroCosto(id_establecimiento){  //alert (".");
	$('#dialog-form-editarCentroCosto').dialog('open');
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view/modal/edit_empresa_centro_costo.php",
   data: "id_establecimiento="+id_establecimiento,
   async:true,
   success: function(datos){
    $('#editarCentroCosto').html(datos);
    
    //$('#dialog-form-editarCentroCosto').dialog('open');
   }
   }); 
}


function newEmpresaCentroCosto(id){
	$('#dialog-form-newCentroCosto').dialog('open');
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view/modal/new_empresa_centro_costo.php",
   data: "id_establecimiento="+id,
   async:true,
   success: function(datos){
    $('#newCentroCosto').html(datos);
    
    //$('#dialog-form-newCentroCosto').dialog('open');
   }
   }); 

}


function crearDialogoNewEmpresaCentroCosto(){
	$("#dialog-form-newCentroCosto").dialog({            
			autoOpen: false,
			height: 310,
			width: 400,
			modal: true,                        
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {					
					
					if(true){
						var from_data =  $("#formNewEmpresaCentroCosto").serialize();
						//---------------------------
						$.getJSON(
							'sunat_planilla/controller/EstablecimientoCentroCostoController.php?'+from_data,
							function(data){
								if(data){
									//jQuery("#list").trigger("reloadGrid");
									$("#dialog-form-newCentroCosto").dialog('close');
									alert("Se Guardo Correctamente.");	
								}else{
									alert("Ocurrio un error, intente nuevamente");						
								}
							}
						);	
						//---------------------------			
						
					}//ENDIF		
				}               
			},
			open: function() {},
			close: function() {}
	});
}

//---------------------------------------------------------
//------------------------------------------------------------------------------------
//  intranet intranet intranet intranet intranet intranet intranet intranet

/*
function crearDialogoPersonaDireccion(){

	$("#dialog-form-editarDireccion").dialog({ 
           
			autoOpen: false,
			height: 310,
			width: 860,
			modal: true,                        
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {	
					estado_form = true;					
					if(estado_form==true){
						var from_data =  $("#form_direccion").serialize();
						//---------------------------
						$.getJSON(
							'sunat_planilla/controller/PersonaDireccionController.php?oper=edit&'+from_data,
							function(data){
								if(data){
									jQuery("#list").trigger("reloadGrid");
									$("#dialog-form-editarDireccion").dialog('close');				
								}else{
									alert("Ocurrio un error, intente nuevamente");						
								}
							}
						);	
						//---------------------------			
						
					}//ENDIF
				
									
				}
                                
			},
			open: function() {},
			close: function() {}
	});
}



function editarPersonaDireccion(id_persona_direccion){
	$('#dialog-form-editarDireccion').dialog('open');
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view/modal/detalle_persona_direccion.php",
   data: "id_persona_direccion="+id_persona_direccion,
   async:true,
   success: function(datos){
    $('#editarPersonaDireccion').html(datos);    

   }
   }); 
}

*/



//-----------------------
function llenarComboDinamico(test,objCombo){

	var counteo = objCombo.length;
	for(var i=0;i<counteo;i++){
		objCombo.options[i] = null;
	}
	//console.log("fin limpiado");	
	
	var counteo = 	test.length;		
	objCombo.options[0] = new Option('-', '0');
	for(var i=0;i<counteo;i++){
		objCombo.options[i+1] = new Option(test[i].descripcion, test[i].id);
	}


}
