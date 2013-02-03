<?php
$periodo = $_REQUEST['periodo'];
$ID_DECLARACION = $_REQUEST['id_pdeclaracion'];

?>
<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
</script>


<div class="demo" align="left">

<div class="ocultar">
id_declaracion
<input name="id_declaracion" id="id_declaracion" type="text" value="<?php echo $ID_DECLARACION; ?>">
<br />
periodo
<input type="text" name="periodo" id="periodo" value="<?php echo $periodo; ?>" />
</div>

    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Vacacion</a></li>			

        </ul>
        <div id="tabs-1">
        ass
        
        XXX</div>
</div>

</div>

