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

    function cargarTablaPdeclaracionEmpresa(session_id_pdeclaracion){ 
		//var d = new Date();
		//var n = d.getFullYear(); 
		var anio = document.getElementById('anio').value || 2012;
		
		
        $("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PlameDeclaracionController.php?oper=cargar_tabla_empresa&anio='+anio,
            datatype: 'json',
            colNames:['Id','Periodo','Estado','Modificar','Operaciones'],
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
                    name:'estado',
                    index:'estado',
                    search:false, 
                    editable:false,
                    width:70, 
                    align:'center',
                    hidden:true
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
                    width:100,
                    align:'center'
                }


            ],
            pager: '#pager',
			height:'280px',
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
			
            //--            
            var data = $("#list").getRowData(ids);
            //console.dir(data);

            //--
            if(ids == null) {
				ids=0;                
				if(jQuery("#list10_d").jqGrid('getGridParam','records') >0 )
				{
					jQuery("#list10_d").jqGrid('setGridParam',{ //EtapaPagoController
						url:'sunat_planilla/controller/EtapaPagoController.php?oper=cargar_tabla&id_declaracion='+ids+'&estado_pdeclaracion='+data.estado,
						page:1});
																
					jQuery("#list10_d").jqGrid('setCaption',"E.P.de : "+ids)
					.trigger('reloadGrid');
				}
			} else {
					jQuery("#list10_d").jqGrid('setGridParam',{
						url:'sunat_planilla/controller/EtapaPagoController.php?oper=cargar_tabla&id_declaracion='+ids+'&estado_pdeclaracion='+data.estado,
						page:1});
				
				jQuery("#list10_d").jqGrid('setCaption',"E.P.de : "+ids)
				.trigger('reloadGrid');	
                
                if(data.estado ==1){	
 				   jQuery("#id_pdeclaracion").val(ids);
                }else{
                     jQuery("#id_pdeclaracion").val(null);
                }
			}
		},
        gridComplete    : function(){  //alert("grid okD");
    
        var ids = $("#list").getDataIDs();
        var act;
        for(var i=0;i<ids.length;i++){
            var data = $("#list").getRowData(ids[i]);
            if (data.id_pdeclaracion == session_id_pdeclaracion) {
                act =' <b class="red">'+data.periodo+'</b>';
                $("#list").setRowData(ids[i],{periodo: act });
            }
        }//ENDFOR
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
                console.log(data);
							
				if(data){
					//cargar_pagina('sunat_planilla/view-plame/edit_declaracion.php?periodo='+periodo,'#CapaContenedorFormulario');
					alert("Se registro correctamente el Periodo");
					javascript:cargar_pagina('sunat_planilla/view-empresa/view_periodo.php','#CapaContenedorFormulario')
				}else{
					alert("Periodo ya se encuntra registrado\nOcurrio un error.");
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
                'AMaterno', 'Nombres','F inicio','F fin','Sueldo','C.Costo'],
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
                    align:'center',
                    hidden:true,
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
                    width:80,
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
                    editable:false,
                    width:90,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    }                     
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
                }


                		


            ],
            pager: '#pager',
            rownumbers: true,
			height:320,
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_trabajador',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            caption: 'list',
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
   $("#adelanto_01").click(function(){ //alert("gddd");
											
	var news = new Array();
	var j=0;
	for(var i=0; i<arreglo.length;i++){
		if(arreglo[i]!=null){
			news[j]=arreglo[i];
			j++;
		}
	}
	//console.log(news);

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

console.log("Cargandou..."+id_etapa_pago);
//alert("Cargando... "+id_etapa_pago);


		var arreglo = new Array();
        
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PagoController.php?oper=cargar_tabla&id_etapa_pago='+id_etapa_pago,
            datatype: 'json',
            colNames:['Id','Tipo doc','Numero Doc','APaterno',
                'AMaterno', 'Nombres','dias T.','Sueldo.','Ccosto','Opciones'],
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
                    align:'center',
                    hidden:true,
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
                    width:80,
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
                    editable:false,
                    width:90,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    }                     
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
                    name:'sueldo_neto',
                    index:'sueldo_neto',
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
                    name:'opciones',
                    index:'opciones',
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                }	
            ],
            pager: '#pager',
			height:320,
            rowNum:9,
            rowList:[15,30,45],
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


	//$("#t_list").append($("#reporte15_01"));
	$("#t_list").append($("#reporte15_02"));
	
	$("#t_list").append($("#reporte15_mas"));
	
	var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
	var id_etapa_pago = document.getElementById('id_etapa_pago').value;

	
	
	
	//01 = indidual
   $("#reporte15_01").click(function(){
											
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
	
		var url = "sunat_planilla/controller/PagoController.php?"+cadena;
		url +="&oper=recibo15&id_pdeclaracion="+id_pdeclaracion+"&id_etapa_pago="+id_etapa_pago;
		
		window.location.href = url;
		//$("#list-2").jqGrid('GridUnload');
		   jQuery("#list").trigger("reloadGrid"); 
			limpiarArray(arreglo);
			limpiarArray(news);
	}else{
		alert("Debe seleccionar un registro,\n para generar el Adelanto Individual");
	}

		
	});
   
   //02  = total
	$("#reporte15_02").click(function(){
		
		//registrarEtapa(null);
		var url = "sunat_planilla/controller/PagoController.php";
		url +="?oper=recibo15&id_pdeclaracion="+id_pdeclaracion+"&id_etapa_pago="+id_etapa_pago;
		url +="&todo=todo";
		
		window.location.href = url;
		//window.open(url);

	});
	
	   //02  = total
	$("#reporte15_mas").click(function(){
		
		editarPagoMasOpciones(id_pdeclaracion,id_etapa_pago);
		/*
		var url = "sunat_planilla/controller/PagoController.php";
		url +="?oper=recibo15&id_pdeclaracion="+id_pdeclaracion+"&id_etapa_pago="+id_etapa_pago;
		
		window.location.href = url;
		//window.open(url);
		*/

	});


}
	
	
	
	//---- new 06/09/2012
