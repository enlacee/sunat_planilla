<?php
class DeclaracionDConceptoGratiDao extends AbstractDao{
    
    function add($obj){
        
        $model = new DeclaracionDConceptoGrati();        
        $model = $obj;
        $query = "
        INSERT INTO declaraciones_dconceptos_grati
                    (
                     id_trabajador_grati,
                     cod_detalle_concepto,
                     monto_devengado,
                     monto_pagado)
        VALUES (
                ?,
                ?,
                ?,
                ?);
      ";
        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $model->getId_trabajador_grati());
        $stm->bindValue(2, $model->getCod_detalle_concepto());
        $stm->bindValue(3, $model->getMonto_devengado());
        $stm->bindValue(4, $model->getMonto_pagado());
        $stm->execute();
        //$lista = $stm->fetchAll();
        $stm = null;
        return true;
        
        
    }
    
    
    public function view_listarConcepto($id_trabajador_grati,$cod_concepto) {
        if (is_array($cod_concepto) && count($cod_concepto) >= 1) {
            $sql = " ";
            for ($i = 0; $i < count($cod_concepto); $i++) {
                $sql .= $cod_concepto[$i];
                if ($i == (count($cod_concepto)) - 1) {
                    //null                    
                } else {
                    $sql .= ",";
                }
            }
        }else{
            $sql = $cod_concepto;
        }
        $query = "
        SELECT
	  ddg.id_declaracion_dconcepto_grati,
	  ddg.id_trabajador_grati,          
          ddg.cod_detalle_concepto,
          ddg.monto_pagado,
          
          dc.descripcion,
          dc.descripcion_abreviada,
          c.cod_concepto  
        FROM declaraciones_dconceptos_grati AS ddg -- detalles_conceptos_empleadores_maestros AS ddg
        INNER JOIN detalles_conceptos AS dc
        ON ddg.cod_detalle_concepto = dc.cod_detalle_concepto
        INNER JOIN conceptos AS c
        ON dc.cod_concepto = c.cod_concepto

        WHERE (ddg.id_trabajador_grati = ?)
        AND c.cod_concepto in ($sql)  
        ";

        $stm = $this->pdo->prepare($query);
        $stm->bindValue(1, $id_trabajador_grati);        
        $stm->execute();
        $lista = $stm->fetchAll();
        $stm = null;
        return $lista;
    }    
}

?>
