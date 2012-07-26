<?php

//session_start();
//header("Content-Type: text/html; charset=utf-8");


$op = $_REQUEST["oper"];
if ($op) {
    //Modelo Direccion
    // require_once '../model/PersonaDireccion.php';
    // require_once '../dao/PersonaDireccionDao.php';
    require_once '../dao/AbstractDao.php';
    require_once '../dao/ComboCategoriaDao.php';
}


$responce = null;

if ($op == "cargar_tabla") {

} elseif ($op == "ocupaciones") { //Ojo Listado de Ocupaciones Segun Anexo 2 SUNAT
    $responce = comboOcupacionPorIdCategoriaOcupacional($_REQUEST['cbo_categoria_ocupacional']);
} else {
    // echo "oper INCORRECTO COMBO";
}

//echo count($responce);
echo (!empty($responce)) ? json_encode($responce) : '';



/**
 *   -----------------------------------------------------------------------------------------
 * 	FUNCIONES COMBO_BOX
 * 	-----------------------------------------------------------------------------------------
 * */
//OJO oPTIMIZAR  crear clases!!!! 

/**
 * tabla  motivos_bajas_registro
 * id_tipo_trabajador:
 * 1 = sector privado
 * 2 = sector publico
 * 3 = otros
 */
function comboeps(){
    $dao = new ComboCategoriaDao();
    return $dao->comboeps();    
}

function comboTipoTrabajadorPorIdTipoEmpleador($id_tipo_empleador) {

    $dao = new ComboCategoriaDao();
    $arreglo = $dao->comboTipoTrabajadorPorIdTipoEmpleador($id_tipo_empleador);

    /**
     * * .:::SUNAT:::. 
      R�gimen laboral
      El empleador deber� se�alar por cada uno de los trabajadores el r�gimen laboral que le resulta aplicable. Para los tipos de trabajador 23(Practicante Senati), 66 (Pescador y Procesador Artesanal Independiente), 71 (conductor de microempresa) y 98 (PS 4ta-5ta) no se habilita este dato, dado que no existe una relaci�n laboral de dependencia. Solo para efectos del aplicativo son incorporados bajo la categor�a Trabajador.
     *
     * Y TAMBOEN ELIMINAR PENSIONISTAS!! = 24)PENSIONISTA O CESANTE ,26)PENSIONISTA 20
     */
    //ID A ELIMINAR
    $id = array('23', '66', '71', '98', '24', 26);
    $counteo = count($id);
    for ($i = 0; $i < $counteo; $i++) {
        //------------------------------------------
        foreach ($arreglo as $indice) {
            if ($id[$i] == $indice['cod_tipo_trabajador']) {
                //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                unset($id[$i]);
                //Elimina _arreglo GET indice Primero				
                $clave = array_search($indice, $arreglo);
                unset($arreglo[$clave]);
                break;
            }
        }
    }

    //-------------------ORDENAR------------------------//
    $new = array();
    $xi = 0;
    foreach ($arreglo as $indice) {
        $new[$xi]['cod_tipo_trabajador'] = $indice['cod_tipo_trabajador'];
        $new[$xi]['descripcion'] = $indice['descripcion'];
        $xi++;
    }
    //-------------------ORDENAR------------------------//

    return $new;
}

//---------------------------------------------------------------
/**
 * 	Motivos Bajas Registros = motivos_bajas_registros
 * 	Filtro para La Categoria Trabajador
 * */
