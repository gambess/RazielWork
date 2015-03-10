-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 17-04-2014 a las 10:33:31
-- Versión del servidor: 5.5.35
-- Versión de PHP: 5.3.10-1ubuntu3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=58 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=104 ;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=62 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=106 ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Accion`
--
ALTER TABLE `Accion`
  ADD CONSTRAINT `FK_8DAEE682E1605BE2` FOREIGN KEY (`incidencia_id`) REFERENCES `Incidencia` (`id`);

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
-- Filtros para la tabla `Resolucion`
--
ALTER TABLE `Resolucion`
  ADD CONSTRAINT `FK_7F40637CE1605BE2` FOREIGN KEY (`incidencia_id`) REFERENCES `Incidencia` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
