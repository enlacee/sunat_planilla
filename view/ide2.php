<?php
session_start();
/**
 * Varibles DE Identificacion  (Empleador)
 * */
$ruc_login = $_SESSION['gRucEmpresa'];
//$nombrEmpresa_login = $_SESSION['gNombreEmpresa'];


if (!empty($ruc_login)) {

    require_once('../../dao/AbstractDao.php');
    require_once('../../dao/PersonaDao.php');
    require_once('../../controller/PersonaController.php');
    //Empleador 
    require_once('../../dao/EmpleadorDao.php');

    $dao_empleador = new EmpleadorDao();
    $DATA_EMPLEADOR = $dao_empleador->buscaEmpleadorPorRuc($ruc_login);

    $_SESSION['sunat_empleador'] = $DATA_EMPLEADOR;

    //echo "<pre>";
    //print_r($_SESSION);
    //echo "Empleador Identificado = " . $DATA_EMPLEADOR['ruc'];
    //echo "</pre>";
} else {
    ?>

    <script type="text/javascript">
        var pagina = 'index.php';
        var segundos = 3;
        function redireccion() {
            alert('Empleador No Indentificado \n No puede acceder');
            document.location.href=pagina;
        }
        setTimeout("redireccion()",segundos);
    </script>

<?php } ?>