function comboMotivoBajaRegistroCatTrabajador() {

    $dao = new ComboCategoriaDao();
    $arreglo = $dao->comboMotivoBajaRegistro();

    /* filtro
     * 02,11,12,13
     */

    //ID A ELIMINAR
    $id = array('02', '11', '12', '13');

    $counteo = count($id);
    for ($i = 0; $i < $counteo; $i++) { //echo "===== ENTRO = ".$id[$i]."<br>";
        //------------------------------------------
        foreach ($arreglo as $indice) { //RECORRIDO ALL ARRAY busca 1 ID
            //echo "INDICE = ".$indice['cod_motivo_baja_registro'];
            if ($id[$i] == $indice['cod_motivo_baja_registro']) {
                //echo "Encontro y elimiaaa<br>";
                //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                unset($id[$i]);

                //Elimina _arreglo GET indice Primero				
                $clave = array_search($indice, $arreglo);
                unset($arreglo[$clave]);
                //echo "<pre>clave ===>$clave</pre>";
                //---- OJO encontro id unico USA BREAK queda claro que indice =id ES UNICO
                break;
            } else {
                //echo "No encontro<br>";
            //
			}
        }//end foreach
    }//end for
    //ORDENANDO indice ARRAY
    $new = array();
    $xi = 0;
    foreach ($arreglo as $indice) {
        $new[$xi]['cod_motivo_baja_registro'] = $indice['cod_motivo_baja_registro'];
        $new[$xi]['descripcion_abreviada'] = $indice['descripcion_abreviada'];
        $xi++;
    }

    return $new;
}

//################################################################################################
//------------------------------------------------------------------------------------------------
//  Categoria Pensionista
//################################################################################################

/**
 * 	Motivos Bajas Registros = motivos_bajas_registros
 * 	Filtro para La Categoria Pensionista
 * */
function comboMotivoBajaRegistroCatPensionista() {

    $dao = new ComboCategoriaDao();
    $arreglo = $dao->comboMotivoBajaRegistro();
    //ID A ELIMINAR
    $id = array('01', '02', '03', '04', '05', '06', '07', '08', '11', '12', '13', '15', '17');

    $counteo = count($id);
    for ($i = 0; $i < $counteo; $i++) {
        //------------------------------------------
        foreach ($arreglo as $indice) {
            if ($id[$i] == $indice['cod_motivo_baja_registro']) {
                //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                unset($id[$i]);
                //Elimina _arreglo GET indice Primero				
                $clave = array_search($indice, $arreglo);
                unset($arreglo[$clave]);
                break;
            }
        }
    }
    return $arreglo;
}

//end function

function comboRegimenPensionarioCatP() {
    $dao = new ComboCategoriaDao();
    $arreglo = $dao->comboRegimenPensionario();
    //ID A ELIMINAR
    $id = array('02', '03', '09', '10', '11', '13', '99');

    $counteo = count($id);
    for ($i = 0; $i < $counteo; $i++) {
        //------------------------------------------
        foreach ($arreglo as $indice) {
            if ($id[$i] == $indice['cod_regimen_pensionario']) {
                //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                unset($id[$i]);
                //Elimina _arreglo GET indice Primero				
                $clave = array_search($indice, $arreglo);
                unset($arreglo[$clave]);
                break;
            }
        }
    }
    return $arreglo;
}

/* * *
 * 	tb_regimenes_laborales
 *   SI = remype =1 Filtra
 *
 */

function comboRegimenLaboralPorTipoEmpleador($id_tipo_empleador, $remype) {
    $dao = new ComboCategoriaDao();
    $arreglo = $dao->comboRegimenLaboralPorTipoEmpleador($id_tipo_empleador);

    //Filtro si la EMPRESA pertenece a la REMYPE
	$id = array('16','17');
	
    if (true) { //SI REMYTE IS TRUE NO VALIDADO PARA REMYPE
        //ID A ELIMINAR
        //$id[] = '16';
        $counteo = count($id);
        for ($i = 0; $i < $counteo; $i++) {
            //------------------------------------------
            foreach ($arreglo as $indice) {
                if ($id[$i] == $indice['cod_regimen_laboral']) {
                    //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                    unset($id[$i]);
                    //Elimina _arreglo GET indice Primero				
                    $clave = array_search($indice, $arreglo);
                    unset($arreglo[$clave]);
                    break;
                }
            }
        }
        return $arreglo;
    }//ENDIF

    return $arreglo;
}

/* * *
 * 	tb_regimenes_laborales
 */

function comboCategoriaOcupacionalPorTipoEmpleador($id_tipo_empleador) {
    $dao = new ComboCategoriaDao();
    $data = $dao->comboCategoriaOcupacionalPorTipoEmpleador($id_tipo_empleador);

    return $data;
}