function editarPagoMasOpciones(id_pdeclaracion,id_etapa_pago){
	crearDialogoMasOp();

   $('#dialog_editarPagoMasOP').dialog('open');

   $.ajax({
   type: "POST",
   url: "sunat_planilla/view-empresa/modal/edit_mas_opciones.php",
   data: {id_pdeclaracion : id_pdeclaracion, id_etapa_pago : id_etapa_pago},
   async:true,
   success: function(datos){
    $('#data_editarPagoMasOP').html(datos);
    
    
   }
   }); 
}

//

	function crearDialogoMasOp(){

	$("#dialog_editarPagoMasOP").dialog({ 
           
			autoOpen: false,
			height: 300,
			width: 280,
			modal: true,                        
			buttons: {
				'Generar': function() {	
				var id_pdeclaracion = document.getElementById('id_pdeclaracion').value;
				var id_etapa_pago =  document.getElementById('id_etapa_pago').value;
				
				var id_establecimientos = document.getElementById('id_establecimientos').value;		
                //var id_establecimientos = document.getElementById('cbo_establecimiento_local').value;    
                console.log("error carga de combo dinamico centro costo ??"); 
                		
				var cboCentroCosto = document.getElementById('cboCentroCostoX').value;
				
				var id = id_establecimientos.split('|');
		
				
				var url = "sunat_planilla/controller/PagoController.php";
				url += "?oper=recibo15";
				url += "&id_pdeclaracion="+id_pdeclaracion;
				url += "&id_etapa_pago="+id_etapa_pago;
				url += "&id_establecimientos="+id[0];
				url += "&cboCentroCosto="+cboCentroCosto;
				//alert(url);
		
				window.location.href = url;				
				},
                'Cancelar': function() {
                    $(this).dialog('close');
                },


                                
			},			
			open: function() {},
			close: function() {}
	});
}

//---------------------------------------------------------------------


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
					sortable: false,
                    search:false,
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'apellido_paterno', 
                    index:'apellido_paterno',
					sortable: fale,
                    editable:false,
                    width:80,
                    align:'center'
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
					sortable: false,
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'nombres', 
                    index:'nombres',
					sortable: false,
                    editable:false,
                    width:90,
                    align:'center'
                },
                {
                    name:'dias',
                    index:'dias',
                    search:false,
					sortable: false,
                    editable:false,
                    width:60,
                    align:'center'
                },
				
                {
                    name:'valor',
                    index:'valor',
					sortable: false,
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                },
				{
                    name:'descuento',
                    index:'descuento',
					sortable: false,
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                },
                {
                    name:'neto_pagar',
                    index:'neto_pagar',
					sortable: false,
                    search:false,
                    editable:false,
                    width:60,
                    align:'center'
                },											
                {
                    name:'estado',
                    index:'estado',
					sortable:false,
                    search:false,                    
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
                    width:30,
                    align:'center'
                },		
                {
                    name:'cod_tipo_documento',
                    index:'cod_tipo_documento',
                    search:true, 
                    editable:false,
                    width:90, 
                    align:'center',
                    search:false,

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
                    width:100,
                    align:'center'
                },
                {
                    name:'apellido_materno', 
                    index:'apellido_materno',
                    editable:false,
                    width:100,
                    align:'center'
                },
				
                {
                    name:'nombres',
                    index:'nombres',
                    search:true,
                    editable:false,
                    width:100,
                    align:'center'
                },
                {
                    name:'estado',
                    index:'estado',
                    search:false,
                    editable:false,
                    width:100,
                    align:'center',
                    search:false,
                }											


            ],
            pager: '#pager',
            //rownumbers: true,
			height:320,
			//width :390,
            rowNum:50,
            rowList:[50],
            sortname: 'id_pago',
            sortorder: 'asc',
            viewrecords: true,
            gridview: true,
            //caption: 'List',
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
		
		generarDeclaracionPlanilla(id_pdeclaracion,$(this),null);
	});
	
	
	
	
	
	
	
	
    }







