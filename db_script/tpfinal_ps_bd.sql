-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-04-2026 a las 22:08:29
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
-- Base de datos: `tpfinal_ps_bd`
--
DROP DATABASE IF EXISTS `tpfinal_ps_bd`;
CREATE DATABASE IF NOT EXISTS `tpfinal_ps_bd` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `tpfinal_ps_bd`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diagnostico`
--

CREATE TABLE `diagnostico` (
  `diagnostico_id` int(11) NOT NULL,
  `tipo_diagnostico_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `plan_cuidado_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `diagnostico`
--

INSERT INTO `diagnostico` (`diagnostico_id`, `tipo_diagnostico_id`, `paciente_id`, `medico_id`, `fecha`, `descripcion`, `plan_cuidado_id`, `estado_id`, `deleted_at`, `updated_at`, `created_at`) VALUES
(1, 1, 7, 12, '2026-04-04', 'prueba de carga de diagnostico', 0, 1, NULL, '2026-04-04 19:54:16', '2026-04-04 19:54:16'),
(2, 2, 7, 12, '2026-04-03', 'prueba de segundo diagnostico pasado', 0, 1, NULL, '2026-04-04 20:02:29', '2026-04-04 20:02:29'),
(3, 2, 13, 12, '2026-04-03', 'ta todo bien', 0, 1, NULL, '2026-04-04 20:03:53', '2026-04-04 20:03:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entidad_medica`
--

