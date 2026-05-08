-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-05-2026 a las 22:19:58
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestionspabd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `duracion_servicio`
--

CREATE TABLE `duracion_servicio` (
  `id_Duracion_Servicio` int NOT NULL,
  `Tiempo_Duracion` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `duracion_servicio`
--

INSERT INTO `duracion_servicio` (`id_Duracion_Servicio`, `Tiempo_Duracion`) VALUES
(12, '01:10:00'),
(21, '00:30:00'),
(22, '01:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_Empleado` int NOT NULL,
  `Empleado_CBU` varchar(22) DEFAULT NULL,
  `id_Usuario` int NOT NULL,
  `id_Rol` int NOT NULL,
  `id_Persona` int NOT NULL,
  `CUIL` varchar(15) DEFAULT NULL,
  `Especialidad` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id_Empleado`, `Empleado_CBU`, `id_Usuario`, `id_Rol`, `id_Persona`, `CUIL`, `Especialidad`) VALUES
(1, '0011223344556677889900', 1, 2, 1, '27-12345678-9', 'Masajes'),
(11, '0', 56, 3, 1, '2346394033', 'ADMINISTRADOR'),
(12, '0', 58, 2, 2, '20213589448', 'Masajes'),
(13, NULL, 77, 3, 12, NULL, 'ADMINISTRADOR'),
(14, NULL, 84, 2, 13, '24556781239', 'Masajista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id_Paciente` int NOT NULL,
  `Observaciones_Paciente` varchar(45) DEFAULT NULL,
  `id_Usuario` int NOT NULL,
  `id_Persona` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`id_Paciente`, `Observaciones_Paciente`, `id_Usuario`, `id_Persona`) VALUES
(1, 'nudos en zona cervical', 56, 1),
(11, NULL, 78, 10),
(12, NULL, 83, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_Persona` int NOT NULL,
  `Persona_Nombre` varchar(25) NOT NULL,
  `Persona_Apellido` varchar(10) NOT NULL,
  `Persona_DNI` varchar(15) DEFAULT NULL,
  `Persona_Telefono` bigint DEFAULT NULL,
  `Persona_Domicilio` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Fecha_Nac` date DEFAULT NULL,
  `Persona_Descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_Persona`, `Persona_Nombre`, `Persona_Apellido`, `Persona_DNI`, `Persona_Telefono`, `Persona_Domicilio`, `Fecha_Nac`, `Persona_Descripcion`) VALUES
(1, 'Emanuel Alejandro', 'Ocampo', NULL, 1123456789, 'Av. Siempre Viva 742', NULL, NULL),
(2, 'Marcos', 'Gómez', NULL, 1134567890, 'Calle Belgrano 1200', NULL, NULL),
(3, 'Ana', 'Martínez', NULL, 1145678901, 'San Martín 455', NULL, NULL),
(4, 'Julián', 'Pérez', NULL, 1156789012, 'Rivadavia 2331', NULL, NULL),
(5, 'Sofía', 'López', NULL, 1167890123, 'Av. Córdoba 150', NULL, NULL),
(6, 'Camila', 'Rojas', NULL, 1178901234, 'Las Heras 880', NULL, NULL),
(7, 'Bruno', 'Castillo', NULL, 1189012345, 'Mitre 612', NULL, NULL),
(8, 'Valentina', 'Moreno', NULL, 1190123456, 'Pasaje Sarmiento 35', NULL, NULL),
(9, 'Ezequiel', 'Ramírez', NULL, 1122334455, 'Dorrego 1277', NULL, NULL),
(10, 'Nahuel', 'Sanchez', '12345678', 1133445566, 'Av. Lelong 208', '2005-03-12', 'Dolor en la zona baja de la espalda'),
(11, 'Paolo', 'Gomez', '66777444', 3704505050, 'Barrio la Floresta mz 44 cs 2', '2000-04-10', 'Dolor en el hombro'),
(12, 'Ramiro', 'Fleitas', '46394033', 12345678, 'Calle Falsa 123', NULL, NULL),
(13, 'Josefa', 'Paniagua', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_Rol` int NOT NULL,
  `Rol_Descripcion` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_Rol`, `Rol_Descripcion`) VALUES
(0, 'Paciente'),
(1, 'Recepcionista'),
(2, 'Terapeuta'),
(3, 'Admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `id_Servicio` int NOT NULL,
  `Nombre_servicio` varchar(45) NOT NULL,
  `Descripcion` varchar(250) DEFAULT NULL,
  `Costo` int DEFAULT NULL,
  `id_Duracion_Servicio` int DEFAULT NULL,
  `Estado` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id_Servicio`, `Nombre_servicio`, `Descripcion`, `Costo`, `id_Duracion_Servicio`, `Estado`) VALUES
(70, 'Masaje Descontracturante', 'Relaja músculos', 38000, NULL, NULL),
(80, 'Tratamiento Facial', 'eliminar células muertas, renovar la capa epidérmica y devolverle luminosidad y salud al rostro mediante el uso de productos cosmecéuticos y aparatología específica', 42000, NULL, NULL),
(81, 'Reflexología Podal', 'Terapia de presión en puntos específicos de los pies para liberar tensión.', 28000, NULL, NULL),
(82, 'Drenaje Linfático Manual', 'Técnica suave para reducir retención de líquidos y mejorar la circulación.', 47000, NULL, NULL),
(83, 'Circuito Hídrico', 'Acceso a sauna, hidromasaje, baño turco y áreas de relax.', 70000, NULL, NULL),
(84, 'Exfoliación Corporal', 'Pulido integral de la piel con sales o semillas para renovar células.', 35000, NULL, NULL),
(85, 'Fangoterapia Corporal', 'Aplicación de barros minerales con propiedades desintoxicantes.', 42000, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_empleado`
--

CREATE TABLE `servicio_empleado` (
  `id_Servicio_Empleado` int NOT NULL,
  `id_Servicio` int NOT NULL,
  `id_Empleado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `servicio_empleado`
--

INSERT INTO `servicio_empleado` (`id_Servicio_Empleado`, `id_Servicio`, `id_Empleado`) VALUES
(1, 70, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `id_Turno` int NOT NULL,
  `id_Paciente` int NOT NULL,
  `id_Empleado` int NOT NULL,
  `id_Servicio` int NOT NULL,
  `Fecha_Turno` date NOT NULL,
  `Hora_Turno` time NOT NULL,
  `Estado_Turno` varchar(20) DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`id_Turno`, `id_Paciente`, `id_Empleado`, `id_Servicio`, `Fecha_Turno`, `Hora_Turno`, `Estado_Turno`) VALUES
(7, 11, 1, 82, '2026-05-15', '20:35:00', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_Usuario` int NOT NULL,
  `Usuario_Nombre` varchar(15) NOT NULL,
  `Usuario_Contraseña` varchar(255) DEFAULT NULL,
  `Descripcion` varchar(250) DEFAULT NULL,
  `Fecha_Creacion` datetime DEFAULT NULL,
  `Correo_E` varchar(40) DEFAULT NULL,
  `id_Rol` int DEFAULT '0',
  `codigo_recuperacion` varchar(10) DEFAULT NULL,
  `codigo_expira` datetime DEFAULT NULL,
  `id_Persona` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_Usuario`, `Usuario_Nombre`, `Usuario_Contraseña`, `Descripcion`, `Fecha_Creacion`, `Correo_E`, `id_Rol`, `codigo_recuperacion`, `codigo_expira`, `id_Persona`) VALUES
(1, 'Alejandro', 'Thegamer17', NULL, NULL, 'Alejuemm@gmail.com', 0, NULL, NULL, NULL),
(56, 'ramiro', '12345', 'Admin', '2025-01-10 00:00:00', 'ramiro@gmail.com', 3, NULL, NULL, NULL),
(57, 'martina', 'm4rt1na!', 'Recepcionista', '2025-01-11 00:00:00', 'martina@gmail.com', 0, NULL, NULL, NULL),
(58, 'facundo', 'f4cund0', 'Terapeuta', '2025-01-12 00:00:00', 'facundo@hotmail.com', 2, NULL, NULL, NULL),
(75, 'Guada', 'pass1234', 'Admin', NULL, 'Guadex@gmail.com', 3, NULL, NULL, NULL),
(77, 'Rama04', '$2y$10$bR86RciXBjArVlmfmW/UPOg3.gl0CMQaxOPtUyUWfaRpI1lbUt7B2', NULL, '2026-05-01 17:38:45', 'ramirofleitasb@gmail.com', 3, NULL, NULL, NULL),
(78, 'Nahuelshz', '$2y$10$wyaxSY4H3T8vWNV9ZYSqYOOTsAUq46iH.gc7NjWJ7C1bzq1XjBBfC', NULL, '2026-05-01 19:52:41', 'nawel1414@gmail.com', 0, NULL, NULL, NULL),
(83, 'Paolo', '$2y$10$0g/jCxNMU0lOYPPSbxQprOZRXMcE9a1S3XsWC0.MeGSxJ27UTe7R6', NULL, '2026-05-04 01:17:13', 'Paolito123@gmail.com', 0, NULL, NULL, NULL),
(84, 'JosefaP', '$2y$10$VFvae7RCz5h3AD5wiwtSX.dca.a.76v7CDY7iWrUKlPF/gpNwEbEW', NULL, NULL, 'JosefaP@gmail.com', 2, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `duracion_servicio`
--
ALTER TABLE `duracion_servicio`
  ADD PRIMARY KEY (`id_Duracion_Servicio`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_Empleado`),
  ADD KEY `fk_Empleado_Persona` (`id_Persona`),
  ADD KEY `fk_Empleado_Rol` (`id_Rol`),
  ADD KEY `fk_Empleado_Usuario` (`id_Usuario`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_Paciente`),
  ADD KEY `fk_Paciente_Persona` (`id_Persona`),
  ADD KEY `fk_Paciente_Usuario` (`id_Usuario`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_Persona`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_Rol`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`id_Servicio`),
  ADD KEY `fk_Servicio_Duracion` (`id_Duracion_Servicio`);

--
-- Indices de la tabla `servicio_empleado`
--
ALTER TABLE `servicio_empleado`
  ADD PRIMARY KEY (`id_Servicio_Empleado`),
  ADD KEY `fk_SE_Servicio` (`id_Servicio`),
  ADD KEY `fk_SE_Empleado` (`id_Empleado`);

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`id_Turno`),
  ADD KEY `fk_Turno_Paciente` (`id_Paciente`),
  ADD KEY `fk_Turno_Empleado` (`id_Empleado`),
  ADD KEY `fk_Turno_Servicio` (`id_Servicio`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_Usuario`),
  ADD KEY `fk_usuario_rol` (`id_Rol`),
  ADD KEY `fk_usuario_persona` (`id_Persona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `duracion_servicio`
--
ALTER TABLE `duracion_servicio`
  MODIFY `id_Duracion_Servicio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_Empleado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_Paciente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_Persona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_Rol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_Servicio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `servicio_empleado`
--
ALTER TABLE `servicio_empleado`
  MODIFY `id_Servicio_Empleado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `id_Turno` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_Usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_Empleado_Persona` FOREIGN KEY (`id_Persona`) REFERENCES `persona` (`id_Persona`),
  ADD CONSTRAINT `fk_Empleado_Rol` FOREIGN KEY (`id_Rol`) REFERENCES `rol` (`id_Rol`),
  ADD CONSTRAINT `fk_Empleado_Usuario` FOREIGN KEY (`id_Usuario`) REFERENCES `usuario` (`id_Usuario`);

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `fk_Paciente_Persona` FOREIGN KEY (`id_Persona`) REFERENCES `persona` (`id_Persona`),
  ADD CONSTRAINT `fk_Paciente_Usuario` FOREIGN KEY (`id_Usuario`) REFERENCES `usuario` (`id_Usuario`);

--
-- Filtros para la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD CONSTRAINT `fk_Servicio_Duracion` FOREIGN KEY (`id_Duracion_Servicio`) REFERENCES `duracion_servicio` (`id_Duracion_Servicio`);

--
-- Filtros para la tabla `servicio_empleado`
--
ALTER TABLE `servicio_empleado`
  ADD CONSTRAINT `fk_SE_Empleado` FOREIGN KEY (`id_Empleado`) REFERENCES `empleado` (`id_Empleado`),
  ADD CONSTRAINT `fk_SE_Servicio` FOREIGN KEY (`id_Servicio`) REFERENCES `servicio` (`id_Servicio`);

--
-- Filtros para la tabla `turno`
--
ALTER TABLE `turno`
  ADD CONSTRAINT `fk_Turno_Empleado` FOREIGN KEY (`id_Empleado`) REFERENCES `empleado` (`id_Empleado`),
  ADD CONSTRAINT `fk_Turno_Paciente` FOREIGN KEY (`id_Paciente`) REFERENCES `paciente` (`id_Paciente`),
  ADD CONSTRAINT `fk_Turno_Servicio` FOREIGN KEY (`id_Servicio`) REFERENCES `servicio` (`id_Servicio`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_persona` FOREIGN KEY (`id_Persona`) REFERENCES `persona` (`id_Persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_Rol`) REFERENCES `rol` (`id_Rol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
