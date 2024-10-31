-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-10-2024 a las 06:33:59
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
-- Base de datos: `matriz_resp_prueba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `estado` tinyint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `nombre`, `estado`) VALUES
(2, 'Registrar Clientes', 2),
(3, 'Actualizar Inventario', 0),
(4, 'Revisión de Código', 0),
(5, 'Atender Consultas', 0),
(6, 'Diseñar Logo', 0),
(7, 'Evaluar Proveedores', 0),
(10, 'Planificar Campaña', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `valor` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`id`, `nombre`, `valor`) VALUES
(1, 'codigo_jefe_hash', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` tinyint(3) NOT NULL,
  `id_jefe` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_completado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id`, `nombre`, `descripcion`, `estado`, `id_jefe`, `fecha_creacion`, `fecha_completado`) VALUES
(4, 'Gestión de Inventarios', 'Sistema para controlar existencias y automatizar reposición de productos.', 2, 1, '2024-10-30 01:05:45', NULL),
(8, 'App de Tareas', 'Aplicación para organizar y priorizar tareas diarias con recordatorios.', 1, 1, '2024-10-30 01:05:48', NULL),
(9, 'Portal Educativo', 'Plataforma para cursos en línea con seguimiento de progreso y exámenes.', 2, 1, '2024-10-30 01:05:50', NULL),
(10, 'E-commerce Local', 'Tienda en línea para comercios locales con entregas rápidas.', 1, 1, '2024-10-30 01:05:52', NULL),
(11, 'Sistema de Reservas', 'Solución para gestionar reservas en restaurantes y hoteles.', 1, 1, '2024-10-30 01:05:56', NULL),
(13, 'Encuestas de Satisfacción', 'Herramienta para obtener opiniones y calificar servicios.', 1, 10, '2024-10-30 11:50:34', NULL),
(15, 'Control de Gasto', 'App para registrar y categorizar gastos personales.', 1, 10, '2024-10-30 19:30:03', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_actividad`
--

