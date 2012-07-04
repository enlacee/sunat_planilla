
-- 0 00 0
INSERT INTO `empleadores` (`id_empleador`, `id_tipo_empleador`, `cod_telefono_codigo_nacional`, `ruc`, `razon_social`, `id_tipo_sociedad_comercial`, `nombre_comercial`, `cod_tipo_actividad`, `telefono`, `correo`, `empresa_dedica`, `senati`, `remype`, `remype_tipo_empresa`, `trabajador_sin_rp`, `actividad_riesgo_sctr`, `trabajadores_sctr`, `persona_discapacidad`, `agencia_empleo`, `desplaza_personal`, `terceros_desplaza_usted`, `estado_empleador`, `fecha_creacion`) VALUES

(1, 1, '65', '20100338611', 'CAMUENTE', 1, 'nombre bien', '52511', '147147147', 'laromana@com.pe', 'TERCERIZACION', 1, NULL, NULL, 1, 1, 1, 1, 1, 1, '1', 'ACTIVO', NULL),
(4, 2, '0', '45154545454', 'la estrella', 1, 'comida marina', '10100', '15421541', 'mario@hotmail.com', 'TERCERIZACION', 0, 0, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', '2012-05-21'),
(16, 1, '83', '45454545454', 'Nombre Razon Social', 1, 'aaa', '10100', '15421541', 'larama@hotmail.com', 'NINGUNA', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', '2012-05-21'),
(17, 1, '41', '12345678912', 'Armonia3', 1, 'pepe', '10100', '54454545', 'pep@c.vom', 'TERCERIZACION', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', '2012-05-28'),
(18, 1, '43', '14725836914', 'empresa sa', 1, 'mi empres', '74996', '123456', 'empresa@gmail.com', 'NINGUNA', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', '2012-05-31');



INSERT INTO `empleadores_maestros` (`id_empleador_maestro`, `id_empleador`, `fecha_creacion`) VALUES
(1, 1, NULL),
(2, 17, NULL);

-- 0 00 0
INSERT INTO `establecimientos` (`id_establecimiento`, `id_empleador`, `id_tipo_establecimiento`, `cod_establecimiento`, `realizaran_actividad_riesgo`, `fecha_creacion`) VALUES

(2, 18, 1, '0001', NULL,  '2012-06-06'),
(3, 4, 1, '0001', NULL,  '2012-06-01'),
(4, 4, 2, '0002', NULL,  '2012-06-01'),
(5, 18, 2, '0002', NULL,  '2012-06-06'),
(6, 18, 2, '0004', NULL,  '2012-06-06'),
(7, 18, 2, '0005', NULL,  '2012-06-06'),
(8, 18, 2, '0003', NULL,  '2012-06-06'),
(9, 1, 1, '0001', 1,  '2012-06-09'),
(10, 16, 1, '0001', NULL,  '2012-06-09'),
(11, 1, 2, '0002', 1, '2012-06-10');


INSERT INTO `establecimientos_direcciones` (`id_establecimiento_direccion`, `id_establecimiento`, `cod_ubigeo_reniec`, `cod_via`, `nombre_via`, `numero_via`, `departamento`, `interior`, `manzana`, `lote`, `kilometro`, `block`, `etapa`, `cod_zona`, `nombre_zona`, `referencia`) VALUES
(2, 2, '010201', '02', 'hh', '', '', '', '', '', '', '02', '', '02', 'hhh', ''),
(3, 3, '030201', '04', 'j', '', '', '', '', '', '', '', '', '02', 'j', ''),
(4, 4, '010103', '01', ',,', '', '', '', '', '', '', '', '', '01', 'mm', ''),
(5, 5, '010204', '01', 'los molinos', '', '', '', '', '', '', '0', '', '0', 'n', ''),
(6, 6, '100103', '0', '-', '', '', '', '', '', '', '02', '', '02', 'los girando', ''),
(7, 7, '140102', '0', '', '', '', '', '', '', '', '04', '', '04', 'los mirones', ''),
(8, 8, '070201', '01', 'los mariales', '2514', '', '', '', '', '', '', '', '0', '', ''),
(9, 9, '140124', '01', 'presscott', '265', '', '', '', '', '', '', '', '0', '', ''),
(10, 10, '150504', '0', '', '154', '', '', '', '', '', '', '', '03', 'limo', ''),
(11, 11, '140114', '01', 'RIOS', '255', '', '', '', '', '', '', '', '0', '', '');

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Table structure for table `lugares_testaques`
--

INSERT INTO `montos_remuneraciones` (`id_monto_remuneracion`, `cantidad`, `fecha_creacion`) VALUES
(0, '0.00', '0000-00-00'),
(1, '675.00', '0000-00-00'),
(2, '1000.00', '2012-06-14'),
(3, '0.00', NULL);





INSERT INTO `personas` (`id_persona`, `id_empleador`, `cod_pais_emisor_documento`, `cod_tipo_documento`, `cod_nacionalidad`, `num_documento`, `fecha_nacimiento`, `apellido_paterno`, `apellido_materno`, `nombres`, `sexo`, `id_estado_civil`, `cod_telefono_codigo_nacional`, `telefono`, `correo`, `tabla_trabajador`, `tabla_pensionista`, `tabla_personal_formacion_laboral`, `tabla_personal_terceros`, `estado`, `fecha_creacion`, `fecha_modificacion`, `fecha_baja`) VALUES
(1, 1, '604', '04', '9017', '45269187', '0000-00-00', 'copitan', 'norabuena', 'anibal', 'M', 1, '1', '4514545', 'anbcopitan@hotmail.com', 1, 0, 1, 0, 'ACTIVO', '2012-05-10', NULL, NULL),
(2, 1, '604', '01', '9017', '45269187', '0000-00-00', 'copitanERE', 'norabuena', 'anibal', 'M', 1, '43', '123456', 'pepe@hotmial.com', 0, 0, 0, 0, 'ACTIVO', '2012-05-10', '2012-05-15', NULL),
(7, 1, '604', '01', '9017', '45269187', '1988-08-08', 'copitan', 'norabuena', 'anibal', 'M', 1, '0', '', '', 1, 0, 0, 1, 'ACTIVO', '2012-05-11', '2012-05-21', NULL),
(35, 1, '604', '01', '9589', '45269187', '1988-08-08', 'copitan', 'norabuena', 'anibal', 'F', 2, '0', '', '', 1, 0, 0, 0, 'ACTIVO', '2012-05-14', '2012-06-11', NULL),
(69, 1, '604', '01', '9589', '15245454', '1999-05-02', 'rojo', 'rinlo', 'fiy lore', 'F', 1, '0', '', '', 0, 0, 0, 0, 'INACTIVO', '2012-06-15', NULL, NULL),
(70, 1, '604', '01', '9589', '12452145', '2001-02-02', 'asd', 'dos', 'pepe', 'F', 1, '0', '', '', 0, 0, 0, 0, 'INACTIVO', '2012-06-15', NULL, NULL),
(71, 1, '604', '01', '9589', '12452121', '1988-01-01', 'pep', 'nino', 'lucho rio', 'F', 1, '0', '', '', 0, 0, 0, 0, 'ACTIVO', '2012-06-15', NULL, NULL),
(72, 1, '604', '01', '9589', '44208951', '1986-12-21', 'RUIZ', 'ACEVEDO', 'JESUS IVAN', 'M', 1, '1', '56607821', '', 0, 0, 0, 0, 'ACTIVO', '2012-06-16', '2012-06-16', NULL);



INSERT INTO `personas_direcciones` (`id_persona_direccion`, `id_persona`, `cod_ubigeo_reniec`, `cod_via`, `nombre_via`, `numero_via`, `departamento`, `interior`, `manzana`, `lote`, `kilometro`, `block`, `etapa`, `cod_zona`, `nombre_zona`, `referencia`, `referente_essalud`, `estado_direccion`) VALUES
(1, 1, '010103', '21', '1212', '0', NULL, '00', '0', '121', '00', '00', NULL, '02', 'DS', NULL, NULL, 1),
(2, 2, '140102', '01', 'nueva - nueva', '0', '0', '0', '00', '0', '00', '10', '00', '10', 'casero', '', 0, NULL),
(3, 35, '010109', '18', 'ASD', 'S2', '2', '2', '', '', '2', '07', '2', '07', '', '', 0, 1),
(4, 35, '010104', '20', 'D', '0', '0', '0', '0', NULL, '0', '0', '0', '08', 'SD', NULL, NULL, 2),
(53, 69, '010206', '0', '', '1452', '', '', '', '', '', '0', '', '01', 'mirleos', '', 0, 1),
(54, 69, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, 2),
(55, 70, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, 1),
(56, 70, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, 2),
(57, 71, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, 1),
(58, 71, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, 2),
(59, 72, '140126', '0', '', '', '', '', 'Q', '11', '', '', '', '05', 'DANIEL ALCIDEZ CARRI', '', 0, 1),
(60, 72, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, 2);




