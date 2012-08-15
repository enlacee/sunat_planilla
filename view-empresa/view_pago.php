<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//

$ID_ETAPA_PAGO = $_REQUEST['id_etapa_pago']

?>
<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
	var id_etapa_pago = document.getElementById('id_etapa_pago').value;
	cargarTabla_Pago(id_etapa_pago);
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Pagos</a></li>			

        </ul>
        <div id="tabs-1">
        id_etapa_pago
        <input type="text" name="id_etapa_pago" id="id_etapa_pago"  value="<?php echo $ID_ETAPA_PAGO; ?>"/>
        <br />
        
<table id="list">
</table>
<div id="pager"></div>

        
        </div>
</div>

</div>