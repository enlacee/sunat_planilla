/*
Created		23/04/2012
Modified		16/06/2012
Project		T-PLAME
Model		db
Company		CAMUENTE S.A.
Author		anibal Copitan Norabuena
Version		1.0
Database		mySQL 5 
*/


drop table IF EXISTS establecimientos_vinculados;
drop table IF EXISTS servicios_prestados;
drop table IF EXISTS servicios_prestados_yourself;
drop table IF EXISTS empleadores_destaques_yourself;
drop table IF EXISTS empleadores_destaques;
drop table IF EXISTS detalle_establecimiento;
drop table IF EXISTS detalle_regimenes_salud;
drop table IF EXISTS empleadores_maestros;
drop table IF EXISTS v_familiares_d_v_familiares;
drop table IF EXISTS tipos_sociedades_comerciales;
drop table IF EXISTS establecimientos_direcciones;
drop table IF EXISTS montos_remuneraciones;
drop table IF EXISTS t_empleadores_t_r_laborales;
drop table IF EXISTS estados_civiles;
drop table IF EXISTS derechohabientes_direcciones;
drop table IF EXISTS ubigeo_reniec;
drop table IF EXISTS ubigeo_provincias;
drop table IF EXISTS ubigeo_departamentos;
drop table IF EXISTS personas_direcciones;
drop table IF EXISTS conceptos_seleccionados;
drop table IF EXISTS d_conceptos_afectaciones;
drop table IF EXISTS afectaciones;
drop table IF EXISTS detalles_conceptos;
drop table IF EXISTS conceptos;
drop table IF EXISTS tipos_comprobantes_pagos;
drop table IF EXISTS tipos_suspensiones_relaciones_laborales;
drop table IF EXISTS motivos_bajas_derechohabientes;
drop table IF EXISTS situaciones;
drop table IF EXISTS documentos_vinculos_familiares;
drop table IF EXISTS vinculos_familiares;
drop table IF EXISTS derechohabientes;
drop table IF EXISTS coberturas_salud;
drop table IF EXISTS lugares_testaques;
drop table IF EXISTS periodos_destaques;
drop table IF EXISTS personales_terceros;
drop table IF EXISTS detalle_establecimientos_formacion;
drop table IF EXISTS detalle_periodos_formativos;
drop table IF EXISTS ocupacion_2;
drop table IF EXISTS modalidades_formativas;
drop table IF EXISTS personas;
drop table IF EXISTS personales_formaciones_laborales;
drop table IF EXISTS detalle_periodos_laborales_pensionistas;
drop table IF EXISTS pensionistas;
drop table IF EXISTS convenios;
drop table IF EXISTS detalle_regimenes_pensionarios;
drop table IF EXISTS t_empleadores_r_pensionarios;
drop table IF EXISTS regimenes_pensionarios;
drop table IF EXISTS regimenes_aseguramientos_salud;
drop table IF EXISTS tipos_establecimientos;
drop table IF EXISTS establecimientos;
drop table IF EXISTS periodos_remuneraciones;
drop table IF EXISTS tipos_pagos;
drop table IF EXISTS tipos_contratos;
drop table IF EXISTS c_ocupaciones_ocupaciones_p;
drop table IF EXISTS ocupaciones_p;
drop table IF EXISTS t_empleadores_c_ocupacionales;
drop table IF EXISTS categorias_ocupacionales;
drop table IF EXISTS niveles_educativos;
drop table IF EXISTS detalle_tipos_trabajadores;
drop table IF EXISTS t_trabajadores_t_empleadores;
drop table IF EXISTS tipos_empleadores;
drop table IF EXISTS empleadores;
drop table IF EXISTS regimenes_laborales;
drop table IF EXISTS detalle_periodos_laborales;
drop table IF EXISTS motivos_bajas_registros;
drop table IF EXISTS tipos_trabajadores;
drop table IF EXISTS zonas;
drop table IF EXISTS vias;
drop table IF EXISTS telefonos_codigos_nacionales;
drop table IF EXISTS nacionalidades;
drop table IF EXISTS tipos_actividades;
drop table IF EXISTS paises_emisores_documentos;
drop table IF EXISTS tipos_documentos;
drop table IF EXISTS trabajadores;


Create table if not exists trabajadores (
	id_trabajador Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_persona Int UNSIGNED NOT NULL,
	cod_regimen_laboral Char(2) NOT NULL,
	cod_nivel_educativo Char(2) NOT NULL,
	cod_categorias_ocupacionales Char(2) NOT NULL,
	id_ocupacion_2 Char(6) NOT NULL COMMENT 'select ->Publico',
	cod_ocupacion_p Int NOT NULL COMMENT 'sector -> privado',
	cod_tipo_contrato Char(2) NOT NULL,
	cod_tipo_pago Char(1) NOT NULL,
	cod_periodo_remuneracion Int NOT NULL,
	monto_remuneracion Decimal(10,2),
	id_monto_remuneracion Int UNSIGNED NOT NULL,
	id_establecimiento Int UNSIGNED NOT NULL,
	jornada_laboral Varchar(200) COMMENT 'chek box ->datos linel
01 = joranada de trabajo maxima
02 = joranada atipica acumulatica
03 = trabajo en horario nocturno',
	situacion_especial Varchar(30) COMMENT '01 = trabajador-direccion
02 = trabajador-confianza
03 = ninguna',
	discapacitado Bool,
	sindicalizado Bool,
	percibe_renta_5ta_exonerada Bool COMMENT 'DATOS TRIBUTARIOS
¿Percibe rentas de 5ta exoneradas (Inc. e) Art. 19 de la LIR?',
	aplicar_convenio_doble_inposicion Bool COMMENT 'DATOS TRIBUTARIOS
¿Aplica convenio para evitar doble imposición?

SI = TABLA 25 = convenios',
	cod_convenio Int NOT NULL,
	cod_situacion Char(1) NOT NULL,
	estado Char(10) COMMENT 'sistema:
1 = activo
2 = inactivo',
 Primary Key (id_trabajador)) ENGINE = InnoDB
COMMENT = '*';

Create table if not exists tipos_documentos (
	cod_tipo_documento Char(2) NOT NULL,
	descripcion Varchar(40),
	descripcion_abreviada Varchar(20),
 Primary Key (cod_tipo_documento)) ENGINE = InnoDB
COMMENT = '''TABLA 3: "TIPO DE DOCUMENTO DE IDENTIDAD"';

Create table if not exists paises_emisores_documentos (
	cod_pais_emisor_documento Char(3) NOT NULL,
	descripcion Varchar(40),
 Primary Key (cod_pais_emisor_documento)) ENGINE = InnoDB
COMMENT = '''TABLA 26 "PAÍS EMISOR DEL DOCUMENTO - SOLO PARA TIPO DE DOC. PASAPORTE"
Solo para  (TD) = pasaporte';

Create table tipos_actividades (
	cod_tipo_actividad Char(5) NOT NULL,
	descripcion Varchar(50),
 Primary Key (cod_tipo_actividad)) ENGINE = InnoDB
