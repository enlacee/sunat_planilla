<?php
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';
    //IDE_EMPLEADOR_MAESTRO
    require_once '../controller/ideController.php';
    //DECLARACION
    require_once '../dao/OcupacionDao.php';

}

$responce = NULL;
if ($op == "cargar_tabla") {
    $response = listarOcupacion();
}
echo (!empty($response)) ? json_encode($response) : '';

function listarOcupacion() {

    $cod_categoria_o = $_REQUEST['cbo_categoria_ocupacional'];
    $dao = new OcupacionDao();
    //$dao = new PrestamoDao();
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];

    $WHERE = '';
    if (isset($_GET['searchField']) && ($_GET['searchString'] != null)) {

        $operadores["eq"] = "=";
        $operadores["ne"] = "<>";
        $operadores["lt"] = "<";
        $operadores["le"] = "<=";
        $operadores["gt"] = ">";
        $operadores["ge"] = ">=";
        $operadores["cn"] = "LIKE";
        if ($_GET['searchOper'] == "cn")
            $WHERE = "AND " . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . " '%" . $_GET['searchString'] . "%' ";
        else
            $WHERE = "AND " . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . "'" . $_GET['searchString'] . "'";
    }

    if (!$sidx)
        $sidx = 1;

    $count = $dao->listarCount($cod_categoria_o, $WHERE);

    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        //$total_pages = 0;
    }
    //valida
    if ($page > $total_pages)
        $page = $total_pages;

    // calculate the starting position of the rows
    $start = $limit * $page - $limit;
    //valida
    if ($start < 0)
        $start = 0;
    //88888888888888888888888888888888888888888888888888888888888888888888888888
    // podria usarse categoria trabajaador para cargar los mismos trabajadores...
    $lista = array();
    $lista = $dao->listar($cod_categoria_o, $WHERE, $start, $limit, $sidx, $sord);


    //88888888888888888888888888888888888888888888888888888888888888888888888888
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $responce;
    }

    foreach ($lista as $rec) {
        $param = $rec["cod_ocupacion_p"];
        $_01 = $rec["descripcion"];
        
        $js = "javascript:return_modal_anb_ocupacion('" . $param . "')";
        $opciones = '<div class="red">
          <span  title="Editar" >
          <a href="' . $js . '">seleccionar</a>
          </span>
          &nbsp;
          </div>';
        // }

        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_01,
            $opciones
        );
        $i++;
    }

    return $responce;
}


?>
