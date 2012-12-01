<?php
$op = $_REQUEST["oper"];
if ($op) {
    session_start();
    require_once '../util/funciones.php';
    require_once '../dao/AbstractDao.php';

    require_once '../dao/Concepto_E_EmpleadorDao.php';
    
    require_once '../controller/ideController.php';
}


$response = NULL;

if ($op == "cargar_tabla") {
    $response = cargar_tabla_ConceptoEEmpleador();
    
} else if ($op == "dual") {
    $response = dualConceptoEEmpleador();
} 

echo (!empty($response)) ? json_encode($response) : '';



// view
function listarConceptosEmpleador(){
    $dao = new Concepto_E_EmpleadorDao();
    $data = $dao->listar(ID_EMPLEADOR);    
    /*
    $array =array();
    for($i=0;$i<count($data);$i++){        
        $array[] = $data[$i]['id_concepto_e'];
    }
    */
    return $data;//$array;
}


function dualConceptoEEmpleador(){
//echo "<pre>";
//    print_r($_REQUEST);
//echo "</pre>";
   
    // nuevo o Actualizar
    $id_concepto_e_empleador = $_REQUEST['id_concepto_e_empleador']; 
    $id_concepto_e = $_REQUEST['id_concepto_e'];    
    $estado = $_REQUEST['estado'];
    //$chek = $_REQUEST['chk_detalle_concepto'];
    
    $seleccionado = $_REQUEST['seleccionado'];
    
    $dao = new Concepto_E_EmpleadorDao();
    
    for ($i = 0; $i < count($id_concepto_e); $i++) {
        //echo "entro $i";
        if ($id_concepto_e_empleador[$i]=="") { //Registrar
//            echo "\nINSERT\n";

            $dao->add(ID_EMPLEADOR, $id_concepto_e[$i], $seleccionado[$i]);
           
        } else if($id_concepto_e_empleador[$i]) { //Actualizar 
 //           echo "\nUPDATE\N";
 
            $dao->edit($id_concepto_e_empleador[$i],$seleccionado[$i]);
          
        }
    }//ENDFOR
    $rpta = true;
    
return $rpta;
    
}



function cargar_tabla_ConceptoEEmpleador() {
    $id_pdeclaracion = $_REQUEST['id_pdeclaracion'];
    $periodo = $_REQUEST['periodo'];
    
    $dao_trabajador = new Concepto_E_EmpleadorDao();

    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
    $sord = $_GET['sord']; // get the direction

    $WHERE = "";

    if (isset($_GET['searchField']) && ($_GET['searchString'] != null)) {

        $operadores["eq"] = "=";
        $operadores["ne"] = "<>";
        $operadores["lt"] = "<";
        $operadores["le"] = "<=";
        $operadores["gt"] = ">";
        $operadores["ge"] = ">=";
        $operadores["cn"] = "LIKE";
        if ($_GET['searchOper'] == "cn")
            $WHERE = "AND cee." . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . " '%" . $_GET['searchString'] . "%' ";
        else
            $WHERE = "AND cee." . $_GET['searchField'] . " " . $operadores[$_GET['searchOper']] . "'" . $_GET['searchString'] . "'";
    }


    if (!$sidx)
        $sidx = 1; 

    $lista = array();
   // echo "ID_EMPLEADOR ".ID_EMPLEADOR;
    $lista = $dao_trabajador->cargar_tabla(ID_EMPLEADOR,1,$WHERE, $start, $limit, $sidx, $sord);
    
    
    $count=count($lista);   
    // $count = $count['numfilas'];
    if ($count > 0) {
        $total_pages = ceil($count / $limit); //CONTEO DE PAGINAS QUE HAY
    } else {
        //$total_pages = 0;
    }
    //valida
    if ($page > $total_pages)
        $page = $total_pages;

    // calculate the starting position of the rows
    $start = $limit * $page - $limit; // do not put $limit*($page - 1)
    //valida
    if ($start < 0)
        $start = 0;

    //$dao_trabajador->actualizarStock();

// CONTRUYENDO un JSON
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;

    // ----- Return FALSE no hay Productos
    if ($lista == null || count($lista) == 0) {
        return $responce;  /* break; */
    }
    
    foreach ($lista as $rec) { 

        $param = $rec["id_concepto_e_empleador"];
        $_01 = $rec['id_concepto_e'];
        $_02 = $rec["descripcion"];
       // echo $_01."\n";
        
        
        // diferentes vistas ::: segun concepto.
             $js = '#';
             $parametro = "?id_declaracion=$id_pdeclaracion&periodo=$periodo";
        switch ($_01) {
            case 1: // PRESTAMO
                $js ="javascript:cargar_pagina('sunat_planilla/view-empresa/view_cprestamo.php$parametro','#CapaContenedorFormulario')";
                break;
            case 2: // PARA TI FAMILIA
                $js ="javascript:cargar_pagina('sunat_planilla/view-empresa/view_cparatifamilia.php$parametro','#CapaContenedorFormulario')"; 
                break;
            case 3: // VACACION
                $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/view_pvacaciones.php$parametro','#CapaContenedorFormulario')";
                break;
            default:
                $js = 'null';
                break;
        }
           // $js = "javascript:cargar_pagina('sunat_planilla/view-empresa/add_registro_concepto_e.php?id_concepto_e=$_01&id_concepto_e_empleador=$param','#CapaContenedorFormulario')";

        $opciones = '<div id="divEliminar_Editar">				
        <span  title="Editar"   >
        <a href="' . $js . '" class="divEditar" ></a>
        </span> 
        </div>';
        $responce->rows[$i]['id'] = $param;
        $responce->rows[$i]['cell'] = array(
            $param,
            $_01,
            $_02,
            $opciones
        );

        $i++;
    }

    return $responce;  //RETORNO A intranet.js
}


?>