COMMENT = '''TABLA 1: "TIPO DE ACTIVIDAD"';

Create table if not exists nacionalidades (
	cod_nacionalidad Char(4) NOT NULL,
	descripcion Varchar(50),
 Primary Key (cod_nacionalidad)) ENGINE = InnoDB
COMMENT = '''TABLA 4: "NACIONALIDAD"';

Create table telefonos_codigos_nacionales (
	cod_telefono_codigo_nacional Char(3) NOT NULL,
	descripcion Varchar(30),
 Primary Key (cod_telefono_codigo_nacional)) ENGINE = InnoDB
COMMENT = '''TABLA 29 "CÓDIGOS DE LARGA DISTANCIA NACIONAL"
CLDN = codigos de larga distancia nacional';

Create table vias (
	cod_via Char(2) NOT NULL,
	descripcion Char(30),
 Primary Key (cod_via)) ENGINE = InnoDB
COMMENT = '''TABLA 5: "VÍA"';

Create table zonas (
	cod_zona Char(2) NOT NULL,
	descripcion Varchar(30),
 Primary Key (cod_zona)) ENGINE = InnoDB
COMMENT = '''TABLA 6: "ZONA"';

Create table if not exists tipos_trabajadores (
	cod_tipo_trabajador Char(2) NOT NULL,
	descripcion Varchar(100),
	descripcion_abreviada Varchar(50),
 Primary Key (cod_tipo_trabajador)) ENGINE = InnoDB
COMMENT = '''TABLA 8: "TIPO DE TRABAJADOR, PENSIONISTA O PRESTADOR DE SERVICIOS"
----tipo trabajador=Categoría Ocupacional<------->ejecutivo,obrero,empleado formulario SUNAT';

Create table if not exists motivos_bajas_registros (
	cod_motivo_baja_registro Char(2) NOT NULL,
	descripcion Varchar(100),
	descripcion_abreviada Varchar(60),
 Primary Key (cod_motivo_baja_registro)) ENGINE = InnoDB
COMMENT = '''TABLA 17: "MOTIVO DE LA BAJA DEL REGISTRO"

<-----CONDICION------>
crear los filtros para 
TB_TRABAJADOR
TB_PENSIONISTA';

Create table detalle_periodos_laborales (
	id_detalle_periodo_laboral Int NOT NULL AUTO_INCREMENT,
	id_trabajador Int UNSIGNED NOT NULL,
	cod_motivo_baja_registro Char(2) NOT NULL,
	fecha_inicio Date,
	fecha_fin Date,
 Primary Key (id_detalle_periodo_laboral)) ENGINE = InnoDB
COMMENT = '------HISTORIAL------';

Create table if not exists regimenes_laborales (
	cod_regimen_laboral Char(2) NOT NULL,
	descripcion Varchar(100),
	descripcion_abreviada Varchar(50),
 Primary Key (cod_regimen_laboral)) ENGINE = InnoDB
COMMENT = '''TABLA 33:  "RÉGIMEN LABORAL"
---Trabaja con 2 combos Condicional ----';

Create table if not exists empleadores (
	id_empleador Int NOT NULL AUTO_INCREMENT,
	id_tipo_empleador Int NOT NULL,
	cod_telefono_codigo_nacional Char(3) NOT NULL,
	ruc Varchar(11),
	razon_social Varchar(100) COMMENT '*',
	id_tipo_sociedad_comercial Int UNSIGNED NOT NULL,
	nombre_comercial Varchar(100) COMMENT '*',
	cod_tipo_actividad Char(5) NOT NULL COMMENT 'RESTAURANT ''LA ROMANA''',
	telefono Varchar(20),
	correo Varchar(100),
	empresa_dedica Varchar(30) COMMENT 'NINGUNA
INTERMEDIACION
TERCERIZACION
',
	senati Bool,
	remype Bool COMMENT 'SI es Remype = 1
tiene dos opciones mas 
remype microempres 
o
remipe pequenia empresa',
	remype_tipo_empresa Varchar(20) COMMENT 'microempresa
o
pequenia empresa',
	trabajador_sin_rp Bool COMMENT 'R P = Regigem  Pensionario',
	actividad_riesgo_sctr Bool COMMENT 'SCTR = Seguro Complementario de Riesgo',
	trabajadores_sctr Bool COMMENT 'Tiene trabajadores por los que aporta al SCTR?',
	persona_discapacidad Bool,
	agencia_empleo Bool COMMENT 'Es una Agencia Privada de empleos',
	desplaza_personal Bool COMMENT '¿Destaca o desplaza personal a otros empleadores?',
	terceros_desplaza_usted Char(20) COMMENT '¿Terceros empleadores le destacan o desplazan personal?',
	estado_empleador Varchar(20) COMMENT '01 = titular
02 = no-titular

*** Empleador Titular sera validado con acceso al sistema***',
	fecha_creacion Date,
	UNIQUE (ruc),
 Primary Key (id_empleador)) ENGINE = InnoDB
COMMENT = 'remype CONDICIONADO tabla 33=regimen laboral
Registro de Todos los Empleadores...';

Create table if not exists tipos_empleadores (
	id_tipo_empleador Int NOT NULL AUTO_INCREMENT,
	descripcion Varchar(30),
 Primary Key (id_tipo_empleador)) ENGINE = InnoDB
COMMENT = 'Sector Publico
Sector Privado
Otro
--- Entidaddes de Trabajo ---';

Create table if not exists t_trabajadores_t_empleadores (
	id_t_trabajador_t_empleador Int NOT NULL AUTO_INCREMENT,
	id_tipo_empleador Int NOT NULL,
	cod_tipo_trabajador Char(2) NOT NULL,
 Primary Key (id_t_trabajador_t_empleador,id_tipo_empleador,cod_tipo_trabajador)) ENGINE = InnoDB
COMMENT = 'relacion segun tablas
1=tipos_trabajadores
2=tipos_empleadores';

Create table if not exists detalle_tipos_trabajadores (
	id_detalle_tipo_trabajador Int NOT NULL AUTO_INCREMENT,
	id_trabajador Int UNSIGNED NOT NULL,
	cod_tipo_trabajador Char(2) NOT NULL,
	fecha_inicio Date,
	fecha_fin Date,
 Primary Key (id_detalle_tipo_trabajador)) ENGINE = InnoDB
COMMENT = '-T_REGISTRO :Tipo de trabajador.
muestra primero (ACTUAL)
el resto en Historial
---- Historial-----';

Create table if not exists niveles_educativos (
	cod_nivel_educativo Char(2) NOT NULL,
	descripcion Varchar(50),
	descripcion_abreviada Varchar(40),
 Primary Key (cod_nivel_educativo)) ENGINE = InnoDB
COMMENT = '
''TABLA 9: "NIVEL EDUCATIVO"';

Create table categorias_ocupacionales (
	cod_categorias_ocupacionales Char(2) NOT NULL,
	descripcion Varchar(30),
 Primary Key (cod_categorias_ocupacionales)) ENGINE = InnoDB
