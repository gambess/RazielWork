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

namespace Pi2\Fractalia\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Pi2\Fractalia\Entity\SGSD\Incidencia;
//use Pi2\Fractalia\SmsBundle\Manager\SmsManager;
use Pi2\Fractalia\SmsBundle\Manager\FiltrosManager;

class IncidenciaListener
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
     * @param LifecycleEventArgs $event
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function launchTrigger(Incidencia $incidencia, LifecycleEventArgs $event)
    {
        //capturo el objeto en un insercion o actualizacion
        $inci = $event->getObject();
        $em = $event->getEntityManager();


        //Se inicia el monitoreo de la incidencia si en los campos grupo origen o grupo destino
        //se encuentra algun servicio SOC, obtenido del fichero de configuración
        if ($this->filtrarByServicesSOC($inci))
        {
            $arrayEventos = $this->configuraciones->getEventos();

            $filtros = new FiltrosManager($arrayEventos);
            $plantilla = $filtros->pasarFiltro($inci);
            if ($plantilla != "")
            {
                $this->logger->info("Se enviaran uno o mas sms's: "
                    . "Utilizando Plantilla: " . $plantilla
                    . " Datos Incidencia.- numeroCaso: " . $incidencia->getNumeroCaso()
                    . " prioridad: " . $incidencia->getPrioridad()
                    . " estado: " . $incidencia->getEstado()
                );
                if (count($this->configuraciones->getDestinos()) > 0)
                {
                    $id_mensaje = $this->mensajeManager->createMensaje($inci, $plantilla, $em);
                    $this->crearSmsPorDestinatario($id_mensaje, $this->configuraciones->getDestinos());
                }
            }
            if ($plantilla == "")
            {
                $this->logger->info("No se encontro Plantilla para una Incidencia.- numeroCaso: " . $incidencia->getNumeroCaso() . ", prioridad: " . $incidencia->getPrioridad() . " estado: " . $incidencia->getEstado());
            }
        }
    }

    protected function crearSmsPorDestinatario($id_mensaje, $destinatarios)
    {
        $arrayDias = array();
        $now = (new \DateTime('NOW'));
        foreach ($destinatarios as $d)
        {
//            $this->logger->info('Nuevo Intento de Notificación a las', array('Fecha y Hora' => $now->format('d/m/y H:i')));
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

        if (in_array($incidencia->getGrupoDestino(), $this->configuraciones->getServiciosSOC()) or in_array($incidencia->getGrupoOrigen(), $this->configuraciones->getServiciosSOC()))
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
