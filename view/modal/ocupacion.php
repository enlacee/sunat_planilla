<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<!-- new -->
<link rel="stylesheet" type="text/css" href="../../../css/main.css" />
<link rel="stylesheet" type="text/css" href="../../../css/smoothness/jquery-ui-1.8.9.custom.css">
<link rel="stylesheet" type="text/css" href="../../../css/ui.jqgrid.css" media="screen"/>


<!-- JS-->
<script language="javascript" type="text/javascript" src="../../../js/jquery-1.7_min.js"></script>
<script language="javascript" type="text/javascript" src="../../../js/jquery-ui-1.8.9.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="../../../js/function.js"></script>


<script language="javascript" type="text/javascript" src="../../../js/grid.locale-es.js"></script>
<script language="javascript" type="text/javascript" src="../../../js/jquery.jqGrid.min.js"></script>
<script language="javascript" type="text/javascript" src="../../../js/jquery.jqGrid.src.js"></script>
<script language="javascript" type="text/javascript" src="../../../js/src/grid.subgrid.js"></script>


<!-- -->
<script src="../../view/js/misfunciones_sunat.js" type="text/javascript"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>


</head>

<body>
<script type="text/javascript">
    $(document).ready(function(){
                  
        //$( "#tabs").tabs();
		//cargarTablaGridTrabajador();
        cargarTablaOcupacionTrabajador();
		
	});


//-------------------------------------------------
function buscarCodigoDeInputaComboModal(){ // view/modal/usado ocupacion.php
    
    var obj =document.form_trabajador.txt_ocupacion_codigo;    
    
    var aguja = obj.value;  
    var eCombo = document.form_trabajador.cboOcupacion;
    var counteo = eCombo.options.length;
    
    var encontro = false
    
    for(i=0;i<counteo;i++){
        if(aguja == eCombo.options[i].value){           
            eCombo.options[i].selected = true;
            encontro = true;
            break;
        }           
    }//end for  
    if(encontro==false){
        eCombo.options[0].selected = true;
        obj.value="";
    }
}//ENDFN
//--------------------------------------------------
		

function cargarTablaOcupacionTrabajador(cod_estado){
		
		var arg = (typeof cod_estado == 'undefined') ? 0 : cod_estado;
        var codigo = "<?php echo $_REQUEST['cbo_categoria_ocupacional'];?>";
        console.log(codigo);
	
        //$("#list").jqGrid('GridUnload');
        $("#list").jqGrid({
            url:'../../controller/OcupacionController.php?oper=cargar_tabla&estado='+arg+'&cbo_categoria_ocupacional='+codigo,
            datatype: 'json',
            colNames:['Codigo','descripcion','Opciones'],
            colModel :[
                {
                    name:'cod_ocupacion_p',
                    key : true, 
                    editable:false, 
                    index:'cod_ocupacion_p',
                    search:true,
                    width:70,
                    align:'left',
					hidden:false
                },		
                {
                    name:'descripcion',
                    index:'descripcion',
                    search:true, 
                    editable:false,
                    width:300, 
                    align:'left'
                },

                {
                    name:'opciones',
                    index:'opciones',
                    search:false,
                    editable:false,
                    sortable:false,
                    width:110, 
                    align:'left'
                }							

		
            ],
            pager: '#pager',
            //rownumbers: true,
            //autowidth: true,
            rowNum:10,
            rowList:[10,20],
            sortname: 'cod_ocupacion_p',
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