COMMENT = '''TABLA 24 "CATEGORÍA OCUPACIONAL DEL TRABAJADOR" (*)';

Create table t_empleadores_c_ocupacionales (
	id_t_empleador_c_ocupacional Int NOT NULL AUTO_INCREMENT,
	id_tipo_empleador Int NOT NULL,
	cod_categorias_ocupacionales Char(2) NOT NULL,
 Primary Key (id_t_empleador_c_ocupacional,id_tipo_empleador,cod_categorias_ocupacionales)) ENGINE = InnoDB
COMMENT = '1=tipos_trabajadores
2=tipos_empleadores';

Create table ocupaciones_p (
	cod_ocupacion_p Int NOT NULL AUTO_INCREMENT,
	nombre Varchar(150),
 Primary Key (cod_ocupacion_p)) ENGINE = InnoDB
COMMENT = '''TABLA 30 "OCUPACIÓN APLICABLE A LOS EMPLEADORES DEL SECTOR PRIVADO".
=Ocupaciones Privadas

 ocupacion_2 = ocupacion_p
con algunos cambios';

Create table c_ocupaciones_ocupaciones_p (
	id_c_ocupacion_ocupacion_p Int UNSIGNED NOT NULL AUTO_INCREMENT,
	cod_categorias_ocupacionales Char(2) NOT NULL,
	cod_ocupacion_p Int NOT NULL,
 Primary Key (id_c_ocupacion_ocupacion_p,cod_categorias_ocupacionales,cod_ocupacion_p)) ENGINE = InnoDB;

Create table if not exists tipos_contratos (
	cod_tipo_contrato Char(2) NOT NULL,
	descripcion Varchar(100),
	descripcion_abreviada Varchar(50),
 Primary Key (cod_tipo_contrato)) ENGINE = InnoDB
COMMENT = '''TABLA 12: "TIPO DE CONTRATO DE TRABAJO/CONDICIÓN LABORAL"
condicion manual php';

Create table if not exists tipos_pagos (
	cod_tipo_pago Char(1) NOT NULL COMMENT 'NO AUTOINCREMENT',
	descripcion Varchar(30),
 Primary Key (cod_tipo_pago)) ENGINE = InnoDB
COMMENT = '''TABLA 16: "TIPO DE PAGO"
efectivo
cheke
....';

Create table if not exists periodos_remuneraciones (
	cod_periodo_remuneracion Int NOT NULL COMMENT 'NO AUTOINCREMENTAL',
	descripcion Varchar(30),
 Primary Key (cod_periodo_remuneracion)) ENGINE = InnoDB
COMMENT = '''TABLA 13: "PERIODICIDAD DE LA REMUNERACIÓN O RETRIBUCIÓN"';

Create table establecimientos (
	id_establecimiento Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_empleador Int NOT NULL,
	id_tipo_establecimiento Int UNSIGNED NOT NULL,
	cod_establecimiento Char(4) COMMENT 'segun codigos que maneja sunat!!

0001
0003
0004
etc
Algoritmo PHP crear codigo',
	realizaran_actividad_riesgo Bool COMMENT 'OKK FULL Analisis = NO POR default
Contatenado THIS UPDATE 
con tabla Establecimientos
:: ¿Desarrolla actividades de riesgo SCTR?  = NO por default
',
	fecha_creacion Date,
 Primary Key (id_establecimiento)) ENGINE = InnoDB
COMMENT = 'Relacion_OK_con trabajador
SUNAT  ESTABLECIMIENTOS 
form de presentacion
-------------
Establecimientos en el que labora';

Create table tipos_establecimientos (
	id_tipo_establecimiento Int UNSIGNED NOT NULL AUTO_INCREMENT,
	cod_tipo_establecimiento Char(2) NOT NULL,
	descripcion Varchar(30),
	comentario Text,
	UNIQUE (cod_tipo_establecimiento),
 Primary Key (id_tipo_establecimiento)) ENGINE = InnoDB
COMMENT = 'SUNAT
-Registro Unico de Contribullente
Establecimientos ANEXOS
formulario 2046

ANBCOPITAN - T-REGISTRO
domicilio fiscal => recibos,tramites de sunat 
solo puede tener 1 domicilio fiscal. la EMPRESA

domicilio Sucursal =>';

Create table regimenes_aseguramientos_salud (
	cod_regimen_aseguramiento_salud Char(2) NOT NULL,
	descripcion Varchar(70),
	descripcion_abreviada Varchar(50),
 Primary Key (cod_regimen_aseguramiento_salud)) ENGINE = InnoDB
COMMENT = '''TABLA 32: "REGIMEN DE ASEGURAMIENTO DE SALUD".
---------------
condicional listado del combo';

Create table regimenes_pensionarios (
	cod_regimen_pensionario Char(2) NOT NULL,
	descripcion Varchar(70),
	descripcion_abreviada Varchar(50),
 Primary Key (cod_regimen_pensionario)) ENGINE = InnoDB
COMMENT = '''TABLA 11: "RÉGIMEN PENSIONARIO"';

Create table t_empleadores_r_pensionarios (
	id_t_empleador_r_pensionario Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_tipo_empleador Int NOT NULL,
	cod_regimen_pensionario Char(2) NOT NULL,
 Primary Key (id_t_empleador_r_pensionario,id_tipo_empleador,cod_regimen_pensionario)) ENGINE = InnoDB
COMMENT = 'COMBO BOX
Filtro para LISTADO DEL COMBO - 
segun sectores
SIRVE SOLO PARA EL LISTADO
okk';

Create table detalle_regimenes_pensionarios (
	id_detalle_regimen_pensionario Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_trabajador Int UNSIGNED NOT NULL,
	cod_regimen_pensionario Char(2) NOT NULL,
	CUSPP Char(15) COMMENT 'no tiene codigo si es 
ONP',
	fecha_inicio Date,
	fecha_fin Date,
 Primary Key (id_detalle_regimen_pensionario,id_trabajador,cod_regimen_pensionario)) ENGINE = InnoDB
COMMENT = '--------HISTORIAL-------
link detalle';

Create table if not exists convenios (
	cod_convenio Int NOT NULL,
	descripcion Varchar(20),
 Primary Key (cod_convenio)) ENGINE = InnoDB
COMMENT = '''TABLA 25 "CONVENIOS PARA EVITAR LA DOBLE TRIBUTACIÓN"';

Create table if not exists pensionistas (
	id_pensionista Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_persona Int UNSIGNED NOT NULL,
	cod_tipo_trabajador Char(2) NOT NULL,
	cod_regimen_pensionario Char(2) NOT NULL,
	cod_tipo_pago Char(1) NOT NULL,
	cod_situacion Char(1) NOT NULL COMMENT 'ACTIVO
INACTIVO = SUNAT',
	estado Char(10) COMMENT 'sistema:
1 = activo
2 = inactivo',
 Primary Key (id_pensionista)) ENGINE = InnoDB
COMMENT = '*';

Create table detalle_periodos_laborales_pensionistas (
	id_detalle_periodo_laboral_pensionista Int NOT NULL AUTO_INCREMENT,
	cod_motivo_baja_registro Char(2) NOT NULL COMMENT 'IMPORTANT
(  USAR condicion para 
PARA LLENAR EL COMBO EXCLUSIvo para
el pensionista
validar... )

ALL PHP 
Duro=No',
	id_pensionista Int UNSIGNED NOT NULL,
	fecha_inicio Date,
	fecha_fin Date,
 Primary Key (id_detalle_periodo_laboral_pensionista)) ENGINE = InnoDB
