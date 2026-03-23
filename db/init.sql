-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-03-2026 a las 17:18:27
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
-- Base de datos: `mod_comp`
--
USE mod_comp;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

DROP TABLE IF EXISTS `asignaturas`;
CREATE TABLE `asignaturas` (
  `id_asig` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `cod` varchar(50) DEFAULT NULL,
  `teacher` varchar(100) DEFAULT NULL,
  `schedule` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`id_asig`, `name`, `cod`, `teacher`, `schedule`) VALUES
(18, 'BI', '004', 'Jorge ', 'Lunes 8-10'),
(21, 'OLTP-II', '006', 'Timaran', 'Martes 10-12'),
(28, 'ProgIII', '008', 'Davila', 'Lunes 8-10'),
(29, 'Simulación', '009', 'Jorge sPrueba', 'Viernes 8-10'),
(30, 'Calculo-Diferencial', '010', 'Ricardo', 'Martes 8-10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

DROP TABLE IF EXISTS `estudiante`;
CREATE TABLE `estudiante` (
  `id_est` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `identity` int(24) NOT NULL,
  `age` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id_est`, `name`, `identity`, `age`) VALUES
(6, 'Deiby Alejandro delgado', 1004235257, 34),
(14, 'alvarito', 1233412, 21),
(17, 'Samu DHCP', 1004235256, 21),
(19, 'Santi', 1234567, 23);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_asignatura`
--

DROP TABLE IF EXISTS `estudiante_asignatura`;
CREATE TABLE `estudiante_asignatura` (
  `id` int(11) NOT NULL,
  `id_est` int(11) NOT NULL,
  `id_asig` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante_asignatura`
--

INSERT INTO `estudiante_asignatura` (`id`, `id_est`, `id_asig`) VALUES
(19, 6, 18),
(20, 6, 21),
(22, 19, 29);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id_asig`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id_est`);

--
-- Indices de la tabla `estudiante_asignatura`
--
ALTER TABLE `estudiante_asignatura`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_est` (`id_est`),
  ADD KEY `id_asig` (`id_asig`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id_asig` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id_est` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `estudiante_asignatura`
--
ALTER TABLE `estudiante_asignatura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estudiante_asignatura`
--
ALTER TABLE `estudiante_asignatura`
  ADD CONSTRAINT `estudiante_asignatura_ibfk_1` FOREIGN KEY (`id_est`) REFERENCES `estudiante` (`id_est`) ON DELETE CASCADE,
  ADD CONSTRAINT `estudiante_asignatura_ibfk_2` FOREIGN KEY (`id_asig`) REFERENCES `asignaturas` (`id_asig`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