/*
 * tb_niveles_educativos
 */

function comboNivelEducativo() {
    $dao = new ComboCategoriaDao();
    $data = $dao->comboNivelEducativo();
    return $data;
}

/**
 *
 * tb ocupaciones_p
  con filtro SEGUN  condiciones SUNAT

  OJOO   PETENICIONES AJAX (SE REQUIEREE!!!!!) FORM
  combo box dependientes
  1 PADRE = combo->tipo trabajador
  2 HIJO  = combo->ocupacion
 */
function comboOcupacionPorIdCategoriaOcupacional($id_categoria_ocupacional) {

    $dao = new ComboCategoriaDao();
    $data = $dao->comboOcupacionPorIdCategoriaOcupacional($id_categoria_ocupacional);

    return $data;
}

/**
 *
 * 	tb tipos_contratos
 * 	Con Filtro condicion SUNAT
 *   '(1)  NO APLICA A EMPLEADOR DEL SECTOR PRIVADO.
 *   cod_tipo_contrato = 14,15,16,17
 */
function comboTiposContrato($id_tipo_empleador) { // :: ES IGUAL $id_tipo_empleador  CASO ESPECIAL
    $dao = new ComboCategoriaDao();
    $arreglo = $dao->comboTiposContrato();

    //ID A ELIMINAR
    if ($id_tipo_empleador == 1) { //data BASE DATOS !!	
        $id = array('14', '15', '16', '17');
        $counteo = count($id);
        for ($i = 0; $i < $counteo; $i++) {
            //------------------------------------------
            foreach ($arreglo as $indice) {
                if ($id[$i] == $indice['cod_tipo_contrato']) {
                    //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                    unset($id[$i]);
                    //Elimina _arreglo GET indice Primero				
                    $clave = array_search($indice, $arreglo);
                    unset($arreglo[$clave]);
                    break;
                }
            }
        }
        return $arreglo;
    }//ENDIF

    return $arreglo;
}

/**
 *
 * tb tipos_pagos

 */
function comboTipoPago() {
    $dao = new ComboCategoriaDao();
    $data = $dao->comboTipoPago();
    return $data;
}

/**
 *
 *
 */
function comboPeriodoRemuneracion() {
    $dao = new ComboCategoriaDao();
    $data = $dao->comboPeriodoRemuneracion();
    return $data;
}

/**
 *
 *
 */
function comboMontoRemuneracion() {
    $dao = new ComboCategoriaDao();
    $data = $dao->comboMontoRemuneracion();
    return $data;
}

/**
 *
 *
 */
//Envio de Variable OPER = tipo_trabajador
function comboRegimenAseguramientoSalud($id_tipo_empleador, $remype, $id_tipo_trabajador) {
    $dao = new ComboCategoriaDao();
    $arreglo = $dao->comboRegimenAseguramientoSalud();

    /*
     * 	$id_tipo_empleador = 1->sector_privado,2->sector_publico,3->otros
     */

//tipo trabjador = 20,21 obrero y empleado 


    if ($id_tipo_trabajador == '20' or $id_tipo_trabajador == '21') {
        //ID A ELIMINAR
        if ($id_tipo_empleador == 2) { //Si empleador es publico 2 ENTONSES no elimino COD['20']
            $id = array('02', '03', '04', '05', '21');
        } else {
            $id = array('02', '03', '04', '05', '20', '21');
        }

        $counteo = count($id);
        for ($i = 0; $i < $counteo; $i++) {
            //------------------------------------------
            foreach ($arreglo as $indice) {
                if ($id[$i] == $indice['cod_regimen_aseguramiento_salud']) {
                    unset($id[$i]);
                    $clave = array_search($indice, $arreglo);
                    unset($arreglo[$clave]);
                    break;
                }
            }
        }
        //return $arreglo;		
    } else if ($id_tipo_trabajador == '36') {//ENDIF 
        //ID A ELIMINAR
        $id = array('00', '01', '04', '05', '20', '21');
        $counteo = count($id);
        for ($i = 0; $i < $counteo; $i++) {
            //------------------------------------------
            foreach ($arreglo as $indice) {
                if ($id[$i] == $indice['cod_regimen_aseguramiento_salud']) {
                    unset($id[$i]);
                    $clave = array_search($indice, $arreglo);
                    unset($arreglo[$clave]);
                    break;
                }
            }
        }
        //return $arreglo;	
    } else {//ENDIF
        //ID A ELIMINAR		
        $id = array('02', '03', '04', '05', '20', '21');
        $counteo = count($id);
        for ($i = 0; $i < $counteo; $i++) {
            //------------------------------------------
            foreach ($arreglo as $indice) {
                if ($id[$i] == $indice['cod_regimen_aseguramiento_salud']) {
                    unset($id[$i]);
                    $clave = array_search($indice, $arreglo);
                    unset($arreglo[$clave]);
                    break;
                }
            }
        }
        //return $arreglo;	
        //hereeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee
    }//ENDIF


    return $arreglo;
}