COMMENT = '
------ HISTORIAL ------';

Create table if not exists personales_formaciones_laborales (
	id_personal_formacion_laboral Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_persona Int UNSIGNED NOT NULL,
	cod_nivel_educativo Char(2) NOT NULL,
	id_modalidad_formativa Char(2) NOT NULL,
	id_ocupacion_2 Char(6) NOT NULL,
	centro_formacion Varchar(30) COMMENT 'RadioButton all txt
universidad
intituto
otro
otro
',
	seguro_medico Varchar(30) COMMENT 'radio button',
	presenta_discapacidad Bool,
	horario_nocturno Bool,
	cod_situacion Char(1) NOT NULL COMMENT 'ACTIVO
INACTIVO',
	estado Char(10) COMMENT 'sistema:
1 = activo
2 = inactivo',
 Primary Key (id_personal_formacion_laboral)) ENGINE = InnoDB
COMMENT = '*';

Create table if not exists personas (
	id_persona Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_empleador Int NOT NULL,
	cod_pais_emisor_documento Char(3) NOT NULL,
	cod_tipo_documento Char(2),
	cod_nacionalidad Char(4) NOT NULL,
	num_documento Varchar(15),
	fecha_nacimiento Date,
	apellido_paterno Varchar(40),
	apellido_materno Varchar(40),
	nombres Varchar(40),
	sexo Char(1) COMMENT 'masculino = 1
femenino = 2',
	id_estado_civil Int UNSIGNED NOT NULL COMMENT '1=soltero
2=casado
MYSAM',
	cod_telefono_codigo_nacional Char(3),
	telefono Char(20) COMMENT 'Dato obligatorio solo si registro CLDN. ',
	correo Varchar(250),
	tabla_trabajador Bool COMMENT 'estdo = INSERT',
	tabla_pensionista Bool COMMENT 'estdo = INSERT',
	tabla_personal_formacion_laboral Bool COMMENT 'estdo = INSERT',
	tabla_personal_terceros Bool COMMENT 'estdo = INSERT',
	estado Char(10) COMMENT 'ACTIVO
INACTIVO',
	fecha_creacion Date,
	fecha_modificacion Date,
	fecha_baja Date,
 Primary Key (id_persona)) ENGINE = InnoDB
COMMENT = '*';

Create table modalidades_formativas (
	id_modalidad_formativa Char(2) NOT NULL,
	descripcion Varchar(50) COMMENT 'radio button
1 esalud
2 privado',
	descripcion_abreviada Char(40),
 Primary Key (id_modalidad_formativa)) ENGINE = InnoDB
COMMENT = '''TABLA 18: "TIPO DE MODALIDAD FORMATIVA LABORAL Y OTROS"';

Create table ocupacion_2 (
	id_ocupacion_2 Char(6) NOT NULL,
	descripcion Varchar(200),
 Primary Key (id_ocupacion_2)) ENGINE = InnoDB
COMMENT = '''TABLA 10: "OCUPACIÓN ADMINISTRACIÓN PÚBLICA Y PS MODALIDAD FORMATIVA LABORAL"

 ocupacion_2 = ocupacion_p
con algunos cambios';

Create table detalle_periodos_formativos (
	id_detalle_periodo_formativo Int NOT NULL AUTO_INCREMENT,
	id_personal_formacion_laboral Int UNSIGNED NOT NULL,
	fecha_inicio Date,
	fecha_fin Date,
 Primary Key (id_detalle_periodo_formativo)) ENGINE = InnoDB
COMMENT = '------ detallle HISTORIAL- ---';

Create table detalle_establecimientos_formacion (
	id_detalle_establecimiento_formacion Int NOT NULL AUTO_INCREMENT,
	id_personal_formacion_laboral Int UNSIGNED NOT NULL,
	id_establecimiento Int UNSIGNED NOT NULL,
 Primary Key (id_detalle_establecimiento_formacion,id_personal_formacion_laboral,id_establecimiento)) ENGINE = InnoDB
COMMENT = 'Establecimiento en el que se forma:
ANALIZADO! funciona

link_detalle';

Create table personales_terceros (
	id_personal_tercero Int NOT NULL AUTO_INCREMENT,
	id_persona Int UNSIGNED NOT NULL,
	ruc_empresa_que_destaca Varchar(15),
	cobertura_pension Varchar(20) COMMENT 'RADIO BUTTON
Cobertura Pensión:	 

ONP	 
Seguro Privado',
 Primary Key (id_personal_tercero)) ENGINE = InnoDB
COMMENT = 'RUC de la empresa que destaca o desplaza';

Create table periodos_destaques (
	id_periodo_destaque Int NOT NULL AUTO_INCREMENT COMMENT 'Periodos de destaque o desplace:',
	id_personal_tercero Int NOT NULL,
	fecha_inicio Date,
	fecha_fin Date,
 Primary Key (id_periodo_destaque)) ENGINE = InnoDB
COMMENT = 'LINK_DETALLE';

Create table lugares_testaques (
	id_lugar_destaque Int NOT NULL AUTO_INCREMENT,
	id_personal_tercero Int NOT NULL,
	id_establecimiento Int UNSIGNED NOT NULL,
 Primary Key (id_lugar_destaque,id_personal_tercero,id_establecimiento)) ENGINE = InnoDB;

Create table coberturas_salud (
	id_cobertura_salud Int NOT NULL AUTO_INCREMENT,
	id_personal_tercero Int NOT NULL,
	nombre_cobertura Varchar(20) COMMENT 'RADIO BUTTON
Cobertura Salud:	 
EsSalud
EPS',
	fecha_inicio Date,
	fecha_fin Date,
 Primary Key (id_cobertura_salud)) ENGINE = MyISAM
COMMENT = 'SCTR Salud - Vigencia de cobertura
------ HISTORIAL ------
link_detalle
VALIDAR fechas!! OJO!
fechas periodicas';

Create table if not exists derechohabientes (
	id_derechohabiente Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_persona Int UNSIGNED NOT NULL,
	cod_tipo_documento Char(2) NOT NULL,
	num_documento Varchar(15),
	cod_pais_emisor_documento Char(3) NOT NULL,
	fecha_nacimiento Date,
	apellido_paterno Varchar(40),
	apellido_materno Varchar(40),
	nombres Varchar(40),
	sexo Char(1) COMMENT 'masculino = 1
femenino = 2',
	id_estado_civil Int UNSIGNED NOT NULL COMMENT '1=soltero
2=casado',
	cod_vinculo_familiar Char(2) NOT NULL,
	cod_documento_vinculo_familiar Char(2) NOT NULL,
	vf_num_documento Varchar(20) COMMENT 'vinculo familiar Numero Documento',
	vf_mes_concepcion Date COMMENT 'mes/anio',
	cod_telefono_codigo_nacional Char(3) COMMENT 'SET = 0',
	telefono Char(20) COMMENT 'Dato obligatorio solo si registro CLDN. ',
	correo Varchar(150),
	cod_motivo_baja_derechohabiente Char(2) NOT NULL,
	cod_situacion Char(1) NOT NULL COMMENT 'default = ACTIVO
vf_situacion',
	estado Char(10) COMMENT 'sistema:
1 = activo
2 = inactivo',
	fecha_baja Date,
	fecha_creacion Date,
 Primary Key (id_derechohabiente)) ENGINE = InnoDB;

