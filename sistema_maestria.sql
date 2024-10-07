-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-10-2024 a las 07:50:17
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
-- Base de datos: `sistema_maestria`
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordinadores`
--

CREATE TABLE `coordinadores` (
  `clave` bigint(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `a_paterno` varchar(50) NOT NULL,
  `a_materno` varchar(50) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `programa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `coordinadores`
--

INSERT INTO `coordinadores` (`clave`, `nombre`, `a_paterno`, `a_materno`, `telefono`, `correo`, `programa`) VALUES
(4411071968, 'Jesus', 'Garcia', 'Santiago', 4411071968, 'jesusgs0729@gmail.com', 'MSC');

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
(301612, 'LOPEZ80', 'D'),
(114090, 'PAULIN55', 'D'),
(4411071968, '123456', 'C'),
(301574, 'GARCIA68', 'A'),
(115260, 'PEREZ10', 'A'),
(113278, 'PEREZ10', 'A'),
(445698, 'PEREZ68', 'A'),
(301574, 'GARCIA68', 'A'),
(301612, 'GONZALEZ80', 'A'),
(202020, 'PEREZ20', 'D'),
(101010, 'IBARRA10', 'D'),
(303030, 'JIMENEZ30', 'D');

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
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `clave` bigint(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `a_paterno` varchar(50) NOT NULL,
  `a_materno` varchar(50) NOT NULL,
  `status` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`clave`, `nombre`, `a_paterno`, `a_materno`, `status`) VALUES
(11, 'Sergio', 'G', 'L', 'A'),
(22, 'Diego', 'H', 'S', 'A'),
(33, 'Jesus', 'G', 'S', 'A'),
(101010, 'MAURICIO ARTURO', 'IBARRA', 'CORONA', 'A'),
(202020, 'JORGE LUIS', 'PEREZ', 'RAMOS', 'A'),
(303030, 'HUGO', 'JIMENEZ', 'HERNANDEZ', 'A'),
(1234, 'Diana', 'Gutierrez', 'Paredes', 'A'),
(12345, 'Karla', 'Villarreal', 'Lopez', 'A'),
(123456, 'Valeria', 'Diaz', 'Zuñiga', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `exp` bigint(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `a_paterno` varchar(50) NOT NULL,
  `a_materno` varchar(50) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `programa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`exp`, `nombre`, `a_paterno`, `a_materno`, `telefono`, `correo`, `programa`) VALUES
(301574, 'JESUS', 'GARCIA', 'SANTIAGO', 4411071968, 'jesusgs0729@gmail.com', 'MSC'),
(301612, 'SERGIO', 'GONZALEZ', 'LOPEZ', 4427794980, 'sgonzalez201@alumnos.uaq.mx', 'MSC'),
(301234, 'Diego', 'Hernandez', 'Sanchez', 4424322665, 'Diego@gmail.com', 'DCC'),
(302345, 'Tania', 'GONZALEZ', 'LOPEZ', 4427793765, 'Tania@alumnos.uaq.mx', 'DTE'),
(303456, 'Diana', 'GARCIA', 'SANTIAGO', 4411079636, 'Diana@gmail.com', 'DITE'),
(304567, 'Julio', 'GONZALEZ', 'LOPEZ', 4427794432, 'Julio@alumnos.uaq.mx', 'MCC'),
(305678, 'Erica', 'GARCIA', 'SANTIAGO', 4411076410, 'Erica@gmail.com', 'MIEVEA'),
(306789, 'Octavio', 'GONZALEZ', 'LOPEZ', 4427792487, 'Octavio@alumnos.uaq.mx', 'MSC');

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
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`clave`);

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
  ADD CONSTRAINT `asignaciones_ibfk_2` FOREIGN KEY (`sinodo1`) REFERENCES `docentes` (`clave`),
  ADD CONSTRAINT `asignaciones_ibfk_3` FOREIGN KEY (`sinodo2`) REFERENCES `docentes` (`clave`),
  ADD CONSTRAINT `asignaciones_ibfk_4` FOREIGN KEY (`sinodo3`) REFERENCES `docentes` (`clave`),
  ADD CONSTRAINT `asignaciones_ibfk_5` FOREIGN KEY (`externo`) REFERENCES `docentes` (`clave`),
  ADD CONSTRAINT `asignaciones_ibfk_6` FOREIGN KEY (`clave_coordinador`) REFERENCES `coordinadores` (`clave`);

--
-- Filtros para la tabla `detalle_evaluaciones`
--
ALTER TABLE `detalle_evaluaciones`
  ADD CONSTRAINT `detalle_evaluaciones_ibfk_1` FOREIGN KEY (`id_sinodo`) REFERENCES `docentes` (`clave`);

--
-- Filtros para la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD CONSTRAINT `evaluaciones_ibfk_1` FOREIGN KEY (`exp_alumno`) REFERENCES `estudiantes` (`exp`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
