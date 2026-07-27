-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_cuidado_estandar`
--

CREATE TABLE `plan_cuidado_estandar` (
  `plan_cuidado_estandar_id` int(11) NOT NULL,
  `tipo_diagnostico_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan_cuidado_estandar_tarea`
--

CREATE TABLE `plan_cuidado_estandar_tarea` (
  `plan_cuidado_estandar_tarea_id` int(11) NOT NULL,
  `plan_cuidado_estandar_id` int(11) NOT NULL,
  `tipo_meta_id` int(11) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indices de la tabla `plan_cuidado_estandar`
--
ALTER TABLE `plan_cuidado_estandar`
  ADD PRIMARY KEY (`plan_cuidado_estandar_id`),
  ADD KEY `pce_tipo_diag` (`tipo_diagnostico_id`);

--
-- Indices de la tabla `plan_cuidado_estandar_tarea`
--
ALTER TABLE `plan_cuidado_estandar_tarea`
  ADD PRIMARY KEY (`plan_cuidado_estandar_tarea_id`),
  ADD KEY `pcet_plan` (`plan_cuidado_estandar_id`),
  ADD KEY `pcet_tipo_meta` (`tipo_meta_id`);

--
-- AUTO_INCREMENT de la tabla `plan_cuidado_estandar`
--
ALTER TABLE `plan_cuidado_estandar`
  MODIFY `plan_cuidado_estandar_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `plan_cuidado_estandar_tarea`
--
ALTER TABLE `plan_cuidado_estandar_tarea`
  MODIFY `plan_cuidado_estandar_tarea_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Filtros para la tabla `plan_cuidado_estandar`
--
ALTER TABLE `plan_cuidado_estandar`
  ADD CONSTRAINT `pce_tipo_diag_fk` FOREIGN KEY (`tipo_diagnostico_id`) REFERENCES `tipo_diagnostico` (`tipo_diagnostico_id`);

--
-- Filtros para la tabla `plan_cuidado_estandar_tarea`
--
ALTER TABLE `plan_cuidado_estandar_tarea`
  ADD CONSTRAINT `pcet_plan_fk` FOREIGN KEY (`plan_cuidado_estandar_id`) REFERENCES `plan_cuidado_estandar` (`plan_cuidado_estandar_id`),
  ADD CONSTRAINT `pcet_tipo_meta_fk` FOREIGN KEY (`tipo_meta_id`) REFERENCES `tipo_meta` (`tipo_meta_id`);
