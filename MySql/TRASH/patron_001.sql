SELECT 
e.id_empleador,
e.razon_social,
e.ruc,
-- 
em.id_empleador_maestro,
em.id_empleador,
-- 
eme.id_empleador AS id_empleador_subordinado_xxx,

-- detalle est vinculado
dev.id_detalle_establecimiento_vinculado,
dev.id_establecimiento

-- establecimientos
est.cod_establecimiento,
est.id_establecimiento,

FROM empleadores AS e

INNER JOIN empleadores_maestros AS em
ON e.id_empleador = em.id_empleador

INNER JOIN empleadores_maestros_empleadores AS eme
ON em.id_empleador_maestro = em.id_empleador_maestro

-- detalle estaablecimiento vinculados
INNER JOIN detalles_establecimientos_vinculados AS dev
-- ID UNICO
-- ( id_empleador_maestro_empleador ) = nace del vinculo =  EmpleadorMaestro con Empleador
ON eme.id_empleador_maestro_empleador  = dev.id_empleador_maestro_empleador 


-- ENDDDDD
INNER JOIN establecimientos AS est
ON dev.id_establecimiento = est.id_establecimiento




-- 
WHERE e.id_empleador = 1
AND eme.empleador_que_yo_destaco_personal ='ACTIVO'