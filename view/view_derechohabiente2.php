<?php
//*******************************************************************//
require_once('ide.php');
//*******************************************************************//
?>
<?php
//require_once('../dao/PersonaDao.php');
//require_once('../controller/PersonaController.php')


$dao_persona = new PersonaDao();
$data_persona = $dao_persona->buscarPersonaPorId($_REQUEST['id_persona'], $DATA_EMPLEADOR['id_empleador']);

//var_dump($data_persona);
?>

<script>

                //INICIO HISTORIAL
                $(document).ready(function(){
						   
                    //demoApp = new Historial();
					 $( "#tabs").tabs();
					 cargarTablaDerechoHabiente();					 

                });
				
				
                /*****************************************************/
                /***************** Terrenos ***************************/
                /*****************************************************/

                //FUNNCION CARGAR_TABLA PASARELAS 10/12/2011
                function cargarTablaDerechoHabiente(){
					
					var id_persona = document.getElementById('id_persona').value;
					var id_empleador = document.getElementById('id_empleador').value;
					//console.log(id_persona);

                    $("#list").jqGrid('GridUnload');
                    $("#list").jqGrid({
                        url:'sunat_planilla/controller/DerechohabienteController.php?oper=cargar_tabla&id_persona='+id_persona+'&id_empleador='+id_empleador,
                        datatype: 'json',
                        colNames:['id_derechohabiente','id_persona','DOC','nUM doc','Paterno',
						'Materno','Nombres','Fech Nacimiento','nombre_vinculo_familiar','nombre_situacion',
						'Opciones'],
                        colModel :[
							{
                                name:'id_derechohabiente', 
                                index:'id_derechohabiente',
								search:false,
								editable:false, 
                                width:10,
                                align:'left'
                            },		
                            {
                                name:'id_persona', 
                                editable:false, 
                                index:'id_persona',
                                width:10,
								hidden:true,
                                align:'left'
                            },		
                            {
                                name:'nombre_documento',
                                index:'nombre_documento', 
								search:false,
                                editable:true,                                
                                width:40, 
                                align:'left', 
                            },
                            {
                                name:'num_documento', 
                                index:'num_documento',
                                editable:false,
                                width:40,
                                align:'left'
                            },
                            {
                                name:'apellido_paterno', 
                                index:'apellido_paterno',
                                editable:true,
                                width:40,
                                align:'left'
                            },
                            {
                                name:'apellido_materno',
                                editable:true,
                                width:40, 
                                index:'apellido_materno', 
                                align:'left'
                            },
                            {
                                name:'nombres',
								index:'nombres',
                                editable:true,
                                width:80,                                 
                                align:'left'
                            },
							{
                                name:'fecha_nacimiento',
								index:'fecha_nacimiento',								
                                editable:true,
                                width:40,                                 
                                align:'left'
                            },
                            {
                                name:'nombre_vinculo_familiar',
								index:'nombre_vinculo_familiar',
								search:false,
                                editable:true,
                                width:120,                                 
                                align:'left' 
                            },
                            {
                                name:'nombre_situacion',
								index:'nombre_situacion',
								search:false,
                                editable:true,
                                width:120,                                 
                                align:'left'
                            },							
							{
                                name:'opciones',
                                editable:false,
								search:false,
                                width:60, 
                                index:'opciones', 
                                align:'center'
                            },
	
		
		
                        ],
                        pager: '#pager',
						autowidth: true,
                        rowNum:10,
                        rowList:[10,20,30],
                        sortname: 'id_derechohabiente',
                        sortorder: 'asc',
                        viewrecords: true,
                        gridview: true,
                        caption: 'Lista de DerechoHabientes',
                        onSelectRow: function(ids) {
                        },


                        height:200,
                        width:720 
                    });
				jQuery("#list").jqGrid('navGrid','#pager',{add:false,edit:false,del:false});
                    //--- PIE GRID


                }






</script>

            
			
			
<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Registro de Derechohabientes</a></li>			
			
        </ul>
      <div id="tabs-1">
        <h3>Identificación del Titular </h3>
        <table cellspacing="0" cellpadding="0">
          <tr>
            <td height="0">id_persona</td>
            <td height="0"><input name="id_persona" type="text" id="id_persona" 
			value="<?php echo $_REQUEST['id_persona']; ?>" readonly=""></td>
          </tr>
          <tr class="ocultar">
            <td class="" height="0">id_empleador</td>
            <td height="0"><input name="id_empleador" type="text" id="id_empleador" 
			value="<?php  //echo $DATA_EMPLEADOR['id_empleador']; ?>" readonly="readonly"></td>
          </tr>
          <tr>
            <td height="0" width="29%">Tipo y Número de Documento de Identidad:</td>
            <td height="0" width="71%"><input name="textfield" type="text" 
			value="<?php echo $data_persona['documento']; ?>" size="50" readonly="readonly"></td>
          </tr>
          <tr>
            <td height="0">Apellidos y Nombres:</td>
            <td height="0"><input name="textfield2" type="text" 
			value="<?php echo $data_persona['apellido_paterno']." ".$data_persona['apellido_materno']." ".$data_persona['nombres'] ?>" size="50" readonly="readonly"></td>
          </tr>
        </table>
        <h3>Identificación del Titular </h3>
        <p>
          <input name="cbxFlagSituacion" id="cbxFlagSituacion" onClick="cargarDerechohabientes(document.frmTitular.cbxFlagSituacion.checked);" type="checkbox">
          Ver también derechohabientes dados de baja<br />
          Datos GRID
<input type="button" name="Submit" value="Nuevo DerechoHabiente"
			 onclick="javascript:cargar_pagina('sunat_planilla/view/new_derechohabiente.php?id_persona=<?php echo $data_persona['id_persona']; ?>','#CapaContenedorFormulario')" />
		<input type="button" name="btn_baja" id="btn_baja" value="Baja" />
        </p>
<table id="list"></table>
            <div id="pager"></div>
      </div>

        
    </div>
</div>

