<?php
session_start();
/**
 * Varibles DE Identificacion  (Empleador)
 * */
$ruc_login = $_SESSION['gRucEmpresa'];
//$nombrEmpresa_login = $_SESSION['gNombreEmpresa'];

$SCRIPT = true;

if (!empty($ruc_login)) {

    require_once('../dao/AbstractDao.php');
    require_once('../dao/PersonaDao.php');
    require_once('../controller/PersonaController.php');
    //Empleador 
    require_once('../dao/EmpleadorDao.php');

    $dao_empleador = new EmpleadorDao();
    $DATA_EMPLEADOR = $dao_empleador->buscaEmpleadorPorRuc($ruc_login);

	if( is_null($DATA_EMPLEADOR) ){
		session_destroy();
		$SCRIPT = true;
		//unset($_SESSION);
		
	}else{
    	$_SESSION['sunat_empleador'] = $DATA_EMPLEADOR;
		$SCRIPT = false;
	}	
	
}
/*
echo "<pre>";
print_r($_SESSION);
echo "Empleador Identificado = " . $DATA_EMPLEADOR['ruc'];
echo "</pre>";
*/
//echo "<h1>".var_dump($ESTADO)."</h1>"

?>



<?php if($SCRIPT): ?>
    <script type="text/javascript">
        var pagina = 'index.php';
        var segundos = 3;
        function redireccion() {
            alert('Empleador No Indentificado \n No puede acceder');
            document.location.href=pagina;
        }
        setTimeout("redireccion()",segundos);
    </script>

<?php endif; ?>