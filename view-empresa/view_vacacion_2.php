<script type="text/javascript">
    $(document).ready(function(){
        var id_pdeclaracion = $("#id_pdeclaracion").val();        
                  
        $( "#tabs").tabs();



        $("#eliminarTodoVacacion").click(function(event){ 
            var estado = confirm("Seguro que desea eliminar Toda la Planilla de Vacacion?");    
            if(estado == true){
                    $(this).attr("disabled","disabled");
                    $.ajax({
                    type: "POST",
                    url: "sunat_planilla/controller/TrabajadorVacacionController.php",
                    data: { oper: 'delAll',id_pdeclaracion : id_pdeclaracion },
                    dataType: 'json',
                    async:true,
                    success: function(data){
                        console.log("Se elimino correctamente");
                        //jQuery("#list").trigger("reloadGrid");
                        jQuery("#list").trigger("reloadGrid");
                    }
                    });
            }

        });//end event
		
	});//end LOAD.
	
	var id = document.getElementById('id_pdeclaracion').value;
    var periodo = document.getElementById('periodo').value;
    cargarTablaTrabajadorVacacion(id,periodo);
	//cargarTablaVacacion(id,periodo);
	
</script>


<div class="demo" align="left">
<div class="ocultar">
id_pdeclaracion
<input type="text" name="id_pdeclaracion" id="id_pdeclaracion" 
value="<?php echo $_REQUEST['id_pdeclaracion']; ?>"/><br />
periodo
<input type="text" name="periodo" id="periodo"
value="<?php echo $_REQUEST['periodo'];?>" />
</div>

    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">view vacacion 2 grid</a></li>			

        </ul>
        <div id="tabs-1">

            OPERACIONES <input type="button" name="eliminarTodoVacacion" id="eliminarTodoVacacion" value="Eliminar Todo">

            <table id="list">
            </table>
            <div id="pager"></div>
        </div>

</div>

