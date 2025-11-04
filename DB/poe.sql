-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-11-2025 a las 20:01:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `poe`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adicional_estudiante`
--

CREATE TABLE `adicional_estudiante` (
  `id_adicional` int(11) NOT NULL,
  `jacom_adicional` varchar(50) NOT NULL COMMENT 'Jornada contraria acompañamiento',
  `ccuento_adicional` varchar(50) NOT NULL COMMENT 'En casa cuento con',
  `transp_adicional` varchar(50) NOT NULL COMMENT 'Me transporto al colegio en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atributo_estudiante`
--

CREATE TABLE `atributo_estudiante` (
  `id_atributo` int(11) NOT NULL,
  `comunidad_atributo` varchar(20) NOT NULL COMMENT 'Comunidad donde se identifica',
  `des_atributo` varchar(10) NOT NULL COMMENT 'desplazado\r\n',
  `ind_atributo` varchar(10) NOT NULL COMMENT 'indigena?',
  `cuales_atributo` varchar(100) NOT NULL,
  `educom_atributo` varchar(50) NOT NULL COMMENT 'Educacion complementaria',
  `dia_educom_atributo` text NOT NULL COMMENT 'dias educacion complementaria',
  `horario_educom_atributo` time NOT NULL COMMENT 'horarios educacion complementaria',
  `deporte_atributo` text NOT NULL COMMENT 'Entrenamiento deportivo',
  `dia_deporte_atributo` text NOT NULL COMMENT 'dia de entrenamiento deportivo',
  `horario_deporte_atributo` time NOT NULL COMMENT 'horario deportivo',
  `jtrab_atributo` text NOT NULL COMMENT 'Trabajo',
  `dia_jtrab_atributo` text NOT NULL COMMENT 'dias de trabajo',
  `horario_jtrab_atributo` time NOT NULL COMMENT 'Horarios de trabajo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caracteristicas_estudiante`
--

CREATE TABLE `caracteristicas_estudiante` (
  `id_caracteristicas` int(11) NOT NULL,
  `dir_caracteristicas` varchar(40) NOT NULL,
  `barri_caracteristicas` varchar(50) NOT NULL,
  `com_caracteristicas` int(2) NOT NULL COMMENT 'comuna',
  `est_caracteristicas` int(2) NOT NULL COMMENT 'estrato',
  `eps_caracteristicas` varchar(50) NOT NULL,
  `cel_caracteristicas` bigint(12) NOT NULL,
  `p_tel_m1_caracteristicas` varchar(10) NOT NULL COMMENT 'padres telefono 1',
  `num_m1_caracteristicas` bigint(12) NOT NULL COMMENT 'padres 1',
  `p_tel_m2_caracteristicas` varchar(10) NOT NULL COMMENT 'padres telefono 2',
  `num_m2_caracteristicas` bigint(12) NOT NULL COMMENT 'numero padres 2',
  `gmail_p_caracteristicas` varchar(100) NOT NULL COMMENT 'Direccion electronica padres',
  `acu_caracteristicas` varchar(100) NOT NULL COMMENT 'acudiente',
  `acu_paren_caracteristicas` varchar(20) NOT NULL COMMENT 'acudiente parentesco',
  `acu_doc_caracteristicas` bigint(11) NOT NULL,
  `acu_cel_caracteristicas` bigint(12) NOT NULL,
  `acu_esco_caracteristicas` varchar(150) NOT NULL COMMENT 'Acudiente Ocupación',
  `acu_ocup_caracteristicas` varchar(50) NOT NULL,
  `pd_nom_caracteristicas` varchar(100) NOT NULL COMMENT 'padre nombre',
  `pd_doc_caracteristicas` int(11) NOT NULL COMMENT 'Documento del padre',
  `pd_esco_caracteristicas` varchar(40) NOT NULL COMMENT 'padre escolaridad',
  `pd_edad_caracteristicas` int(3) NOT NULL COMMENT 'padre edad',
  `pd_ocu_caracteristicas` varchar(40) NOT NULL COMMENT 'padre ocupacion',
  `pd_trab_caracteristicas` varchar(40) NOT NULL COMMENT 'padre trabajo',
  `md_nom_caracteristicas` varchar(100) NOT NULL COMMENT 'madre nombre',
  `md_doc_caracteristicas` int(12) NOT NULL COMMENT 'Documento de la madre\r\n',
  `md_esco_caracteristicas` varchar(40) NOT NULL COMMENT 'madre escolaridad',
  `md_edad_caracteristicas` int(3) NOT NULL COMMENT 'madre edad',
  `md_ocu_caracteristicas` varchar(40) NOT NULL COMMENT 'madre ocupacion',
  `md_trab_caracteristicas` varchar(40) NOT NULL COMMENT 'madre trabajo',
  `economia_caracteristicas` varchar(100) NOT NULL COMMENT 'Ingresos economicos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dato_estudiante`
--

CREATE TABLE `dato_estudiante` (
  `id_dato` int(11) NOT NULL,
  `fecha_de_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `nom_dato` varchar(150) NOT NULL,
  `lugar_nac_dato` varchar(50) NOT NULL,
  `nac_dato` date NOT NULL,
  `edad_dato` int(2) NOT NULL,
  `tipo_doc_dato` varchar(4) NOT NULL COMMENT 'Tipo de Documento',
  `doc_dato` int(11) NOT NULL,
  `rh_dato` varchar(2) NOT NULL,
  `estado_dato` varchar(20) NOT NULL,
  `col_dato` varchar(100) NOT NULL,
  `sede_dato` varchar(100) NOT NULL,
  `jornada_dato` varchar(10) NOT NULL,
  `id_adicional` int(11) NOT NULL,
  `id_atributo` int(11) NOT NULL,
  `id_caracteristicas` int(11) NOT NULL,
  `id_entorno` int(11) NOT NULL,
  `id_fichai` int(11) NOT NULL,
  `id_observador` int(11) NOT NULL,
  `id_salud` int(11) NOT NULL,
  `id_folder` int(11) NOT NULL COMMENT 'Llave Foránea Folder'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresados`
--

CREATE TABLE `egresados` (
  `id_egresados` int(11) NOT NULL,
  `año_egresado` int(11) NOT NULL COMMENT 'Año de graduacion',
  `nom_egresados` varchar(50) NOT NULL COMMENT 'Nombre Egresado',
  `fechanac_egresados` date NOT NULL COMMENT 'Fecha de Nacimiento',
  `edad_egresados` int(3) NOT NULL,
  `tip_doc_egresados` varchar(4) NOT NULL COMMENT 'Tipo de documento',
  `num_doc_egresados` int(13) NOT NULL COMMENT 'Numero de Documento',
  `gruposanguineo_egresados` varchar(2) NOT NULL,
  `especialidad_egresados` varchar(200) NOT NULL,
  `email_egresados` varchar(200) NOT NULL,
  `tel_egresados` bigint(12) NOT NULL,
  `ocup_egresados` varchar(250) NOT NULL,
  `estudios_egresados` varchar(500) NOT NULL,
  `institucion_egresados` varchar(250) NOT NULL,
  `foto_egresados` varchar(250) NOT NULL,
  `biografia_egresados` mediumtext NOT NULL,
  `aporte_egresados` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `egresados`
--

INSERT INTO `egresados` (`id_egresados`, `año_egresado`, `nom_egresados`, `fechanac_egresados`, `edad_egresados`, `tip_doc_egresados`, `num_doc_egresados`, `gruposanguineo_egresados`, `especialidad_egresados`, `email_egresados`, `tel_egresados`, `ocup_egresados`, `estudios_egresados`, `institucion_egresados`, `foto_egresados`, `biografia_egresados`, `aporte_egresados`) VALUES
(1, 0, 'Juan Pablo Chacon Barragán', '2008-02-01', 17, 'T.I', 1077229021, 'O+', 'Software', 'juanpablochacon708j.t@gmail.com', 3150489683, 'Estudiante', 'Técnico en Programación de Software', 'Institución Educativa Técnico Superior de Neiva', '', 'Liko', 'Liko'),
(2, 0, 'Juan Felipe Avila Murcia', '2008-05-19', 17, 'T.I', 1076506022, 'O+', 'Software', 'avilamurciajuanfelipe606@gmail.com', 3158063726, 'Estudiante', 'Desarrollo de Software y diseño grafico', 'Institución Educativa Técnico Superior de Neiva', '', 'Tengo 17 años, me encanta la creación de contenido y hacer deporte', 'Crear eventos los cuales sean didácticos para poder hacer creación de contenido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entorno_estudiantes`
--

CREATE TABLE `entorno_estudiantes` (
  `id_entorno` int(11) NOT NULL,
  `vive_entorno` varchar(50) NOT NULL COMMENT 'vive con',
  `casa_entorno` varchar(50) NOT NULL COMMENT 'mi casa es',
  `hijou_entorno` varchar(2) NOT NULL COMMENT 'hijo unico',
  `hermano_entorno` varchar(2) NOT NULL COMMENT 'Hermanos en el colegio',
  `n_hermanos_entorno` int(11) NOT NULL COMMENT 'Numero de Hermanos',
  `tieli_entorno` varchar(100) NOT NULL COMMENT 'tiempo libre ',
  `esp_entorno` int(11) NOT NULL COMMENT 'especifique',
  `totalv_entorno` int(2) NOT NULL COMMENT 'total de personas que viven juntos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escuelap`
--

CREATE TABLE `escuelap` (
  `id_escuelap` int(11) NOT NULL COMMENT 'Id del post',
  `titulo_escuelap` varchar(50) NOT NULL COMMENT '	Titulo del post',
  `texto_escuelap` text NOT NULL COMMENT 'Contenido del post',
  `archivo1_escuelap` binary(255) NOT NULL COMMENT 'Espacio para cargar archivo (Imagen o documento)',
  `archivo2_escuelap` binary(255) NOT NULL COMMENT 'Espacio para cargar archivo (Imagen o documento)',
  `archivo3_escuelap` binary(255) NOT NULL COMMENT 'Espacio para cargar archivo (Imagen o documento)',
  `like_escuelap` int(11) NOT NULL COMMENT 'Numero de likes del post'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichai_estudiante`
--

CREATE TABLE `fichai_estudiante` (
  `id_fichai` int(11) NOT NULL,
  `espdoc_fichai` varchar(50) NOT NULL COMMENT 'Especialidad Docente',
  `docente_fichai` varchar(100) NOT NULL COMMENT 'Docente',
  `remitente_fichai` varchar(30) NOT NULL COMMENT 'Remitente',
  `f_remic_fichai` date NOT NULL COMMENT 'Fecha de remisión',
  `f_aten_fichai` date NOT NULL COMMENT 'Fecha de atención',
  `asesor_fichai` varchar(30) NOT NULL COMMENT 'Asesor de Grupo',
  `situs_fichai` varchar(100) NOT NULL COMMENT 'Situación de salud',
  `seguit_fichai` varchar(500) NOT NULL COMMENT 'Seguimiento y Terapias',
  `mconsulta_fichai` text NOT NULL COMMENT 'Descripcion del caso o Motivo de consulta',
  `antecedentes_fichai` text NOT NULL COMMENT 'Antecedentes',
  `acc_realizadas_fichai` text NOT NULL COMMENT 'Acciones realizadas',
  `compromiso_fichai` text NOT NULL COMMENT 'Tareas, Acuerdos o compromisos Personales',
  `continu_fichai` text NOT NULL COMMENT 'Continuación anamnesis',
  `oriente_fichai` varchar(50) NOT NULL COMMENT 'Nombre orientadora escolar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `folder_estudiante`
--

CREATE TABLE `folder_estudiante` (
  `id_folder` int(11) NOT NULL COMMENT 'Id folder',
  `archivo1_folder` varchar(255) NOT NULL COMMENT 'Campo para archivo',
  `archivo2_folder` varchar(255) NOT NULL COMMENT 'Campo para archivo',
  `archivo3_folder` varchar(255) NOT NULL COMMENT 'Campo para archivo',
  `archivo4_folder` varchar(255) NOT NULL COMMENT 'Campo para archivo',
  `archivo5_folder` varchar(255) NOT NULL COMMENT 'Campo para archivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foro`
--

CREATE TABLE `foro` (
  `id_foro` int(11) NOT NULL COMMENT 'Id del post',
  `destino_foro` varchar(8) NOT NULL COMMENT 'Destino final del post (escuela/talleres)',
  `titulo_foro` varchar(50) NOT NULL COMMENT 'Titulo del post',
  `texto_foro` text NOT NULL COMMENT 'Contenido del post',
  `archivo1_foro` varchar(255) NOT NULL COMMENT 'Espacio para cargar archivo (Imagen o documento)',
  `archivo2_foro` varchar(255) NOT NULL COMMENT 'Espacio para cargar archivo (Imagen o documento)',
  `archivo3_foro` varchar(255) NOT NULL COMMENT 'Espacio para cargar archivo (Imagen o documento)',
  `like_foro` int(11) NOT NULL COMMENT 'Numero de likes del post'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_index`
--

CREATE TABLE `info_index` (
  `id_index` int(12) NOT NULL,
  `titulo_principal` varchar(255) NOT NULL,
  `desc_principal` varchar(255) NOT NULL,
  `titulo_sec` varchar(255) NOT NULL,
  `desc_sec` varchar(255) NOT NULL,
  `img_sec` varchar(255) NOT NULL,
  `titulo_division1` varchar(255) NOT NULL,
  `desc_division1` varchar(255) NOT NULL,
  `img_division1` varchar(255) NOT NULL,
  `titulo_division2` varchar(255) NOT NULL,
  `desc_division2` varchar(255) NOT NULL,
  `img_division2` varchar(255) NOT NULL,
  `titulo_division3` varchar(255) NOT NULL,
  `desc_division3` varchar(255) NOT NULL,
  `img_division3` varchar(255) NOT NULL,
  `contacto_psicoo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `info_index`
--

INSERT INTO `info_index` (`id_index`, `titulo_principal`, `desc_principal`, `titulo_sec`, `desc_sec`, `img_sec`, `titulo_division1`, `desc_division1`, `img_division1`, `titulo_division2`, `desc_division2`, `img_division2`, `titulo_division3`, `desc_division3`, `img_division3`, `contacto_psicoo`) VALUES
(1, 'SERVICIO DE ORIENTACIÓN ESCOLAR \r\n', '“Orientar para formar seres humanos íntegros y felices para la vida\"', '¿Que es el POE?', '“¿Es confuso, verdad?  Sin embargo sabes perfectamente cuando estás mal; todo tu cuerpo, física y mentalmente te lo hace saber. Te notas flojo, con pensamientos fatalistas, esa sensación de que todo está perdido, que ya nada será como antes. Te torturas r', 'uploads/68ed9f08b438f_Img_FuncionPOE.png', '', '', 'uploads/68ee8428293a0_Captura de pantalla 2025-10-04 152123.png', '', '', 'uploads/68ed9f08b6a3c_Captura de pantalla 2025-10-10 072239.png', '', '', 'uploads/68ed933d5ad52_Img_Divisiones.png', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `observador_estudiante`
--

CREATE TABLE `observador_estudiante` (
  `id_observador` int(11) NOT NULL,
  `foto_observador` varchar(200) NOT NULL,
  `inf_prp_observador` text NOT NULL COMMENT 'Informe primer periodo',
  `firma_prp_observador` varchar(2) NOT NULL COMMENT 'Firma primer periodo',
  `inf_sgp_observador` text NOT NULL COMMENT 'Informe Segundo Periodo',
  `firma_sgp_observador` varchar(2) NOT NULL COMMENT 'Firma segundo periodo',
  `inf_terp_observador` text NOT NULL COMMENT 'Informe Tercer Periodo',
  `firma_terp_observador` varchar(2) NOT NULL COMMENT 'Firma Tercer Periodo',
  `inf_cuarp_observador` text NOT NULL COMMENT 'Informe Cuarto Periodo',
  `firma_cuarp_observador` varchar(2) NOT NULL COMMENT 'Firma Cuarto Periodo',
  `año_observador` int(11) NOT NULL,
  `prom_observador` varchar(2) NOT NULL COMMENT 'Promovido',
  `gradop_observador` int(4) NOT NULL COMMENT 'Grado al que fue promovido.   (Actualizacion en cascada a grado_dato)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salud_estudiante`
--

CREATE TABLE `salud_estudiante` (
  `id_salud` int(11) NOT NULL,
  `dis_salud` text NOT NULL COMMENT 'Discapacidad',
  `trasa_salud` text NOT NULL COMMENT 'Trastorno de Aprendizaje',
  `diag_salud` text NOT NULL COMMENT 'Enfermedad Diagnosticada',
  `tie_atributo` varchar(40) NOT NULL COMMENT 'tiempo de la enfermedad',
  `trat_salud` text NOT NULL COMMENT 'Tratamiento medico',
  `def_salud` varchar(200) NOT NULL COMMENT 'Padece deficiencias en',
  `med_salud` text NOT NULL COMMENT 'Consumo medicamento',
  `expreso_salud` text NOT NULL COMMENT 'Excepcionalidad demostrable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tformativos`
--

CREATE TABLE `tformativos` (
  `id_tformativos` int(11) NOT NULL COMMENT 'Id del post',
  `titulo_tformativos` varchar(50) NOT NULL COMMENT 'Titulo del post',
  `texto_tformativos` text NOT NULL COMMENT 'Contenido del post',
  `archivo1_tformativos` binary(255) NOT NULL COMMENT '	Espacio para cargar archivo (Imagen o documento)',
  `archivo2_tformativos` binary(255) NOT NULL COMMENT '	Espacio para cargar archivo (Imagen o documento)',
  `archivo3_tformativos` binary(255) NOT NULL COMMENT '	Espacio para cargar archivo (Imagen o documento)',
  `like_tformativos` int(11) NOT NULL COMMENT 'Numero de likes del post'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL COMMENT 'Nombre Usuarios',
  `contraseña` varchar(20) NOT NULL COMMENT 'Contraseña',
  `tipo_usuario` varchar(20) NOT NULL COMMENT 'Tipo de Usuario',
  `grado_autorizado` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `contraseña`, `tipo_usuario`, `grado_autorizado`) VALUES
(1, 'admin', 'admin123', 'administrador', 'todos'),
(2, 'novenos', 'novenos2025', 'docente', '900'),
(3, 'primero', 'primero2025', 'docente', '100'),
(4, 'segundos', 'sengundos2025', 'docente', '200'),
(5, 'terceros', 'terceros2025', 'docente', '300'),
(6, 'cuarto', 'cuarto2025', 'docente', '400'),
(7, 'quintos', 'quintos2025', 'docente', '500'),
(8, 'sextos', 'sextos2025', 'docente', '600'),
(9, 'septimos', 'septimos2025', 'docente', '700'),
(10, 'octavos', 'octavos2025', 'docente', '800'),
(11, 'decimos', 'decimos2025', 'docente', '1000'),
(12, 'onces', 'onces2025', 'docente', '1100'),
(13, 'psicoorientacion', 'psicotec2025', 'psicoorientador', 'todos');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `adicional_estudiante`
--
ALTER TABLE `adicional_estudiante`
  ADD PRIMARY KEY (`id_adicional`);

--
-- Indices de la tabla `atributo_estudiante`
--
ALTER TABLE `atributo_estudiante`
  ADD PRIMARY KEY (`id_atributo`);

--
-- Indices de la tabla `caracteristicas_estudiante`
--
ALTER TABLE `caracteristicas_estudiante`
  ADD PRIMARY KEY (`id_caracteristicas`);

--
-- Indices de la tabla `dato_estudiante`
--
ALTER TABLE `dato_estudiante`
  ADD PRIMARY KEY (`id_dato`),
  ADD KEY `id_dato` (`id_adicional`),
  ADD KEY `id_atributo` (`id_atributo`),
  ADD KEY `id_caracteristicas` (`id_caracteristicas`),
  ADD KEY `id_entorno` (`id_entorno`),
  ADD KEY `id_fichai` (`id_fichai`),
  ADD KEY `id_observador` (`id_observador`),
  ADD KEY `id_salud` (`id_salud`),
  ADD KEY `id_folder` (`id_folder`),
  ADD KEY `doc_dato` (`doc_dato`) USING BTREE;

--
-- Indices de la tabla `egresados`
--
ALTER TABLE `egresados`
  ADD PRIMARY KEY (`id_egresados`);

--
-- Indices de la tabla `entorno_estudiantes`
--
ALTER TABLE `entorno_estudiantes`
  ADD PRIMARY KEY (`id_entorno`);

--
-- Indices de la tabla `escuelap`
--
ALTER TABLE `escuelap`
  ADD PRIMARY KEY (`id_escuelap`);

--
-- Indices de la tabla `fichai_estudiante`
--
ALTER TABLE `fichai_estudiante`
  ADD PRIMARY KEY (`id_fichai`);

--
-- Indices de la tabla `folder_estudiante`
--
ALTER TABLE `folder_estudiante`
  ADD PRIMARY KEY (`id_folder`);

--
-- Indices de la tabla `foro`
--
ALTER TABLE `foro`
  ADD PRIMARY KEY (`id_foro`);

--
-- Indices de la tabla `observador_estudiante`
--
ALTER TABLE `observador_estudiante`
  ADD PRIMARY KEY (`id_observador`);

--
-- Indices de la tabla `salud_estudiante`
--
ALTER TABLE `salud_estudiante`
  ADD PRIMARY KEY (`id_salud`);

--
-- Indices de la tabla `tformativos`
--
ALTER TABLE `tformativos`
  ADD PRIMARY KEY (`id_tformativos`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `foro`
--
ALTER TABLE `foro`
  MODIFY `id_foro` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id del post', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dato_estudiante`
--
ALTER TABLE `dato_estudiante`
  ADD CONSTRAINT `id_atributo` FOREIGN KEY (`id_atributo`) REFERENCES `atributo_estudiante` (`id_atributo`),
  ADD CONSTRAINT `id_caracteristicas` FOREIGN KEY (`id_caracteristicas`) REFERENCES `caracteristicas_estudiante` (`id_caracteristicas`),
  ADD CONSTRAINT `id_dato` FOREIGN KEY (`id_adicional`) REFERENCES `adicional_estudiante` (`id_adicional`),
  ADD CONSTRAINT `id_entorno` FOREIGN KEY (`id_entorno`) REFERENCES `entorno_estudiantes` (`id_entorno`),
  ADD CONSTRAINT `id_fichai` FOREIGN KEY (`id_fichai`) REFERENCES `fichai_estudiante` (`id_fichai`),
  ADD CONSTRAINT `id_folder` FOREIGN KEY (`id_folder`) REFERENCES `folder_estudiante` (`id_folder`),
  ADD CONSTRAINT `id_observador` FOREIGN KEY (`id_observador`) REFERENCES `observador_estudiante` (`id_observador`),
  ADD CONSTRAINT `id_salud` FOREIGN KEY (`id_salud`) REFERENCES `salud_estudiante` (`id_salud`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