Create table vinculos_familiares (
	cod_vinculo_familiar Char(2) NOT NULL,
	descripcion Varchar(50),
	descripcion_abreviada Varchar(40),
 Primary Key (cod_vinculo_familiar)) ENGINE = InnoDB
COMMENT = '''TABLA 19: "VÍNCULO FAMILIAR"';

Create table documentos_vinculos_familiares (
	cod_documento_vinculo_familiar Char(2) NOT NULL,
	descripcion Varchar(100),
	descripcion_abreviada Varchar(50),
 Primary Key (cod_documento_vinculo_familiar)) ENGINE = InnoDB
COMMENT = '''TABLA 27 "DOCUMENTO QUE SUSTENTA VÍNCULO FAMILIAR"';

Create table situaciones (
	cod_situacion Char(1) NOT NULL,
	descripcion Varchar(70),
	descripcion_abreviada Varchar(40),
 Primary Key (cod_situacion)) ENGINE = InnoDB
COMMENT = '''TABLA 15: "SITUACIÓN DEL TRABAJADOR O PENSIONISTA"';

Create table motivos_bajas_derechohabientes (
	cod_motivo_baja_derechohabiente Char(2) NOT NULL,
	descripcion Varchar(70),
	descripcion_abreviada Varchar(40),
	condicion Text COMMENT 'condicion segun ocurra
anexo 2: Inportante',
 Primary Key (cod_motivo_baja_derechohabiente)) ENGINE = InnoDB
COMMENT = '''TABLA 20: "MOTIVO DE BAJA COMO DERECHOHABIENTE"';

Create table tipos_suspensiones_relaciones_laborales (
	cod_tipo_suspen_relacion_laboral Char(2) NOT NULL,
	descripcion Varchar(100),
	descripcion_abreviada Varchar(50),
 Primary Key (cod_tipo_suspen_relacion_laboral)) ENGINE = MyISAM
COMMENT = '''TABLA 21: "TIPO DE SUSPENSIÓN DE LA RELACIÓN LABORAL"
S.P. = SUSPENSIÓN PERFECTA	
S.I. = SUSPENSIÓN IMPERFECTA';

Create table tipos_comprobantes_pagos (
	cod_tipo_comprobante_pago Char(1) NOT NULL,
	descripcion Varchar(30),
 Primary Key (cod_tipo_comprobante_pago)) ENGINE = MyISAM
COMMENT = 'TABLA 23: "TIPO DE COMPROBANTE DEL PRESTADOR DE SERVICIOS A QUE SE REFIERE EL NUMERAL I) DEL LITERAL D) DEL ARTÍCULO 1° DEL DECRETO SUPREMO N.° 018-2007-TR Y NORMA MODIFICATORIAS (PS 4TA CATEGORÍA)';

Create table conceptos (
	cod_concepto Char(4) NOT NULL,
	descripcion Varchar(50),
	seleccionado Bool,
 Primary Key (cod_concepto)) ENGINE = InnoDB
COMMENT = '''TABLA 22:  "INGRESOS, TRIBUTOS Y DESCUENTOS"
0100
0200
0300
Lista total para Todos los empleadores';

Create table detalles_conceptos (
	cod_detalle_concepto Char(4) NOT NULL,
	cod_concepto Char(4) NOT NULL,
	descripcion Varchar(200),
 Primary Key (cod_detalle_concepto)) ENGINE = InnoDB
COMMENT = 'insert
0101
0102
0103
..xxxx
Lo mismo para todos los Empleadores';

Create table afectaciones (
	cod_afectacion Char(4) NOT NULL,
	descripcion Varchar(100),
 Primary Key (cod_afectacion)) ENGINE = InnoDB
COMMENT = 'NO MODIFICABLE';

Create table d_conceptos_afectaciones (
	id_d_concepto_afectacion Int UNSIGNED NOT NULL AUTO_INCREMENT,
	cod_detalle_concepto Char(4) NOT NULL,
	cod_afectacion Char(4) NOT NULL,
	seleccionado Bool,
 Primary Key (id_d_concepto_afectacion,cod_detalle_concepto,cod_afectacion)) ENGINE = InnoDB
COMMENT = 'check_marcados
No Modificable XQ son fijos = relacion';

Create table conceptos_seleccionados (
	id_concepto_seleccionado Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_empleador Int NOT NULL,
	cod_detalle_concepto Char(4) NOT NULL,
	seleccionado Bool,
	fecha Date,
 Primary Key (id_concepto_seleccionado)) ENGINE = InnoDB;

Create table personas_direcciones (
	id_persona_direccion Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_persona Int UNSIGNED NOT NULL,
	cod_ubigeo_reniec Char(6) NOT NULL,
	cod_via Char(2) NOT NULL,
	nombre_via Varchar(20),
	numero_via Char(4),
	departamento Char(4) COMMENT 'Numero Departamento',
	interior Char(4),
	manzana Char(4),
	lote Char(4),
	kilometro Char(4),
	block Char(4),
	etapa Char(4),
	cod_zona Char(2) NOT NULL,
	nombre_zona Varchar(20),
	referencia Varbinary(100),
	referente_essalud Bool COMMENT 'radio button',
	estado_direccion Int COMMENT '1 = direccion principal
0 = direccion 2',
 Primary Key (id_persona_direccion)) ENGINE = InnoDB
COMMENT = 'MAX 2 direcciones por Persona';

Create table ubigeo_departamentos (
	cod_departamento Char(2) NOT NULL,
	descripcion Varchar(30),
 Primary Key (cod_departamento)) ENGINE = InnoDB;

Create table ubigeo_provincias (
	cod_provincia Char(4) NOT NULL,
	descripcion Varchar(30),
 Primary Key (cod_provincia)) ENGINE = InnoDB;

Create table ubigeo_reniec (
	cod_ubigeo_reniec Char(6) NOT NULL,
	cod_departamento Char(2) NOT NULL,
	cod_provincia Char(4) NOT NULL,
	descripcion Varchar(30),
 Primary Key (cod_ubigeo_reniec)) ENGINE = InnoDB
COMMENT = 'TABLA 28 - UBIGEO RENIEC - PARA SER UTILIZADO EN EL T-REGISTRO';

Create table derechohabientes_direcciones (
	id_derechohabiente_direccion Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_derechohabiente Int UNSIGNED NOT NULL,
	cod_ubigeo_reniec Char(6) NOT NULL,
	cod_via Char(2) NOT NULL,
	nombre_via Varchar(20),
	numero_via Char(4),
	departamento Char(4) COMMENT 'Numero Departamento',
	interior Char(4),
	manzana Char(4),
	lote Char(4),
	kilometro Char(4),
	block Char(4),
	etapa Char(4),
	cod_zona Char(2) NOT NULL,
	nombre_zona Varchar(20),
	referencia Varbinary(100),
	referente_essalud Bool COMMENT 'radio button',
	estado_direccion Int COMMENT '1 = primera direccion
2 = segunda direccion',
 Primary Key (id_derechohabiente_direccion)) ENGINE = InnoDB
