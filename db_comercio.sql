-- MySQL dump 10.13  Distrib 5.6.32, for Linux (x86_64)
--
-- Host: localhost    Database: db_comercio
-- ------------------------------------------------------
-- Server version	5.6.32-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favoritos` (
  `codigofavorito` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `codigousuario` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `codigousuariofavorito` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`codigofavorito`),
  UNIQUE KEY `i_codigousuario_codigousuariofavorito` (`codigousuario`,`codigousuariofavorito`),
  KEY `FK_favoritos_codigousuariofavorito` (`codigousuariofavorito`),
  CONSTRAINT `FK_favoritos_codigousuario` FOREIGN KEY (`codigousuario`) REFERENCES `usuarios` (`codigousuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_favoritos_codigousuariofavorito` FOREIGN KEY (`codigousuariofavorito`) REFERENCES `usuarios` (`codigousuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos`
--

LOCK TABLES `favoritos` WRITE;
/*!40000 ALTER TABLE `favoritos` DISABLE KEYS */;
/*!40000 ALTER TABLE `favoritos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pagos` (
  `codigopago` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `importe` decimal(8,2) unsigned NOT NULL DEFAULT '0.00',
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`codigopago`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `codigousuario` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` char(60) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `clave` char(40) COLLATE utf8_spanish_ci NOT NULL DEFAULT '' COMMENT 'Quedará conformada como SHA1(MD5(CLAVE_SIN_ENCRIPTAR)).',
  `edad` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`codigousuario`),
  UNIQUE KEY `i_usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuariospagos`
--

DROP TABLE IF EXISTS `usuariospagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuariospagos` (
  `codigousuariopago` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codigopago` int(10) unsigned NOT NULL DEFAULT '0',
  `codigousuario` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`codigousuariopago`),
  UNIQUE KEY `i_codigopago` (`codigopago`),
  KEY `FK_usuariospagos_codigousuario` (`codigousuario`),
  CONSTRAINT `FK_usuariospagos_codigopago` FOREIGN KEY (`codigopago`) REFERENCES `pagos` (`codigopago`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_usuariospagos_codigousuario` FOREIGN KEY (`codigousuario`) REFERENCES `usuarios` (`codigousuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Si bien la tabla se plantearía como multivaluada, no se debería permitir que un cierto pago se pueda imputar a dos o más usuarios. Por lo tanto, se coloca un índice UNIQUE para el campo `codigopago`.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuariospagos`
--

LOCK TABLES `usuariospagos` WRITE;
/*!40000 ALTER TABLE `usuariospagos` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuariospagos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-22  2:16:44
