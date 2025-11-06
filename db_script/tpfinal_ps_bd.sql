-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-11-2025 a las 00:16:41
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
CREATE DATABASE IF NOT EXISTS `tpfinal_ps_bd` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `tpfinal_ps_bd`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diagnostico`
--

DROP TABLE IF EXISTS `diagnostico`;
CREATE TABLE `diagnostico` (
  `diagnostico_id` int(11) NOT NULL,
  `tipo_diagnostico_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `plan_cuidado_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entidad_medica`
--

DROP TABLE IF EXISTS `entidad_medica`;
CREATE TABLE `entidad_medica` (
  `entidad_medica_id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

DROP TABLE IF EXISTS `especialidad`;
CREATE TABLE `especialidad` (
  `especialidad_id` int(11) NOT NULL,
  `nombre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_diagnostico`
--

DROP TABLE IF EXISTS `estado_diagnostico`;
CREATE TABLE `estado_diagnostico` (
  `estado_diagnostico_id` int(11) NOT NULL,
  `estado` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medico`
--

DROP TABLE IF EXISTS `medico`;
CREATE TABLE `medico` (
  `medico_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `especialidad_id` int(11) NOT NULL,
  `entidad_medica_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metas_plan_cuidado`
--

DROP TABLE IF EXISTS `metas_plan_cuidado`;
CREATE TABLE `metas_plan_cuidado` (
  `metas_plan_cuidado_id` int(11) NOT NULL,
  `plan_cuidado_id` int(11) NOT NULL,
  `tipo_meta_id` int(11) NOT NULL,
  `meta_cumplida` blob NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

DROP TABLE IF EXISTS `permiso`;
CREATE TABLE `permiso` (
  `permiso_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `rol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`permiso_id`, `descripcion`, `rol_id`) VALUES
(1, 'permiso base admin', 1),
(2, 'permiso medico', 2),
(3, 'permiso paciente', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_cuidado`
--

DROP TABLE IF EXISTS `plan_cuidado`;
CREATE TABLE `plan_cuidado` (
  `plan_cuidado_id` int(11) NOT NULL,
  `fec_inicio` date NOT NULL,
  `fec_fin` date NOT NULL,
  `comentario_paciente` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol` (
  `rol_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `nombre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rol_id`, `descripcion`, `nombre`) VALUES
(1, 'admin', ''),
(2, 'medico', ''),
(3, 'paciente', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_medico`
--

DROP TABLE IF EXISTS `servicio_medico`;
CREATE TABLE `servicio_medico` (
  `servicio_med_id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` text NOT NULL,
  `especialidad_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_diagnostico`
--

DROP TABLE IF EXISTS `tipo_diagnostico`;
CREATE TABLE `tipo_diagnostico` (
  `tipo_diagnostico_id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_meta`
--

DROP TABLE IF EXISTS `tipo_meta`;
CREATE TABLE `tipo_meta` (
  `tipo_meta_id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `usuario_id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `deleted_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

DROP TABLE IF EXISTS `usuario_rol`;
CREATE TABLE `usuario_rol` (
  `usuario_rol_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
  ADD KEY `medico_espec` (`especialidad_id`),
  ADD KEY `medico_ent_med` (`entidad_medica_id`);

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
  ADD PRIMARY KEY (`permiso_id`);

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
  MODIFY `diagnostico_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entidad_medica`
--
ALTER TABLE `entidad_medica`
  MODIFY `entidad_medica_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  MODIFY `especialidad_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_diagnostico`
--
ALTER TABLE `estado_diagnostico`
  MODIFY `estado_diagnostico_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medico`
--
ALTER TABLE `medico`
  MODIFY `medico_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `tipo_diagnostico_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipo_meta`
--
ALTER TABLE `tipo_meta`
  MODIFY `tipo_meta_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  MODIFY `usuario_rol_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `diagnostico`
--
ALTER TABLE `diagnostico`
  ADD CONSTRAINT `diagnostico_estado` FOREIGN KEY (`estado_id`) REFERENCES `estado_diagnostico` (`estado_diagnostico_id`),
  ADD CONSTRAINT `diagnostico_medico` FOREIGN KEY (`medico_id`) REFERENCES `medico` (`medico_id`),
  ADD CONSTRAINT `diagnostico_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `medico`
--
ALTER TABLE `medico`
  ADD CONSTRAINT `medico_ent_med` FOREIGN KEY (`entidad_medica_id`) REFERENCES `entidad_medica` (`entidad_medica_id`),
  ADD CONSTRAINT `medico_espec` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidad` (`especialidad_id`),
  ADD CONSTRAINT `medico_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);

--
-- Filtros para la tabla `metas_plan_cuidado`
--
ALTER TABLE `metas_plan_cuidado`
  ADD CONSTRAINT `metas_plan` FOREIGN KEY (`plan_cuidado_id`) REFERENCES `plan_cuidado` (`plan_cuidado_id`),
  ADD CONSTRAINT `metas_tipo_meta` FOREIGN KEY (`tipo_meta_id`) REFERENCES `tipo_meta` (`tipo_meta_id`);

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
