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
   		{name:'id_etapa_pago',index:'id_etapa_pago', width:55},
   		{name:'descripcion',index:'descripcion', width:180},
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
    function cargarTabla_Etapa(){
		var cod_periodo_remuneracion = document.getElementById('cod_periodo_remuneracion').value;
		var id_declaracion = document.getElementById('id_declaracion').value;
		
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
                    search:false,
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
	
	
	function registrarEtapa(){
		var cod_periodo_remuneracion = document.getElementById('cod_periodo_remuneracion').value;
		var id_declaracion = document.getElementById('id_declaracion').value;
		
	$.ajax({
   type: "POST",
   url: "sunat_planilla/controller/EtapaPagoController.php",
   data: {cod_periodo_remuneracion : cod_periodo_remuneracion,
   id_declaracion : id_declaracion,
   oper : "registrar_etapa"},
   async:true,
   success: function(datos){

   console.log("LLEGO "+datos);
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
			heigth:'250px',
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
	
function cargarTablaTrabajadoresPorEtapa(id_etapa_pago){ alert("cargarTablaTrabajadoresPorEtapa id_etapa_pago "+id_etapa_pago);
		
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
			heigth:'450px',
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
			heigth:'80px',
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
