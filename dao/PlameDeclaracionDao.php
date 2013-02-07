<?php

class PlameDeclaracionDao extends AbstractDao {

    public function registrar($id_empleador_maestro, $periodo) {

        $date = date("Y-m-d");
        $query = "
        INSERT INTO pdeclaraciones
                    (
                    id_empleador_maestro,
                    periodo,
                    fecha_creacion,
                    fecha_modificacion)
        VALUES (
                ?,
                ?,
                ?,
                ?);
        ";
        $this->pdo->beginTransaction();

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $periodo);
        $stm->bindValue(3, $date);
        $stm->bindValue(4, $date);
        $stm->execute();

        $query2 = "select last_insert_id() as id";
        $stm = $this->pdo->prepare($query2);
        $stm->execute();
        $lista = $stm->fetchAll();

        $this->pdo->commit();
        $stm = null;

        return $lista[0]['id'];
        //$lista = $stm->fetchAll();
    }

    public function actualizar($id_pdeclaracion, $fecha_modificacion) {

        $query = "
        UPDATE pdeclaraciones
        SET
          fecha_modificacion = ?
        WHERE (id_pdeclaracion = ? );            
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $fecha_modificacion);
        $stm->bindValue(2, $id_pdeclaracion);
        $stm->execute();
        $stm = null;
        return true;
    }
    
    public function del($id_pdeclaracion){
        
        $query ="
            DELETE
            FROM pdeclaraciones
            WHERE id_pdeclaracion = ?
            ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);        
        $stm->execute();       
        $stm = null;
        return true; 
    }
    /**
     *
     * @param type $id_empleador_maestro
     * @param type $periodo
     * @return type 
     * Pregunta si existe periodo registrado.
     */
    public function baja($id_pdeclaracion){
//        echo "---------";
//        echo($id_pdeclaracion);
//        echo "---------";
        $query = "
        UPDATE pdeclaraciones
        SET
          estado = 0
        WHERE (id_pdeclaracion = ? );            
        ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);        
        $stm->execute();
        $stm = null;
        return true;   
    }
    
    
    
    public function existeDeclaracion($id_empleador_maestro, $periodo) { //paso 01
        $query = "
        SELECT
            COUNT(*) as nunfilas
        FROM pdeclaraciones
        WHERE (id_empleador_maestro = ? AND periodo = '$periodo' )
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        //$stm->bindValue(2, $periodo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['nunfilas'];
    }

    /*
      public function contarTrabajadoresEnPeriodo($id_empleador_maestro,$periodo){//paso 02

      $query = "
      SELECT
      COUNT(*) AS numfilas

      FROM pdeclaraciones AS pd
      INNER JOIN empleadores_maestros AS em
      ON pd.id_empleador_maestro = em.id_empleador_maestro

      INNER JOIN ptrabajadores AS pt
      ON pd.id_pdeclaracion = pt.id_pdeclaracion

      INNER JOIN trabajadores AS tra
      ON pt.id_trabajador = tra.id_trabajador

      INNER JOIN personas AS p
      ON tra.id_persona = p.id_persona


      INNER JOIN pjornadas_laborales AS pjl
      ON pt.id_ptrabajador = pjl.id_ptrabajador

      WHERE (em.id_empleador_maestro = ? AND  pd.periodo = ?)
      ";

      $stm = $this->pdo->prepare($query);
      $stm->bindValue(1, $id_empleador_maestro);
      $stm->bindValue(2, $periodo);
      $stm->execute();
      $lista = $stm->fetchAll();
      $stm = null;
      return $lista[0]['numfilas'];


      }

     */

    public function listarXDeclaracion($id_empleador_maestro) {
        $query = "
        SELECT 
        id_pdeclaracion,
        periodo,
        fecha_modificacion

        FROM pdeclaraciones AS pd
        INNER JOIN empleadores_maestros AS em
        ON pd.id_empleador_maestro = em.id_empleador_maestro

        WHERE(em.id_empleador_maestro= ?)
        ORDER BY periodo ASC
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        //$stm->bindValue(2, $periodo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    
    
    
        public function listarGrid($id_empleador_maestro, $anio,$WHERE, $start, $limit, $sidx, $sord) {
            
        $cadena = null;
        if (is_null($start)) {
            $cadena = "";
        } else {
            $cadena = " , $sidx $sord LIMIT $start,  $limit";
        }
        
        $query = "
        SELECT 
        id_pdeclaracion,
        periodo,
        fecha_modificacion,
        estado

        FROM pdeclaraciones AS pd
        INNER JOIN empleadores_maestros AS em
        ON pd.id_empleador_maestro = em.id_empleador_maestro

        WHERE(em.id_empleador_maestro= ? AND YEAR(pd.periodo) = ?)
        
        ORDER BY periodo ASC $cadena       
        ";
//echoo($query);
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $anio);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
       
    // funcional renta quinta        
    public function listar($id_empleador_maestro, $anio) {
        $query = "
        SELECT 
        id_pdeclaracion,
        periodo,
        fecha_modificacion,
        estado

        FROM pdeclaraciones AS pd
        INNER JOIN empleadores_maestros AS em
        ON pd.id_empleador_maestro = em.id_empleador_maestro

        WHERE(em.id_empleador_maestro= ? AND YEAR(pd.periodo) = ?)        
        ORDER BY periodo ASC
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $anio);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }

    /*
     * Lista 1 Declaracion y 2 etapas = 2quincenas
     */
    /*
 public function listarDeclaracionEtapaCount($id_declaracion, $WHERE) {
        
        $query = "
        SELECT 
        count(*) AS counteo

        FROM pdeclaraciones AS pd
        INNER JOIN etapas_pagos AS ep
        ON pd.id_pdeclaracion = ep.id_pdeclaracion
        INNER JOIN pagos AS pg
        ON ep.id_etapa_pago = pg.id_etapa_pago
        -- tra
        inner join trabajadores as t
        on pg.id_trabajador = t.id_trabajador
        inner join personas as p
        on t.id_persona = p.id_persona
        -- tra
        WHERE pd.id_pdeclaracion= ?
        -- new 
        $WHERE
        GROUP BY t.id_trabajador
        
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_declaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['counteo'];
    }
  */  

    //usado en funciones fuera de grid! ok
    public function listarDeclaracionEtapa($id_declaracion, $WHERE/*,$start, $limit, $sidx, $sord*/) {
    
        //query Antigua ->no permite busqueda.        
        $query = "
        SELECT 
        -- pago
        pg.id_pago,
        pg.id_trabajador,
        -- pago
        -- persona
        p.id_persona,
        p.num_documento,
        p.cod_tipo_documento,
        p.apellido_paterno,
        p.apellido_materno,
        p.nombres
        -- persona

        FROM pdeclaraciones AS pd
        INNER JOIN etapas_pagos AS ep
        ON pd.id_pdeclaracion = ep.id_pdeclaracion
        INNER JOIN pagos AS pg
        ON ep.id_etapa_pago = pg.id_etapa_pago
        -- tra
        inner join trabajadores as t
        on pg.id_trabajador = t.id_trabajador
        inner join personas as p
        on t.id_persona = p.id_persona
        -- tra
        WHERE pd.id_pdeclaracion= ?
        -- new 
        $WHERE
        GROUP BY t.id_trabajador
        -- new
        -- ORDER BY $sidx $sord LIMIT $start,  $limit        
        ";   
        
     

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_declaracion);
        //$stm->bindValue(2, $anio);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
       public function listarDeclaracionEtapa_HIJO($id_declaracion) {
        $query = "
        SELECT 
        pg.id_pago,
        pg.id_trabajador
           
        FROM pdeclaraciones AS pd
        INNER JOIN etapas_pagos AS ep
        ON pd.id_pdeclaracion = ep.id_pdeclaracion
        INNER JOIN pagos AS pg
        ON ep.id_etapa_pago = pg.id_etapa_pago
        -- tra
        inner join trabajadores as t
        on pg.id_trabajador = t.id_trabajador
        inner join personas as p
        on t.id_persona = p.id_persona
        -- tra
        WHERE pd.id_pdeclaracion= ?
        GROUP BY id_trabajador
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_declaracion);      
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
    

    //--------------------------------------------------------------------------//
    //--------------------------------------------------------------------------//
    /*
     * Primera 15CENA para concepto 0701 ADELANTO
     */
    public function PrimerAdelantoMensual($ID_TRABAJADOR, $id_pdeclaracion) {

        // PERIMERA QUINCENA  = 15
        $query = "
        SELECT 
        -- pago
        pg.id_pago,
        pg.id_trabajador,
        pg.sueldo      
        -- pago

        FROM pdeclaraciones AS pd
        INNER JOIN etapas_pagos AS ep
        ON pd.id_pdeclaracion = ep.id_pdeclaracion
        INNER JOIN pagos AS pg
        ON ep.id_etapa_pago = pg.id_etapa_pago
        -- tra
        INNER JOIN trabajadores AS t
        ON pg.id_trabajador = t.id_trabajador
        INNER JOIN personas AS p
        ON t.id_persona = p.id_persona
        -- tra
        WHERE pd.id_pdeclaracion= ?
        AND DAY(ep.fecha_fin) = '15'
        AND pg.id_trabajador = ?
            ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $ID_TRABAJADOR);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista[0]['sueldo'];
    }

    //HERE consulta SUM para trabajadores_pdeclaraciones



    public function buscar_ID($id_pdeclaracion) {

        $query = "
        SELECT
         id_pdeclaracion,
         id_empleador_maestro,
         periodo,
         estado,
         fecha_creacion,
         fecha_modificacion
        FROM pdeclaraciones 
        WHERE id_pdeclaracion = ?
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];
    }

    public function Buscar_IDPeriodo($id_empleador_maestro,$periodo) {
        $query = "
        SELECT
        id_pdeclaracion,
        id_empleador_maestro,
        periodo,
        fecha_creacion,
        fecha_modificacion
        FROM pdeclaraciones
        WHERE (id_empleador_maestro = ? AND periodo = ? ) 
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $periodo);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista[0];
    }
    
    //listado de periodos correspondiente a un anio determinado.
    function dataPeriodos($id_empleador_maestro, $anio){
        
        $query = "
        SELECT 
        id_pdeclaracion
        FROM pdeclaraciones
        WHERE id_empleador_maestro = ?
        AND YEAR(periodo) = ?            
        ";
        
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_empleador_maestro);
        $stm->bindValue(2, $anio);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }
    
    
    

}

?>
