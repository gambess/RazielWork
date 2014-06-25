<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

/**
 * Description of ConfiguracionManager
 * Clase que gestiona y carga el fichero de configuracion sms_manager
 * Implementada como servicio
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class ConfiguracionManager
{
    /*
     * Inyectamos el logger en el servicio se asigna en el constructor
     */
    private $logger;
    
    /*
     * Array donde se almacenan los datos de Envio de SMS a traves del API de telefonica
     */
    private $datosApi = array(); 
 
    /*
     * Array donde se almacenan los distintos servicios del servicio SOC
     */
    private $serviciosSOC = array(); 
    
    /*
     * Array donde se almacenan los distintos destinatarios y sus horarios de envio
     */
    private $destinos = array(); 
    
    /*
     * Main Array de la configuracion contiene toda la informacion de los eventos a monitorizar
     * Y ademas todas las particularidades de cada evento
     */
    private $eventos = array(); 
    
    //Datos solo para renderizar y miniprocesamiento
    
    /*
     *  Array que contiene los nombres cortos para identificar el cliente en el campo titulo
     */
    private $nombresCortos = array();
    
    /*
     * Array que contiene la traducciones de cada tipo de caso - esta indizado por tipo
     * Index tipo numerico - value tipo en caracter
     */
    private $traduccionesTipos = array();
    
    /*
     * Array que tiene las iniciales del TSOL
     */
    private $tsolGuardia = array();
    
    //Etiquetas de las plantillas
    /*
     * Array con todas las plantillas de Eventos que existan ene le fichero de configuracion
     */
    private $plantillas = array();
    
    /*
     * Array con las plantillas de resumen y los estados que se deben monitorizar
     */
    private $resumenes = array();
    
    function __construct($logger)
    {
        $this->logger = $logger;
    }

    /*
     * Setear los datos de conexión a la API
     * Parametro del Servicio Inyectado por configuración
     */
    public function setDatosApi($datosApi)
    {
        $this->datosApi = $datosApi;
    }
    
    /*
     * Obtener los datos del API para enviar los mensajes
     * 
     */
    public function getDatosApi()
    {
        return $this->datosApi;
    }
    
    
    public function setServiciosSOC($servicios)
    {
        $this->serviciosSOC = $servicios;
    }
    
    public function getServiciosSOC()
    {
        return $this->serviciosSOC;
    }

    public function setDestinos($destinos)
    {
        $this->destinos = $destinos;
    }
    
    public function getDestinos()
    {
        return $this->destinos;
    }

    public function setEventos($eventos)
    {
        $this->eventos = $eventos;
    }
    
    public function getEventos()
    {
        return $this->eventos;
    }

    public function setNombresCortos($nombresCortos)
    {
        $this->nombresCortos = $nombresCortos;
    }
    
    public function getNombresCortos()
    {
        return $this->nombresCortos;
    }

    public function setTraduccionesTipos($traduccionesTipos)
    {
        $this->traduccionesTipos = $traduccionesTipos;
    }
    
    public function getTraduccionesTipos()
    {
        return $this->traduccionesTipos;
    }

    public function setTsolGuardia($tsolGuardia)
    {
        $this->tsolGuardia = $tsolGuardia;
    }
    
    public function getTsolGuardia()
    {
        return $this->tsolGuardia;
    }

    public function setPlantillas($plantillas)
    {
        $this->plantillas = $plantillas;
    }
    
    public function getPlantillas()
    {
        return $this->plantillas;
    }

    public function setResumenes($resumenes)
    {
        $this->resumenes = $resumenes;
    }
    
    public function getResumenes()
    {
        return $this->resumenes;
    }

    public function getLogger()
    {
        if( $this->logger == NULL)
       {
//           $this->logger = $GLOBALS['kernel']->getContainer()->get('logger');
           $this->logger = $this->getContainer()->get('logger');
       }
       return $this->logger;
    }

}
