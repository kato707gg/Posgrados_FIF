-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-11-2024 a las 01:19:26
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
  `director` bigint(20) NOT NULL,
  `sinodo2` bigint(20) NOT NULL,
  `sinodo3` bigint(20) NOT NULL,
  `externo` bigint(20) NOT NULL,
  `clave_coordinador` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignaciones`
--

INSERT INTO `asignaciones` (`id`, `exp_alumno`, `director`, `sinodo2`, `sinodo3`, `externo`, `clave_coordinador`) VALUES
(34, 1, 4321, 1234, 33333, 54321, 567575),
(35, 2, 4321, 1234, 12345, 33333, 567575),
(36, 3, 4321, 1234, 12345, 114090, 567575);

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
(456324, 'Laura', 'Chavero', 'Basaldúa ', 4411071968, 'mievea@uaq.mx', 'MIEVEA'),
(567574, 'Jorge Luis', 'Pérez', 'Ramos', 4425674647, 'jorge.luis.perez@uaq.edu.mx', 'MSC'),
(567575, 'Julio Alejandro', 'Romero', 'González', 4425875678, 'julio.romero@uaq.mx', 'MCC'),
(567576, 'Hugo', 'Jiménez', 'Hernández', 4425875679, 'hugo.jimenez@uaq.mx', 'MCD'),
(567577, 'Diana Margarita', 'Córdova', 'Esparza', 4425875680, 'diana.cordova@uaq.mx', 'DCC'),
(567578, 'Sofía Amadis', 'Rivera', 'López', 4425875681, 'doctorado.te@uaq.mx', 'DTE'),
(567579, 'Teresa', 'García', 'Ramírez', 4425875682, 'ditevirtual@uaq.mx', 'DITE');

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
(456324, 'CHAVERO68', 'C'),
(567574, 'PEREZ47', 'C'),
(567575, 'ROMERO78', 'C'),
(567576, 'JIMENEZ79', 'C'),
(567577, 'CORDOVA80', 'C'),
(567578, 'RIVERA81', 'C'),
(567579, 'GARCIA82', 'C'),
(1234, 'IBARRA34', 'D'),
(12345, 'OLMOS45', 'D'),
(54321, 'GONZALES21', 'D'),
(4321, 'RUBALCABA21', 'D'),
(1, 'HERNANDEZ65', 'A'),
(2, 'GONZALES75', 'A'),
(3, 'GARCIA53', 'A'),
(4, 'DOMINGUEZ57', 'A'),
(5, 'VILLAREAL88', 'A'),
(6, 'BUENROSTRO28', 'A'),
(7, 'PAREDES36', 'A'),
(301612, 'AA87', 'A'),
(301574, 'GARCIA87', 'A'),
(114090, 'PAULIN90', 'D'),
(111111, 'PEREZ11', 'D'),
(222222, 'ESPINOZA22', 'D'),
(33333, 'DELGADO33', 'D'),
(444444, 'BLANKED44', 'D'),
(128976, 'SANTIAGO65', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_evaluaciones`
--