/* * **********************************************************************************
 * 	COMBO PARA EMPLEADOR
 * ************************************************************************************
 */

function comboTipoEmpleador() {
    $dao = new ComboCategoriaDao();
    $data = $dao->comboTipoEmpleador();
    return $data;
}

function comboTipoSociedadComercial() {
    $dao = new ComboCategoriaDao();
    $data = $dao->comboTipoSociedadComercial();
    return $data;
}

function comboTipoActividad() {
    $dao = new ComboCategoriaDao();
    $data = $dao->comboTipoActividad();
    return $data;
}

function comboRegimenSalud() {
    $dao = new ComboCategoriaDao();
    $arreglo = $dao->comboRegimenSalud();
    //ID A ELIMINAR
    $id = array('02', '03', '05', '20', '21');

    $counteo = count($id);
    for ($i = 0; $i < $counteo; $i++) {
        //------------------------------------------
        foreach ($arreglo as $indice) {
            if ($id[$i] == $indice['cod_regimen_aseguramiento_salud']) {
                //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                unset($id[$i]);
                //Elimina _arreglo GET indice Primero				
                $clave = array_search($indice, $arreglo);
                unset($arreglo[$clave]);
                break;
            }
        }
    }
    return $arreglo;
    
}

function comboRegimenPensionario() {
    $dao = new ComboCategoriaDao();
    $arreglo = $dao->comboRegimenPensionario();
    //ID A ELIMINAR
    $id = array('12', '03', '09', '10', '11', '13', '99');

    $counteo = count($id);
    for ($i = 0; $i < $counteo; $i++) {
        //------------------------------------------
        foreach ($arreglo as $indice) {
            if ($id[$i] == $indice['cod_regimen_pensionario']) {
                //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                unset($id[$i]);
                //Elimina _arreglo GET indice Primero				
                $clave = array_search($indice, $arreglo);
                unset($arreglo[$clave]);
                break;
            }
        }
    }
    return $arreglo;
}

//Convenio con paises para no pagar doble tributacion
function comboConvenio() {
    $dao = new ComboCategoriaDao();
    return $dao->comboConvenio();
}

//NEW




