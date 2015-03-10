<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IncidenciaListener
 * Implementación del Trigger Encapsulado a traves de un listener Doctrine
 * Servicio que gestiona las persistencias de incidencias.
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

use Pi2\Fractalia\SmsBundle\Manager\FiltrosManager;
use Pi2\Fractalia\Entity\SGSD\Incidencia;

class IncidenciaFilterManager
{
    /*
     * Servicios Inyectados
     */
    private $logger;
    private $configuraciones;
    private $mensajeManager;
    private $smsManager;

    public function __construct($logger, $configuracionManager, $smsManager, $mensajeManager)
    {
        $this->logger = $logger;
        $this->configuraciones = $configuracionManager;
        $this->smsManager = $smsManager;
        $this->mensajeManager = $mensajeManager;
    }

    /**
     * Trigger para capturar las inserciones y actualizaciones
     *
     * @param Incidencia $incidenciaActual
     * @param EntityManager $em 
     * 
     */
    public function incidenciaFilter(Incidencia $incidenciaActual, $estadoPrevio = null, $previas=null, $em = null)
    {
        $plantilla = "";
        if ($em == null)
        {
            $em = $this->getDoctrineManager();
        }

        //Se inicia el monitoreo de la incidencia si en los campos grupo origen o grupo destino
        //se encuentra algun servicio SOC, obtenido del fichero de configuración
        if ($this->filtrarByServicesSOC($incidenciaActual))
        {
            $arrayEventos = $this->configuraciones->getEventos();

            $filtros = new FiltrosManager($arrayEventos);
            $plantilla = $filtros->pasarFiltro($incidenciaActual, $estadoPrevio, $previas);
            if ($plantilla != "")
            {
                $this->logger->info("Plantilla Encontrada para la Incidencia numCaso: " . $incidenciaActual->getNumeroCaso() . ", prioridad: " . $incidenciaActual->getPrioridad() . " estado: " . $incidenciaActual->getEstado());
                if (count($this->configuraciones->getDestinos()) > 0)
                {
                    $this->logger->info("Destinatario configurado encontrado");
                    $id_mensaje = $this->mensajeManager->createMensaje($incidenciaActual, $plantilla, $em);
                    $this->crearSmsPorDestinatario($id_mensaje, $this->configuraciones->getDestinos());
                    $this->logger->info("Creado sms");
                }
            }
            if ($plantilla == "")
            {
                $this->logger->info("No se encontro una Plantilla para Incidencia numCaso: " . $incidenciaActual->getNumeroCaso() . ", prioridad: " . $incidenciaActual->getPrioridad() . " estado: " . $incidenciaActual->getEstado());
            }
        }
        else
        {
            $this->logger->info("Se omite la comparación con los filtros puesto que los grupos de Origen y Destino enviados no pertenecen a los servicios SOC", array('origen' => $incidenciaActual->getGrupoOrigen(), 'destino' => $incidenciaActual->getGrupoDestino()));
        }

        if (is_null($incidenciaActual->getPrioridad()) or empty($incidenciaActual->getPrioridad()))
        {
            $this->logger->info("La Incidencia numCaso: " . $incidenciaActual->getNumeroCaso() . " estado: " . $incidenciaActual->getEstado() . ". Tiene prioridad nula o vacia");
        }
    }

    protected function getDoctrineManager()
    {
        $doctrine = $GLOBALS['kernel']->getContainer()->get('doctrine');
        return $doctrine->getManager();
    }

    protected function crearSmsPorDestinatario($id_mensaje, $destinatarios)
    {
        $arrayDias = array();
        $now = (new \DateTime('NOW'));
        foreach ($destinatarios as $d)
        {
            $arrayDias = preg_split('/\s*,\s*/', $d['dias']);
            if (in_array($this->getDiaEsp(), $arrayDias) && ($now->format('H:i') >= $d['desde']) && ($now->format('H:i') <= $d['hasta']))
            {
                $this->smsManager->createSms($d['destinatario'], $id_mensaje);
            }
        }
    }

    /*
     * Primer filtro del Listener SOLO SERVICIOS SOC
     */

    protected function filtrarByServicesSOC(Incidencia $incidencia)
    {

        if (in_array(strtoupper($incidencia->getGrupoDestino()), $this->configuraciones->getServiciosSOC()) or in_array(strtoupper($incidencia->getGrupoOrigen()), $this->configuraciones->getServiciosSOC()))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //Se obtiene el dia de hoy en idioma español
    private function getDiaEsp()
    {
        $now = (new \DateTime('NOW'));
        switch (strtolower($now->format('D')))
        {
            case 'mon': $dia = 'lunes';
                break;
            case 'tue': $dia = 'martes';
                break;
            case 'wed': $dia = 'miercoles';
                break;
            case 'thu': $dia = 'jueves';
                break;
            case 'fri': $dia = 'viernes';
                break;
            case 'sat': $dia = 'sabado';
                break;
            case 'sun': $dia = 'domingo';
                break;
            default:
                $dia = null;
                break;
        }
        return ($dia);
    }

}
