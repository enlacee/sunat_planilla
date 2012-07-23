<?php
//*******************************************************************//
require_once('../view/ide.php');
//*******************************************************************//
require_once '../controller/ideController.php';
$data = $_SESSION['sunat_empleador'];

?>

<script type="text/javascript">
    $(document).ready(function(){
                  
        $( "#tabs").tabs();
		
	});
	
	cargarTablaPDeclaracion();
</script>


<div class="demo" align="left">
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Declaraciones Registradas</a></li>			

        </ul>
        <div id="tabs-1">
        RUC: <?php echo $data['ruc']. " - ". $data['razon_social_concatenado']; ?>

        <table id="list">
        </table>
        <div id="pager">
        </div>


        </div>
</div>

</div>