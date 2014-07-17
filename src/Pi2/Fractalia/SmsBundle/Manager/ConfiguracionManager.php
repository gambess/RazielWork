<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

use Pi2\Fractalia\SmsBundle\Entity\Nombretsol;

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
     * Array que tiene las iniciales del TSOL
     */
    private $tsolGuardia = array();

    /*
     * Array que contiene la traducciones de cada tipo de caso - esta indizado por tipo
     * Index tipo numerico - value tipo en caracter
     */
    private $traduccionesTipos = array();
    //Etiquetas de las plantillas
    /*
     * Array con todas las plantillas de Eventos que existan ene le fichero de configuracion
     */
    private $plantillas = array();

    /*
     * Array con las plantillas de resumen y los estados que se deben monitorizar
     */
    private $resumenes = array();


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

    public function setTraduccionesTipos($traduccionesTipos)
    {
        $this->traduccionesTipos = $traduccionesTipos;
    }

    public function getTraduccionesTipos()
    {
        return $this->traduccionesTipos;
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

    /*
     * Setter tsolGuardia from Config
     */

    public function setTsolGuardia($tsolGuardia)
    {
        $this->tsolGuardia = $tsolGuardia;
    }

    /*
     * Methods para tomar de configuración y añadir
     */
    public function isTsolReady()
    {
        $em = $this->getManagerDoctrine();
        $tsol = $em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
        if ($tsol instanceof Nombretsol and ( !is_null($tsol->getNombre())))
        {
            return true;
        }
        if (is_null($tsol))
        {
            return false;
        }
    }

    public function saveTsol()
    {
        $now = (new \DateTime('NOW'));
        $em = $this->getManagerDoctrine();
        $newTsolObj = new Nombretsol();
        $newTsolObj->setFechaModificacion($now);
        if (is_array($this->tsolGuardia) and count($this->tsolGuardia) == 1)
        {
            $newTsolObj->setNombre($this->tsolGuardia['nombre']);
        }
//        else
//        {
//            $newTsolObj->setNombre('TSOLDEF');
//        }
        $em->persist($newTsolObj);
        $em->flush();
    }

     /*
     * Getter tsolGuardia from DB
     */
    public function getTsolGuardia()
    {
        if (!$this->isTsolReady())
        {
            $this->saveTsol();
        }
        if ($this->isTsolReady())
        {
            $em = $this->getManagerDoctrine();
            $tsol = $em->getRepository('FractaliaSmsBundle:Nombretsol')->getTsol();
        }
        if($tsol instanceof Nombretsol){
            return array('nombre'=>$tsol->getNombre());
        }
        return $this->tsolGuardia;
    }

    public function setNombresCortos($nombresCortos)
    {
        $this->nombresCortos = $nombresCortos;
    }

    public function getNombresCortos()
    {
        return $this->nombresCortos;
    }

    public function saveNombresCortos()
    {
        
    }

    /*
     * Accesos Globales de Symfony
     */

    private function getService($name)
    {
        return $GLOBALS['kernel']->getContainer()->get($name);
    }

    private function getParameter($name)
    {
        return $GLOBALS['kernel']->getContainer()->getParameter($name);
    }

    private function getManagerDoctrine()
    {
        $doctrine = $this->getService('doctrine');
        return $doctrine->getManager();
    }

}
