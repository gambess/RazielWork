-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-03-2015 a las 12:14:44
-- Versión del servidor: 5.5.41-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `reports`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Notificacion`
--

CREATE TABLE IF NOT EXISTS `Notificacion` (
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
  KEY `TipoAccion` (`TipoAccion`),
  KEY `TipoCaso` (`TipoCaso`),
  KEY `Tipificacion1` (`Tipificacion1`),
  KEY `GrupoOrigen` (`GrupoOrigen`),
  KEY `GrupoDestino` (`GrupoDestino`),
  KEY `TecnicoAsignadoInicial` (`TecnicoAsignadoInicial`),
  KEY `FechaResolucion` (`FechaResolucion`),
  KEY `Estado` (`Estado`),
  KEY `FechaApertura` (`FechaApertura`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=215812 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Rechazada`
--

CREATE TABLE IF NOT EXISTS `Rechazada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `NumeroCaso` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `IncidenciaAjena` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `FechaActualizacion` datetime DEFAULT NULL,
  `TipoAccion` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `VisibleUsuario` tinyint(1) DEFAULT NULL,
  `Motivo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `TipoCaso` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `FechaApertura` datetime DEFAULT NULL,
  `Titulo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tipificacion1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tipificacion2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tipificacion3` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tipificacion4` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `GrupoOrigen` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `GrupoDestino` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `TecnicoAsignadoInicial` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `TecnicoAsignadoFinal` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Impacto` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Urgencia` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Prioridad` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CI` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CIEtiqueta` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CITipo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CINumeroSerie` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CIFabricante` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CIModelo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Ubicacion` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `NombreUbicacion` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Pais` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Provincia` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Localidad` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Direccion` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CodigoPostal` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `UsuarioAfectado` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `TelefonoUsuarioAfectado` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `EmailUsuarioAfectado` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `InformadoPor` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Contrato` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Subactividad` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ServicioAfectado` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Cliente` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `OrganizacionInterna` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CodigoResolucion` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `FechaResolucion` datetime DEFAULT NULL,
  `CasoRelacionado` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `NumAdjuntos` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Estado` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `SistemaOrigen` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `IDCasoSistemaOrigen` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `FechaInsercion` datetime DEFAULT NULL,
  `notifica_vista` tinyint(1) DEFAULT NULL,
  `hideInMonitor` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `numerocaso` (`NumeroCaso`),
  KEY `tipoaccion` (`TipoAccion`),
  KEY `tipocaso` (`TipoCaso`),
  KEY `grupoorigen` (`GrupoOrigen`),
  KEY `grupodestino` (`GrupoDestino`),
  KEY `tecnicoinicial` (`TecnicoAsignadoInicial`),
  KEY `Tipificacion1` (`Tipificacion1`),
  KEY `Estado` (`Estado`),
  KEY `FechaResolucion` (`FechaResolucion`),
  KEY `FechaApertura` (`FechaApertura`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6338 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
