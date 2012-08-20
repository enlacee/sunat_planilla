// JavaScript Document


function tabla_Conf_Remuneracion(){
	
        $("#list").jqGrid({
            url:'sunat_planilla/controller/ConfPeriodoRemuneracionController.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['','Id','Descripcion','Valor'],
            colModel :[
				{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',
					formatoptions:{keys:true}
				},
				{
				name:'id_conf_periodo_remuneracion',
				sortable:true,
				key : true,
				index:'id_conf_periodo_remuneracion',
				width:55
				},		
  	
                {
                    name:'descripcion',
                    index:'descripcion',
                    search:true, 
					sortable:false,
                    editable:false,
                    width:120, 
                    align:'center' 
                },
                {
                    name:'valor', 
                    index:'valor',
                    search:true,
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:90,
                    align:'center'
                }		


            ],
            pager: '#pager',
			//height:250,
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_conf_periodo_remuneracion',
            sortorder: 'asc',
            viewrecords: true,
			editurl: "sunat_planilla/controller/ConfPeriodoRemuneracionController.php", 
            caption: 'Configuracion Remuneracion',
            //toolbar: [true,"top"],
            //multiselect: true,
            //hiddengrid: false,
			//jsonReader: {
			//repeatitems : false
			//},

			
        });
		
	
     //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:true,edit:false,del:false,search:true});
	//---------

}


function tabla_Conf_Uit(){
	
        $("#list").jqGrid({
            url:'sunat_planilla/controller/ConfUitController.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['','Id','Valor','Fecha Vigencia'],
            colModel :[
				{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',
					formatoptions:{keys:true}
				},
				{
				name:'id_conf_uit',
				sortable:true,
				key : true,
				index:'id_conf_uit',
				width:55
				},		
  	
                {
                    name:'valor',
                    index:'valor',
                    search:true, 
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:120, 
                    align:'center' 
                },
                {
                    name:'fecha', 
                    index:'fecha',
                    search:true,
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:90,
                    align:'center'
                }		


            ],
            pager: '#pager',
			//height:200,
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_conf_periodo_remuneracion',
            sortorder: 'asc',
            viewrecords: true,
			editurl: "sunat_planilla/controller/ConfUitController.php", 
            caption: 'Configuracion',
            //toolbar: [true,"top"],
            //multiselect: true,
            //hiddengrid: false,
			//jsonReader: {
			//repeatitems : false
			//},

			
        });
		
	
     //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:true,edit:false,del:false,search:false});
	//---------

}


//----------------------------------------------
function tabla_Conf_Afp(){
	
        $("#list").jqGrid({
            url:'sunat_planilla/controller/ConfAfpController.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['','Id','Ap. Obligatorio','Comision', 'Prima de Seguro','fecha'],
            colModel :[
				{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',
					formatoptions:{keys:true}
				},
				{
				name:'id_conf_afp',
				sortable:true,
				key : true,
				index:'id_conf_afp',
				width:55
				},		
  	
                {
                    name:'aporte_obligatorio',
                    index:'aporte_obligatorio',
                    search:false, 
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:120, 
                    align:'center' 
                },
                {
                    name:'comision',
                    index:'comision',
                    search:false, 
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:120, 
                    align:'center' 
                },				
                {
                    name:'prima_seguro',
                    index:'prima_seguro',
                    search:false, 
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:120, 
                    align:'center' 
                },			

                {
                    name:'fecha', 
                    index:'fecha',
                    search:false,
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:90,
                    align:'center'
                }		


            ],
            pager: '#pager',
			height:'200px',
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_conf_afp',
            sortorder: 'asc',
            viewrecords: true,
			editurl: "sunat_planilla/controller/ConfAfpController.php", 
            caption: 'Configuracion Tasas %',
            //toolbar: [true,"top"],
            //multiselect: true,
            //hiddengrid: false,
			//jsonReader: {
			//repeatitems : false
			//},

			
        });
		
	
     //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:true,edit:false,del:false,search:false});
	//---------

}


//------------------------------------------------------
function tabla_Conf_Afamiliar(){
	
        $("#list").jqGrid({
            url:'sunat_planilla/controller/ConfAsignacionFamimiliarController.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['','Id','Tasa','Fecha Vigencia'],
            colModel :[
				{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',
					formatoptions:{keys:true}
				},
				{
				name:'id_conf_uit',
				sortable:true,
				key : true,
				index:'id_conf_uit',
				width:55
				},		
  	
                {
                    name:'valor',
                    index:'valor',
                    search:true, 
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:120, 
                    align:'center' 
                },
                {
                    name:'fecha', 
                    index:'fecha',
                    search:true,
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:90,
                    align:'center'
                }		


            ],
            pager: '#pager',
			heigth:'200px',
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_conf_periodo_remuneracion',
            sortorder: 'asc',
            viewrecords: true,
			editurl: "sunat_planilla/controller/ConfAsignacionFamimiliarController.php", 
            caption: 'Configuracion',
            //toolbar: [true,"top"],
            //multiselect: true,
            //hiddengrid: false,
			//jsonReader: {
			//repeatitems : false
			//},

			
        });
		
	
     //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:true,edit:false,del:false,search:false});
	//---------

}

