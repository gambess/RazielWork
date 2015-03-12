-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-03-2015 a las 12:14:07
-- Versión del servidor: 5.5.41-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `sgsd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Accion`
--

CREATE TABLE IF NOT EXISTS `Accion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `incidencia_id` int(11) DEFAULT NULL,
  `texto` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8DAEE682E1605BE2` (`incidencia_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=813204 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ColumnaEvento`
--

CREATE TABLE IF NOT EXISTS `ColumnaEvento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `incidencia_id` int(11) DEFAULT NULL,
  `numero_caso` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cliente` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tecnico` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tsol` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `modo` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `detalle` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_AB3F3E06E1605BE2` (`incidencia_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=337 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ColumnaResumen`
--

CREATE TABLE IF NOT EXISTS `ColumnaResumen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mensaje_id` int(11) DEFAULT NULL,
  `numero_caso` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `servicio` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Incidencia_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_375B2965521E1017` (`Incidencia_id`),
  KEY `IDX_375B29654C54F362` (`mensaje_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Descripcion`
--

CREATE TABLE IF NOT EXISTS `Descripcion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `incidencia_id` int(11) DEFAULT NULL,
  `texto` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_26B6A0D7E1605BE2` (`incidencia_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14570247 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Incidencia`
--

CREATE TABLE IF NOT EXISTS `Incidencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `NumeroCaso` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `IncidenciaAjena` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FechaActualizacion` datetime DEFAULT NULL,
  `TipoAccion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `VisibleUsuario` tinyint(1) DEFAULT NULL,
  `Motivo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `TipoCaso` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FechaApertura` datetime DEFAULT NULL,
  `Titulo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tipificacion1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tipificacion2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tipificacion3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tipificacion4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `GrupoOrigen` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `GrupoDestino` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `TecnicoAsignadoInicial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `TecnicoAsignadoFinal` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Impacto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Urgencia` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Prioridad` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CI` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CIEtiqueta` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CITipo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CINumeroSerie` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CIFabricante` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CIModelo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Ubicacion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `NombreUbicacion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Pais` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Provincia` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Localidad` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Direccion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CodigoPostal` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `UsuarioAfectado` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `TelefonoUsuarioAfectado` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `EmailUsuarioAfectado` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `InformadoPor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Contrato` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Subactividad` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ServicioAfectado` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Cliente` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `OrganizacionInterna` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CodigoResolucion` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FechaResolucion` datetime DEFAULT NULL,
  `CasoRelacionado` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `NumAdjuntos` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Estado` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `SistemaOrigen` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `IDCasoSistemaOrigen` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FechaInsercion` datetime DEFAULT NULL,
  `notifica_vista` tinyint(1) DEFAULT NULL,
  `hideInMonitor` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `NumeroCaso` (`NumeroCaso`),
  KEY `FechaActualizacion` (`FechaActualizacion`),
  KEY `GrupoOrigen` (`GrupoOrigen`),
  KEY `GrupoDestino` (`GrupoDestino`),
  KEY `TecnicoAsignadoInicial` (`TecnicoAsignadoInicial`),
  KEY `TecnicoAsignadoFinal` (`TecnicoAsignadoFinal`),
  KEY `FechaInsercion` (`FechaInsercion`),
  KEY `Estado` (`Estado`),
  KEY `Prioridad` (`Prioridad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=71273 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `InfoAdjunto`
--

CREATE TABLE IF NOT EXISTS `InfoAdjunto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `incidencia_id` int(11) DEFAULT NULL,
  `idattach` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lenattach` int(11) DEFAULT NULL,
  `nameattach` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipoattach` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `operadorattach` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fechaattach` datetime DEFAULT NULL,
  `numberOfSegments` int(11) DEFAULT NULL,
  `compressed` tinyint(1) DEFAULT NULL,
  `lenCompressed` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_573C2AEE1605BE2` (`incidencia_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=42343 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Mensaje`
--

CREATE TABLE IF NOT EXISTS `Mensaje` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `columna_evento_id` int(11) DEFAULT NULL,
  `nombre_plantilla` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_mensaje` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `texto` longtext COLLATE utf8_unicode_ci,
  `estado` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log` longtext COLLATE utf8_unicode_ci,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `fecha_adjuntado_sms` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_54DE249D7695B42` (`columna_evento_id`),
  KEY `nombre_plantilla` (`nombre_plantilla`),
  KEY `estado` (`estado`),
  KEY `fecha_creacion` (`fecha_creacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=337 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Nombrecorto`
--

CREATE TABLE IF NOT EXISTS `Nombrecorto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=323 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Nombretsol`
--

CREATE TABLE IF NOT EXISTS `Nombretsol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Resolucion`
--

CREATE TABLE IF NOT EXISTS `Resolucion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `incidencia_id` int(11) DEFAULT NULL,
  `texto` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7F40637CE1605BE2` (`incidencia_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=849721 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Sms`
--

CREATE TABLE IF NOT EXISTS `Sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mensaje_id` int(11) DEFAULT NULL,
  `destinatario` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remitente` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `respuesta_api` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado_envio` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log` longtext COLLATE utf8_unicode_ci,
  `fecha_envio` datetime DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL,
  `notifica_fallo` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_88E47C974C54F362` (`mensaje_id`),
  KEY `destinatario` (`destinatario`),
  KEY `estado_envio` (`estado_envio`),
  KEY `fecha_creacion` (`fecha_creacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=621 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Accion`
--
ALTER TABLE `Accion`
  ADD CONSTRAINT `FK_8DAEE682E1605BE2` FOREIGN KEY (`incidencia_id`) REFERENCES `Incidencia` (`id`);

--
-- Filtros para la tabla `ColumnaEvento`
--
ALTER TABLE `ColumnaEvento`
  ADD CONSTRAINT `FK_AB3F3E06E1605BE2` FOREIGN KEY (`incidencia_id`) REFERENCES `Incidencia` (`id`);

--
-- Filtros para la tabla `ColumnaResumen`
--
ALTER TABLE `ColumnaResumen`
  ADD CONSTRAINT `FK_375B29654C54F362` FOREIGN KEY (`mensaje_id`) REFERENCES `Mensaje` (`id`),
  ADD CONSTRAINT `FK_375B2965521E1017` FOREIGN KEY (`Incidencia_id`) REFERENCES `Incidencia` (`id`);

--
-- Filtros para la tabla `Descripcion`
--
ALTER TABLE `Descripcion`
  ADD CONSTRAINT `FK_26B6A0D7E1605BE2` FOREIGN KEY (`incidencia_id`) REFERENCES `Incidencia` (`id`);

--
-- Filtros para la tabla `InfoAdjunto`
--
ALTER TABLE `InfoAdjunto`
  ADD CONSTRAINT `FK_573C2AEE1605BE2` FOREIGN KEY (`incidencia_id`) REFERENCES `Incidencia` (`id`);

--
-- Filtros para la tabla `Mensaje`
--
ALTER TABLE `Mensaje`
  ADD CONSTRAINT `FK_54DE249D7695B42` FOREIGN KEY (`columna_evento_id`) REFERENCES `ColumnaEvento` (`id`);

--
-- Filtros para la tabla `Resolucion`
--
ALTER TABLE `Resolucion`
  ADD CONSTRAINT `FK_7F40637CE1605BE2` FOREIGN KEY (`incidencia_id`) REFERENCES `Incidencia` (`id`);

--
-- Filtros para la tabla `Sms`
--
ALTER TABLE `Sms`
  ADD CONSTRAINT `FK_88E47C974C54F362` FOREIGN KEY (`mensaje_id`) REFERENCES `Mensaje` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
