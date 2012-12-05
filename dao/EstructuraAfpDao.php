<?php

class EstructuraAfpDao extends AbstractDao{
    
        //12/11/2012
    public function listarTrabajadoresAfp($id_pdeclaracion, $codcubodin=null){
        $afp = 0;
        $afp = ($codcubodin == null) ? '21,22,23,24' : $codcubodin;
        
      $query = "
        SELECT
        p.id_persona,
        tpd.id_trabajador,
        rp.cuspp,
        p.num_documento,
        p.apellido_paterno,
        p.apellido_materno,
        p.nombres
        -- count( p.id_persona) bug

        FROM trabajadores_pdeclaraciones AS tpd
        INNER JOIN trabajadores AS t
        ON tpd.id_trabajador = t.id_trabajador

        INNER JOIN detalle_regimenes_pensionarios AS rp
        ON t.id_trabajador = rp.id_trabajador 

        LEFT JOIN personas AS p
        ON t.id_persona = p.id_persona

        WHERE tpd.id_pdeclaracion = ?
        AND tpd.cod_regimen_pensionario IN($afp)
        -- group by (p.id_persona) bug     
";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;
    }
    
    //1 = Inicio de relación laboral
    public function codigoMovimiento_1($id_pdeclaracion, $anio, $mes, $codcubodin = null){
        $afp = 0;
        $afp = ($codcubodin == null) ? '21,22,23,24' : $codcubodin;
        
        $query = "  
        SELECT 
        tpd.id_trabajador_pdeclaracion,
        tpd.id_pdeclaracion,
        tpd.id_trabajador,
        dpl.fecha_inicio        

        FROM trabajadores_pdeclaraciones AS tpd
        
        INNER JOIN detalle_periodos_laborales AS dpl
        ON tpd.id_trabajador = dpl.id_trabajador

        WHERE tpd.id_pdeclaracion = ?
        AND tpd.cod_regimen_pensionario IN($afp)
        AND YEAR(dpl.fecha_inicio) = ?
        AND MONTH(dpl.fecha_inicio) = ?          
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $anio);
        $stm->bindValue(3, $mes);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;

        return $lista;         
    }
    
    
    public function codigoMovimiento_2($id_pdeclaracion, $anio, $mes){
        $query = "  
        SELECT 
        tpd.id_trabajador_pdeclaracion,
        tpd.id_pdeclaracion,
        tpd.id_trabajador,
        dpl.fecha_fin        

        FROM trabajadores_pdeclaraciones AS tpd
       
        INNER JOIN detalle_periodos_laborales AS dpl
        ON tpd.id_trabajador = dpl.id_trabajador

        WHERE tpd.id_pdeclaracion = ?
        AND tpd.cod_regimen_pensionario IN(21,22,23,24)
        AND YEAR(dpl.fecha_fin) = ?
        AND MONTH(dpl.fecha_fin) = ?          
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $anio);
        $stm->bindValue(3, $mes);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }
    
    
    public function codigoMovimiento_3($id_pdeclaracion){
        $query = "  
        SELECT 
        tpd.id_trabajador_pdeclaracion,
        tpd.id_pdeclaracion,
        tpd.id_trabajador,
        ds.cantidad_dia,
        ds.fecha_inicio,
        ds.fecha_fin

        FROM trabajadores_pdeclaraciones AS tpd
        INNER JOIN dias_subsidiados AS ds
        ON tpd.id_trabajador_pdeclaracion = ds.id_trabajador_pdeclaracion


        WHERE tpd.id_pdeclaracion = ?
        AND tpd.cod_regimen_pensionario IN(21,22,23,24)         
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;   
    }
    
    
    
    public function codigoMovimiento_4($id_pdeclaracion){
        $query = "  
        SELECT 
        tpd.id_trabajador_pdeclaracion,
        tpd.id_pdeclaracion,
        tpd.id_trabajador,
        dns.cantidad_dia,
        dns.fecha_inicio,
        dns.fecha_fin

        FROM trabajadores_pdeclaraciones AS tpd
        INNER JOIN dias_nosubsidiados AS dns
        ON tpd.id_trabajador_pdeclaracion = dns.id_trabajador_pdeclaracion

        WHERE tpd.id_pdeclaracion = ?
        AND tpd.cod_regimen_pensionario IN(21,22,23,24)
        AND dns.cod_tipo_suspen_relacion_laboral = 05
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;   
    }
    
     public function codigoMovimiento_5($id_pdeclaracion,$anio,$mes, $codcubodin=null){
        $afp = 0;
        $afp = ($codcubodin == null) ? '21,22,23,24' : $codcubodin;         
         
        $query = "  
        SELECT 
        tpd.id_trabajador_pdeclaracion,
        tpd.id_pdeclaracion,
        tpd.id_trabajador,
        dns.cantidad_dia,
        -- dns.fecha_inicio,
        -- dns.fecha_fin,
        v.fecha_programada AS fecha_inicio,
        v.fecha_programada_fin AS fecha_fin


        FROM trabajadores_pdeclaraciones AS tpd
        INNER JOIN dias_nosubsidiados AS dns
        ON tpd.id_trabajador_pdeclaracion = dns.id_trabajador_pdeclaracion

        INNER JOIN vacaciones AS v
        ON tpd.id_trabajador = v.id_trabajador

        WHERE tpd.id_pdeclaracion = ?
        AND tpd.cod_regimen_pensionario IN($afp)
        AND dns.cod_tipo_suspen_relacion_laboral =23

        AND YEAR(v.fecha_programada) = ?
        AND MONTH(v.fecha_programada) = ? 
";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_pdeclaracion);
        $stm->bindValue(2, $anio);
        $stm->bindValue(3, $mes);
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;   
    }  
    
    
    
}

?>
