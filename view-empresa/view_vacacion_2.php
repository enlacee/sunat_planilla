<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
	var id = document.getElementById('id_pdeclaracion').value;
	cargarTablaVacacion(id);
	
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

<table id="list">
</table>
<div id="pager">

        
        </div>
</div>

</div>

