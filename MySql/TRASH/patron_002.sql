

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
dev.id_establecimiento,

-- establecimientos
est.cod_establecimiento,
est.id_establecimiento,

-- inicio Direccion Lineal
	esd.cod_ubigeo_reniec,
	ud.descripcion AS ubigeo_departamento,
	up.descripcion AS ubigeo_provincia,
	ur.descripcion  AS ubigeo_distrito,
	esd.cod_via,
	v.descripcion AS ubigeo_nombre_via,	
	esd.nombre_via,
	esd.cod_via,
	esd.numero_via,
	esd.departamento,
	esd.interior,
	esd.manzana,
	esd.lote,
	esd.kilometro,
	esd.block,
	esd.etapa,
	esd.cod_zona,
	z.descripcion AS ubigeo_nombre_zona,
	esd.nombre_zona,
	esd.referencia

-- finall Direccion Lineal

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

-- inicio Direccion Lineal
	INNER JOIN  establecimientos_direcciones AS esd
	ON est.id_establecimiento = esd.id_establecimiento
	
	INNER JOIN vias AS v
	ON esd.cod_via = v.cod_via
	
	INNER JOIN ubigeo_reniec AS  ur
	ON esd.cod_ubigeo_reniec = ur.cod_ubigeo_reniec
	
	
	INNER JOIN ubigeo_provincias AS  up
	ON ur.cod_provincia = up.cod_provincia
	
	INNER JOIN ubigeo_departamentos AS ud
	ON ur.cod_departamento = ud.cod_departamento
	
	INNER JOIN zonas AS z
	ON esd.cod_zona = z.cod_zona

-- finall Direccion Lineal

-- 
WHERE e.id_empleador = 1
AND eme.empleador_que_yo_destaco_personal ='ACTIVO'


















SELECT *FROM empleadores_maestros


SELECT *FROM empleadores_maestros_empleadores


SELECT *FROM establecimientos

SELECT *FROM detalles_establecimientos_vinculados

