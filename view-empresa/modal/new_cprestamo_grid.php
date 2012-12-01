<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<link rel="stylesheet" type="text/css" href="../../../css/main.css" />

<link rel="stylesheet" type="text/css" href="../../../anb_themes/base/jquery.ui.all.css">
<link rel="stylesheet" type="text/css" href="../../../anb_css/ui.jqgrid.css" media="screen" />

<script language="javascript" type="text/javascript" src="../../../js/jquery.js"></script>
<script src="../../../anb_ui/i18n/jquery.ui.datepicker-es.js"></script>
<script src="../../../anb_ui/jquery-ui-1.8.16.custom.js" type="text/javascript"></script>

<script src="../../../anb_js/grid.locale-es.js" type="text/javascript"></script>
<script src="../../../anb_js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../../../anb_js/jquery.jqGrid.src.js" type="text/javascript"></script>
<script src="../../../anb_js/src/grid.subgrid.js" type="text/javascript"></script>

<!-- <script src="anb_js/src/grid.common.js" type="text/javascript"></script>
<script src="anb_js/src/grid.postext.js" type="text/javascript"></script>
-->

<!-- -->
<script src="../../view/js/misfunciones_sunat.js" type="text/javascript"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>


</head>

<body>
<script type="text/javascript">
    $(document).ready(function(){
                  
        //$( "#tabs").tabs();
		cargarTablaGridTrabajador();
		
	});
		

function cargarTablaGridTrabajador(cod_estado){
		
		var arg = (typeof cod_estado == 'undefined') ? 0 : cod_estado;
	
        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'../../controller/PrestamoController.php?oper=cargar_tabla_trabajador&estado='+arg,
            datatype: 'json',
            colNames:['id','tipo','Numero Doc','Paterno',
                'Materno','Nombres','# Pres','Opciones'],
            colModel :[
                {
                    name:'id_trabajador',
                    key : true, 
                    editable:false, 
                    index:'id_trabajador',
                    search:false,
                    width:20,
                    align:'center',
					hidden:false
                },		
                {
                    name:'nombre_tipo_documento',
                    index:'nombre_tipo_documento',
                    search:false, 
                    editable:false,
                    width:50, 
                    align:'center',
					hidden:true
                },
                {
                    name:'num_documento', 
                    index:'num_documento',
                    editable:false,
                    width:80,
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
                    width:80,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    } 					
                },
                
                {
                    name:'nombres', 
                    index:'nombres',
                    editable:true,
                    width:60,
                    align:'center',
                    cellattr: function(rowId, value, rowObject, colModel, arrData) {
                        return " style=display:none; ";
                    } 					
                },
                {
                    name:'numero_prestamo',
                    index:'numero_prestamo',
                    search:false,
                    editable:false,
                    width:30, 
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
            sortname: 'id_trabajador',
            sortorder: 'asc',
            viewrecords: true,
            /*gridview: true,*/
            //caption: 'Lista de Personal',
			//multiselect: false,
			//hiddengrid: true,
            onSelectRow: function(ids) {},
            height:220,
           // width:720
        });
        //--- PIE GRID
        jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
					
    }

</script>


<table id="list">
</table>
<div id="pager">




</body>
</html>