COMMENT = 'MAX 2 direcciones por Persona';

Create table estados_civiles (
	id_estado_civil Int UNSIGNED NOT NULL AUTO_INCREMENT,
	descripcion Char(20),
 Primary Key (id_estado_civil)) ENGINE = InnoDB
COMMENT = 'MYSAM';

Create table t_empleadores_t_r_laborales (
	id_t_empleadores_t_r_laborales Int UNSIGNED NOT NULL AUTO_INCREMENT,
	cod_regimen_laboral Char(2) NOT NULL,
	id_tipo_empleador Int NOT NULL,
 Primary Key (id_t_empleadores_t_r_laborales,cod_regimen_laboral,id_tipo_empleador)) ENGINE = MyISAM
COMMENT = 'tipos empleadores

regimenes laborales';

Create table montos_remuneraciones (
	id_monto_remuneracion Int UNSIGNED NOT NULL AUTO_INCREMENT,
	cantidad Decimal(10,2),
	fecha_creacion Date,
 Primary Key (id_monto_remuneracion)) ENGINE = InnoDB
COMMENT = 'Monto de remuneración básica inicial:';

Create table establecimientos_direcciones (
	id_establecimiento_direccion Int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'llave no USAR!',
	id_establecimiento Int UNSIGNED NOT NULL,
	cod_ubigeo_reniec Char(6) NOT NULL,
	cod_via Char(2) NOT NULL,
	nombre_via Varchar(20),
	numero_via Char(4),
	departamento Char(4) COMMENT 'Numero Departamento',
	interior Char(4),
	manzana Char(4),
	lote Char(4),
	kilometro Char(4),
	block Char(4),
	etapa Char(4),
	cod_zona Char(2) NOT NULL,
	nombre_zona Varchar(20),
	referencia Varbinary(100),
 Primary Key (id_establecimiento_direccion,id_establecimiento)) ENGINE = InnoDB
COMMENT = 'direccion UNICA principal ante SUNAT
1 Establecimiento tiene 1Direccion VALIDAR THIS';

Create table tipos_sociedades_comerciales (
	id_tipo_sociedad_comercial Int UNSIGNED NOT NULL AUTO_INCREMENT,
	descripcion Char(250),
	descripcion_abreviada Varchar(10),
 Primary Key (id_tipo_sociedad_comercial)) ENGINE = InnoDB
COMMENT = 'Ley 26887 9/12/1997 Ley de Sociedades';

Create table v_familiares_d_v_familiares (
	id_v_familiares_d_v_familiares Int UNSIGNED NOT NULL AUTO_INCREMENT,
	cod_documento_vinculo_familiar Char(2) NOT NULL,
	cod_vinculo_familiar Char(2) NOT NULL,
 Primary Key (id_v_familiares_d_v_familiares,cod_documento_vinculo_familiar,cod_vinculo_familiar)) ENGINE = MyISAM
COMMENT = 'vinculos falimiares _ documentos_vinculos_familiares';

Create table empleadores_maestros (
	id_empleador_maestro Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_empleador Int NOT NULL,
	fecha_creacion Date,
 Primary Key (id_empleador_maestro)) ENGINE = InnoDB
COMMENT = 'Empleador Maestro 
id_empleador ->Se convierte en Empleador_maestro

NO ESTA IMPLEMENTADO!';

Create table detalle_regimenes_salud (
	id_detalle_regimen_salud Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_trabajador Int UNSIGNED NOT NULL,
	cod_regimen_aseguramiento_salud Char(2) NOT NULL,
	fecha_inicio Char(20),
	fecha_fin Date,
 Primary Key (id_detalle_regimen_salud)) ENGINE = InnoDB;

Create table detalle_establecimiento (
	id_detalle_establecimiento Int NOT NULL AUTO_INCREMENT,
	id_trabajador Int UNSIGNED NOT NULL,
	id_establecimiento Int UNSIGNED NOT NULL,
 Primary Key (id_detalle_establecimiento)) ENGINE = InnoDB
COMMENT = 'detalle :
Historial de  Establecimientos donde trabajo si es nedd
No Implementado

NO SE ELIMINA Alert!!
Se eliminaran los establecimientos vinculados';

Create table empleadores_destaques (
	id_empleador_destaque Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_empleador Int NOT NULL,
	id_empleador_maestro Int UNSIGNED NOT NULL,
 Primary Key (id_empleador_destaque)) ENGINE = InnoDB;

Create table empleadores_destaques_yourself (
	id_empleador_destaque_yoursef Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_empleador Int NOT NULL,
	id_empleador_maestro Int UNSIGNED NOT NULL,
 Primary Key (id_empleador_destaque_yoursef)) ENGINE = InnoDB;

Create table servicios_prestados_yourself (
	id_servicio_prestado_yoursef Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_empleador_destaque_yoursef Int UNSIGNED NOT NULL,
	cod_tipo_actividad Char(5) NOT NULL,
	fecha_inicio Date,
	fecha_fin Date,
	estado Varchar(10),
 Primary Key (id_servicio_prestado_yoursef)) ENGINE = InnoDB;

Create table servicios_prestados (
	id_servicio_prestado Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_empleador_destaque Int UNSIGNED NOT NULL,
	cod_tipo_actividad Char(5) NOT NULL,
	fecha_inicio Date,
	fecha_fin Date,
	estado Date,
 Primary Key (id_servicio_prestado)) ENGINE = InnoDB;

Create table establecimientos_vinculados (
	id_establecimiento_vinculado Int UNSIGNED NOT NULL AUTO_INCREMENT,
	id_empleador_destaque Int UNSIGNED NOT NULL,
	id_establecimiento Int UNSIGNED NOT NULL,
	realizan_trabajo_de_riesgo Bool,
	estado Varchar(10),
 Primary Key (id_establecimiento_vinculado)) ENGINE = InnoDB;


