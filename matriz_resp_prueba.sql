-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-10-2024 a las 00:06:11
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
(2, 'actividad lolaso', 2),
(3, 'otra actividad jeje', 0),
(4, 'actividad 3', 0),
(5, 'actividad 4', 0),
(6, 'actividad 5', 0);

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
-- Estructura de tabla para la tabla `matriz`
--

CREATE TABLE `matriz` (
  `id` int(50) NOT NULL,
  `id_usuario` int(50) NOT NULL,
  `actividades` varchar(1000) NOT NULL,
  `encargado` varchar(50) NOT NULL,
  `nombre_proyecto` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `matriz`
--

INSERT INTO `matriz` (`id`, `id_usuario`, `actividades`, `encargado`, `nombre_proyecto`) VALUES
(6, 3, 'item rocio', 'encargao rocio', 'proyecto rosio'),
(10, 1, 'Diseñar los modelos de los personajes', 'Daniel Guibarra Mendoza', 'Jueguito epico'),
(11, 1, 'adsad', 'asdasdsadsadsad', 'asdadsad'),
(12, 5, 'Actividad de usuario nuevo', 'Encargado nuevo', 'Proyecto nuevo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` tinyint(3) NOT NULL,
  `id_jefe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyectos`
--

INSERT INTO `proyectos` (`id`, `nombre`, `descripcion`, `estado`, `id_jefe`) VALUES
(4, 'proyecto1', 'este es un proyectazo man', 2, 1),
(8, 'proyecto ejemplo lol', 'asdsa', 0, 1),
(9, 'proyecto 3', 'descripcion del proyecto 3', 0, 1),
(10, 'proyecto 4', 'el cuarto proyecto', 0, 1),
(11, 'proyecto 5', 'ahora hay 5 proyectos', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto_actividad`
--

CREATE TABLE `proyecto_actividad` (
  `id_proyecto` int(11) NOT NULL,
  `id_actividad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyecto_actividad`
--

INSERT INTO `proyecto_actividad` (`id_proyecto`, `id_actividad`) VALUES
(4, 2),
(4, 4),
(8, 2),
(9, 4),
(11, 2),
(11, 4);

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
  `es_jefe` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `contraseña`, `es_jefe`) VALUES
(1, 'Daniel', 'Alfonso', 'danialfonso215@gmail.com', '123456', 1),
(3, 'Rocio', 'Guibarra', 'rocioguibarra00@gmail.com', '123', 0),
(4, 'Daniel', 'Guibarra Mendoza', 'danielguibarra@gmail.com', 'asdasd', 0),
(5, 'Usuario', 'Nuevo', 'usuarionuevo@gmail.com', 'asdasd', 0),
(6, 'jefazo', 'otro mas', 'dani@gmail.com', 'asd', 1),
(7, 'otro jefe', 'mas', 'danidani@gmail.com', 'asdasd', 1),
(8, 'jefeeeeeee', 'alto jefe', 'jefe@gmail.com', 'asd', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_actividad`
--

CREATE TABLE `usuario_actividad` (
  `id_usuario` int(11) NOT NULL,
  `id_actividad` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_actividad`
--

INSERT INTO `usuario_actividad` (`id_usuario`, `id_actividad`, `id_proyecto`) VALUES
(3, 2, 4),
(4, 2, 4);

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
(4, 4),
(4, 11),
(5, 4),
(5, 9),
(5, 11);

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
-- Indices de la tabla `matriz`
--
ALTER TABLE `matriz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario_fk` (`id_usuario`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `matriz`
--
ALTER TABLE `matriz`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `matriz`
--
ALTER TABLE `matriz`
  ADD CONSTRAINT `id_usuario_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

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
