/**
* Esta SQL No puede retornar 
* id_establecimiento Duplicado. OJO
* TEST
* 
* xq cada empleador tiene sus 
* diferentes establecimiento=locales.
**/

SELECT 
eme.id_empleador_maestro_empleador,
eme.id_empleador,
eme.empleador_que_yo_destaco_personal,
dev.id_detalle_establecimiento_vinculado,
dev.id_establecimiento,
dev.realizaran_trabajo_de_riesgo


FROM empleadores_maestros_empleadores AS eme
INNER JOIN detalles_establecimientos_vinculados AS dev
ON eme.id_empleador_maestro_empleador = dev.id_empleador_maestro_empleador

-- inner join establecimientos as est
-- on dev.id_establecimiento = est.id_establecimiento


WHERE eme.empleador_que_yo_destaco_personal = 'ACTIVO'