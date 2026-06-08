CREATE DATABASE  IF NOT EXISTS `gestionspabd` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `gestionspabd`;
-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: gestionspabd
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `dias_bloqueados`
--

DROP TABLE IF EXISTS `dias_bloqueados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dias_bloqueados` (
  `id_Bloqueo` int NOT NULL AUTO_INCREMENT,
  `id_Empleado` int NOT NULL,
  `Fecha_Bloqueada` date NOT NULL,
  `Motivo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_Bloqueo`),
  KEY `id_Empleado` (`id_Empleado`),
  CONSTRAINT `dias_bloqueados_ibfk_1` FOREIGN KEY (`id_Empleado`) REFERENCES `empleado` (`id_Empleado`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dias_bloqueados`
--

LOCK TABLES `dias_bloqueados` WRITE;
/*!40000 ALTER TABLE `dias_bloqueados` DISABLE KEYS */;
/*!40000 ALTER TABLE `dias_bloqueados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `duracion_servicio`
--

DROP TABLE IF EXISTS `duracion_servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `duracion_servicio` (
  `id_Duracion_Servicio` int NOT NULL AUTO_INCREMENT,
  `Tiempo_Duracion` time DEFAULT NULL,
  PRIMARY KEY (`id_Duracion_Servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `duracion_servicio`
--

LOCK TABLES `duracion_servicio` WRITE;
/*!40000 ALTER TABLE `duracion_servicio` DISABLE KEYS */;
INSERT INTO `duracion_servicio` VALUES (12,'01:10:00'),(21,'00:30:00'),(22,'01:00:00');
/*!40000 ALTER TABLE `duracion_servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleado`
--

DROP TABLE IF EXISTS `empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado` (
  `id_Empleado` int NOT NULL AUTO_INCREMENT,
  `Empleado_CBU` varchar(22) DEFAULT NULL,
  `id_Usuario` int NOT NULL,
  `id_Rol` int NOT NULL,
  `id_Persona` int NOT NULL,
  `CUIL` varchar(15) DEFAULT NULL,
  `Especialidad` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_Empleado`),
  KEY `fk_Empleado_Persona` (`id_Persona`),
  KEY `fk_Empleado_Rol` (`id_Rol`),
  KEY `fk_Empleado_Usuario` (`id_Usuario`),
  CONSTRAINT `fk_Empleado_Persona` FOREIGN KEY (`id_Persona`) REFERENCES `persona` (`id_Persona`),
  CONSTRAINT `fk_Empleado_Rol` FOREIGN KEY (`id_Rol`) REFERENCES `rol` (`id_Rol`),
  CONSTRAINT `fk_Empleado_Usuario` FOREIGN KEY (`id_Usuario`) REFERENCES `usuario` (`id_Usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado`
--

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
INSERT INTO `empleado` VALUES (1,'0011223344556677889900',1,2,1,'27-12345678-9','Masajes'),(11,'0',56,3,1,'2346394033','ADMINISTRADOR'),(12,'0',58,2,2,'20213589448','Masajes'),(13,NULL,77,3,12,NULL,'ADMINISTRADOR'),(14,NULL,84,2,13,'24556781239','Masajista'),(15,NULL,88,2,15,'','Esteticista'),(16,NULL,91,2,18,'','Masajista');
/*!40000 ALTER TABLE `empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especialidad_empleado`
--

DROP TABLE IF EXISTS `especialidad_empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `especialidad_empleado` (
  `id_Especialidad` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_Especialidad`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especialidad_empleado`
--

LOCK TABLES `especialidad_empleado` WRITE;
/*!40000 ALTER TABLE `especialidad_empleado` DISABLE KEYS */;
INSERT INTO `especialidad_empleado` VALUES (2,'Esteticista'),(1,'Fisioterapeuta'),(3,'Masajista');
/*!40000 ALTER TABLE `especialidad_empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulo` (
  `id_Modulo` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` varchar(255) DEFAULT '',
  `Ruta` varchar(255) NOT NULL,
  `Icono` varchar(50) DEFAULT 'bx-grid-alt',
  PRIMARY KEY (`id_Modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Perfil','Ver y editar perfil','/Spaa/Views/perfil.php','bx-user'),(2,'Turnos','Calendario de turnos','/Spaa/Controllers/TurnoController.php','bx-calendar'),(3,'Mis Citas','Ver turnos/citas asignadas','/Spaa/Controllers/CitasController.php','bx-calendar-check'),(4,'Gestión de Usuarios','Administrar usuarios','/Spaa/Controllers/admin_usuariosController.php','bx-cog'),(5,'Asignación de Módulos','Configurar módulos por rol','/Spaa/Controllers/ModulosController.php','bx-customize');
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo_rol`
--

DROP TABLE IF EXISTS `modulo_rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulo_rol` (
  `id_Modulo_Rol` int NOT NULL AUTO_INCREMENT,
  `id_Modulo` int NOT NULL,
  `id_Rol` int NOT NULL,
  PRIMARY KEY (`id_Modulo_Rol`),
  UNIQUE KEY `id_Modulo` (`id_Modulo`,`id_Rol`),
  CONSTRAINT `modulo_rol_ibfk_1` FOREIGN KEY (`id_Modulo`) REFERENCES `modulo` (`id_Modulo`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo_rol`
--

LOCK TABLES `modulo_rol` WRITE;
/*!40000 ALTER TABLE `modulo_rol` DISABLE KEYS */;
INSERT INTO `modulo_rol` VALUES (25,1,0),(28,1,2),(26,2,0),(29,2,2),(27,3,0),(30,3,2),(31,4,3),(32,5,3);
/*!40000 ALTER TABLE `modulo_rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paciente`
--

DROP TABLE IF EXISTS `paciente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paciente` (
  `id_Paciente` int NOT NULL AUTO_INCREMENT,
  `Observaciones_Paciente` varchar(45) DEFAULT NULL,
  `id_Usuario` int NOT NULL,
  `id_Persona` int NOT NULL,
  PRIMARY KEY (`id_Paciente`),
  KEY `fk_Paciente_Persona` (`id_Persona`),
  KEY `fk_Paciente_Usuario` (`id_Usuario`),
  CONSTRAINT `fk_Paciente_Persona` FOREIGN KEY (`id_Persona`) REFERENCES `persona` (`id_Persona`),
  CONSTRAINT `fk_Paciente_Usuario` FOREIGN KEY (`id_Usuario`) REFERENCES `usuario` (`id_Usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paciente`
--

LOCK TABLES `paciente` WRITE;
/*!40000 ALTER TABLE `paciente` DISABLE KEYS */;
INSERT INTO `paciente` VALUES (1,'nudos en zona cervical',56,1),(11,NULL,78,10),(12,NULL,83,11),(13,NULL,87,14),(14,NULL,89,16),(15,NULL,90,17),(16,NULL,92,19),(17,NULL,93,20),(18,NULL,94,21);
/*!40000 ALTER TABLE `paciente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id_Persona` int NOT NULL AUTO_INCREMENT,
  `Persona_Nombre` varchar(25) NOT NULL,
  `Persona_Apellido` varchar(10) NOT NULL,
  `Persona_DNI` varchar(15) DEFAULT NULL,
  `Persona_Telefono` bigint DEFAULT NULL,
  `Persona_Domicilio` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `Fecha_Nac` date DEFAULT NULL,
  `Persona_Descripcion` text,
  PRIMARY KEY (`id_Persona`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (1,'Emanuel Alejandro','Ocampo',NULL,1123456789,'Av. Siempre Viva 742',NULL,NULL),(2,'Marcos','Gómez',NULL,1134567890,'Calle Belgrano 1200',NULL,NULL),(3,'Ana','Martínez',NULL,1145678901,'San Martín 455',NULL,NULL),(4,'Julián','Pérez',NULL,1156789012,'Rivadavia 2331',NULL,NULL),(5,'Sofía','López',NULL,1167890123,'Av. Córdoba 150',NULL,NULL),(6,'Camila','Rojas',NULL,1178901234,'Las Heras 880',NULL,NULL),(7,'Bruno','Castillo',NULL,1189012345,'Mitre 612',NULL,NULL),(8,'Valentina','Moreno',NULL,1190123456,'Pasaje Sarmiento 35',NULL,NULL),(9,'Ezequiel','Ramírez',NULL,1122334455,'Dorrego 1277',NULL,NULL),(10,'Nahuel','Sanchez','12345678',1133445566,'Av. Lelong 208','2005-03-12','Dolor en la zona baja de la espalda'),(11,'Paolo','Gomez','66777444',3704505050,'Barrio la Floresta mz 44 cs 2','2000-04-10','Dolor en el hombro'),(12,'Ramiro','Fleitas','46394033',12345678,'Calle Falsa 123',NULL,NULL),(13,'Josefa','Paniagua',NULL,NULL,NULL,NULL,NULL),(14,'Diana','Fleitas','34567123',3704282828,'asdasd','1998-03-13',''),(15,'Saturnina','Culasso',NULL,NULL,NULL,NULL,NULL),(16,'Inocencio','Alcides',NULL,NULL,NULL,NULL,NULL),(17,'ramoncito10','sandoval','12345678',3704765435,'Av. Siempre Viva 790','2005-02-09',''),(18,'Bianca','Ramirez',NULL,NULL,NULL,NULL,NULL),(19,'guadita','',NULL,NULL,NULL,NULL,NULL),(20,'penelope','',NULL,NULL,NULL,NULL,NULL),(21,'Fabricio','Rodriguez','51618282',3282373819,'','1999-02-11','');
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `id_Rol` int NOT NULL AUTO_INCREMENT,
  `Rol_Descripcion` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id_Rol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (0,'Paciente'),(1,'Recepcionista'),(2,'Terapeuta'),(3,'Admin');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio`
--

DROP TABLE IF EXISTS `servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicio` (
  `id_Servicio` int NOT NULL AUTO_INCREMENT,
  `Nombre_servicio` varchar(45) NOT NULL,
  `Descripcion` varchar(250) DEFAULT NULL,
  `Costo` int DEFAULT NULL,
  `id_Duracion_Servicio` int DEFAULT NULL,
  `Estado` tinyint DEFAULT NULL,
  PRIMARY KEY (`id_Servicio`),
  KEY `fk_Servicio_Duracion` (`id_Duracion_Servicio`),
  CONSTRAINT `fk_Servicio_Duracion` FOREIGN KEY (`id_Duracion_Servicio`) REFERENCES `duracion_servicio` (`id_Duracion_Servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio`
--

LOCK TABLES `servicio` WRITE;
/*!40000 ALTER TABLE `servicio` DISABLE KEYS */;
INSERT INTO `servicio` VALUES (70,'Masaje Descontracturante','Relaja músculos',38000,NULL,NULL),(80,'Tratamiento Facial','eliminar células muertas, renovar la capa epidérmica y devolverle luminosidad y salud al rostro mediante el uso de productos cosmecéuticos y aparatología específica',42000,NULL,NULL),(81,'Reflexología Podal','Terapia de presión en puntos específicos de los pies para liberar tensión.',28000,NULL,NULL),(82,'Drenaje Linfático Manual','Técnica suave para reducir retención de líquidos y mejorar la circulación.',47000,NULL,NULL),(83,'Circuito Hídrico','Acceso a sauna, hidromasaje, baño turco y áreas de relax.',70000,NULL,NULL),(84,'Exfoliación Corporal','Pulido integral de la piel con sales o semillas para renovar células.',35000,NULL,NULL),(85,'Fangoterapia Corporal','Aplicación de barros minerales con propiedades desintoxicantes.',42000,NULL,NULL);
/*!40000 ALTER TABLE `servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio_empleado`
--

DROP TABLE IF EXISTS `servicio_empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicio_empleado` (
  `id_Servicio_Empleado` int NOT NULL AUTO_INCREMENT,
  `id_Servicio` int NOT NULL,
  `id_Empleado` int NOT NULL,
  PRIMARY KEY (`id_Servicio_Empleado`),
  KEY `fk_SE_Servicio` (`id_Servicio`),
  KEY `fk_SE_Empleado` (`id_Empleado`),
  CONSTRAINT `fk_SE_Empleado` FOREIGN KEY (`id_Empleado`) REFERENCES `empleado` (`id_Empleado`),
  CONSTRAINT `fk_SE_Servicio` FOREIGN KEY (`id_Servicio`) REFERENCES `servicio` (`id_Servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio_empleado`
--

LOCK TABLES `servicio_empleado` WRITE;
/*!40000 ALTER TABLE `servicio_empleado` DISABLE KEYS */;
INSERT INTO `servicio_empleado` VALUES (1,70,1);
/*!40000 ALTER TABLE `servicio_empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turno`
--

DROP TABLE IF EXISTS `turno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `turno` (
  `id_Turno` int NOT NULL AUTO_INCREMENT,
  `id_Paciente` int NOT NULL,
  `id_Empleado` int NOT NULL,
  `id_Servicio` int NOT NULL,
  `Fecha_Turno` date NOT NULL,
  `Hora_Turno` time NOT NULL,
  `Estado_Turno` varchar(20) DEFAULT 'Pendiente',
  PRIMARY KEY (`id_Turno`),
  KEY `fk_Turno_Paciente` (`id_Paciente`),
  KEY `fk_Turno_Empleado` (`id_Empleado`),
  KEY `fk_Turno_Servicio` (`id_Servicio`),
  CONSTRAINT `fk_Turno_Empleado` FOREIGN KEY (`id_Empleado`) REFERENCES `empleado` (`id_Empleado`),
  CONSTRAINT `fk_Turno_Paciente` FOREIGN KEY (`id_Paciente`) REFERENCES `paciente` (`id_Paciente`),
  CONSTRAINT `fk_Turno_Servicio` FOREIGN KEY (`id_Servicio`) REFERENCES `servicio` (`id_Servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turno`
--

LOCK TABLES `turno` WRITE;
/*!40000 ALTER TABLE `turno` DISABLE KEYS */;
INSERT INTO `turno` VALUES (9,11,15,80,'2026-06-12','20:00:00','Pendiente'),(10,15,12,81,'2026-06-22','17:00:00','Cancelado'),(11,15,12,83,'2026-06-23','09:00:00','Pendiente'),(14,18,12,82,'2026-06-25','18:00:00','Pendiente');
/*!40000 ALTER TABLE `turno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_Usuario` int NOT NULL AUTO_INCREMENT,
  `Usuario_Nombre` varchar(15) NOT NULL,
  `Usuario_Contraseña` varchar(255) DEFAULT NULL,
  `Descripcion` varchar(250) DEFAULT NULL,
  `Fecha_Creacion` datetime DEFAULT NULL,
  `Correo_E` varchar(40) DEFAULT NULL,
  `id_Rol` int DEFAULT '0',
  `codigo_recuperacion` varchar(10) DEFAULT NULL,
  `codigo_expira` datetime DEFAULT NULL,
  `id_Persona` int DEFAULT NULL,
  `Estado` varchar(20) DEFAULT 'Activo',
  PRIMARY KEY (`id_Usuario`),
  KEY `fk_usuario_rol` (`id_Rol`),
  KEY `fk_usuario_persona` (`id_Persona`),
  CONSTRAINT `fk_usuario_persona` FOREIGN KEY (`id_Persona`) REFERENCES `persona` (`id_Persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_Rol`) REFERENCES `rol` (`id_Rol`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Alejandro','$2y$10$sULPuTck.Go5Ry0lNG3JeOdIlGmKt.rWfmglFugJddRdIlqA/TO7C',NULL,NULL,'Alejuemm@gmail.com',0,NULL,NULL,NULL,'Inactivo'),(56,'ramiro','$2y$10$5FTIx1uWqZdVfXgWmlrD.ug2RLWcZz7asxD/dJDb03AhbHf28lBiO','Admin','2025-01-10 00:00:00','ramiro@gmail.com',3,NULL,NULL,NULL,'Inactivo'),(57,'martina','$2y$10$NZiycZvl0FMna2ZXisO/4.tLxOuxRIyT2qehBxmJXVhSkuou4eD2a','Recepcionista','2025-01-11 00:00:00','martina@gmail.com',0,NULL,NULL,NULL,'Inactivo'),(58,'facundo','$2y$10$4BNUBHR2GwzqMd2I6Sauku0Y8l.8MvqXIGAcVl3uXtuZ5L1RQYJ0u','Terapeuta','2025-01-12 00:00:00','facundo@hotmail.com',2,NULL,NULL,NULL,'Activo'),(75,'Guada','$2y$10$/ELhIZdC86.YL7Se9sOaPuqbSzxHN6h5mwRSBm3jLo06Bk6OI/CDG','Admin',NULL,'Guadex@gmail.com',3,NULL,NULL,NULL,'Activo'),(77,'Rama04','$2y$10$PeSd87vPdRsb7YMo1cm.Eu28dra5abg3luJs0tlgrHNAC3XPCmilC',NULL,'2026-05-01 17:38:45','ramirofleitasb@gmail.com',3,NULL,NULL,NULL,'Activo'),(78,'Nahuelshz','$2y$10$wyaxSY4H3T8vWNV9ZYSqYOOTsAUq46iH.gc7NjWJ7C1bzq1XjBBfC',NULL,'2026-05-01 19:52:41','nawel1414@gmail.com',0,NULL,NULL,NULL,'Activo'),(83,'Paolo','$2y$10$0g/jCxNMU0lOYPPSbxQprOZRXMcE9a1S3XsWC0.MeGSxJ27UTe7R6',NULL,'2026-05-04 01:17:13','Paolito123@gmail.com',0,NULL,NULL,NULL,'Activo'),(84,'JosefaP','$2y$10$VFvae7RCz5h3AD5wiwtSX.dca.a.76v7CDY7iWrUKlPF/gpNwEbEW',NULL,NULL,'JosefaP@gmail.com',2,NULL,NULL,NULL,'Inactivo'),(87,'Diana','$2y$10$yAOM4K5L44t1ZC0Bi/P.yeveBPllYVBTLFyYRHgbzxpEl2YsjZs9.',NULL,'2026-06-07 18:36:25','dianahola123@gmail.com',0,NULL,NULL,NULL,'Inactivo'),(88,'Saturninaa','$2y$10$9QSu.Dhk963gxdNm6mZBu.tDwjjbg5M4N42rt5FbPQkLZsLwur7R2',NULL,NULL,'saturnina@gmail.com',2,NULL,NULL,NULL,'Activo'),(89,'InoAlcides','$2y$10$PbH8JBotUT4ypA84uzLHc.tEndXBaJvDEmbvXOYdEVKLepMWuR1qK',NULL,NULL,'Inocencio@gmail.com',0,NULL,NULL,NULL,'Activo'),(90,'ramoncito10','$2y$10$04qpeFaK3n/PRzwLNGsRLe9P5GLWbvjGDpOC.t3ktfsNSx7Avba0.',NULL,'2026-06-07 20:39:18','ramoncito@gmail.com',0,NULL,NULL,NULL,'Activo'),(91,'bianquis','$2y$10$F5FI8dbJCyfwQPaPduRUYe1jbeaD59.imjdsFG.BVqaL5TnGiOfcO',NULL,NULL,'biancaramirez2026@gmail.com',2,NULL,NULL,NULL,'Activo'),(92,'guadita','$2y$10$9bTFn78hD4ClF6lJ15oE0eJyB2UeaaIPcchOmg0dnhgrsYqC0yTF2',NULL,'2026-06-07 21:30:07','Milanesasdepollo584@gmail.com',0,'4540','2026-06-08 02:40:24',NULL,'Activo'),(93,'penelope','$2y$10$cAKZ/A.q4gUPUBjja52sVuvbca3XmazrbkEPexHV3W.FUZMz/WnO2',NULL,'2026-06-07 21:32:24','villalbagarciaguadalupe@gmail.com',0,'2125','2026-06-08 04:39:16',NULL,'Activo'),(94,'carmela','$2y$10$cLqUXS.kTh5t1XJTf7mtceA5SvvWpWFJgpuz5avYXcy62gYE.95Pu',NULL,'2026-06-07 22:52:25','fabrirodrig16@gmail.com',0,NULL,NULL,NULL,'Activo');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-08 12:31:28
