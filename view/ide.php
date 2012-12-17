<?php
session_start();
/**
 * Varibles DE Identificacion  (Empleador)
 * */

$ruc_login = $_SESSION['gRucEmpresa'];

$SCRIPT = true;

// 01 INSTANCIA LOGIN
if ($ruc_login) {
	$SCRIPT = false;
// 02 INSTANCIA LOGIN
	if($_SESSION['sunat_empleador']):
		//hay datos en session
		//Errorr Errorr Errorr Errorr Errorr Errorr
		require_once('../dao/AbstractDao.php');
	else:
		require_once('../dao/AbstractDao.php');
		require_once('../dao/PersonaDao.php');
		require_once('../controller/PersonaController.php');
		//Empleador 
		require_once('../dao/EmpleadorDao.php');
	
		$dao_empleador = new EmpleadorDao();
		$DATA_EMPLEADOR = $dao_empleador->buscaEmpleadorPorRuc($ruc_login);
		$_SESSION['sunat_empleador'] = $DATA_EMPLEADOR;
	endif;
	
}else{
	session_destroy();
	$SCRIPT = true;			
}

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