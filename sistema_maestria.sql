-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-08-2024 a las 06:25:38
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
-- Base de datos: `maestria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones`
--

CREATE TABLE `asignaciones` (
  `id` int(11) NOT NULL,
  `exp_alumno` bigint(20) NOT NULL,
  `sinodo1` bigint(20) NOT NULL,
  `sinodo2` bigint(20) NOT NULL,
  `sinodo3` bigint(20) NOT NULL,
  `externo` bigint(20) NOT NULL,
  `clave_coordinador` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaciones`
--

INSERT INTO `asignaciones` (`id`, `exp_alumno`, `sinodo1`, `sinodo2`, `sinodo3`, `externo`, `clave_coordinador`) VALUES
(11, 270309, 1, 2, 301614, 301615, 6),
(13, 150618, 1, 2, 301614, 301615, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordinadores`
--

CREATE TABLE `coordinadores` (
  `clave` bigint(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `a_paterno` varchar(50) NOT NULL,
  `a_materno` varchar(50) NOT NULL,
  `carrera` varchar(50) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `correo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `coordinadores`
--

INSERT INTO `coordinadores` (`clave`, `nombre`, `a_paterno`, `a_materno`, `carrera`, `telefono`, `correo`) VALUES
(5, 'C', 'C', 'C', 'Ciencia de Datos', 4427794980, 'C@gmail.com'),
(6, 'A', 'A', 'A', 'Ciencias de la Computación', 4427794980, 'a@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `id` bigint(20) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `tipo` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`id`, `contrasena`, `tipo`) VALUES
(1, '123', 'A'),
(11, '123', 'A'),
(111, '123', 'A'),
(2, '123', 'D'),
(22, '123', 'D'),
(222, '123', 'D'),
(3, '123', 'C'),
(33, '123', 'C'),
(333, '123', 'C');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_evaluaciones`
--

CREATE TABLE `detalle_evaluaciones` (
  `id_detalle` int(11) DEFAULT NULL,
  `id_evaluacion` int(11) NOT NULL,
  `id_sinodo` bigint(20) NOT NULL,
  `calificacion` double NOT NULL,
  `observacion` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `exp` bigint(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `a_paterno` varchar(50) NOT NULL,
  `a_materno` varchar(50) NOT NULL,
  `carrera` varchar(50) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `correo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`exp`, `nombre`, `a_paterno`, `a_materno`, `carrera`, `telefono`, `correo`) VALUES
(7, 'B', 'B', 'B', 'Ciencias de la Computación', 4427794980, 'B@gmail.com'),
(8, 'C', 'C', 'C', 'Ciencias de la Computación', 4427794980, 'C@gmail.com'),
(150618, 'María', 'López', 'Martínez', 'Ciencias de la Computación', 2587419630, 'maria@example.com'),
(180712, 'Carlos', 'Rodríguez', 'García', 'Ciencia de Datos', 7418529630, 'carlos@example.com'),
(210925, 'Laura', 'Martínez', 'López', 'Innovación de Entornos Virtuales de Enseñanza Apre', 9876543210, 'laura@example.com'),
(270309, 'Ana', 'Hernández', 'Pérez', 'Ciencias de la Computación', 3698521470, 'ana@example.com'),
(301612, 'Alejandro', 'González', 'Hernández', 'Sistemas Computacionales', 1234567890, 'alejandro@example.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `id` int(11) NOT NULL,
  `exp_alumno` bigint(20) NOT NULL,
  `fecha_evaluacion` date NOT NULL,
  `cal_final` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sinodos`
--

CREATE TABLE `sinodos` (
  `clave` bigint(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `a_paterno` varchar(50) NOT NULL,
  `a_materno` varchar(50) NOT NULL,
  `status` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sinodos`
--

INSERT INTO `sinodos` (`clave`, `nombre`, `a_paterno`, `a_materno`, `status`) VALUES
(1, 'Sergio', 'G', 'L', 'A'),
(2, 'Sergio', 'G', 'L', 'A'),
(301614, 'Sergio', 'G', 'L', 'A'),
(301615, 'Sergio', 'G', 'L', 'I'),
(1234567890, 'Juan', 'García', 'López', 'A'),
(3216549870, 'Luis', 'López', 'Gutiérrez', 'I'),
(4561237890, 'Pedro', 'Sánchez', 'Pérez', 'I'),
(7890123456, 'Ana', 'Rodríguez', 'Gómez', 'A'),
(9876543210, 'María', 'Martínez', 'Hernández', 'A');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exp_alumno` (`exp_alumno`),
  ADD KEY `sinodo1` (`sinodo1`),
  ADD KEY `sinodo2` (`sinodo2`),
  ADD KEY `sinodo3` (`sinodo3`),
  ADD KEY `externo` (`externo`),
  ADD KEY `clave_coordinador` (`clave_coordinador`);

--
-- Indices de la tabla `coordinadores`
--
ALTER TABLE `coordinadores`
  ADD PRIMARY KEY (`clave`);

--
-- Indices de la tabla `detalle_evaluaciones`
--
ALTER TABLE `detalle_evaluaciones`
  ADD KEY `id_sinodo` (`id_sinodo`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`exp`);

--
-- Indices de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exp_alumno` (`exp_alumno`);

--
-- Indices de la tabla `sinodos`
--
ALTER TABLE `sinodos`
  ADD PRIMARY KEY (`clave`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD CONSTRAINT `asignaciones_ibfk_1` FOREIGN KEY (`exp_alumno`) REFERENCES `estudiantes` (`exp`),
  ADD CONSTRAINT `asignaciones_ibfk_2` FOREIGN KEY (`sinodo1`) REFERENCES `sinodos` (`clave`),
  ADD CONSTRAINT `asignaciones_ibfk_3` FOREIGN KEY (`sinodo2`) REFERENCES `sinodos` (`clave`),
  ADD CONSTRAINT `asignaciones_ibfk_4` FOREIGN KEY (`sinodo3`) REFERENCES `sinodos` (`clave`),
  ADD CONSTRAINT `asignaciones_ibfk_5` FOREIGN KEY (`externo`) REFERENCES `sinodos` (`clave`),
  ADD CONSTRAINT `asignaciones_ibfk_6` FOREIGN KEY (`clave_coordinador`) REFERENCES `coordinadores` (`clave`);

--
-- Filtros para la tabla `detalle_evaluaciones`
--
ALTER TABLE `detalle_evaluaciones`
  ADD CONSTRAINT `detalle_evaluaciones_ibfk_1` FOREIGN KEY (`id_sinodo`) REFERENCES `sinodos` (`clave`);

--
-- Filtros para la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD CONSTRAINT `evaluaciones_ibfk_1` FOREIGN KEY (`exp_alumno`) REFERENCES `estudiantes` (`exp`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
