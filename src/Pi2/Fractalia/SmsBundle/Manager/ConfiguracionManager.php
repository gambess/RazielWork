<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

use Pi2\Fractalia\SmsBundle\Entity\Nombretsol;
use Pi2\Fractalia\SmsBundle\Entity\Nombrecorto;

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
    private $numeros = array();

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

    /*
     * Array que contiene la traducciones de cada matricula de cada tecnico - esta indizado por matricula tecnico
     * Index asociativo y valor tipo caracter
     */
    private $traduccionesMatriculas = array();
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
     * Array con las plantillas de resumen y los estados que se deben monitorizar
     */

//    private $prioridades = array();

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

    public function setNumeros($numeros)
    {
        $this->numeros = $numeros;
    }

    public function getNumeros()
    {
        return $this->numeros;
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

    public function setTraduccionesMatriculas($traduccionesMatriculas)
    {
        $this->traduccionesMatriculas = $traduccionesMatriculas;
    }

    public function getTraduccionesMatriculas()
    {
        return $this->traduccionesMatriculas;
    }

    /*
     * Setter tsolGuardia from Config
     */

    public function setTsolGuardia($tsolGuardia)
    {
        $this->tsolGuardia = $tsolGuardia;
    }

    /*
     * Methods para tomar de configuración y añadir en dase de datos
     */
    /*
     * Comprobar si esta tsol sincronizado en base de datos
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

    /*
     * Guardar tsol de configuracion en base de datos
     *
     */

    public function saveTsol()
    {
        $em = $this->getManagerDoctrine();
        $newTsolObj = new Nombretsol();

        if (is_array($this->tsolGuardia) and count($this->tsolGuardia) == 1)
        {
            $newTsolObj->setNombre(strtoupper($this->tsolGuardia['nombre']));
            $newTsolObj->setFechaModificacion(new \DateTime('NOW'));
        }
        $em->persist($newTsolObj);
        $em->flush();
    }

    /*
     * Sobre carga del Metodo para que en vez de recuperar de Config lo haga de DB
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
        if ($tsol instanceof Nombretsol)
        {
            return array('nombre' => strtoupper($tsol->getNombre()));
        }
        return $this->tsolGuardia;
    }

    public function setNombresCortos($nombresCortos)
    {
        $this->nombresCortos = $nombresCortos;
    }

    /*
     * Methods para tomar de configuración y añadir en dase de datos
     */
    /*
     * Comprobar si esta tsol sincronizado en base de datos
     */

    public function isNombreCortoReady()
    {
        $em = $this->getManagerDoctrine();
        $nombres = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->findAll();
        if (is_array($nombres) and count($nombres) > 0)
        {
            return true;
        }
        if (!is_array($nombres) or count($nombres) == 0)
        {
            return false;
        }
    }

    /*
     * Guardar tsol de configuracion en base de datos
     *
     */

    public function saveNombreCorto()
    {
        $now = (new \DateTime('NOW'));
        $em = $this->getManagerDoctrine();
        if (is_array($this->nombresCortos) and count($this->nombresCortos) > 0)
        {
            foreach ($this->nombresCortos as $nombreCorto)
            {
                $newNombreCortoObj = new Nombrecorto();
                $newNombreCortoObj->setFechaCreacion($now);
                $newNombreCortoObj->setFechaModificacion($now);
                $newNombreCortoObj->setNombre(strtoupper($nombreCorto));
                $em->persist($newNombreCortoObj);
                $em->flush();
            }
        }
    }

    /*
     * Sobre carga del Metodo para que en vez de recuperar de Config lo haga de DB
     * Getter tsolGuardia from DB
     */

    public function getNombresCortos()
    {
        $arrayTmp = array();
        if (!$this->isNombreCortoReady())
        {
            $this->saveNombreCorto();
        }
        if ($this->isNombreCortoReady())
        {
            $em = $this->getManagerDoctrine();
            $nombres = $em->getRepository('FractaliaSmsBundle:Nombrecorto')->findAll();
        }
        foreach ($nombres as $indice => $nombre)
        {
            $arrayTmp[$indice] = strtoupper($nombre->getNombre());
        }
        return $arrayTmp;
    }

    public function getNumerosFromGrupo($grupo)
    {

        $numeros = $this->getNumeros();
        if (count($numeros) == 0)
        {
            //No hay numeros en el fichero de configuracion
            return -1;
        }

        if ($grupo)
        {
            if (array_key_exists($grupo, $numeros))
            {
                $numerosGrupo = $numeros[$grupo];
                if (count($numerosGrupo) >= 1 or count($numerosGrupo) <= 5)
                {
                    if(count($numerosGrupo) == 1)
                    {
                        return $numerosGrupo[0];
                    }else{
                        return implode(',', $numerosGrupo);
                    }
                    
                }
                else
                {
                    //El grupo o no tiene numeros o tiene mas de 5 numeros
                    return -4;
                }
            }
            else
            {
                //No se encontro el grupo en los numeros del fichero de configuracion
                return -3;
            }
        }
        else
        {
            //No se envia grupo
            return -2;
        }
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
