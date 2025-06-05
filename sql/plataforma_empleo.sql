-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-06-2025 a las 05:49:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `plataforma_empleo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_postulaciones`
--

CREATE TABLE `auditoria_postulaciones` (
  `id` int(11) NOT NULL,
  `postulacion_id` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria_postulaciones`
--

INSERT INTO `auditoria_postulaciones` (`id`, `postulacion_id`, `fecha`) VALUES
(1, 1, '2025-05-29 12:01:38'),
(2, 2, '2025-05-29 12:39:06'),
(3, 3, '2025-05-29 22:46:38'),
(4, 4, '2025-05-30 01:49:49'),
(5, 5, '2025-05-31 03:15:50'),
(6, 6, '2025-05-31 03:30:30'),
(7, 7, '2025-05-31 03:43:27'),
(8, 8, '2025-05-31 05:05:23'),
(9, 9, '2025-05-31 05:48:16'),
(10, 10, '2025-05-31 17:34:14'),
(11, 11, '2025-06-03 11:49:13'),
(12, 12, '2025-06-04 14:35:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas_info`
--

CREATE TABLE `empresas_info` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `correo_contacto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas_info`
--

INSERT INTO `empresas_info` (`id`, `usuario_id`, `direccion`, `correo_contacto`) VALUES
(4, 20, '', ''),
(7, 28, 'miami united states', 'cocacola@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas_laborales`
--

CREATE TABLE `ofertas_laborales` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `profesion_solicitada` varchar(255) NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ofertas_laborales`
--

INSERT INTO `ofertas_laborales` (`id`, `empresa_id`, `titulo`, `descripcion`, `profesion_solicitada`, `fecha_publicacion`) VALUES
(18, 20, 'cocinero con experiencia', 'saber cocinar', 'cocinero', '2025-05-30 00:13:51'),
(19, 20, 'empresario ', 'saber ser empresario activo', 'empresario', '2025-05-30 00:14:22'),
(20, 20, 'enfermera con experiencia', 'enfermera activa pro ', 'enfermera', '2025-05-30 01:49:39'),
(24, 20, 'manejador', 'manejar mucho', 'manejador', '2025-05-30 02:44:04'),
(25, 20, 'barcelona', 'messi', 'messi', '2025-05-30 02:50:52'),
(26, 20, 'programador', 'programador', 'programador', '2025-05-31 03:29:58'),
(27, 28, 'conductor de camion de cocacola', 'experiencia y tener licencia mayir de 18 años y activo', 'conductor', '2025-05-31 05:45:24'),
(28, 28, 'desarrollador full stack 5 años de xp', 'saber todos los lenguajes de programacion y tener expereincia laboral y tener margen de activo', 'desarrollador full stack', '2025-05-31 05:46:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postulaciones`
--

CREATE TABLE `postulaciones` (
  `id` int(11) NOT NULL,
  `oferta_id` int(11) NOT NULL,
  `postulante_id` int(11) NOT NULL,
  `fecha_postulacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','aceptado','rechazado') DEFAULT 'pendiente',
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `postulaciones`
--

INSERT INTO `postulaciones` (`id`, `oferta_id`, `postulante_id`, `fecha_postulacion`, `estado`, `fecha_actualizacion`) VALUES
(8, 26, 27, '2025-05-31 05:05:23', 'aceptado', '2025-05-31 01:02:24'),
(9, 27, 29, '2025-05-31 05:48:16', 'rechazado', '2025-05-31 01:02:24'),
(10, 28, 30, '2025-05-31 17:34:14', 'pendiente', '2025-05-31 12:34:14'),
(11, 26, 31, '2025-06-03 11:49:13', 'rechazado', '2025-06-03 06:49:50'),
(12, 26, 32, '2025-06-04 14:35:36', 'rechazado', '2025-06-04 09:36:35');

--
-- Disparadores `postulaciones`
--
DELIMITER $$
CREATE TRIGGER `trg_insert_postulacion` AFTER INSERT ON `postulaciones` FOR EACH ROW BEGIN
  INSERT INTO auditoria_postulaciones (postulacion_id)
  VALUES (NEW.id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postulantes_info`
--

CREATE TABLE `postulantes_info` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `habilidades` text DEFAULT NULL,
  `experiencia` text DEFAULT NULL,
  `profesion` varchar(100) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `postulantes_info`
--

INSERT INTO `postulantes_info` (`id`, `usuario_id`, `habilidades`, `experiencia`, `profesion`, `cv`) VALUES
(8, 27, 'programador', '5 años', 'programador', 'uploads/cv/cv_27_1748667936.pdf'),
(9, 29, 'humilde activo ', '5 años activo', 'conductor', 'uploads/cv/cv_29_1748670585.pdf'),
(10, 30, 'sisas', '5 años', 'desarrollador full stack', 'uploads/cv/cv_30_1748712823.pdf'),
(11, 31, 'sisas', '5 años', 'programador', 'uploads/cv/cv_31_1748951347.pdf'),
(12, 32, 'ser caucho', '5 años', 'programador', 'uploads/cv/cv_32_1749047728.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo` enum('empresa','postulante') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `tipo`) VALUES
(20, 'talentotech', 'talentotech@gmail.com', '$2y$10$hASt5rjG.RPnJeGGxTwI2eL1OjWBMP8/00qg8I/UX6nVO49kzPpJy', 'empresa'),
(27, 'andres', 'andres@gmail.com', '$2y$10$924d6SqLKGHC6Aq.2VFGseICj1CYVxSSTGVJc4Mv7hN4WXm.SjCKq', 'postulante'),
(28, 'cocacola', 'cocacola@gmail.com', '$2y$10$2rjDHX3evMIuJtf/dy29ceZtGD0/AAgSUaDBKtTgr9PBC77wURWHe', 'empresa'),
(29, 'jose', 'jose@gmail.com', '$2y$10$LBhMCcoCWZCIbHESi9fsC.ZG6IqhStIOsRzshNbAH/FO1P1csdDym', 'postulante'),
(30, 'destroc', 'destroc@gmail.com', '$2y$10$7jlWyWFMVfBexYUZQuAYROUiSkQpnGvjnweHHIyU2nididyUteUJu', 'postulante'),
(31, 'servio', 'servio@gmail.com', '$2y$10$ohm7OFFZvsCx1bw54gH3he6iWpbpi/7GxJll6m4xrwsnhaLsNPNeG', 'postulante'),
(32, 'jostin', 'jostin@gmail.com', '$2y$10$88Zml46l3E6vT7J7Xq.On.NqJ1PkQjv51xRIgIcRsdNmIvdWGaMbu', 'postulante');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_postulaciones_completas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_postulaciones_completas` (
`postulacion_id` int(11)
,`postulante` varchar(100)
,`oferta` varchar(255)
,`empresa` varchar(100)
,`estado` enum('pendiente','aceptado','rechazado')
,`fecha_postulacion` timestamp
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_postulaciones_completas`
--
DROP TABLE IF EXISTS `vista_postulaciones_completas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_postulaciones_completas`  AS SELECT `p`.`id` AS `postulacion_id`, `u`.`nombre` AS `postulante`, `o`.`titulo` AS `oferta`, `e`.`nombre` AS `empresa`, `p`.`estado` AS `estado`, `p`.`fecha_postulacion` AS `fecha_postulacion` FROM (((`postulaciones` `p` join `usuarios` `u` on(`u`.`id` = `p`.`postulante_id`)) join `ofertas_laborales` `o` on(`o`.`id` = `p`.`oferta_id`)) join `usuarios` `e` on(`e`.`id` = `o`.`empresa_id`)) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria_postulaciones`
--
ALTER TABLE `auditoria_postulaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresas_info`
--
ALTER TABLE `empresas_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `ofertas_laborales`
--
ALTER TABLE `ofertas_laborales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Indices de la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oferta_id` (`oferta_id`),
  ADD KEY `postulante_id` (`postulante_id`);

--
-- Indices de la tabla `postulantes_info`
--
ALTER TABLE `postulantes_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria_postulaciones`
--
ALTER TABLE `auditoria_postulaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `empresas_info`
--
ALTER TABLE `empresas_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ofertas_laborales`
--
ALTER TABLE `ofertas_laborales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `postulantes_info`
--
ALTER TABLE `postulantes_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empresas_info`
--
ALTER TABLE `empresas_info`
  ADD CONSTRAINT `empresas_info_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ofertas_laborales`
--
ALTER TABLE `ofertas_laborales`
  ADD CONSTRAINT `ofertas_laborales_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `postulaciones`
--
ALTER TABLE `postulaciones`
  ADD CONSTRAINT `postulaciones_ibfk_1` FOREIGN KEY (`oferta_id`) REFERENCES `ofertas_laborales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `postulaciones_ibfk_2` FOREIGN KEY (`postulante_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `postulantes_info`
--
ALTER TABLE `postulantes_info`
  ADD CONSTRAINT `postulantes_info_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