Alter table detalle_tipos_trabajadores add Foreign Key (id_trabajador) references trabajadores (id_trabajador) on delete cascade on update cascade;
Alter table detalle_periodos_laborales add Foreign Key (id_trabajador) references trabajadores (id_trabajador) on delete cascade on update cascade;
Alter table detalle_regimenes_pensionarios add Foreign Key (id_trabajador) references trabajadores (id_trabajador) on delete cascade on update cascade;
Alter table detalle_regimenes_salud add Foreign Key (id_trabajador) references trabajadores (id_trabajador) on delete cascade on update cascade;
Alter table detalle_establecimiento add Foreign Key (id_trabajador) references trabajadores (id_trabajador) on delete cascade on update cascade;
Alter table personas add Foreign Key (cod_tipo_documento) references tipos_documentos (cod_tipo_documento) on delete cascade on update cascade;
Alter table derechohabientes add Foreign Key (cod_tipo_documento) references tipos_documentos (cod_tipo_documento) on delete cascade on update cascade;
Alter table personas add Foreign Key (cod_pais_emisor_documento) references paises_emisores_documentos (cod_pais_emisor_documento) on delete cascade on update cascade;
Alter table derechohabientes add Foreign Key (cod_pais_emisor_documento) references paises_emisores_documentos (cod_pais_emisor_documento) on delete cascade on update cascade;
Alter table empleadores add Foreign Key (cod_tipo_actividad) references tipos_actividades (cod_tipo_actividad) on delete cascade on update cascade;
Alter table servicios_prestados_yourself add Foreign Key (cod_tipo_actividad) references tipos_actividades (cod_tipo_actividad) on delete cascade on update cascade;
Alter table servicios_prestados add Foreign Key (cod_tipo_actividad) references tipos_actividades (cod_tipo_actividad) on delete cascade on update cascade;
Alter table personas add Foreign Key (cod_nacionalidad) references nacionalidades (cod_nacionalidad) on delete  restrict on update  restrict;
Alter table empleadores add Foreign Key (cod_telefono_codigo_nacional) references telefonos_codigos_nacionales (cod_telefono_codigo_nacional) on delete cascade on update cascade;
Alter table derechohabientes add Foreign Key (cod_telefono_codigo_nacional) references telefonos_codigos_nacionales (cod_telefono_codigo_nacional) on delete no action on update no action;
Alter table personas add Foreign Key (cod_telefono_codigo_nacional) references telefonos_codigos_nacionales (cod_telefono_codigo_nacional) on delete no action on update no action;
Alter table personas_direcciones add Foreign Key (cod_via) references vias (cod_via) on delete cascade on update cascade;
Alter table derechohabientes_direcciones add Foreign Key (cod_via) references vias (cod_via) on delete  restrict on update  restrict;
Alter table establecimientos_direcciones add Foreign Key (cod_via) references vias (cod_via) on delete cascade on update cascade;
Alter table personas_direcciones add Foreign Key (cod_zona) references zonas (cod_zona) on delete  restrict on update  restrict;
Alter table derechohabientes_direcciones add Foreign Key (cod_zona) references zonas (cod_zona) on delete  restrict on update  restrict;
Alter table establecimientos_direcciones add Foreign Key (cod_zona) references zonas (cod_zona) on delete cascade on update cascade;
Alter table t_trabajadores_t_empleadores add Foreign Key (cod_tipo_trabajador) references tipos_trabajadores (cod_tipo_trabajador) on delete cascade on update cascade;
Alter table detalle_tipos_trabajadores add Foreign Key (cod_tipo_trabajador) references tipos_trabajadores (cod_tipo_trabajador) on delete cascade on update cascade;
Alter table pensionistas add Foreign Key (cod_tipo_trabajador) references tipos_trabajadores (cod_tipo_trabajador) on delete cascade on update cascade;
Alter table detalle_periodos_laborales_pensionistas add Foreign Key (cod_motivo_baja_registro) references motivos_bajas_registros (cod_motivo_baja_registro) on delete cascade on update cascade;
Alter table detalle_periodos_laborales add Foreign Key (cod_motivo_baja_registro) references motivos_bajas_registros (cod_motivo_baja_registro) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (cod_regimen_laboral) references regimenes_laborales (cod_regimen_laboral) on delete cascade on update cascade;
Alter table t_empleadores_t_r_laborales add Foreign Key (cod_regimen_laboral) references regimenes_laborales (cod_regimen_laboral) on delete cascade on update cascade;
Alter table establecimientos add Foreign Key (id_empleador) references empleadores (id_empleador) on delete cascade on update cascade;
Alter table conceptos_seleccionados add Foreign Key (id_empleador) references empleadores (id_empleador) on delete cascade on update cascade;
Alter table personas add Foreign Key (id_empleador) references empleadores (id_empleador) on delete cascade on update cascade;
Alter table empleadores_maestros add Foreign Key (id_empleador) references empleadores (id_empleador) on delete no action on update no action;
Alter table empleadores_destaques add Foreign Key (id_empleador) references empleadores (id_empleador) on delete cascade on update cascade;
Alter table empleadores_destaques_yourself add Foreign Key (id_empleador) references empleadores (id_empleador) on delete  restrict on update  restrict;
Alter table empleadores add Foreign Key (id_tipo_empleador) references tipos_empleadores (id_tipo_empleador) on delete cascade on update cascade;
Alter table t_trabajadores_t_empleadores add Foreign Key (id_tipo_empleador) references tipos_empleadores (id_tipo_empleador) on delete cascade on update cascade;
Alter table t_empleadores_c_ocupacionales add Foreign Key (id_tipo_empleador) references tipos_empleadores (id_tipo_empleador) on delete cascade on update cascade;
Alter table t_empleadores_r_pensionarios add Foreign Key (id_tipo_empleador) references tipos_empleadores (id_tipo_empleador) on delete cascade on update cascade;
Alter table t_empleadores_t_r_laborales add Foreign Key (id_tipo_empleador) references tipos_empleadores (id_tipo_empleador) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (cod_nivel_educativo) references niveles_educativos (cod_nivel_educativo) on delete cascade on update cascade;
Alter table personales_formaciones_laborales add Foreign Key (cod_nivel_educativo) references niveles_educativos (cod_nivel_educativo) on delete  restrict on update  restrict;
Alter table t_empleadores_c_ocupacionales add Foreign Key (cod_categorias_ocupacionales) references categorias_ocupacionales (cod_categorias_ocupacionales) on delete cascade on update cascade;
Alter table c_ocupaciones_ocupaciones_p add Foreign Key (cod_categorias_ocupacionales) references categorias_ocupacionales (cod_categorias_ocupacionales) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (cod_categorias_ocupacionales) references categorias_ocupacionales (cod_categorias_ocupacionales) on delete cascade on update cascade;
Alter table c_ocupaciones_ocupaciones_p add Foreign Key (cod_ocupacion_p) references ocupaciones_p (cod_ocupacion_p) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (cod_ocupacion_p) references ocupaciones_p (cod_ocupacion_p) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (cod_tipo_contrato) references tipos_contratos (cod_tipo_contrato) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (cod_tipo_pago) references tipos_pagos (cod_tipo_pago) on delete cascade on update cascade;
Alter table pensionistas add Foreign Key (cod_tipo_pago) references tipos_pagos (cod_tipo_pago) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (cod_periodo_remuneracion) references periodos_remuneraciones (cod_periodo_remuneracion) on delete cascade on update cascade;
Alter table detalle_establecimientos_formacion add Foreign Key (id_establecimiento) references establecimientos (id_establecimiento) on delete cascade on update cascade;
Alter table lugares_testaques add Foreign Key (id_establecimiento) references establecimientos (id_establecimiento) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (id_establecimiento) references establecimientos (id_establecimiento) on delete  restrict on update  restrict;
Alter table establecimientos_direcciones add Foreign Key (id_establecimiento) references establecimientos (id_establecimiento) on delete cascade on update cascade;
Alter table detalle_establecimiento add Foreign Key (id_establecimiento) references establecimientos (id_establecimiento) on delete cascade on update cascade;
Alter table establecimientos_vinculados add Foreign Key (id_establecimiento) references establecimientos (id_establecimiento) on delete cascade on update cascade;
Alter table establecimientos add Foreign Key (id_tipo_establecimiento) references tipos_establecimientos (id_tipo_establecimiento) on delete cascade on update cascade;
Alter table detalle_regimenes_salud add Foreign Key (cod_regimen_aseguramiento_salud) references regimenes_aseguramientos_salud (cod_regimen_aseguramiento_salud) on delete cascade on update cascade;
Alter table t_empleadores_r_pensionarios add Foreign Key (cod_regimen_pensionario) references regimenes_pensionarios (cod_regimen_pensionario) on delete cascade on update cascade;
Alter table detalle_regimenes_pensionarios add Foreign Key (cod_regimen_pensionario) references regimenes_pensionarios (cod_regimen_pensionario) on delete cascade on update cascade;
Alter table pensionistas add Foreign Key (cod_regimen_pensionario) references regimenes_pensionarios (cod_regimen_pensionario) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (cod_convenio) references convenios (cod_convenio) on delete cascade on update cascade;
Alter table detalle_periodos_laborales_pensionistas add Foreign Key (id_pensionista) references pensionistas (id_pensionista) on delete cascade on update cascade;
Alter table detalle_periodos_formativos add Foreign Key (id_personal_formacion_laboral) references personales_formaciones_laborales (id_personal_formacion_laboral) on delete cascade on update cascade;
Alter table detalle_establecimientos_formacion add Foreign Key (id_personal_formacion_laboral) references personales_formaciones_laborales (id_personal_formacion_laboral) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (id_persona) references personas (id_persona) on delete cascade on update cascade;
Alter table personales_formaciones_laborales add Foreign Key (id_persona) references personas (id_persona) on delete cascade on update cascade;
Alter table pensionistas add Foreign Key (id_persona) references personas (id_persona) on delete cascade on update cascade;
Alter table personales_terceros add Foreign Key (id_persona) references personas (id_persona) on delete cascade on update cascade;
Alter table derechohabientes add Foreign Key (id_persona) references personas (id_persona) on delete cascade on update cascade;
Alter table personas_direcciones add Foreign Key (id_persona) references personas (id_persona) on delete cascade on update cascade;
Alter table personales_formaciones_laborales add Foreign Key (id_modalidad_formativa) references modalidades_formativas (id_modalidad_formativa) on delete cascade on update cascade;
Alter table personales_formaciones_laborales add Foreign Key (id_ocupacion_2) references ocupacion_2 (id_ocupacion_2) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (id_ocupacion_2) references ocupacion_2 (id_ocupacion_2) on delete cascade on update cascade;
Alter table periodos_destaques add Foreign Key (id_personal_tercero) references personales_terceros (id_personal_tercero) on delete cascade on update cascade;
Alter table lugares_testaques add Foreign Key (id_personal_tercero) references personales_terceros (id_personal_tercero) on delete cascade on update cascade;
Alter table coberturas_salud add Foreign Key (id_personal_tercero) references personales_terceros (id_personal_tercero) on delete cascade on update cascade;
Alter table derechohabientes_direcciones add Foreign Key (id_derechohabiente) references derechohabientes (id_derechohabiente) on delete cascade on update cascade;
Alter table derechohabientes add Foreign Key (cod_vinculo_familiar) references vinculos_familiares (cod_vinculo_familiar) on delete cascade on update cascade;
Alter table v_familiares_d_v_familiares add Foreign Key (cod_vinculo_familiar) references vinculos_familiares (cod_vinculo_familiar) on delete  restrict on update  restrict;
Alter table derechohabientes add Foreign Key (cod_documento_vinculo_familiar) references documentos_vinculos_familiares (cod_documento_vinculo_familiar) on delete cascade on update cascade;
Alter table v_familiares_d_v_familiares add Foreign Key (cod_documento_vinculo_familiar) references documentos_vinculos_familiares (cod_documento_vinculo_familiar) on delete  restrict on update  restrict;
Alter table personales_formaciones_laborales add Foreign Key (cod_situacion) references situaciones (cod_situacion) on delete cascade on update cascade;
Alter table pensionistas add Foreign Key (cod_situacion) references situaciones (cod_situacion) on delete cascade on update cascade;
Alter table trabajadores add Foreign Key (cod_situacion) references situaciones (cod_situacion) on delete cascade on update cascade;
Alter table derechohabientes add Foreign Key (cod_situacion) references situaciones (cod_situacion) on delete cascade on update cascade;
Alter table derechohabientes add Foreign Key (cod_motivo_baja_derechohabiente) references motivos_bajas_derechohabientes (cod_motivo_baja_derechohabiente) on delete cascade on update cascade;
Alter table detalles_conceptos add Foreign Key (cod_concepto) references conceptos (cod_concepto) on delete  restrict on update  restrict;
Alter table d_conceptos_afectaciones add Foreign Key (cod_detalle_concepto) references detalles_conceptos (cod_detalle_concepto) on delete cascade on update cascade;
Alter table conceptos_seleccionados add Foreign Key (cod_detalle_concepto) references detalles_conceptos (cod_detalle_concepto) on delete cascade on update cascade;
Alter table d_conceptos_afectaciones add Foreign Key (cod_afectacion) references afectaciones (cod_afectacion) on delete cascade on update cascade;
Alter table ubigeo_reniec add Foreign Key (cod_departamento) references ubigeo_departamentos (cod_departamento) on delete cascade on update cascade;
Alter table ubigeo_reniec add Foreign Key (cod_provincia) references ubigeo_provincias (cod_provincia) on delete cascade on update cascade;
Alter table personas_direcciones add Foreign Key (cod_ubigeo_reniec) references ubigeo_reniec (cod_ubigeo_reniec) on delete cascade on update cascade;
Alter table derechohabientes_direcciones add Foreign Key (cod_ubigeo_reniec) references ubigeo_reniec (cod_ubigeo_reniec) on delete cascade on update cascade;
Alter table establecimientos_direcciones add Foreign Key (cod_ubigeo_reniec) references ubigeo_reniec (cod_ubigeo_reniec) on delete cascade on update cascade;
Alter table personas add Foreign Key (id_estado_civil) references estados_civiles (id_estado_civil) on delete cascade on update cascade;
Alter table derechohabientes add Foreign Key (id_estado_civil) references estados_civiles (id_estado_civil) on delete  restrict on update  restrict;
Alter table trabajadores add Foreign Key (id_monto_remuneracion) references montos_remuneraciones (id_monto_remuneracion) on delete cascade on update cascade;
Alter table empleadores add Foreign Key (id_tipo_sociedad_comercial) references tipos_sociedades_comerciales (id_tipo_sociedad_comercial) on delete cascade on update cascade;
Alter table empleadores_destaques add Foreign Key (id_empleador_maestro) references empleadores_maestros (id_empleador_maestro) on delete cascade on update cascade;
Alter table empleadores_destaques_yourself add Foreign Key (id_empleador_maestro) references empleadores_maestros (id_empleador_maestro) on delete cascade on update cascade;
Alter table servicios_prestados add Foreign Key (id_empleador_destaque) references empleadores_destaques (id_empleador_destaque) on delete cascade on update cascade;
Alter table establecimientos_vinculados add Foreign Key (id_empleador_destaque) references empleadores_destaques (id_empleador_destaque) on delete cascade on update cascade;
Alter table servicios_prestados_yourself add Foreign Key (id_empleador_destaque_yoursef) references empleadores_destaques_yourself (id_empleador_destaque_yoursef) on delete cascade on update cascade;


/* Users permissions */