//---------------------------------------------------------------
// DIALOG

//---------------------------------------------------
function editarDiaSubsidiado(id_pago){
	crearDialogoDiaSubsidiado();	
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
			width: 450,
			modal: true,
                        
			buttons: {
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
				},
                'Cancelar': function() {
                $(this).dialog('close');
                },                



                                
			},
			open: function() {},
			close: function() {}
	});
}



function editarPtrabajador(id_trabajador){ 
crearDialogoPtrabajador();
    $.ajax({
   type: "POST",
   url: "sunat_planilla/view-empresa/modal/edit_ptrabajador.php",
   data: { id_trabajador : id_trabajador },
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
   success: function(data){	

        if(data){
            alert("Se Genero la planilla correctamente");   
        }else{
            alert("Ocurrio un error");
        }

        cargar_pagina('sunat_planilla/view-empresa/view_periodo.php','#CapaContenedorFormulario')

		
	
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
   data: { oper: 'del', id_etapa_pago : id_etapa_pago },
   async:true,
   success: function(data){
	console.log("Se elimino correctamente");
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
	   data: { oper: 'del', id_pago : id },
	   async:true,
	   success: function(data){
		console.log("Se elimino correctamente");		
		jQuery("#list").trigger("reloadGrid");
		
	   }
   }); 
}
}
//---------------------------------
function eliminarTrabajadorPdeclaracion(id,id_trabajador){
	
    var id_pdeclaracion = $('#id_declaracion').val();
    //alert("id_pdeclaracion = "+id_pdeclaracion);

	var estado = confirm("Seguro que desea eliminar?");
	if(estado == true){
		$.ajax({
	   type: "POST",
	   url: "sunat_planilla/controller/TrabajadorPdeclaracionController.php",
	   data: { oper: 'del', id : id, id_trabajador : id_trabajador, id_pdeclaracion : id_pdeclaracion },
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


//---------------------------------------------------------------------------------
//---------------------------------------------------------------------------------
function crearDialogoNewRPC(tipo_conceptos){

	$("#dialog_new_trabajador_rpc").dialog({ 
           
			autoOpen: false,
			height: 230,
			width: 350,
			modal: true,
                        
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				    },
				    'Guardar': function() {	
				
					//---	VALIDACION ECHA EN 	modal/detalle_persona_direccion.php	
					var form = $("#nuevo_trabajador_rpc");
					var estado_form = validarNewRPC(form); //$("#form_direccion").valid();
					
					if(estado_form){
	
						var from_data =  form.serialize();

						var tipo_concepto = form[0].tipo_concepto.value;
                        if(tipo_concepto=='2'){
                            console.log("entro");
                        //---------------------------
                        $.getJSON(
                            'sunat_planilla/controller/RegistroConceptoEController.php?'+from_data,
                            function(data){                             
                                
                                if(data.estado){                                    
                                    alert("Se Registro Correctamente.");    
                                    $("#dialog_new_trabajador_rpc").dialog('close');
                                    jQuery("#list").trigger("reloadGrid");
                                        
                                }else{
                                    alert(data.mensaje);
                                                            
                                }
                            }
                        );
                        //---------------------------
                        }else{

						//---------------------------
						$.getJSON(
							'sunat_planilla/controller/RegistroPorConceptoController.php?'+from_data,
							function(data){								
                                
								if(data.estado){									
									alert("Se Registro Correctamente.");	
									$("#dialog_new_trabajador_rpc").dialog('close');
									jQuery("#list").trigger("reloadGrid");
										
								}else{
                                    alert(data.mensaje);
															
								}
							}
						);	
						//---------------------------			
                        }
						
					}//ENDIF
				
									
				}
                                
			},
			open: function() {},
			close: function() {}
	});
}



function validarNewRPC(obj){
	rpta = false;
	
	var numDni = obj[0].num_documento.value;
	
	if(!isNaN(numDni)){
		if((numDni.length==8)){
			rpta = true;
		}else{
			alert("Solo se permite 8 digitos");
			rpta = false;
		}
		
	}else{
		alert("Solo se permite Numeros");
		rpta = false;
	}
	return rpta;


}



function newRPC(id,tipo_concepto){
	crearDialogoNewRPC();

    $('#dialog_new_trabajador_rpc').dialog('open');

    $.ajax({
   type: "POST",
   url: "sunat_planilla/view-empresa/modal/new_trabajador_rpc.php",
   data: { cod_detalle_concepto : id, tipo_concepto: tipo_concepto },
   async:true,
   success: function(datos){
    $('#new_trabajador_rpc').html(datos);
    
    
   }
   }); 
}


function editar_EstadoRPC(id,estado){
	var str = (estado ==1) ? "ACTIVO" : "INACTIVO"; 
	var bandera = confirm("Seguro de cambiar a : "+str);
	
if(bandera){
    $.ajax({
   type: "POST",
   url: "sunat_planilla/controller/RegistroPorConceptoController.php?oper=edit-estado",
   data: { id : id, estado : estado },
   async:true,
   success: function(data){
	if(data){
		jQuery("#list").trigger("reloadGrid");
	}else{
		console.log("Ocurrio un Error");
	}
	
	}
   }); 
}

}



function editar_EstadoRPCE(id,estado){
    var str = (estado ==1) ? "ACTIVO" : "INACTIVO"; 
    var bandera = confirm("Seguro de cambiar a : "+str);
    
if(bandera){
    $.ajax({
   type: "POST",
   url: "sunat_planilla/controller/RegistroConceptoEController.php?oper=edit-estado",
   data: { id : id, estado : estado },
   async:true,
   success: function(data){
    if(data){
        jQuery("#list").trigger("reloadGrid");
    }else{
        console.log("Ocurrio un Error");
    }
    
    }
   }); 
}

}



//planilla vaciones 12/09/2012
function crearDialogoVacacion(){

	$("#dialog_view_vacacion").dialog({ 
           
			autoOpen: false,
			height: 400,
			width: 540,
			modal: true,
           /*             
			buttons: {
                   'Cancelar': function() {
					$(this).dialog('close');
				},
				'Guardar': function() {	
				
					//---	VALIDACION ECHA EN 	modal/detalle_persona_direccion.php	
					var form = $("#nuevo_trabajador_rpc");
					//var estado_form = validarNewRPC(form); //$("#form_direccion").valid();
					
					if(estado_form){
	
						var from_data =  form.serialize();						
						//---------------------------
						$.getJSON(
							'sunat_planilla/controller/RegistroPorConceptoController.php?'+from_data,
							function(data){
								if(data.estado){									
									alert("Se Registro Correctamente.");	
									$("#dialog_new_trabajador_rpc").dialog('close');
									jQuery("#list").trigger("reloadGrid");
								}else{
                                    alert(data.mensaje);	
								}
							}
						);	
						//---------------------------			
						
					}//ENDIF
				
									
				}
                                
			},*/
			open: function() {},
			close: function() {}
	});
}



function verVacacion(id,name){
	crearDialogoVacacion();	
    $('#dialog_view_vacacion').dialog('open');
$.ajax({
   type: "POST",
   url: "sunat_planilla/view-empresa/modal/view_vacaciones.php",
   data: {id_trabajador:id,name :name },//Enviando a ediatarProducto.php vareiable=id_producto
   async:true,
   success: function(datos){
    $('#view_vacacion').html(datos);
    
    //$('#dialog_view_vacacion').dialog('open');
   }
   }); 
	

}

//--------------------------
function guardarVacacionProgramada(){
	var id_trabajador = document.getElementById('id_trabajador').value;
    /*
	var fv_calculado = document.getElementById('fv_calculado').value;
	var fv_programado = document.getElementById('fv_programado').value;
    var tipo_vacacion = document.getElementById('tipo_vacacion').value;
	*/
    $data = $('#FrmVacacion').serialize();

$.ajax({
   type: "POST",
   url: "sunat_planilla/controller/VacacionController.php?"+$data,
   data: {
	   oper : 'add',
	   id_trabajador : id_trabajador/*,
	   fv_calculado : fv_calculado,
	   fv_programado: fv_programado,
       tipo_vacacion: tipo_vacacion*/
	    },//Enviando a ediatarProducto.php vareiable=id_producto
        
   async:true,
   success: function(data){
	   alert("Se Registro Correctamente.");
	   $('#dialog_view_vacacion').dialog('close');
	   jQuery("#list").trigger("reloadGrid");
   }
   }); 

}


//---------------------
function eliminarVacacion(id){

$.ajax({
   type: "POST",
   url: "sunat_planilla/controller/VacacionController.php",
   data: {oper : 'del',id_vacacion : id},
   async:true,
   success: function(data){
	   if(data){
	   	alert("Se Elimino Correctamente.");
		jQuery("#list").trigger("reloadGrid");
	   }else{
		alert("Ocurrio un error.");  
	   }
	   $('#dialog_view_vacacion').dialog('close');
	   
   }
   }); 


}




//------------------------------------------------------


	function cargarTablaPrestamo(){

		//var arg = (typeof cod_estado == 'undefined') ? 0 : cod_estado;
	
        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/PrestamoController.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['id','Numero Doc','Paterno',
                'Materno','Nombres','Fecha Prestamo','Estado','Opciones'],
            colModel :[
                {
                    name:'id_prestamo',
                    key : true, 
                    editable:false, 
                    index:'id_prestamo',
                    search:false,
                    width:20,
                    align:'center',
					hidden:true
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    editable:false,
                    width:70,
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
                    width:80,
                    align:'center',
                },
                
                {
                    name:'nombres', 
                    index:'nombres',
                    editable:true,
                    width:80,
                    align:'center'
                },
                {
                    name:'fecha_inicio', 
                    index:'fecha_inicio',
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
                    name:'opciones',
                    index:'opciones',
                    search:false,
                    editable:false,
                    width:100, 
                    align:'center'
                }							

		
            ],
            pager: '#pager',
            //rownumbers: true,
            //autowidth: true,
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_prestamo',
            sortorder: 'asc',
            viewrecords: true,
            /*gridview: true,*/
            caption: 'List',
			//multiselect: false,
			//hiddengrid: true,
            onSelectRow: function(ids) {},
            height:340,
           // width:720
        });
        //--- PIE GRID
        jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
					
    }


	function cargarTablaParaTiFamilia(){

		//var arg = (typeof cod_estado == 'undefined') ? 0 : cod_estado;
	
        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'sunat_planilla/controller/ParatiFamiliaController.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['id','Numero Doc','Paterno',
                'Materno','Nombres','Descripcion','Estado','Opciones'],
            colModel :[
                {
                    name:'id_para_ti_familia',
                    key : true, 
                    editable:false, 
                    index:'id_para_ti_familia',
                    search:false,
                    width:20,
                    align:'center',
					hidden:false
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    editable:false,
                    width:70,
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
                    width:80,
                    align:'center',
                },
                {
                    name:'nombres', 
                    index:'nombres',
                    editable:true,
                    width:80,
                    align:'center'
                },
				
                {
                    name:'descripcion', 
                    index:'descripcion',
                    editable:false,
                    width:120,
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
                    name:'opciones',
                    index:'opciones',
                    search:false,
                    editable:false,
                    width:100, 
                    align:'center'
                }							

		
            ],
            pager: '#pager',
            //rownumbers: true,
            //autowidth: true,
            rowNum:10,
            rowList:[10],
            sortname: 'id_para_ti_familia',
            sortorder: 'asc',
            viewrecords: true,
            /*gridview: true,*/
            caption: 'List',
			//multiselect: false,
			//hiddengrid: true,
            onSelectRow: function(ids) {},
            height:340,
           // width:720
        });
        //--- PIE GRID
        jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
					
    }


//--------------------------------------------------------------------------------------

function grabarParaTiFamilia(obj){
	
var id_trabajador = document.getElementById('id_trabajador').value;

if(id_trabajador != ''){
	

var from_data =  $("#FrmParaTiFamilia").serialize();

//-----	
$.ajax({
	type: 'post',
	dataType: 'json',
	url: 'sunat_planilla/controller/ParatiFamiliaController.php?'+from_data,
	data: {},
	success: function(data){
					
		if(data){
			alert("Se registro correctamente");
			javascript:cargar_pagina('sunat_planilla/view-empresa/view_cparatifamilia.php','#CapaContenedorFormulario')
			//javascript:cargar_pagina('sunat_planilla/view-empresa/view_periodo.php','#CapaContenedorFormulario')
		}else{
			alert("Ocurrio un error.");
		}		
	}
});
//-------
}else{
	alert("Ingrese datos validos");	
}

}