//-----------------------------------------------------------------------------------

function tabla_Conf_Onp(){
			
        $("#list").jqGrid({
            url:'sunat_planilla/controller/ConfOnpController.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['','Id','Tasa','Fecha Vigencia'],
            colModel :[
				{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',
					formatoptions:{keys:true}
				},
				{
				name:'id_conf_onp',
				sortable:true,
				key : true,
				index:'id_conf_onp',
				width:55
				},		
  	
                {
                    name:'valor',
                    index:'valor',
                    search:true, 
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:120, 
                    align:'center' 
                },
                {
                    name:'fecha', 
                    index:'fecha',
                    search:true,
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:90,
                    align:'center'
                }		


            ],
            pager: '#pager',
			heigth:'200px',
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_conf_periodo_remuneracion',
            sortorder: 'asc',
            viewrecords: true,
			editurl: "sunat_planilla/controller/ConfOnpController.php", 
            caption: 'Configuracion',
            //toolbar: [true,"top"],
            //multiselect: true,
            //hiddengrid: false,
			//jsonReader: {
			//repeatitems : false
			//},

			
        });
		
	
     //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:true,edit:false,del:false,search:false});
	//---------

}


//-----------------------------------------------------------------------------------

function tabla_Conf_Essalud(){
			
        $("#list").jqGrid({
            url:'sunat_planilla/controller/ConfEssaludController.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['','Id','Tasa','Fecha Vigencia'],
            colModel :[
				{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',
					formatoptions:{keys:true}
				},
				{
				name:'id_conf_essalud',
				sortable:true,
				key : true,
				index:'id_conf_essalud',
				width:55
				},		
  	
                {
                    name:'valor',
                    index:'valor',
                    search:true, 
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:120, 
                    align:'center' 
                },
                {
                    name:'fecha', 
                    index:'fecha',
                    search:true,
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:90,
                    align:'center'
                }		


            ],
            pager: '#pager',
			heigth:'200px',
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_conf_periodo_remuneracion',
            sortorder: 'asc',
            viewrecords: true,
			editurl: "sunat_planilla/controller/ConfEssaludController.php", 
            caption: 'Configuracion',
            //toolbar: [true,"top"],
            //multiselect: true,
            //hiddengrid: false,
			//jsonReader: {
			//repeatitems : false
			//},

			
        });
		
	
     //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:true,edit:false,del:false,search:false});
	//---------

}


//-----------------------------------------------------------------------------------

function tabla_Conf_SueldoBasico(){
			
        $("#list").jqGrid({
            url:'sunat_planilla/controller/ConfSueldoBasicoController.php?oper=cargar_tabla',
            datatype: 'json',
            colNames:['','Id','Valor','Fecha Vigencia'],
            colModel :[
				{name: 'myac', width:80, fixed:true, sortable:false, resize:false, formatter:'actions',
					formatoptions:{keys:true}
				},
				{
				name:'id_conf_sueldo_basico',
				index:'id_conf_sueldo_basico',
				sortable:true,
				key : true,				
				width:55
				},		
  	
                {
                    name:'valor',
                    index:'valor',
                    search:true, 
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:120, 
                    align:'center' 
                },
                {
                    name:'fecha', 
                    index:'fecha',
                    search:true,
					sortable:false,
                    editable:true,
					editrules:{required:true},
                    width:90,
                    align:'center'
                }		


            ],
            pager: '#pager',
			heigth:'200px',
            rowNum:10,
            rowList:[10,20,30],
            sortname: 'id_conf_periodo_remuneracion',
            sortorder: 'asc',
            viewrecords: true,
			editurl: "sunat_planilla/controller/ConfSueldoBasicoController.php", 
            caption: 'Configuracion',
            //toolbar: [true,"top"],
            //multiselect: true,
            //hiddengrid: false,
			//jsonReader: {
			//repeatitems : false
			//},

			
        });
		
	
     //--- PIE GRID
	jQuery("#list").jqGrid('navGrid','#pager',{add:true,edit:false,del:false,search:false});
	//---------

}