function comboEstablecimientoLineal($id_empleador) {

    $dao = new ComboCategoriaDao();
    $lista = $dao->comboEstablecimientoLineal($id_empleador);

    //--ini
    $data = array();
    $i = 0;
    foreach ($lista as $rec) : /* dato retorna estos datos */
        //--------
        //echo "<pre>";
        //print_r($rec);
        //echo "</pre>";
        //echo "<br>";
        //here
        $ubigeo_nombre_via = $rec["ubigeo_nombre_via"];
        $nombre_via = $rec['nombre_via'];
        $numero_via = $rec['numero_via'];

        $ubigeo_nombre_zona = $rec['ubigeo_nombre_zona'];

        $nombre_zona = $rec['nombre_zona'];
        $etapa = $rec['etapa'];
        $manzana = $rec['manzana'];
        $blok = $rec['blok'];
        $lote = $rec['lote'];

        $departamento = $rec['departamento'];
        $interior = $rec['interior'];

        $kilometro = $rec['kilometro'];
        $referencia = $_rec['referencia'];

        $ubigeo_departamento = str_replace('DEPARTAMENTO', '', $rec['ubigeo_departamento']);
        $ubigeo_provincia = $rec['ubigeo_provincia'];
        $ubigeo_distrito = $rec['ubigeo_distrito'];

        //---------------Inicio Cadena String----------//
        $cadena = '';

        $cadena .= (isset($ubigeo_nombre_via)) ? '' . $ubigeo_nombre_via . ' ' : '';
        $cadena .= (isset($nombre_via)) ? '' . $nombre_via . ' ' : '';
        $cadena .= (isset($numero_via)) ? '' . $numero_via . ' ' : '';

        $cadena .= (isset($ubigeo_nombre_zona)) ? '' . $ubigeo_nombre_zona . ' ' : '';
        $cadena .= (isset($nombre_zona)) ? '' . $nombre_zona . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';

        $cadena .= (isset($manzana)) ? 'MZA. ' . $manzana . ' ' : '';
        $cadena .= (isset($blok)) ? '' . $blok . ' ' : '';
        $cadena .= (isset($etapa)) ? '' . $etapa . ' ' : '';
        $cadena .= (isset($lote)) ? 'LOTE. ' . $lote . ' ' : '';

        $cadena .= (isset($departamento)) ? '' . $departamento . ' ' : '';
        $cadena .= (isset($interior)) ? '' . $interior . ' ' : '';
        $cadena .= (isset($kilometro)) ? '' . $kilometro . ' ' : '';
        $cadena .= (isset($referencia)) ? '' . $referencia . ' ' : '';


        $cadena .= (isset($ubigeo_departamento)) ? '' . $ubigeo_departamento . '-' : '';
        $cadena .= (isset($ubigeo_provincia)) ? '' . $ubigeo_provincia . '-' : '';
        $cadena .= (isset($ubigeo_distrito)) ? '' . $ubigeo_distrito . ' ' : '';

        $cadena = strtoupper($cadena);


        //---------------Inicio Cadena String----------//		
        $data[$i]['id_establecimiento'] = $rec["cod_establecimiento"] . "|" . $rec["id_establecimiento"]; //relacion	
        $data[$i]['descripcion'] = $cadena;

        $i++;
    //--------
    endforeach;
    //--------ini

    return $data;
}

/* * **********************************************************************************
 * 	COMBO Persona en Formacion
 * ************************************************************************************
 */

function comboModalidadFormativa() {
    $dao = new ComboCategoriaDao();
    $arreglo = $dao->comboModalidadFormativa();
    //ID A ELIMINAR
    $id = array('04', '07', '10');

    $counteo = count($id);
    for ($i = 0; $i < $counteo; $i++) {
        //------------------------------------------
        foreach ($arreglo as $indice) {
            if ($id[$i] == $indice['id_modalidad_formativa']) {
                //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                unset($id[$i]);
                //Elimina _arreglo GET indice Primero				
                $clave = array_search($indice, $arreglo);
                unset($arreglo[$clave]);
                break;
            }
        }
    }
    return $arreglo;
}

//ENDFN

function comboOcupacionALLPformacion() {

    $dao = new ComboCategoriaDao();
    $data = $dao->comboOcupacionALLPformacion();

    return $data;
}


// SITUACION
function comboSituacion($estado) {

    $dao_persona = new ComboCategoriaDao();
    $arreglo = $dao_persona->comboSituacion();
    //ID A ELIMINAR
    if($estado == 1){
        $id = array('0', '2', '3');
    }elseif($estado == null){
        $id = array();
    }else{
		$id = array('1','3');
	}
    $counteo = count($id);
    for ($i = 0; $i < $counteo; $i++) {
        //------------------------------------------
        foreach ($arreglo as $indice) {
            if ($id[$i] == $indice['cod_situacion']) {
                //Encontro elimina ID_BUSQUEDA indice para no seguir buscando
                unset($id[$i]);
                //Elimina _arreglo GET indice Primero				
                $clave = array_search($indice, $arreglo);
                unset($arreglo[$clave]);
                break;
            }
        }
    }
    return $arreglo;
    
}







?>