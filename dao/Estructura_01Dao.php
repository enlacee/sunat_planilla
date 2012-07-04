<?php

class Estructura_01Dao extends AbstractDao {
//put your code here

    /**
     *
     * @param type $id_empleador
     * @return type 
     * Solo establecimientos que realizen actividad de riesgo OJO :D
     */
    public function estructura_1($id_empleador) {

        $query = "
        SELECT 
        est.cod_establecimiento,
        est.realizaran_actividad_riesgo
        FROM empleadores AS e

        INNER JOIN establecimientos AS  est
        ON e.id_empleador = est.id_empleador

        WHERE e.id_empleador = ?
        AND est.realizaran_actividad_riesgo = 1       
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }

    
    /**
     * ESTRUCTURA 2
     * Empleadores a quienes destaco personal
     * Nombre del archivo :  RP_###########.edd
     */
    public function estructura_2_1($id_empleador_maestro) {

        $query = "
        SELECT 
        -- ed.id_empleador_destaque,
        -- e.id_empleador, -- Empleadores 'con tipos de servicios Solo distintos' SE puede DAR
	e.ruc,
        sp.cod_tipo_actividad,
        DATE_FORMAT(sp.fecha_inicio, '%d/%m/%Y') AS fecha_inicio,
        DATE_FORMAT(sp.fecha_fin, '%d/%m/%Y') AS fecha_fin

        FROM empleadores_destaques AS ed
        INNER JOIN empleadores AS e
        ON ed.id_empleador = e.id_empleador

        INNER JOIN servicios_prestados AS sp
        ON ed.id_empleador_destaque = sp.id_empleador_destaque

        WHERE ed.id_empleador_maestro = ?
        AND ed.estado = 'ACTIVO'        
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }
    
/**
 * ESTRUCTURA 2
 * Para Importar informacion del Establecimiento a Donde Se destaca o desplaza
 * personal  y el indicador si dicho personal desarrollará ACTIVIDAD DE RIESGO.
 * SCTR.
 * Nombre del archivo : RP_###########.ldd
 */
    public function estructura_2_2($id_empleador_maestro) {

        $query ="
        SELECT 
        -- ed.id_empleador_destaque,  
        -- ed.id_empleador,  
        emp.ruc,
        -- est.id_establecimiento,  
        est.cod_establecimiento,
        ev.realizan_trabajo_de_riesgo

        FROM empleadores_destaques AS ed
        INNER JOIN empleadores AS emp
        ON ed.id_empleador = emp.id_empleador
        -- 
        INNER JOIN establecimientos_vinculados AS ev
        ON ed.id_empleador_destaque = ev.id_empleador_destaque
        INNER JOIN establecimientos AS est
        ON ev.id_establecimiento = est.id_establecimiento

        WHERE ed.id_empleador_maestro =?
        AND ed.estado = 'ACTIVO';       
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }
    
/**
 * ESTRUCTURA 3:
 * Empleadores que me destacan o desplazan personal.
 * OJO empleadores que me destacan o desplazan personal, así como el servicio recibido
 * Nombre del archivo :  RP_###########.med	
 */
    public function estructura_3($id_empleador_maestro) {

        $query = "
        SELECT 
        -- edy.id_empleador_destaque_yoursef,
        -- edy.id_empleador,
        -- edy.id_empleador_maestro,
        emp.ruc,
        -- spy.id_servicio_prestado_yoursef,
        spy.cod_tipo_actividad,
        DATE_FORMAT(spy.fecha_inicio, '%d/%m/%Y') AS fecha_inicio,
        DATE_FORMAT(spy.fecha_fin, '%d/%m/%Y') AS fecha_fin

        FROM empleadores_destaques_yourself AS edy
        INNER JOIN empleadores AS emp
        ON edy.id_empleador = emp.id_empleador

        INNER JOIN servicios_prestados_yourself AS spy
        ON edy.id_empleador_destaque_yoursef = spy.id_empleador_destaque_yoursef

        WHERE edy.id_empleador_maestro = 1
        AND edy.estado = 'ACTIVO';         
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }
    
    /**
     *'ESTRUCTURA 4:
     * Datos personales del trabajador, pensionista, personal en formación
     * - modalidad formativa laboral y personal de terceros" 
     */
    public function estructura_4(){  // NO USADO - usa otras funciones       
        // persona
        // lisa de direcciones
        // direccion 1
        // direccion 2
    }
    
    
    
    
/**
 *
 * @return type 
 */
    public function estructura_5(){

        
        $query = "
        
        ";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }
    
    

}

?>
