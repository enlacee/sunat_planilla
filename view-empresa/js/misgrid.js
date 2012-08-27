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
			height:'200px',
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

        /*****************************************************/
            /*****************Liquidaciones***************************/
            /*****************************************************/
            function cargarTablaLiquidaciones(){
				
				var anio = document.getElementById('anio').value;
				
         $("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PlameDeclaracionController.php?oper=cargar_tabla_empresa&anio='+anio,
            datatype: 'json',
            colNames:['Id','Periodo','Add Adelanto','Edit Adelanto','SUNAT','SUNAT'],
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
                    width:90,
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
                    width:50,
                    align:'center'
                },
                {
                    name:'sunatEdit', 
                    index:'sunatEdit',
                    editable:false,
                    width:50,
                    align:'center'
                }

            ],
            pager: '#pager',

                    rowNum:12,
                    rowList:[12,24,36],
                    sortname: '',
                    sortorder: '',
                    viewrecords: true,
                    gridview: true,
                    height:'100%',
                    width:700,
                    multiselect: false, 
                    subGrid: true, 
                    caption: "Liquidaciones", 
                });
                jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
            }
		
		
            //------------------------------------------------------------------------
            //-----------------------------------------------------------------------
            //-----------------------------------------------------------------------	

//-------------------------------------------------------------------

    function cargarTablaPdeclaracionEmpresa(){ 
		//var d = new Date();
		//var n = d.getFullYear(); 
		var anio = document.getElementById('anio').value || 2012;
		
		
        $("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PlameDeclaracionController.php?oper=cargar_tabla_empresa&anio='+anio,
            datatype: 'json',
            colNames:['Id','Periodo','Add Adelanto','Edit Adelanto'],
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
                    width:90,
                    align:'center'
                },
                {
                    name:'edit', 
                    index:'edit',
                    editable:false,
                    width:90,
                    align:'center'
                }


            ],
            pager: '#pager',
			height:'250px',
            width:'100px',
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
            hiddengrid: false,						
			//----
			multiselect: false, 
			//subGrid: true,
			//----
		onSelectRow: function(ids) {
			if(ids == null) {
				ids=0;
				if(jQuery("#list10_d").jqGrid('getGridParam','records') >0 )
				{
					jQuery("#list10_d").jqGrid('setGridParam',{ //EtapaPagoController
						url:'sunat_planilla/controller/EtapaPagoController.php?oper=cargar_tabla&id_declaracion='+ids,
						page:1});
																
					jQuery("#list10_d").jqGrid('setCaption',"E.P.de : "+ids)
					.trigger('reloadGrid');
				}
			} else {
					jQuery("#list10_d").jqGrid('setGridParam',{
						url:'sunat_planilla/controller/EtapaPagoController.php?oper=cargar_tabla&id_declaracion='+ids,
						page:1});
				
				jQuery("#list10_d").jqGrid('setCaption',"E.P.de : "+ids)
				.trigger('reloadGrid');			
			}
		}	
			
			
	

        });
        //--- PIE GRID
 // jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
	//add
	//----

jQuery("#list10_d").jqGrid({
	height: 100,
	width:'100px',
   	url:'sunat_planilla/controller/EtapaPagoController.php?oper=cargar_tabla&id_declaracion='+0,
	datatype: "json",
   	colNames:['Id','Tipo', 'Finicio', 'Ffin','Opciones'],
   	colModel:[
   		{name:'id_etapa_pago',index:'id_etapa_pago', width:20},
   		{name:'descripcion',index:'descripcion', width:100},
   		{name:'fecha_inicio',index:'fecha_inicio', width:80, align:"left"},
   		{name:'fecha_fin',index:'fecha_fin', width:80, align:"left"},		
   		{name:'opciones',index:'opciones', width:80,align:"center", sortable:false, search:false}
   	],
   	rowNum:5,
   	rowList:[5,10,20],
   	pager: '#pager10_d',
   	sortname: 'id_etapa_pago',
    viewrecords: true,
    sortorder: "asc",
	multiselect: false,
	caption:"E.P.de : "
})
//jQuery("#list10_d").jqGrid('navGrid','#pager10_d',{add:false,edit:false,del:false});

//----------------------------------------------------------------------------




}