CREATE TABLE `entidad_medica` (
  `entidad_medica_id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `entidad_medica`
--

INSERT INTO `entidad_medica` (`entidad_medica_id`, `nombre`, `descripcion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Hospital Zatti', 'hospital publico Viedma', '2025-11-19 14:27:48', '2025-11-19 15:31:43', '2025-11-19 15:31:43'),
(2, 'Clinica Viedma modif', 'clinica de Viedma, unica e inigualable', '2025-11-19 15:32:16', '2025-11-19 16:55:14', NULL),
(3, 'aaa V2', 'hospital publico Viedma', '2025-11-26 12:41:12', '2026-03-11 13:20:22', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

CREATE TABLE `especialidad` (
  `especialidad_id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`especialidad_id`, `nombre`, `deleted_at`, `updated_at`, `created_at`) VALUES
(1, 'Alergia e Inmunología', NULL, NULL, NULL),
(2, 'Anestesiología', NULL, NULL, NULL),
(3, 'Anatomía Patológica', NULL, NULL, NULL),
(4, 'Cardiología', NULL, NULL, NULL),
(5, 'Cirugía General', NULL, NULL, NULL),
(6, 'Clínica Médica', NULL, NULL, NULL),
(7, 'Dermatología', NULL, NULL, NULL),
(8, 'Diagnóstico por Imágenes', NULL, NULL, NULL),
(9, 'Endocrinología', NULL, NULL, NULL),
(10, 'Gastroenterología', NULL, NULL, NULL),
(11, 'Genética Médica', NULL, NULL, NULL),
(12, 'Geriatría', NULL, NULL, NULL),
(13, 'Ginecología', NULL, NULL, NULL),
(14, 'Hematología', NULL, NULL, NULL),
(15, 'Infectología', NULL, NULL, NULL),
(16, 'Medicina Familiar', NULL, NULL, NULL),
(17, 'Medicina del Trabajo', NULL, NULL, NULL),
(18, 'Medicina Física y Rehabilitación', NULL, NULL, NULL),
(19, 'Medicina General', NULL, NULL, NULL),
(20, 'Medicina Interna', NULL, NULL, NULL),
(21, 'Nefrología', NULL, NULL, NULL),
(22, 'Neumonología', NULL, NULL, NULL),
(23, 'Neurocirugía', NULL, NULL, NULL),
(24, 'Neurología', NULL, NULL, NULL),
(25, 'Nutrición', NULL, NULL, NULL),
(26, 'Obstetricia', NULL, NULL, NULL),
(27, 'Oftalmología', NULL, NULL, NULL),
(28, 'Oncología', NULL, NULL, NULL),
(29, 'Otorrinolaringología', NULL, NULL, NULL),
(30, 'Pediatría', NULL, NULL, NULL),
(31, 'Psiquiatría', NULL, NULL, NULL),
(32, 'Radiología', NULL, NULL, NULL),
(33, 'Reumatología', NULL, NULL, NULL),
(34, 'Traumatología y Ortopedia', NULL, NULL, NULL),
(35, 'Urología', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_diagnostico`
--

CREATE TABLE `estado_diagnostico` (
  `estado_diagnostico_id` int(11) NOT NULL,
  `estado` text NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `estado_diagnostico`
--

INSERT INTO `estado_diagnostico` (`estado_diagnostico_id`, `estado`, `deleted_at`, `updated_at`, `created_at`) VALUES
(1, 'Pendiente', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medico`
--

CREATE TABLE `medico` (
  `medico_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `especialidad_id` int(11) NOT NULL,
  `matricula` varchar(20) DEFAULT NULL,
  `biografia` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medico_entidad_medica`
--

CREATE TABLE `medico_entidad_medica` (
  `med_entidad_med_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `entidad_medica_id` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metas_plan_cuidado`
--

CREATE TABLE `metas_plan_cuidado` (
  `metas_plan_cuidado_id` int(11) NOT NULL,
  `plan_cuidado_id` int(11) NOT NULL,
  `tipo_meta_id` int(11) NOT NULL,
  `meta_cumplida` blob NOT NULL,
  `descripcion` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `permiso_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `rol_id` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_cuidado`
--

CREATE TABLE `plan_cuidado` (
  `plan_cuidado_id` int(11) NOT NULL,
  `fec_inicio` date NOT NULL,
  `fec_fin` date NOT NULL,
  `comentario_paciente` text NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `rol_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `nombre` text NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rol_id`, `descripcion`, `nombre`, `deleted_at`, `updated_at`, `created_at`) VALUES
(1, 'Administrador', 'admin', NULL, NULL, NULL),
(2, 'Personal de salud', 'medico', NULL, NULL, NULL),
(3, 'Paciente', 'paciente', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_medico`
--

CREATE TABLE `servicio_medico` (
  `servicio_med_id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` text NOT NULL,
  `especialidad_id` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_diagnostico`
--

CREATE TABLE `tipo_diagnostico` (
  `tipo_diagnostico_id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tipo_diagnostico`
--

INSERT INTO `tipo_diagnostico` (`tipo_diagnostico_id`, `nombre`, `descripcion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Diagnostico dificil V2', 'es un diagnostico muy dificil', '2026-03-11 13:25:44', '2026-03-11 13:27:57', NULL),
(2, 'diag normal', 'un diag', '2026-03-11 15:45:56', '2026-03-11 15:45:56', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_meta`
--

CREATE TABLE `tipo_meta` (
  `tipo_meta_id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` text NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario_id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `activo` int(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `nombre`, `apellido`, `email`, `password`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'denis', 'ruppel', 'druppel@gmail.com', '$2y$10$MiklFpBJ6iolRIdu3jk5wuZDGvEP4IEsEv8JMgZV3Nswp5Sd06wwi', 1, '2025-11-12 11:38:14', '2025-11-12 11:38:14', NULL),
(7, 'bart', 'simpson', 'elbarto@gmail.com', '$2y$10$6YZAVWR3sCycuSpnVfmb4.uyWCLlf1n5SVRoijyGPtvbdi4tPZ/sy', 1, '2025-11-12 15:28:25', '2025-11-12 15:28:25', NULL),
(8, 'lisa', 'simpson', 'lisasimpson@gmail.com', '$2y$10$GAU9Pg/B7OYuZEmiMaoiG.koQV/Atp0ruIe.EAl9nhSTXG0LURWaG', 1, '2025-11-12 15:29:19', '2025-11-12 15:29:19', NULL),
(9, 'Juan', 'admin', 'juanadmin@gmail.com', '$2y$10$Xo/N1kTfowBrw4hlUo73cOd8c78dutWB5cE4wSBxvkR6tcb912/vu', 1, '2025-11-14 15:42:49', '2025-11-14 15:42:49', NULL),
(10, 'denis', 'admin', 'daruppel@admin.com', '$2y$10$fWaROJT7CvcXoq4su5nWk.e6hZPgWf78NQ4nLO8zqZXajQ3CRz7u2', 1, '2025-11-14 16:32:20', '2025-11-14 16:32:20', NULL),
(11, 'Admin', 'Demo', 'admin@demo.com', '$2y$10$W64sApt2x3G8AcBz.cp/nOsBhuqz.sd2A2HYBVDYcaaA9Wu5vjCee', 1, '2026-03-19 23:59:52', '2026-03-19 23:59:52', NULL),
(12, 'Medico', 'Demo', 'medico@demo.com', '$2y$10$y.2zcUEtovfinWj5SDYJEOpr13zTZkbXWDeFX32T5X2yJutiiyJVe', 1, '2026-03-20 00:00:54', '2026-03-20 00:00:54', NULL),
(13, 'lisa', 'simpson', 'lisa@simpson.sp', '$2y$10$JOnYyv0RfjhzSEQ8jGCyt.jZ6DUcrwvFT1VsBgypMDAhnTLdThu.G', 1, '2026-04-04 20:03:18', '2026-04-04 20:03:18', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `usuario_rol_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`usuario_rol_id`, `rol_id`, `usuario_id`, `deleted_at`, `updated_at`, `created_at`) VALUES
(1, 2, 1, NULL, NULL, NULL),
(2, 3, 7, NULL, NULL, NULL),
(3, 2, 8, NULL, NULL, NULL),
(4, 1, 9, NULL, NULL, NULL),
(5, 1, 10, NULL, NULL, NULL),
(6, 1, 11, NULL, NULL, NULL),
(7, 2, 12, NULL, NULL, NULL),
(8, 3, 13, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `diagnostico`
--
ALTER TABLE `diagnostico`
  ADD PRIMARY KEY (`diagnostico_id`),
  ADD KEY `diagnostico_estado` (`estado_id`),
  ADD KEY `diagnostico_medico` (`medico_id`),
  ADD KEY `diagnostico_paciente` (`paciente_id`);

--
-- Indices de la tabla `entidad_medica`
--
ALTER TABLE `entidad_medica`
  ADD PRIMARY KEY (`entidad_medica_id`);

--
-- Indices de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD PRIMARY KEY (`especialidad_id`);

--
-- Indices de la tabla `estado_diagnostico`
--
ALTER TABLE `estado_diagnostico`
  ADD PRIMARY KEY (`estado_diagnostico_id`);

--
-- Indices de la tabla `medico`
--
ALTER TABLE `medico`
  ADD PRIMARY KEY (`medico_id`),
  ADD KEY `medico_usuario` (`usuario_id`),
  ADD KEY `medico_espec` (`especialidad_id`);

--
-- Indices de la tabla `medico_entidad_medica`
--
ALTER TABLE `medico_entidad_medica`
  ADD PRIMARY KEY (`med_entidad_med_id`),
  ADD KEY `med_ent_medico` (`medico_id`),
  ADD KEY `med_entidad` (`entidad_medica_id`);

--
-- Indices de la tabla `metas_plan_cuidado`
--
ALTER TABLE `metas_plan_cuidado`
  ADD PRIMARY KEY (`metas_plan_cuidado_id`),
  ADD KEY `metas_plan` (`plan_cuidado_id`),
  ADD KEY `metas_tipo_meta` (`tipo_meta_id`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`permiso_id`),
  ADD KEY `permiso_rol` (`rol_id`);

--
-- Indices de la tabla `plan_cuidado`
--
ALTER TABLE `plan_cuidado`
  ADD PRIMARY KEY (`plan_cuidado_id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`rol_id`);

--
-- Indices de la tabla `servicio_medico`
--
ALTER TABLE `servicio_medico`
  ADD PRIMARY KEY (`servicio_med_id`),
  ADD KEY `servicio_med_espec` (`especialidad_id`);

--
-- Indices de la tabla `tipo_diagnostico`
--
ALTER TABLE `tipo_diagnostico`
  ADD PRIMARY KEY (`tipo_diagnostico_id`);

--
-- Indices de la tabla `tipo_meta`
--
ALTER TABLE `tipo_meta`
  ADD PRIMARY KEY (`tipo_meta_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`);

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`usuario_rol_id`),
  ADD KEY `user_role_user` (`usuario_id`),
  ADD KEY `user_role_role` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `diagnostico`
--
ALTER TABLE `diagnostico`
  MODIFY `diagnostico_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `entidad_medica`
--
ALTER TABLE `entidad_medica`
  MODIFY `entidad_medica_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  MODIFY `especialidad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `estado_diagnostico`
--
ALTER TABLE `estado_diagnostico`
  MODIFY `estado_diagnostico_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `medico`
--
ALTER TABLE `medico`
  MODIFY `medico_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medico_entidad_medica`
--
ALTER TABLE `medico_entidad_medica`
  MODIFY `med_entidad_med_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `metas_plan_cuidado`
--
ALTER TABLE `metas_plan_cuidado`
  MODIFY `metas_plan_cuidado_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `permiso_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `plan_cuidado`
--
ALTER TABLE `plan_cuidado`
  MODIFY `plan_cuidado_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `rol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicio_medico`
--
ALTER TABLE `servicio_medico`
  MODIFY `servicio_med_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_diagnostico`
--
ALTER TABLE `tipo_diagnostico`
  MODIFY `tipo_diagnostico_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_meta`
--
ALTER TABLE `tipo_meta`
  MODIFY `tipo_meta_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  MODIFY `usuario_rol_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `diagnostico`
--
ALTER TABLE `diagnostico`
  ADD CONSTRAINT `diagnostico_estado` FOREIGN KEY (`estado_id`) REFERENCES `estado_diagnostico` (`estado_diagnostico_id`),
  ADD CONSTRAINT `diagnostico_medico` FOREIGN KEY (`medico_id`) REFERENCES `usuario` (`usuario_id`),
  ADD CONSTRAINT `diagnostico_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `medico`
--
ALTER TABLE `medico`
  ADD CONSTRAINT `medico_espec` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidad` (`especialidad_id`),
  ADD CONSTRAINT `medico_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `medico_entidad_medica`
--
ALTER TABLE `medico_entidad_medica`
  ADD CONSTRAINT `med_ent_medico` FOREIGN KEY (`medico_id`) REFERENCES `medico` (`medico_id`),
  ADD CONSTRAINT `med_entidad` FOREIGN KEY (`entidad_medica_id`) REFERENCES `entidad_medica` (`entidad_medica_id`);

--
-- Filtros para la tabla `metas_plan_cuidado`
--
ALTER TABLE `metas_plan_cuidado`
  ADD CONSTRAINT `metas_plan` FOREIGN KEY (`plan_cuidado_id`) REFERENCES `plan_cuidado` (`plan_cuidado_id`),
  ADD CONSTRAINT `metas_tipo_meta` FOREIGN KEY (`tipo_meta_id`) REFERENCES `tipo_meta` (`tipo_meta_id`);

--
-- Filtros para la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD CONSTRAINT `permiso_rol` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`rol_id`);

--
-- Filtros para la tabla `servicio_medico`
--
ALTER TABLE `servicio_medico`
  ADD CONSTRAINT `servicio_med_espec` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidad` (`especialidad_id`);

--
-- Filtros para la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD CONSTRAINT `user_role_role` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`rol_id`),
  ADD CONSTRAINT `user_role_user` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
