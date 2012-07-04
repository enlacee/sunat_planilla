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
	function cargarTablaPersonalDireccion(id_persona){  //alert ("..");
			
			//OBTENER ID PERSONA
			//$("#list").jqGrid('GridUnload');+	
			$("#list").jqGrid({
				url:'sunat_planilla/controller/PersonaDireccionController.php?oper=cargar_tabla&id_persona='+id_persona,
				datatype: 'json',
				colNames:['Id','id_persona_direccion','nombre_ubigeoreniec','Direccion','Opciones'],
				colModel :[
					{
						name:'id_persona', 
						editable:false, 
						index:'id_persona',
						search:false,
						hidden:true,
						width:15,
						align:'center'
					},
					{
						name:'id_persona_direccion', 
						editable:false, 
						index:'id_persona_direccion',
						search:false,
						width:15,
						align:'center'
					},		
					{
						name:'nombre_ubigeo_reniec',
						index:'nombre_ubigeo_reniec', 
						editable:false,
						width:280, 
						align:'left', 
					},
					{
						name:'estado_direccion',
						index:'estado_direccion', 
						editable:false,
						width:30, 
						align:'left', 
					},					
					{
						name:'opciones', 
						index:'opciones',
						editable:false,
						width:20,
						align:'center'
					}									
					
						

				],
				pager: '#pager',
				autowidth: true,
				rowNum:10,
				rowList:[10,20,30],
				sortname: 'id_persona',
				sortorder: 'asc',
				viewrecords: true,
				gridview: true,
				caption: 'Lista de Direcciones',
				onSelectRow: function(ids) {},
				height:100,
				/*width:915*/ 
			});

			//myGrid.jqGrid('navGrid','#mypager',{edit:true,add:true,del:true,search:true});
			
			
		}
		
	
	
	
		
		
//-----------------------------------------------------------------------------------
//------------------------------------------------------------------------------------
//  intranet intranet intranet intranet intranet intranet intranet intranet

function crearDialogoPersonaDireccion(){
//alert('crearDialogoPersonaDireccion');
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
				
					//---	VALIDACION ECHA EN 	modal/detalle_persona_direccion.php					
					var estado_form = $("#form_direccion").valid();
					if(estado_form){
						var from_data =  $("#form_direccion").serialize();
						//alert ("from_data = "+from_data);
						//---------------------------
						$.getJSON(
							'sunat_planilla/controller/PersonaDireccionController.php?oper=edit&'+from_data,
							function(data){
								if(data){
									jQuery("#list").trigger("reloadGrid");
									alert("Registro Se guardo correctamente "+estado_form);	
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



function editarPersonaDireccion(id_persona_direccion){  //alert (".");
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view/modal/detalle_persona_direccion.php",
   data: "id_persona_direccion="+id_persona_direccion,//Enviando a ediatarProducto.php vareiable=id_producto
   async:true,
   success: function(datos){
    $('#editarPersonaDireccion').html(datos);
    
    $('#dialog-form-editarDireccion').dialog('open');
   }
   }); 
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