//------------------------------------------------------------
function validarNewDeclaracionPeriodo(){ //Registrar Periodo
	var periodo = document.getElementById('txt_periodo_tributario').value;
	var input_estado = document.getElementById('estado');
	var input_inicio = document.getElementById('mes_inicio');
	var input_fin = document.getElementById('mes_fin');
	
	if(validarPeriodo(periodo)==true){
		//-----	
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: 'sunat_planilla/controller/PlameDeclaracionController.php',
			data: {oper: 'add', periodo : periodo },
		    success: function(data){
				// FALSE = YA SE REGISTRO PERIODO
				// TRUE  = NO EXISTE PERIODO REGISTRADO				
				//input_estado.value = data.rows[0]['estado'];				
				//input_inicio.value =  data.rows[0]['data_mes']['mes_inicio'];
				//input_fin.value = data.rows[0]['data_mes']['mes_fin'];
								
				//---------
				alert("data ="+data);			
				if(data=='true'){
					//cargar_pagina('sunat_planilla/view-plame/edit_declaracion.php?periodo='+periodo,'#CapaContenedorFormulario');
					alert("Se registro correctamente el Periodo");
					//javascript:cargar_pagina('sunat_planilla/view-empresa/view_periodo.php','#CapaContenedorFormulario')
				}else if(data =='false'){
					alert("FALSE Periodo Ya se encuentra Registrado!\n O no existe ningun trabajador en el periodo: ."+input_inicio.value);
				}
				
				
				
			}
		});
		//-------
	}//ENDIF
	

}


//----------------------------------------------------------------

   // GRID 2
   //vocabulario Etapa
   //-semanal = ?
   //-quincenal = ?
    function cargarTabla_Etapa(id_declaracion,cod_periodo_remuneracion){

		var arreglo = new Array();
		
        $("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/EtapaPagoController.php?oper=trabajador_por_etapa&cod_periodo_remuneracion='+cod_periodo_remuneracion+"&id_declaracion="+id_declaracion,
            datatype: 'json',
            colNames:['Id','tipo_doc','Numero Doc','APaterno',
                'AMaterno', 'Nombres','F inicio','F fin','Sueldo','C.Costo','Opciones'],
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
                    width:30, 
                    align:'center' 
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    search:true,
                    editable:false,
                    width:80,
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
					search:false,
                    width:90,
                    align:'center'
                },
				 {
                    name:'fecha_fin', 
                    index:'fecha_fin',
                    editable:false,
					search:false,
                    width:90,
                    align:'center'
                },
				{
                    name:'monto_remuneracion', 
                    index:'monto_remuneracion',
                    editable:false,
                    width:90,
                    align:'center'
                },
				 {
                    name:'descripcion', 
                    index:'descripcion',
                    editable:false,
					search:false,
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
			height:'250px',
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_trabajador',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //caption: 'Trabajadores Activos',
            toolbar: [true,"top"],
            multiselect: true,
            hiddengrid: false,
			
            onSelectRow: function(rowid, selected) {
					
                    var bandera = false;					 
                    for(var i = 0; i < arreglo.length ;i++){
                        // alert( rowid +"a igualar = " + ids_trabajadores_2[i]);
                        if(arreglo[i] == rowid){
                            // Ya existe rowid en array
                            bandera = true;
                            arreglo[i] =null;
                            break;
                        }
                    }//ENDFOR
		
                    if(bandera==false){
                        arreglo.push(rowid);						
                    }
					console.log(arreglo);


		
            },
            onSelectAll : function(rowids,selected) {  
				limpiarArray(arreglo)
				
				if(selected){												
					var array = new Array();
					for(var i=0;i<rowids.length;i++){
					arreglo[i] = rowids[i];
					}
					//ids_trabajadores_2 = array;
				}//ENFIF
					console.log(arreglo);				
				
                
            },	
			
			

			
        });
		
		
        //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

	//------------------------
	
	
	$("#t_list").append($("#adelanto_01"));
	$("#t_list").append($("#adelanto_02"));
	
	
	//01 = indidual
   $("#adelanto_01").click(function(){ alert("gddd");
											
	var news = new Array();
	var j=0;
	for(var i=0; i<arreglo.length;i++){
		if(arreglo[i]!=null){
			news[j]=arreglo[i];
			j++;
		}
	}
	console.log(news);

	//-------------
	if(news.length>=1){ 
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
		registrarEtapa(cadena);
		//window.location.href="sunat_planilla/controller/Estructura_01TrabajadorController.php?oper=t-registro-baja&"+cadena;
		//$("#list-2").jqGrid('GridUnload');
		   jQuery("#list").trigger("reloadGrid"); 
			limpiarArray(arreglo);
			limpiarArray(news);
	}else{
		alert("Debe seleccionar un registro,\n para generar el Adelanto Individual");
	}

		
	});
   
   //02  = total
	$("#adelanto_02").click(function(){
		
		registrarEtapa(null);
	});


	