CREATE TABLE `detalle_evaluaciones` (
  `id_detalle` int(11) NOT NULL,
  `id_evaluacion` int(11) NOT NULL,
  `id_sinodo` bigint(20) NOT NULL,
  `calificacion` double NOT NULL,
  `observacion` varchar(500) DEFAULT NULL,
  `periodo` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_evaluaciones`
--

INSERT INTO `detalle_evaluaciones` (`id_detalle`, `id_evaluacion`, `id_sinodo`, `calificacion`, `observacion`, `periodo`) VALUES
(41, 25, 4321, 0, NULL, ''),
(42, 25, 1234, 10, 'Bien', '2024-2'),
(43, 25, 33333, 0, NULL, ''),
(44, 25, 54321, 0, NULL, ''),
(45, 28, 4321, 0, NULL, ''),
(46, 28, 1234, 10, 'ASDAD', '2025-2'),
(47, 28, 12345, 10, 'sdasda', '2025-2'),
(48, 28, 33333, 0, NULL, '');

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
(1234, 'ARTURO MAURICIO', 'IBARRA', 'CORONA', 'A'),
(4321, 'ERNESTO', 'RUBALCABA', 'DURAN', 'A'),
(12345, 'CARLOS ALBERTO', 'OLMOS', 'TREJO', 'A'),
(33333, 'SELENE', 'DELGADO', 'LOPEZ', 'A'),
(54321, 'FIDEL', 'GONZALES', 'GUTIERREZ', 'A'),
(111111, 'JORGE', 'PEREZ', 'RAMOS', 'A'),
(114090, 'JAVIER', 'PAULIN', 'MARTINEZ', 'A'),
(222222, 'JULIO', 'ESPINOZA', 'PAZ', 'A'),
(444444, 'HUGO', 'BLANKED', 'J', 'A');

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
(1, 'DIEGO', 'HERNANDEZ', 'SANCHEZ', 4424322665, 'Diego@hotmail.com', 'DITE'),
(2, 'SERGIO', 'GONZALES', 'LOPEZ', 4428768575, 'Sergio@hotmail.com', 'DTE'),
(3, 'JESUS', 'GARCIA', 'GONZALES', 4423253453, 'Jesus@hotmail.com', 'DCC'),
(4, 'VALERIA', 'DOMINGUEZ', 'CRUZ', 5527657657, 'Valeria@hotmail.com', 'MCC'),
(5, 'CARLA', 'VILLAREAL', 'SOLIS', 5525373688, 'Carla@hotmail.com', 'MCC'),
(6, 'ROSA', 'BUENROSTRO', 'FERNANDEZ', 5523738128, 'Rosa@hotmail.com', 'MSC'),
(7, 'ALEJANDRO', 'PAREDES', 'HUERTA', 8623862836, 'Alejandro@hotmail.com', 'MIEVEA'),
(114589, 'HANNA PAOLA', 'VELEZQUEZ', 'SUAREZ', 5985233178, 'h@gmail.com', 'MCC'),
(128976, 'VIRGINIA', 'SANTIAGO', 'PABLO', 4411200465, 'vicky_changuito@hotmail.com', 'MCC'),
(301574, 'JESUS', 'GARCIA', 'SANTIAGO', 4425562487, 'lyaretzi361@gmail.com', 'MCC'),
(301612, 'YARETZI', 'AA', '123123', 4425562487, 'lyaretzi361@gmail.com', 'MCC'),
(456789, 'SIUL FERNANDO', 'MARTINEZ', 'MANCERA', 9874561237, 's@gmail.com', 'DCC');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `id` int(11) NOT NULL,
  `exp_alumno` bigint(20) NOT NULL,
  `fecha_evaluacion` datetime NOT NULL,
  `cal_final` double NOT NULL,
  `aula` varchar(900) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evaluaciones`
--

INSERT INTO `evaluaciones` (`id`, `exp_alumno`, `fecha_evaluacion`, `cal_final`, `aula`) VALUES
(25, 1, '2024-11-19 17:37:00', 0, 'A13'),
(28, 2, '2025-02-19 19:22:00', 0, 'A10');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exp_alumno` (`exp_alumno`),
  ADD KEY `sinodo1` (`director`),
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
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_sinodo` (`id_sinodo`),
  ADD KEY `id_evaluacion` (`id_evaluacion`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `detalle_evaluaciones`
--
ALTER TABLE `detalle_evaluaciones`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD CONSTRAINT `asignaciones_ibfk_1` FOREIGN KEY (`exp_alumno`) REFERENCES `estudiantes` (`exp`),
  ADD CONSTRAINT `asignaciones_ibfk_2` FOREIGN KEY (`director`) REFERENCES `docentes` (`clave`),
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