CREATE TABLE `proyecto_actividad` (
  `id_proyecto` int(11) NOT NULL,
  `id_actividad` int(11) NOT NULL,
  `estado` tinyint(3) NOT NULL,
  `fecha_asociado` datetime NOT NULL,
  `fecha_completado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyecto_actividad`
--

INSERT INTO `proyecto_actividad` (`id_proyecto`, `id_actividad`, `estado`, `fecha_asociado`, `fecha_completado`) VALUES
(4, 2, 2, '2024-10-30 00:19:28', NULL),
(4, 3, 1, '2024-10-30 11:43:10', NULL),
(8, 2, 3, '2024-10-30 00:19:45', '2024-10-31 01:02:10'),
(8, 3, 1, '2024-10-30 19:19:35', NULL),
(9, 2, 2, '2024-10-30 02:17:50', NULL),
(9, 3, 1, '2024-10-30 02:17:47', NULL),
(9, 4, 1, '2024-10-30 00:19:53', NULL),
(9, 5, 2, '2024-10-30 02:17:49', NULL),
(9, 6, 3, '2024-10-30 02:17:51', '2024-10-31 01:02:23'),
(10, 2, 1, '2024-10-31 00:30:18', NULL),
(10, 5, 1, '2024-10-31 00:36:58', NULL),
(11, 2, 1, '2024-10-30 00:19:57', NULL),
(11, 4, 1, '2024-10-30 00:19:55', NULL),
(13, 4, 1, '2024-10-30 12:19:31', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contraseña` varchar(50) NOT NULL,
  `es_jefe` tinyint(1) NOT NULL,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `contraseña`, `es_jefe`, `fecha_creacion`) VALUES
(1, 'Daniel', 'Alfonso', 'danialfonso215@gmail.com', '123456', 1, '2024-10-30 01:27:18'),
(3, 'Rocio', 'Guibarra', 'rocioguibarra00@gmail.com', '123', 0, '2024-10-30 01:27:21'),
(4, 'Daniel', 'Guibarra Mendoza', 'danielguibarra@gmail.com', 'asdasd', 0, '2024-10-30 01:27:23'),
(5, 'Ana', 'Torres', 'ana.torres@email.com', '1234Ana', 0, '2024-10-30 01:27:25'),
(6, 'Luis', 'Martinez', 'luis.martinez@gmail.com', 'LMart12', 1, '2024-10-30 01:27:27'),
(7, 'Marta', 'López', 'marta.lopez@email.com', 'MartLoz', 1, '2024-10-30 01:27:28'),
(8, 'Pedro', 'Díaz', 'pedro.diaz@email.com', 'PDz2023', 1, '2024-10-30 01:27:30'),
(9, 'Sofía', 'Ruiz', 'sofia.ruiz@email.com', 'SofiRu7', 0, '2024-10-30 02:24:23'),
(10, 'Carlos', 'Gómez', 'carlos.gomez@email.com', 'CarGo21', 1, '2024-10-30 11:48:39'),
(11, 'Elena', 'Pérez', 'elena.perez@email.com', 'EPz2024', 0, '2024-10-30 19:43:46'),
(12, 'Javier', 'Sanchez', 'javier.sanchez@email.com', 'JSan234', 0, '2024-10-30 21:35:04'),
(13, 'Laura', 'Morales', 'laura.morales@email.com', 'LauM78', 0, '2024-10-31 01:56:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_actividad`
--

CREATE TABLE `usuario_actividad` (
  `id_usuario` int(11) NOT NULL,
  `id_actividad` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL,
  `fecha_asignacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_actividad`
--

INSERT INTO `usuario_actividad` (`id_usuario`, `id_actividad`, `id_proyecto`, `fecha_asignacion`) VALUES
(3, 2, 4, '2024-10-30 19:54:18'),
(3, 2, 8, '2024-10-30 18:59:52'),
(3, 5, 9, '2024-10-30 02:17:58'),
(3, 6, 9, '2024-10-30 02:17:58'),
(4, 3, 9, '2024-10-30 02:18:04'),
(4, 6, 9, '2024-10-30 02:18:00'),
(5, 2, 4, '2024-10-30 01:25:33'),
(5, 2, 9, '2024-10-30 02:18:00'),
(5, 4, 9, '2024-10-30 02:17:59'),
(9, 2, 9, '2024-10-30 02:24:55'),
(9, 3, 4, '2024-10-30 20:03:41'),
(9, 4, 9, '2024-10-30 02:24:56'),
(12, 2, 4, '2024-10-31 00:46:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_proyecto`
--

CREATE TABLE `usuario_proyecto` (
  `id_usuario` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_proyecto`
--

INSERT INTO `usuario_proyecto` (`id_usuario`, `id_proyecto`) VALUES
(3, 4),
(3, 8),
(3, 9),
(3, 10),
(3, 13),
(4, 9),
(4, 11),
(4, 13),
(5, 4),
(5, 9),
(5, 10),
(5, 11),
(9, 4),
(9, 9),
(12, 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jefe_fk` (`id_jefe`);

--
-- Indices de la tabla `proyecto_actividad`
--
ALTER TABLE `proyecto_actividad`
  ADD PRIMARY KEY (`id_proyecto`,`id_actividad`),
  ADD KEY `id_actividad_fk4` (`id_actividad`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario_actividad`
--
ALTER TABLE `usuario_actividad`
  ADD PRIMARY KEY (`id_usuario`,`id_actividad`,`id_proyecto`),
  ADD KEY `id_actividad_fk3` (`id_actividad`),
  ADD KEY `id_proyecto_fk3` (`id_proyecto`);

--
-- Indices de la tabla `usuario_proyecto`
--
ALTER TABLE `usuario_proyecto`
  ADD PRIMARY KEY (`id_usuario`,`id_proyecto`),
  ADD KEY `id_proyecto_fk2` (`id_proyecto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD CONSTRAINT `id_jefe_fk` FOREIGN KEY (`id_jefe`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `proyecto_actividad`
--
ALTER TABLE `proyecto_actividad`
  ADD CONSTRAINT `id_actividad_fk4` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_proyecto_fk4` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario_actividad`
--
ALTER TABLE `usuario_actividad`
  ADD CONSTRAINT `id_actividad_fk3` FOREIGN KEY (`id_actividad`) REFERENCES `actividades` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_proyecto_fk3` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_usuario_fk3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario_proyecto`
--
ALTER TABLE `usuario_proyecto`
  ADD CONSTRAINT `id_proyecto_fk2` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_usuario_fk2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