//--

		
//--

	
	

	
    }
	
	
	function registrarEtapa(cadena){
		var cod_periodo_remuneracion = document.getElementById('cod_periodo_remuneracion').value;
		var id_declaracion = document.getElementById('id_declaracion').value;
		if(cadena!=null){
			cadena = "?"+cadena;
		}else{
			cadena = '';	
		}
		
	$.ajax({
   type: "POST",
   url: "sunat_planilla/controller/EtapaPagoController.php"+cadena,
   data: {cod_periodo_remuneracion : cod_periodo_remuneracion,
   id_declaracion : id_declaracion,
   oper : "registrar_etapa"},
   async:true,
   success: function(datos){

   console.log("LLEGO "+datos);
   alert("Se Genero Adelanto Quincenal");
   cargar_pagina('sunat_planilla/view-empresa/view_periodo.php','#CapaContenedorFormulario');
   }
   }); 

		
	}
	
	
//-------------------------------------------------------------------

    function cargarTabla_Pago(id_etapa_pago){
		//var cod_periodo_remuneracion = document.getElementById('cod_periodo_remuneracion').value;
		//var id_declaracion = document.getElementById('id_declaracion').value;
		
        $("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/pagoController.php?oper=cargar_tabla&id_etapa_pago='+id_etapa_pago,
            datatype: 'json',
            colNames:['Id','Apaterno','Amaterno','valor','C.Costo','Opciones'],
            colModel :[
                {
                    name:'id_pago', 
                    editable:false, 
                    index:'id_pago',
                    search:false,
                    width:20,
                    align:'center'
                },		
                {
                    name:'apellido_paterno',
                    index:'apellido_paterno',
                    search:false, 
                    editable:false,
                    width:80, 
                    align:'center' 
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
                    search:false,
                    editable:false,
                    width:80,
                    align:'center'
                },
                {
                    name:'valor', 
                    index:'valor',
                    editable:false,
                    width:80,
                    align:'center'
                },
                {
                    name:'descripcion', 
                    index:'descripcion',
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
			height:'250px',
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_pago',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //caption: 'Trabajadores Activos',           
            //multiselect: true,
			
        });
		
		
        //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

	
    }	
	
	
	//--------------------------------------------------------------------------
	
function cargarTablaTrabajadoresPorEtapa(id_etapa_pago){ 
alert("cargarTablaTrabajadoresPorEtapa "+id_etapa_pago);
		
        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PagoController.php?oper=cargar_tabla&id_etapa_pago='+id_etapa_pago,
            datatype: 'json',
            colNames:['Id','Tipo doc','Numero Doc','APaterno',
                'AMaterno', 'Nombres','dias T.','Valor a.','Ccosto','Estado','Opciones'],
            colModel :[
                {
                    name:'id_pago', 
                    editable:false, 
                    index:'id_pago',
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
                    name:'dia_total',
                    index:'dia_total',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                },
				
                {
                    name:'valor_neto',
                    index:'valor_neto',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                },
				{
                    name:'ccosto',
                    index:'ccosto',
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
			//height:100,
            rowNum:9,
            rowList:[15,30,45],
            sortname: 'id_pago',
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



//----------------------------------------------------------------------
// GRID LINEAL
function cargarTablaPagoGrid_Lineal(id_pago){
		
        //$("#list").jqGrid('GridUnload');
        $("#list_lineal").jqGrid({
            url:'sunat_planilla/controller/PagoController.php?oper=grid_lineal&id_pago='+id_pago,
            datatype: 'json',
            colNames:['Id','Tipo doc','Numero Doc','APaterno',
                'AMaterno', 'Nombres','dias.','Ingresos','Descto.','Neto Pagar','Estado'],
            colModel :[
                {
                    name:'id_pago', 
                    editable:false, 
                    index:'id_pago',
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
                    name:'dias',
                    index:'dias',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                },
				
                {
                    name:'valor',
                    index:'valor',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                },
				{
                    name:'descuento',
                    index:'descuento',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                },
                {
                    name:'neto_pagar',
                    index:'neto_pagar',
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
			//caption: 'Lista de .',
           //pager: '#pager',			
			height:30,
            //rowNum:9,
            //rowList:[15,30,45],
            //sortname: 'id_pago',
            //sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //hiddengrid: false,

			
        });
		
		
        //--- PIE GRID
//	jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

	
    }
	
// DECLARACION SUNAT

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
			height:300,
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

	
    }//end


//--------------------------
    function cargarTablaPDeclaracionEtapaPago(id_pdeclaracion){ 
	
		var	arreglo = new Array();
        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
		//direction: "rtl",
            url:'sunat_planilla/controller/PlameDeclaracionController.php?oper=cargar_tabla_declaracion_etapa&id_pdeclaracion='+id_pdeclaracion,
            datatype: 'json',
            colNames:['Id','T.Doc','Num Doc','A. Paterno',
                'A. Materno', 'Nombres','opcion'],
            colModel :[
                {
                    name:'id_pago', 
                    editable:false, 
                    index:'id_pago',
                    search:false,
                    width:20,
                    align:'center'
                },		
                {
                    name:'cod_tipo_documento',
                    index:'cod_tipo_documento',
                    search:true, 
                    editable:false,
                    width:30, 
                    align:'center' 
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    search:true,
                    editable:false,
                    width:90,
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
                    search:true,
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'estado',
                    index:'estado',
                    search:false,
                    editable:false,
                    width:70,
                    align:'center'
                }											


            ],
            pager: '#pager',
			height:300,
			//width :390,
            rowNum:15,
            rowList:[15,30,45,60],
            sortname: 'id_pago',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //caption: 'Trabajadores Activos',
            toolbar: [true,"top"],
            multiselect: true,
            hiddengrid: false,
			
            onSelectRow: function(rowid, selected) {
					
                    var bandera = false;					 
                    for(var i = 0; i < arreglo.length ;i++){
                        // alert( rowid +"a igualar = " + ids_trabajadores_2[i]);
                        if(arreglo[i] == rowid){
                            // Ya existe rowid en array
                            bandera = true;
                            arreglo[i] =null;
                            break;
                        }
                    }//ENDFOR
		
                    if(bandera==false){
                        arreglo.push(rowid);						
                    }
					console.log(arreglo);


		
            },
            onSelectAll : function(rowids,selected) {  
				limpiarArray(arreglo)
				
				if(selected){												
					var array = new Array();
					for(var i=0;i<rowids.length;i++){
					arreglo[i] = rowids[i];
					}
					//ids_trabajadores_2 = array;
				}//ENFIF
					console.log(arreglo);				
				
                
            },	
			
			
			
			

			
        });
		
		
        //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});

	//------------------------------------------
	//------------------------
	
	
	$("#t_list").append($("#adelanto_mes_01"));
	$("#t_list").append($("#adelanto_mes_02"));
	
	
	//01 = indidual
   $("#adelanto_mes_01").click(function(){ 
											
	var news = new Array();
	var j=0;
	for(var i=0; i<arreglo.length;i++){
		if(arreglo[i]!=null){
			news[j]=arreglo[i];
			j++;
		}
	}
	console.log(news);

	//-------------
	if(news.length>=1){ 
		// -----arrayCadena
		var cadena='';
		for(var i=0; i < news.length;i++){	
			cadena+= "ids[]="+news[i];
			if(i != (news.length-1)){
				cadena+= "&";
			}	
		}
		//alert(cadena);

		generarDeclaracionPlanilla(id_pdeclaracion,$(this),cadena);

		   jQuery("#list").trigger("reloadGrid"); 
			limpiarArray(arreglo);
			limpiarArray(news);
	}else{
		alert("Debe seleccionar un registro,\n para generar periodo Mensual Individual");
	}

		
	});
   
   //02  = total
	$("#adelanto_mes_02").click(function(){
		
		//registrarEtapa(null);
		generarDeclaracionPlanilla(id_pdeclaracion,$(this),null);
	});
	
	
	
	
	
	
	
	
    }







//---------------------------------------------------------------
// DIALOG

//---------------------------------------------------
function editarDiaSubsidiado(id_pago){
	crearDialogoDiaSubsidiado();
	//id_pjoranada_laboral = 0;
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view-empresa/modal/dia_subsidiado.php",
   data: {id_pago : id_pago},
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
function editarDiaNoLaborado(id_pago){
	crearDialogoDiaNoLaborado();
	var dia_subsidiado = document.getElementById('dia_subsidiado').value
   $.ajax({
   type: "POST",
   url: "sunat_planilla/view-empresa/modal/dia_nolaborado.php",
   data: {id_pago : id_pago, dia_subsidiado : dia_subsidiado},
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






//-----------------------------------------------------------------------------------
//------------------------------------------------------------------------------------
//  intranet intranet intranet intranet intranet intranet intranet intranet

function crearDialogoPtrabajador(){
//alert('crearDialogoPtrabajador');
	$("#dialog-form-editarPtrabajador").dialog({ 
           
			autoOpen: false,
			height: 450,
			width: 400,
			modal: true,
                        
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {	
				

					if(true){
						var from_data =  $("#frmPtrabajador").serialize();
						//alert ("from_data = "+from_data);
						//---------------------------
						$.getJSON(
							'sunat_planilla/controller/PlameTrabajadorController.php?'+from_data,
							function(data){
								if(data){
									//jQuery("#list").trigger("reloadGrid");
									alert("Registro Se guardo correctamente.");	
									$("#dialog-form-editarPtrabajador").dialog('close');				
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



function editarPtrabajador(id_trabajador){  //alert (".");
crearDialogoPtrabajador();
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view-empresa/modal/edit_ptrabajador.php",
   data: { id_trabajador : id_trabajador },//Enviando a ediatarProducto.php vareiable=id_producto
   async:true,
   success: function(datos){
    $('#editarPtrabajador').html(datos);
    
    $('#dialog-form-editarPtrabajador').dialog('open');
   }
   }); 
}



//---------------------------------------------------------------
function generarDeclaracionPlanilla(id_pdeclaracion,obj,cadena){
	//obj.disabled = true;
	//obj.value = "Generando...";
		if(cadena!=null){
			cadena = "?"+cadena;
		}else{
			cadena = '';	
		}
	
	
    $.ajax({
   type: "POST",
   url: "sunat_planilla/controller/TrabajadorPdeclaracionController.php"+cadena,
   data: { oper: 'generar_declaracion', id_pdeclaracion : id_pdeclaracion },//Enviando a ediatarProducto.php vareiable=id_producto
   async:true,
   success: function(datos){
	
	console.log("Se Genero la planilla correctamente");
	//obj.value = "OK...";
	
	
   }
   }); 
	



}



//-------------------------------------------
function eliminarEtapaPago(id_etapa_pago){
	var estado = confirm("Seguro que desea eliminar?");
	
if(estado == true){
    $.ajax({
   type: "POST",
   url: "sunat_planilla/controller/EtapaPagoController.php",
   data: { oper: 'del', id_etapa_pago : id_etapa_pago },//Enviando a ediatarProducto.php vareiable=id_producto
   async:true,
   success: function(data){
	console.log("Se elimino correctamente");
	//jQuery("#list").trigger("reloadGrid");
	jQuery("#list10_d").trigger("reloadGrid");
	
   }
   }); 
}
}


//-------------------------------------------
function eliminarPago(id){
	var estado = confirm("Seguro que desea eliminar?");
	
	if(estado == true){
		$.ajax({
	   type: "POST",
	   url: "sunat_planilla/controller/PagoController.php",
	   data: { oper: 'del', id_pago : id },//Enviando a ediatarProducto.php vareiable=id_producto
	   async:true,
	   success: function(data){
		console.log("Se elimino correctamente");
		//jQuery("#list").trigger("reloadGrid");
		jQuery("#list").trigger("reloadGrid");
		
	   }
   }); 
}
}
	//-------------------------------------------------------
	//-------------------------------------------------------
	// FUNCIONES
	
function validarPago(){

var data = $("#formPago").serialize();

    $.ajax({
   type: "POST",
   url: "sunat_planilla/controller/PagoController.php?"+data,
   data: {oper : 'edit'},//Enviando a ediatarProducto.php vareiable=id_producto
   async:true,
   success: function(datos){

	alert(" ? "+datos);
	
	if(datos){
		alert("Se guardo Correctamente los datos");
	}else{
		alert("Error");
	}
	
	
   }
   }); 



}


