<?php
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
//funciones" de EME   D        << CONTROLLER PAGE >>


function getID_EME($id_empleador, $id_empleador_maestro) {

    //--------------------------------------------------------------------------
    $ID_EME = null;
    $dao = new EmpleadorMaestroEmpleadorDao();
    $BANDERA_ID = $dao->existeVinculoAntiguoEME($id_empleador, $id_empleador_maestro);
    //--------------------------------------------------------------------------

    if (is_null($BANDERA_ID)) {// = NO EXISTE Empleador Maestro Empleador REGISTRADO   
        $dao_d = new EmpleadorMaestroEmpleadorDao();
        $ID_EME = $dao_d->registrarEME($id_empleador, $id_empleador_maestro);
    } else { // = SI existe : NO registrar
        $ID_EME = $BANDERA_ID;
    }//ENDIF
    // printr($ID_EME);
    // echo "FINALLL";

    return $ID_EME;
}

//------------------------------------------------------------------------------
// EME = Empleador_Maestro_Empleador
//------------------------------------------------------------------------------







function nuevoEME($id_empleador,$id_empleador_maestro) {

   // $obj_eme = new EmpleadorMaestroEmpleador();
   // $obj_eme->setId_empleador($_REQUEST['id_empleador']);
   // $obj_eme->setId_empleador_maestro($_REQUEST['id_empleador_maestro']);

    //DAO
    // PASO 01 -> Pregunta si Existe EME registrado
    $ID_EME=null;
    $bandera_id = existeVinculoAntiguoEME($id_empleador, $id_empleador_maestro);

    if ($bandera_id) {//true O  ID
        $ID_EME = $bandera_id;
    } else {
        $dao_d = new EmpleadorMaestroEmpleadorDao();       
        $ID_EME = $dao_d->registrarEME($id_empleador,$id_empleador_maestro);
    }
   
    return $ID_EME;
}


//------------------------------------------------------------------------------
// EME = Empleador_Maestro_Empleador<br />
// THIS FUNCION RECURSIVA
//------------------------------------------------------------------------------

/*
 * PRINCIPAL _> Preguntar si existe UN VINCULO ya establecido
 * Para relacionar inert"
 * << JS 'VISTA' Valida que no se Vincule el Empleador MAestro con sigo Mismo>>
 * << Y NO CREAR DUPLICADO En EME en la tb Empleadores_maestros_Empleadores >>.
 * tabla 1 = empleadores_que_me_destacan_personal
 * tabla 2 = empleadores_que_yo_descato_personal
 */

function existeVinculoAntiguoEME($id_empleador, $id_empleador_maestro) {
    $dao = new EmpleadorMaestroEmpleadorDao();
    $data = $dao->existeVinculoAntiguoEME($id_empleador, $id_empleador_maestro);

    if (is_null($data)) {
        return false;
    } else {// Es un array
        return $data['id_empleador_maestro_empleador'];
    }
}



?>