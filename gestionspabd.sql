-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 12-10-2026 a las 19:01:18
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
-- Estructura de tabla para la tabla `auditoria_turnos`
--

CREATE TABLE `auditoria_turnos` (
  `id` int NOT NULL,
  `id_turno` int NOT NULL,
  `id_usuario` int NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `dato_anterior` text,
  `dato_nuevo` text,
  `fecha_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_origen` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `auditoria_turnos`
--

INSERT INTO `auditoria_turnos` (`id`, `id_turno`, `id_usuario`, `modulo`, `accion`, `dato_anterior`, `dato_nuevo`, `fecha_hora`, `ip_origen`) VALUES
(1, 21, 97, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":20,\"id_Servicio\":84,\"id_Empleado\":14,\"Fecha\":\"2026-09-10\",\"Hora\":\"09:00\"}', '2026-09-03 00:38:14', '::1'),
(2, 21, 97, 'Turno', 'MODIFICAR', '{\"id_Turno\":21,\"id_Paciente\":20,\"id_Empleado\":14,\"id_Servicio\":84,\"Fecha_Turno\":\"2026-09-10\",\"Hora_Turno\":\"09:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Exfoliaci\\u00f3n Corporal\",\"Costo\":35000,\"Terapeuta_Nombre\":\"Josefa\",\"Terapeuta_Apellido\":\"Paniagua\"}', '{\"id_Turno\":21,\"id_Paciente\":20,\"id_Empleado\":14,\"id_Servicio\":84,\"Fecha_Turno\":\"2026-09-10\",\"Hora_Turno\":\"20:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Exfoliaci\\u00f3n Corporal\",\"Costo\":35000,\"Terapeuta_Nombre\":\"Josefa\",\"Terapeuta_Apellido\":\"Paniagua\"}', '2026-09-03 00:39:49', '::1'),
(3, 22, 97, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":20,\"id_Servicio\":84,\"id_Empleado\":18,\"Fecha\":\"2026-09-30\",\"Hora\":\"13:00\"}', '2026-09-04 05:07:26', '::1'),
(4, 23, 97, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":20,\"id_Servicio\":82,\"id_Empleado\":18,\"Fecha\":\"2026-09-28\",\"Hora\":\"20:00\"}', '2026-09-04 05:31:45', '::1'),
(5, 24, 97, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":20,\"id_Servicio\":82,\"id_Empleado\":18,\"Fecha\":\"2026-09-16\",\"Hora\":\"17:00\"}', '2026-09-04 05:59:22', '::1'),
(6, 22, 97, 'Turno', 'CANCELAR', '{\"id_Turno\":22,\"id_Paciente\":20,\"id_Empleado\":18,\"id_Servicio\":84,\"Fecha_Turno\":\"2026-09-30\",\"Hora_Turno\":\"13:00:00\",\"Estado_Turno\":\"Cancelado\"}', NULL, '2026-09-04 06:01:53', '::1'),
(7, 25, 102, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":24,\"id_Servicio\":82,\"id_Empleado\":15,\"Fecha\":\"2026-09-07\",\"Hora\":\"09:00\"}', '2026-09-04 23:02:21', '::1'),
(8, 25, 102, 'Turno', 'MODIFICAR', '{\"id_Turno\":25,\"id_Paciente\":24,\"id_Empleado\":15,\"id_Servicio\":82,\"Fecha_Turno\":\"2026-09-07\",\"Hora_Turno\":\"09:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Drenaje Linf\\u00e1tico Manual\",\"Costo\":47000,\"Terapeuta_Nombre\":\"Saturnina\",\"Terapeuta_Apellido\":\"Culasso\"}', '{\"id_Turno\":25,\"id_Paciente\":24,\"id_Empleado\":15,\"id_Servicio\":82,\"Fecha_Turno\":\"2026-09-08\",\"Hora_Turno\":\"09:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Drenaje Linf\\u00e1tico Manual\",\"Costo\":47000,\"Terapeuta_Nombre\":\"Saturnina\",\"Terapeuta_Apellido\":\"Culasso\"}', '2026-09-04 23:02:42', '::1'),
(9, 26, 103, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":25,\"id_Servicio\":70,\"id_Empleado\":16,\"Fecha\":\"2026-09-10\",\"Hora\":\"14:00\"}', '2026-09-04 23:04:38', '::1'),
(10, 26, 103, 'Turno', 'MODIFICAR', '{\"id_Turno\":26,\"id_Paciente\":25,\"id_Empleado\":16,\"id_Servicio\":70,\"Fecha_Turno\":\"2026-09-10\",\"Hora_Turno\":\"14:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Masaje Descontracturante\",\"Costo\":38000,\"Terapeuta_Nombre\":\"Bianca\",\"Terapeuta_Apellido\":\"Ramirez\"}', '{\"id_Turno\":26,\"id_Paciente\":25,\"id_Empleado\":16,\"id_Servicio\":70,\"Fecha_Turno\":\"2026-09-09\",\"Hora_Turno\":\"15:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Masaje Descontracturante\",\"Costo\":38000,\"Terapeuta_Nombre\":\"Bianca\",\"Terapeuta_Apellido\":\"Ramirez\"}', '2026-09-04 23:05:04', '::1'),
(11, 27, 104, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":26,\"id_Servicio\":80,\"id_Empleado\":12,\"Fecha\":\"2026-09-07\",\"Hora\":\"19:00\"}', '2026-09-04 23:07:06', '::1'),
(12, 27, 104, 'Turno', 'MODIFICAR', '{\"id_Turno\":27,\"id_Paciente\":26,\"id_Empleado\":12,\"id_Servicio\":80,\"Fecha_Turno\":\"2026-09-07\",\"Hora_Turno\":\"19:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Tratamiento Facial\",\"Costo\":42000,\"Terapeuta_Nombre\":\"Marcos\",\"Terapeuta_Apellido\":\"G\\u00f3mez\"}', '{\"id_Turno\":27,\"id_Paciente\":26,\"id_Empleado\":12,\"id_Servicio\":80,\"Fecha_Turno\":\"2026-09-11\",\"Hora_Turno\":\"19:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Tratamiento Facial\",\"Costo\":42000,\"Terapeuta_Nombre\":\"Marcos\",\"Terapeuta_Apellido\":\"G\\u00f3mez\"}', '2026-09-04 23:07:22', '::1'),
(13, 28, 105, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":27,\"id_Servicio\":84,\"id_Empleado\":15,\"Fecha\":\"2026-09-16\",\"Hora\":\"16:00\"}', '2026-09-04 23:10:36', '::1'),
(14, 29, 106, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":28,\"id_Servicio\":70,\"id_Empleado\":14,\"Fecha\":\"2026-09-09\",\"Hora\":\"17:00\"}', '2026-09-04 23:15:30', '::1'),
(15, 30, 107, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":29,\"id_Servicio\":70,\"id_Empleado\":18,\"Fecha\":\"2026-09-07\",\"Hora\":\"12:00\"}', '2026-09-04 23:19:43', '::1'),
(16, 30, 107, 'Turno', 'MODIFICAR', '{\"id_Turno\":30,\"id_Paciente\":29,\"id_Empleado\":18,\"id_Servicio\":70,\"Fecha_Turno\":\"2026-09-07\",\"Hora_Turno\":\"12:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Masaje Descontracturante\",\"Costo\":38000,\"Terapeuta_Nombre\":\"Elia Juliana\",\"Terapeuta_Apellido\":\"Garcia\"}', '{\"id_Turno\":30,\"id_Paciente\":29,\"id_Empleado\":18,\"id_Servicio\":70,\"Fecha_Turno\":\"2026-09-11\",\"Hora_Turno\":\"12:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Masaje Descontracturante\",\"Costo\":38000,\"Terapeuta_Nombre\":\"Elia Juliana\",\"Terapeuta_Apellido\":\"Garcia\"}', '2026-09-04 23:20:11', '::1'),
(17, 31, 108, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":30,\"id_Servicio\":84,\"id_Empleado\":15,\"Fecha\":\"2026-09-25\",\"Hora\":\"15:00\"}', '2026-09-04 23:26:33', '::1'),
(18, 31, 108, 'Turno', 'CANCELAR', '{\"id_Turno\":31,\"id_Paciente\":30,\"id_Empleado\":15,\"id_Servicio\":84,\"Fecha_Turno\":\"2026-09-25\",\"Hora_Turno\":\"15:00:00\",\"Estado_Turno\":\"Pendiente\"}', NULL, '2026-09-04 23:26:44', '::1'),
(19, 32, 108, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":30,\"id_Servicio\":84,\"id_Empleado\":15,\"Fecha\":\"2026-09-23\",\"Hora\":\"15:00\"}', '2026-09-04 23:27:08', '::1'),
(20, 33, 109, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":31,\"id_Servicio\":80,\"id_Empleado\":14,\"Fecha\":\"2026-09-21\",\"Hora\":\"17:00\"}', '2026-09-04 23:28:53', '::1'),
(21, 33, 109, 'Turno', 'CANCELAR', '{\"id_Turno\":33,\"id_Paciente\":31,\"id_Empleado\":14,\"id_Servicio\":80,\"Fecha_Turno\":\"2026-09-21\",\"Hora_Turno\":\"17:00:00\",\"Estado_Turno\":\"Pendiente\"}', NULL, '2026-09-04 23:29:04', '::1'),
(22, 34, 109, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":31,\"id_Servicio\":80,\"id_Empleado\":14,\"Fecha\":\"2026-09-28\",\"Hora\":\"19:00\"}', '2026-09-04 23:29:28', '::1'),
(23, 35, 110, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":32,\"id_Servicio\":84,\"id_Empleado\":16,\"Fecha\":\"2026-09-07\",\"Hora\":\"18:00\"}', '2026-09-04 23:34:08', '::1'),
(24, 35, 110, 'Turno', 'MODIFICAR', '{\"id_Turno\":35,\"id_Paciente\":32,\"id_Empleado\":16,\"id_Servicio\":84,\"Fecha_Turno\":\"2026-09-07\",\"Hora_Turno\":\"18:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Exfoliaci\\u00f3n Corporal\",\"Costo\":35000,\"Terapeuta_Nombre\":\"Bianca\",\"Terapeuta_Apellido\":\"Ramirez\"}', '{\"id_Turno\":35,\"id_Paciente\":32,\"id_Empleado\":12,\"id_Servicio\":84,\"Fecha_Turno\":\"2026-09-07\",\"Hora_Turno\":\"18:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Exfoliaci\\u00f3n Corporal\",\"Costo\":35000,\"Terapeuta_Nombre\":\"Marcos\",\"Terapeuta_Apellido\":\"G\\u00f3mez\"}', '2026-09-04 23:34:33', '::1'),
(25, 36, 111, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":33,\"id_Servicio\":80,\"id_Empleado\":18,\"Fecha\":\"2026-09-11\",\"Hora\":\"15:00\"}', '2026-09-04 23:37:06', '::1'),
(26, 36, 111, 'Turno', 'MODIFICAR', '{\"id_Turno\":36,\"id_Paciente\":33,\"id_Empleado\":18,\"id_Servicio\":80,\"Fecha_Turno\":\"2026-09-11\",\"Hora_Turno\":\"15:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Tratamiento Facial\",\"Costo\":42000,\"Terapeuta_Nombre\":\"Elia Juliana\",\"Terapeuta_Apellido\":\"Garcia\"}', '{\"id_Turno\":36,\"id_Paciente\":33,\"id_Empleado\":18,\"id_Servicio\":80,\"Fecha_Turno\":\"2026-09-11\",\"Hora_Turno\":\"18:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Tratamiento Facial\",\"Costo\":42000,\"Terapeuta_Nombre\":\"Elia Juliana\",\"Terapeuta_Apellido\":\"Garcia\"}', '2026-09-04 23:37:21', '::1'),
(27, 37, 112, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":34,\"id_Servicio\":70,\"id_Empleado\":16,\"Fecha\":\"2026-09-10\",\"Hora\":\"07:00\"}', '2026-09-04 23:39:25', '::1'),
(28, 25, 102, 'Turno', 'MODIFICAR', '{\"id_Turno\":25,\"id_Paciente\":24,\"id_Empleado\":15,\"id_Servicio\":82,\"Fecha_Turno\":\"2026-09-08\",\"Hora_Turno\":\"09:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Drenaje Linf\\u00e1tico Manual\",\"Costo\":47000,\"Terapeuta_Nombre\":\"Saturnina\",\"Terapeuta_Apellido\":\"Culasso\"}', '{\"id_Turno\":25,\"id_Paciente\":24,\"id_Empleado\":12,\"id_Servicio\":85,\"Fecha_Turno\":\"2026-09-08\",\"Hora_Turno\":\"17:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Fangoterapia Corporal\",\"Costo\":42000,\"Terapeuta_Nombre\":\"Marcos\",\"Terapeuta_Apellido\":\"G\\u00f3mez\"}', '2026-09-04 23:40:34', '::1'),
(29, 38, 113, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":35,\"id_Servicio\":84,\"id_Empleado\":12,\"Fecha\":\"2026-09-10\",\"Hora\":\"07:00\"}', '2026-09-04 23:42:51', '::1'),
(30, 38, 113, 'Turno', 'CANCELAR', '{\"id_Turno\":38,\"id_Paciente\":35,\"id_Empleado\":12,\"id_Servicio\":84,\"Fecha_Turno\":\"2026-09-10\",\"Hora_Turno\":\"07:00:00\",\"Estado_Turno\":\"Pendiente\"}', NULL, '2026-09-04 23:43:07', '::1'),
(31, 39, 113, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":35,\"id_Servicio\":84,\"id_Empleado\":12,\"Fecha\":\"2026-09-10\",\"Hora\":\"17:00\"}', '2026-09-04 23:43:26', '::1'),
(32, 40, 114, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":36,\"id_Servicio\":70,\"id_Empleado\":12,\"Fecha\":\"2026-09-07\",\"Hora\":\"07:00\"}', '2026-09-04 23:47:59', '::1'),
(33, 41, 108, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":30,\"id_Servicio\":81,\"id_Empleado\":19,\"Fecha\":\"2026-09-17\",\"Hora\":\"17:00\"}', '2026-09-04 23:53:23', '::1'),
(34, 42, 104, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":26,\"id_Servicio\":83,\"id_Empleado\":19,\"Fecha\":\"2026-09-10\",\"Hora\":\"07:00\"}', '2026-09-04 23:54:25', '::1'),
(35, 42, 104, 'Turno', 'CANCELAR', '{\"id_Turno\":42,\"id_Paciente\":26,\"id_Empleado\":19,\"id_Servicio\":83,\"Fecha_Turno\":\"2026-09-10\",\"Hora_Turno\":\"07:00:00\",\"Estado_Turno\":\"Pendiente\"}', NULL, '2026-09-04 23:54:38', '::1'),
(36, 43, 106, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":28,\"id_Servicio\":83,\"id_Empleado\":19,\"Fecha\":\"2026-09-10\",\"Hora\":\"20:00\"}', '2026-09-04 23:56:15', '::1'),
(37, 44, 106, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":28,\"id_Servicio\":80,\"id_Empleado\":19,\"Fecha\":\"2026-09-11\",\"Hora\":\"08:00\"}', '2026-09-04 23:56:46', '::1'),
(38, 40, 114, 'Turno', 'MODIFICAR', '{\"id_Turno\":40,\"id_Paciente\":36,\"id_Empleado\":12,\"id_Servicio\":70,\"Fecha_Turno\":\"2026-09-07\",\"Hora_Turno\":\"07:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Masaje Descontracturante\",\"Costo\":38000,\"Terapeuta_Nombre\":\"Marcos\",\"Terapeuta_Apellido\":\"G\\u00f3mez\"}', '{\"id_Turno\":40,\"id_Paciente\":36,\"id_Empleado\":12,\"id_Servicio\":70,\"Fecha_Turno\":\"2026-09-30\",\"Hora_Turno\":\"07:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Masaje Descontracturante\",\"Costo\":38000,\"Terapeuta_Nombre\":\"Marcos\",\"Terapeuta_Apellido\":\"G\\u00f3mez\"}', '2026-09-15 18:26:39', '::1'),
(39, 45, 116, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":37,\"id_Servicio\":70,\"id_Empleado\":15,\"Fecha\":\"2026-09-24\",\"Hora\":\"16:00\"}', '2026-09-15 22:46:45', '::1'),
(40, 46, 117, 'Turno', 'CREAR', NULL, '{\"id_Paciente\":38,\"id_Servicio\":82,\"id_Empleado\":15,\"Fecha\":\"2026-09-17\",\"Hora\":\"17:00\"}', '2026-09-16 01:08:49', '::1'),
(41, 46, 117, 'Turno', 'MODIFICAR', '{\"id_Turno\":46,\"id_Paciente\":38,\"id_Empleado\":15,\"id_Servicio\":82,\"Fecha_Turno\":\"2026-09-17\",\"Hora_Turno\":\"17:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Drenaje Linf\\u00e1tico Manual\",\"Costo\":47000,\"Terapeuta_Nombre\":\"Saturnina\",\"Terapeuta_Apellido\":\"Culasso\"}', '{\"id_Turno\":46,\"id_Paciente\":38,\"id_Empleado\":15,\"id_Servicio\":82,\"Fecha_Turno\":\"2026-09-17\",\"Hora_Turno\":\"19:00:00\",\"Estado_Turno\":\"Pendiente\",\"Nombre_servicio\":\"Drenaje Linf\\u00e1tico Manual\",\"Costo\":47000,\"Terapeuta_Nombre\":\"Saturnina\",\"Terapeuta_Apellido\":\"Culasso\"}', '2026-09-16 01:09:16', '::1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ayuda_config`
--

CREATE TABLE `ayuda_config` (
  `clave` varchar(40) NOT NULL,
  `valor` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ayuda_config`
--

INSERT INTO `ayuda_config` (`clave`, `valor`) VALUES
('direccion', 'Calle Ejemplo 123, Formosa'),
('email', 'contacto@beautyspa.com'),
('facebook', 'https://www.facebook.com/'),
('horario', 'Lun a Vie · 9:00 a 19:00'),
('instagram', 'https://www.instagram.com/'),
('telefono', '+54 370 000-0000'),
('whatsapp', '543700000000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ayuda_faq`
--

CREATE TABLE `ayuda_faq` (
  `id_Faq` int NOT NULL,
  `Pregunta` varchar(200) NOT NULL,
  `Respuesta` text NOT NULL,
  `Orden` int NOT NULL DEFAULT '0',
  `Activo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ayuda_faq`
--

INSERT INTO `ayuda_faq` (`id_Faq`, `Pregunta`, `Respuesta`, `Orden`, `Activo`) VALUES
(1, '¿Cómo reservo un turno?', 'Iniciá sesión y presioná “Reserva tu cita” en la página de inicio. Elegí el día y el horario disponibles, y confirmá.', 1, 1),
(2, '¿Puedo cancelar o cambiar mi turno?', 'Sí. Entrá a la sección de tus citas, buscá el turno y cancelalo desde ahí. Si necesitás cambiarlo, cancelalo y reservá uno nuevo.', 2, 1),
(3, 'Olvidé mi contraseña, ¿qué hago?', 'En la pantalla de inicio de sesión elegí la opción para recuperarla. Te enviaremos un correo con las instrucciones.', 3, 1),
(4, '¿Cuáles son los horarios de atención?', 'Atendemos de lunes a viernes de 9:00 a 19:00. Los fines de semana no hay turnos disponibles.', 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dias_bloqueados`
--

CREATE TABLE `dias_bloqueados` (
  `id_Bloqueo` int NOT NULL,
  `id_Empleado` int NOT NULL,
  `Fecha_Bloqueada` date NOT NULL,
  `Motivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(14, NULL, 84, 2, 13, '24556781239', 'Masajista'),
(15, NULL, 88, 2, 15, '', 'Esteticista'),
(16, NULL, 91, 2, 18, '', 'Masajista'),
(18, NULL, 100, 2, 27, '', 'Masajista'),
(19, NULL, 115, 2, 42, '', 'Esteticista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad_empleado`
--

CREATE TABLE `especialidad_empleado` (
  `id_Especialidad` int NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `especialidad_empleado`
--

INSERT INTO `especialidad_empleado` (`id_Especialidad`, `nombre`) VALUES
(2, 'Esteticista'),
(1, 'Fisioterapeuta'),
(3, 'Masajista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id_Modulo` int NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` varchar(255) DEFAULT '',
  `Ruta` varchar(255) NOT NULL,
  `Icono` varchar(50) DEFAULT 'bx-grid-alt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`id_Modulo`, `Nombre`, `Descripcion`, `Ruta`, `Icono`) VALUES
(1, 'Perfil', 'Ver y editar perfil', '/Spaa/Views/perfil.php', 'bx-user'),
(2, 'Turnos', 'Calendario de turnos', '/Spaa/Controllers/TurnoController.php', 'bx-calendar'),
(3, 'Mis Citas', 'Ver turnos/citas asignadas', '/Spaa/Controllers/CitasController.php', 'bx-calendar-check'),
(4, 'Gestión de Usuarios', 'Administrar usuarios', '/Spaa/Controllers/admin_usuariosController.php', 'bx-cog'),
(5, 'Asignación de Módulos', 'Configurar módulos por rol', '/Spaa/Controllers/ModulosController.php', 'bx-customize'),
(6, 'Auditoría', 'Registro de cambios y acciones sobre los turnos', '/Spaa/Turno/AuditoriaController.php', 'bx-history'),
(7, 'Reportes y Gráficos', 'Ver gráfico de servicios más solicitados', '/Spaa/Graficos/ReporteController.php', 'bx-bar-chart-alt-2'),
(8, 'Ayuda', 'Preguntas frecuentes, contacto y redes sociales', '/Spaa/Ayuda/AyudaController.php', 'bx-help-circle');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo_rol`
--

CREATE TABLE `modulo_rol` (
  `id_Modulo_Rol` int NOT NULL,
  `id_Modulo` int NOT NULL,
  `id_Rol` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `modulo_rol`
--

INSERT INTO `modulo_rol` (`id_Modulo_Rol`, `id_Modulo`, `id_Rol`) VALUES
(44, 1, 0),
(47, 1, 2),
(45, 2, 0),
(48, 2, 2),
(46, 3, 0),
(49, 3, 2),
(50, 4, 3),
(51, 5, 3),
(52, 6, 3),
(53, 7, 3),
(54, 8, 3);

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
(12, NULL, 83, 11),
(13, NULL, 87, 14),
(14, NULL, 89, 16),
(15, NULL, 90, 17),
(16, NULL, 92, 19),
(17, NULL, 93, 20),
(18, NULL, 94, 21),
(19, NULL, 96, 23),
(20, NULL, 97, 24),
(21, NULL, 98, 25),
(22, NULL, 99, 26),
(23, NULL, 101, 28),
(24, NULL, 102, 29),
(25, NULL, 103, 30),
(26, NULL, 104, 31),
(27, NULL, 105, 32),
(28, NULL, 106, 33),
(29, NULL, 107, 34),
(30, NULL, 108, 35),
(31, NULL, 109, 36),
(32, NULL, 110, 37),
(33, NULL, 111, 38),
(34, NULL, 112, 39),
(35, NULL, 113, 40),
(36, NULL, 114, 41),
(37, NULL, 116, 43),
(38, NULL, 117, 44),
(39, NULL, 118, 45);

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
(13, 'Josefa', 'Paniagua', NULL, NULL, NULL, NULL, NULL),
(14, 'Diana', 'Fleitas', '34567123', 3704282828, 'asdasd', '1998-03-13', ''),
(15, 'Saturnina', 'Culasso', NULL, NULL, NULL, NULL, NULL),
(16, 'Inocencio', 'Alcides', NULL, NULL, NULL, NULL, NULL),
(17, 'ramoncito10', 'sandoval', '12345678', 3704765435, 'Av. Siempre Viva 790', '2005-02-09', ''),
(18, 'Bianca', 'Ramirez', NULL, NULL, NULL, NULL, NULL),
(19, 'guadita', '', NULL, NULL, NULL, NULL, NULL),
(20, 'penelope', '', NULL, NULL, NULL, NULL, NULL),
(21, 'Fabricio', 'Rodriguez', '51618282', 3282373819, '', '1999-02-11', ''),
(23, 'Mariela', 'Palacios', NULL, NULL, NULL, NULL, NULL),
(24, 'Pedro', 'Paqueta', NULL, NULL, NULL, NULL, NULL),
(25, 'mateo', '', NULL, NULL, NULL, NULL, NULL),
(26, 'oficial', '', NULL, NULL, NULL, NULL, NULL),
(27, 'Elia Juliana', 'Garcia', NULL, NULL, NULL, NULL, NULL),
(28, 'gimena', '', NULL, NULL, NULL, NULL, NULL),
(29, 'santiago cesar david', 'mora', NULL, NULL, NULL, NULL, NULL),
(30, 'gonzalo', 'gauna', NULL, NULL, NULL, NULL, NULL),
(31, 'lujan', 'medina', NULL, NULL, NULL, NULL, NULL),
(32, 'marcos ivan', 'kayser', NULL, NULL, NULL, NULL, NULL),
(33, 'patricio javier', 'sosa', NULL, NULL, NULL, NULL, NULL),
(34, 'pancracio miguel', 'romero', NULL, NULL, NULL, NULL, NULL),
(35, 'lucia', 'davis', NULL, NULL, NULL, NULL, NULL),
(36, 'tobias', 'almiron', NULL, NULL, NULL, NULL, NULL),
(37, 'alan sebastian', 'ramirez', NULL, NULL, NULL, NULL, NULL),
(38, 'alexander', 'alarcon', NULL, NULL, NULL, NULL, NULL),
(39, 'facundo', 'esquivel', NULL, NULL, NULL, NULL, NULL),
(40, 'exequiel', 'gonzalez', NULL, NULL, NULL, NULL, NULL),
(41, 'ruperto', 'ruperto', NULL, NULL, NULL, NULL, NULL),
(42, 'lucia', 'davis', NULL, NULL, NULL, NULL, NULL),
(43, 'amena', '', NULL, NULL, NULL, NULL, NULL),
(44, 'santiago0', '', NULL, NULL, NULL, NULL, NULL),
(45, 'Ucliente1', '', NULL, NULL, NULL, NULL, NULL);

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
(9, 11, 15, 80, '2026-06-12', '20:00:00', 'Pendiente'),
(10, 15, 12, 81, '2026-06-22', '17:00:00', 'Cancelado'),
(11, 15, 12, 83, '2026-06-23', '09:00:00', 'Pendiente'),
(14, 18, 12, 82, '2026-06-25', '18:00:00', 'Pendiente'),
(15, 17, 12, 84, '2026-06-18', '12:00:00', 'Pendiente'),
(16, 22, 14, 83, '2026-07-03', '17:00:00', 'Pendiente'),
(17, 22, 16, 85, '2026-07-23', '09:00:00', 'Pendiente'),
(18, 20, 15, 83, '2026-08-27', '17:00:00', 'Cancelado'),
(19, 20, 12, 83, '2026-09-04', '20:00:00', 'Cancelado'),
(20, 20, 16, 80, '2026-09-07', '19:00:00', 'Cancelado'),
(21, 20, 14, 84, '2026-09-10', '20:00:00', 'Cancelado'),
(22, 20, 18, 84, '2026-09-30', '13:00:00', 'Cancelado'),
(23, 20, 18, 82, '2026-09-28', '20:00:00', 'Cancelado'),
(24, 20, 18, 82, '2026-09-16', '17:00:00', 'Pendiente'),
(25, 24, 12, 85, '2026-09-08', '17:00:00', 'Pendiente'),
(26, 25, 16, 70, '2026-09-09', '15:00:00', 'Pendiente'),
(27, 26, 12, 80, '2026-09-11', '19:00:00', 'Pendiente'),
(28, 27, 15, 84, '2026-09-16', '16:00:00', 'Pendiente'),
(29, 28, 14, 70, '2026-09-09', '17:00:00', 'Pendiente'),
(30, 29, 18, 70, '2026-09-11', '12:00:00', 'Pendiente'),
(31, 30, 15, 84, '2026-09-25', '15:00:00', 'Cancelado'),
(32, 30, 15, 84, '2026-09-23', '15:00:00', 'Pendiente'),
(33, 31, 14, 80, '2026-09-21', '17:00:00', 'Cancelado'),
(34, 31, 14, 80, '2026-09-28', '19:00:00', 'Pendiente'),
(35, 32, 12, 84, '2026-09-07', '18:00:00', 'Pendiente'),
(36, 33, 18, 80, '2026-09-11', '18:00:00', 'Pendiente'),
(37, 34, 16, 70, '2026-09-10', '07:00:00', 'Pendiente'),
(38, 35, 12, 84, '2026-09-10', '07:00:00', 'Cancelado'),
(39, 35, 12, 84, '2026-09-10', '17:00:00', 'Pendiente'),
(40, 36, 12, 70, '2026-09-30', '07:00:00', 'Pendiente'),
(41, 30, 19, 81, '2026-09-17', '17:00:00', 'Pendiente'),
(42, 26, 19, 83, '2026-09-10', '07:00:00', 'Cancelado'),
(43, 28, 19, 83, '2026-09-10', '20:00:00', 'Pendiente'),
(44, 28, 19, 80, '2026-09-11', '08:00:00', 'Pendiente'),
(45, 37, 15, 70, '2026-09-24', '16:00:00', 'Pendiente'),
(46, 38, 15, 82, '2026-09-17', '19:00:00', 'Pendiente');

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
  `id_Persona` int DEFAULT NULL,
  `Estado` varchar(20) DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_Usuario`, `Usuario_Nombre`, `Usuario_Contraseña`, `Descripcion`, `Fecha_Creacion`, `Correo_E`, `id_Rol`, `codigo_recuperacion`, `codigo_expira`, `id_Persona`, `Estado`) VALUES
(1, 'Alejandro', '$2y$10$sULPuTck.Go5Ry0lNG3JeOdIlGmKt.rWfmglFugJddRdIlqA/TO7C', NULL, NULL, 'Alejuemm@gmail.com', 0, NULL, NULL, NULL, 'Inactivo'),
(56, 'ramiro', '$2y$10$5FTIx1uWqZdVfXgWmlrD.ug2RLWcZz7asxD/dJDb03AhbHf28lBiO', 'Admin', '2025-01-10 00:00:00', 'ramiro@gmail.com', 3, NULL, NULL, NULL, 'Inactivo'),
(57, 'martina', '$2y$10$NZiycZvl0FMna2ZXisO/4.tLxOuxRIyT2qehBxmJXVhSkuou4eD2a', 'Recepcionista', '2025-01-11 00:00:00', 'martina@gmail.com', 0, NULL, NULL, NULL, 'Inactivo'),
(58, 'facundo', '$2y$10$4BNUBHR2GwzqMd2I6Sauku0Y8l.8MvqXIGAcVl3uXtuZ5L1RQYJ0u', 'Terapeuta', '2025-01-12 00:00:00', 'facundo@hotmail.com', 2, NULL, NULL, NULL, 'Activo'),
(75, 'Guada', '$2y$10$/ELhIZdC86.YL7Se9sOaPuqbSzxHN6h5mwRSBm3jLo06Bk6OI/CDG', 'Admin', NULL, 'Guadex@gmail.com', 3, NULL, NULL, NULL, 'Activo'),
(77, 'Rama04', '$2y$10$PeSd87vPdRsb7YMo1cm.Eu28dra5abg3luJs0tlgrHNAC3XPCmilC', NULL, '2026-05-01 17:38:45', 'ramirofleitasb@gmail.com', 3, NULL, NULL, NULL, 'Activo'),
(78, 'Nahuelshz', '$2y$10$wyaxSY4H3T8vWNV9ZYSqYOOTsAUq46iH.gc7NjWJ7C1bzq1XjBBfC', NULL, '2026-05-01 19:52:41', 'nawel1414@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(83, 'Paolo', '$2y$10$0g/jCxNMU0lOYPPSbxQprOZRXMcE9a1S3XsWC0.MeGSxJ27UTe7R6', NULL, '2026-05-04 01:17:13', 'Paolito123@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(84, 'JosefaP', '$2y$10$VFvae7RCz5h3AD5wiwtSX.dca.a.76v7CDY7iWrUKlPF/gpNwEbEW', NULL, NULL, 'JosefaP@gmail.com', 2, NULL, NULL, NULL, 'Inactivo'),
(87, 'Diana', '$2y$10$yAOM4K5L44t1ZC0Bi/P.yeveBPllYVBTLFyYRHgbzxpEl2YsjZs9.', NULL, '2026-06-07 18:36:25', 'dianahola123@gmail.com', 0, NULL, NULL, NULL, 'Inactivo'),
(88, 'Saturninaa', '$2y$10$9QSu.Dhk963gxdNm6mZBu.tDwjjbg5M4N42rt5FbPQkLZsLwur7R2', NULL, NULL, 'saturnina@gmail.com', 2, NULL, NULL, NULL, 'Activo'),
(89, 'InoAlcides', '$2y$10$PbH8JBotUT4ypA84uzLHc.tEndXBaJvDEmbvXOYdEVKLepMWuR1qK', NULL, NULL, 'Inocencio@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(90, 'ramoncito10', '$2y$10$04qpeFaK3n/PRzwLNGsRLe9P5GLWbvjGDpOC.t3ktfsNSx7Avba0.', NULL, '2026-06-07 20:39:18', 'ramoncito@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(91, 'bianquis', '$2y$10$F5FI8dbJCyfwQPaPduRUYe1jbeaD59.imjdsFG.BVqaL5TnGiOfcO', NULL, NULL, 'biancaramirez2026@gmail.com', 2, NULL, NULL, NULL, 'Activo'),
(92, 'guadita', '$2y$10$9bTFn78hD4ClF6lJ15oE0eJyB2UeaaIPcchOmg0dnhgrsYqC0yTF2', NULL, '2026-06-07 21:30:07', 'Milanesasdepollo584@gmail.com', 0, '4540', '2026-06-08 02:40:24', NULL, 'Activo'),
(93, 'penelope', '$2y$10$z4hcpc/wSAbjxBfCCXuUlehBgtjutpNj8UYyvvsWvOt4XH7bYAonG', NULL, '2026-06-07 21:32:24', 'villalbagarciaguadalupe@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(94, 'carmela', '$2y$10$cLqUXS.kTh5t1XJTf7mtceA5SvvWpWFJgpuz5avYXcy62gYE.95Pu', NULL, '2026-06-07 22:52:25', 'fabrirodrig16@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(96, 'MarielaP', '$2y$10$PojZ596ZMDRANabepwghlOeux5JCfNPFBRUM9wAUTyIS3qkRAWGHG', NULL, NULL, 'marielap@gmail.com', 0, NULL, NULL, NULL, 'Inactivo'),
(97, 'PedroP', '$2y$10$j5tQJifHEm.v7ZxNDA0AKe9TWAZYc0o98/9w64TrDAUVtflNOsbKC', NULL, NULL, 'Pedro123@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(98, 'mateo', '$2y$10$KdnaE2rtpRqAO4R4V1isUeHynVqPN6KtNqNTmXxx/2qUZgIGjJVZe', NULL, '2026-06-28 22:42:07', 'mateogvbh@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(99, 'oficial', '$2y$10$WpscikjiBP2fSCK7j0ogeeVV85nsk1eVSty2v5subIfwEpgiDGNYe', NULL, '2026-06-29 00:54:20', 'oficialpaciente@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(100, 'Juli', '$2y$10$NUZIuY9D92eNZBIIOAzmouRaRfv1b8IjDQx.eztrXFae/QvAERUcK', NULL, NULL, 'garciaeliajuliana@gmail.com', 2, NULL, NULL, NULL, 'Activo'),
(101, 'gimena', '$2y$10$V/SgXk8E2mIwjzWI3Qk5SeB3TJ1aCB412CcmiXbrokZvZo6D74B9.', NULL, '2026-09-04 19:28:43', 'gimenarodriguez@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(102, 'santiago123', '$2y$10$5RGDAHECrskVBaXE7eyE5Of80sVV1.TX3CeoKHrgcHUxz2JPQBWNG', NULL, NULL, 'scdm0407@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(103, 'gonzalo123', '$2y$10$rnVPgK9dS4KZ9LqpiKSj3.tTSbxHdb/bNMGRaYSxIdD9evGXRNO7e', NULL, NULL, 'gg10exequiel@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(104, 'lujan123', '$2y$10$ud4UcbHQxDQSwpXTKWINSuwTfTreuQUNudPyI4OWDc9YzQTNhSouW', NULL, NULL, 'lub899176@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(105, 'marcos124', '$2y$10$DlXSHZZYhDxBYflZdPL/IO456rK/xR/OcwNhp16oMMpiXsYQX6feG', NULL, NULL, 'maquitosk05@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(106, 'patricio123', '$2y$10$j2IvHOdtjJXZkmxDSIrZY.X.uIkrV8KWQVqydbzymnho1pOtuYiOe', NULL, NULL, 'sosapatricio2025@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(107, 'miguelindo', '$2y$10$/J/doOKTHe.uN5tkn/aB2.sVcdYs7oXWT.SzpyefkG7mGhvXXtRlS', NULL, NULL, 'miguelangelromero2o1553@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(108, 'lucy', '$2y$10$mVe7uPCe3AYDLaWtWtetDeExSRIanqm1a9OJty5Is5O5Zz4S6pV7W', NULL, NULL, 'lucybbs@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(109, 'talmiron', '$2y$10$8OXb7kdp.uYHKDyv3Nooc.WExpASMy03VS7jSW/WOoEi7pp6pE.Um', NULL, NULL, 'tobiias398@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(110, 'user', '$2y$10$61MGOd4E5xSc2DdqI/GCteevmYkP1wtxy/H4wupmXmzo58y1rumCa', NULL, NULL, 'sebalr4m@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(111, 'alexander123', '$2y$10$RV/Yc/DKrzAnyRpXBTAO9e.Qx8exazHndZ1U1McPAiqqRhOFXBujW', NULL, NULL, 'alexanderalarcon949@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(112, 'facuE', '$2y$10$rvV0WX560uu0dFVnawGtv.opeU6Luk/ZBGDXujNe1AUQv2R91tKqO', NULL, NULL, 'facundoesquivel03@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(113, 'exequiel13', '$2y$10$y.dRzsEIi4EmaSaH.Hi.ZOxgGr5DziGZJmpbUJOJ7i3KAPollUsj6', NULL, NULL, 'exequiel@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(114, 'rupertox2', '$2y$10$tOaYj/hCXvg8vYBAKC1/weIlQ3hm.k9APxlOLzPSrlMAjJ6Sxfr8K', NULL, NULL, 'ruperto123@yahoo.com', 0, NULL, NULL, NULL, 'Activo'),
(115, 'lucydvs', '$2y$10$dhywyAzsNbPNoDUNrwynQuWFwrzzki82OaFbwRgZfBDGIziBAsf9S', NULL, NULL, 'lucy123@gmail.com', 2, NULL, NULL, NULL, 'Activo'),
(116, 'amena', '$2y$10$d83PvRJpx3qHgqUQmuwzpOihoWB8Qys8YHLn/FBdiekE6nNNs9Y8i', NULL, '2026-09-15 19:46:14', 'amena@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(117, 'santiago0', '$2y$10$JUL/h4eiCWFFHvwuQVSVBu6Zy0OwwLxA/wL8zAyLnw7yeuCjbvPgy', NULL, '2026-09-15 22:07:46', 'admin@gmail.com', 0, NULL, NULL, NULL, 'Activo'),
(118, 'Ucliente1', '$2y$10$gJITcoyMmy1hqE1ybZ3u5uzrVT8rN49lLTE2euRq3Sz5zgXMnmBBW', NULL, '2026-10-11 14:17:38', 'Ucliente1@gmail.com', 0, NULL, NULL, NULL, 'Activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria_turnos`
--
ALTER TABLE `auditoria_turnos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ayuda_config`
--
ALTER TABLE `ayuda_config`
  ADD PRIMARY KEY (`clave`);

--
-- Indices de la tabla `ayuda_faq`
--
ALTER TABLE `ayuda_faq`
  ADD PRIMARY KEY (`id_Faq`);

--
-- Indices de la tabla `dias_bloqueados`
--
ALTER TABLE `dias_bloqueados`
  ADD PRIMARY KEY (`id_Bloqueo`),
  ADD KEY `id_Empleado` (`id_Empleado`);

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
-- Indices de la tabla `especialidad_empleado`
--
ALTER TABLE `especialidad_empleado`
  ADD PRIMARY KEY (`id_Especialidad`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id_Modulo`);

--
-- Indices de la tabla `modulo_rol`
--
ALTER TABLE `modulo_rol`
  ADD PRIMARY KEY (`id_Modulo_Rol`),
  ADD UNIQUE KEY `id_Modulo` (`id_Modulo`,`id_Rol`);

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
-- AUTO_INCREMENT de la tabla `auditoria_turnos`
--
ALTER TABLE `auditoria_turnos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `ayuda_faq`
--
ALTER TABLE `ayuda_faq`
  MODIFY `id_Faq` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `dias_bloqueados`
--
ALTER TABLE `dias_bloqueados`
  MODIFY `id_Bloqueo` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `duracion_servicio`
--
ALTER TABLE `duracion_servicio`
  MODIFY `id_Duracion_Servicio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_Empleado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `especialidad_empleado`
--
ALTER TABLE `especialidad_empleado`
  MODIFY `id_Especialidad` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id_Modulo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `modulo_rol`
--
ALTER TABLE `modulo_rol`
  MODIFY `id_Modulo_Rol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_Paciente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_Persona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

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
  MODIFY `id_Turno` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_Usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dias_bloqueados`
--
ALTER TABLE `dias_bloqueados`
  ADD CONSTRAINT `dias_bloqueados_ibfk_1` FOREIGN KEY (`id_Empleado`) REFERENCES `empleado` (`id_Empleado`) ON DELETE CASCADE;

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_Empleado_Persona` FOREIGN KEY (`id_Persona`) REFERENCES `persona` (`id_Persona`),
  ADD CONSTRAINT `fk_Empleado_Rol` FOREIGN KEY (`id_Rol`) REFERENCES `rol` (`id_Rol`),
  ADD CONSTRAINT `fk_Empleado_Usuario` FOREIGN KEY (`id_Usuario`) REFERENCES `usuario` (`id_Usuario`);

--
-- Filtros para la tabla `modulo_rol`
--
ALTER TABLE `modulo_rol`
  ADD CONSTRAINT `modulo_rol_ibfk_1` FOREIGN KEY (`id_Modulo`) REFERENCES `modulo` (`id_Modulo`) ON DELETE CASCADE;

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
