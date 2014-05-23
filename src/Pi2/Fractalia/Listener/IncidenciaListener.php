<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IncidenciaListener
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace Pi2\Fractalia\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Pi2\Fractalia\Entity\SGSD\Incidencia;

class IncidenciaListener
{
    private $logger;

//    private $formatter;
//    private $another_service;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Trigger para capturar las inserciones
     *
     * @param Incidencia $incidencia
     * @param LifecycleEventArgs $event
     * @ORM\PostPersist
     */
//    public function postPersist(Incidencia $incidencia, LifecycleEventArgs $event)
//    {
//        $this->sendMail($incidencia);
//    }

    /**
     * Trigger para capturar las actualizaciones
     *
     * @param Incidencia $incidencia
     * @param LifecycleEventArgs $event
     * @ORM\PostPersist
     */
//    public function postUpdate(Incidencia $incidencia, LifecycleEventArgs $event)
//    {
//        $this->sendMail($incidencia);
//    }

    /**
     * Trigger para capturar las inserciones y actualizaciones
     *
     * @param Incidencia $incidencia
     * @param LifecycleEventArgs $event
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function launchTrigger(Incidencia $incidencia, LifecycleEventArgs $event)
    {
        $inci = $event->getObject();
        if (null === $inci->getFechaInsercion())
        {
            $inci->setFechaInsercion(new \DateTime(date('Y-m-d H:m:s')));
        }
        if ($this->filterIncidenciaByService($inci))
        {
            $this->logger->notice('Insertado un ticket: ', array('fecha' => $inci->getFechaInsercion(), 'prioridad' => $inci->getPrioridad()));
            $this->logger->notice('Visualizando SMS Preparado: ', array('SMS' => $this->prepareSMS($inci)));
        }
    }

    protected function filterIncidenciaByService(Incidencia $i)
    {

        $soc_service = array('SEGEST MON', 'SEGEST SOC', 'SEG MERCEDES', 'SOPORTE SEGURIDAD', 'SOC SEGURIDAD');

        if (in_array($i->getGrupoDestino(), $soc_service) or in_array($i->getGrupoOrigen(), $soc_service))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    protected function prepareSMS(Incidencia $i)
    {
        $t = array(
            '1' => 'incidencia',
            '2' => 'peticion',
            '3' => 'queja',
            '4' => 'consulta'
            );
        $cliente ='';
        $TSOL = '';
        $format = 'd-m-Y H:m:s';
        $tipo = strtolower($t[$i->getTipoCaso()]) . "/" . strtolower($i->getPrioridad());

        return "RESUELTO ID: {$i->getNumeroCaso()} CLIENTE: ferrovial TIPO: {$tipo} TECNICO: " . strtolower($i->getTecnicoAsignadoFinal()) . " TSOL: tssh FECHA: " . $i->getFechaApertura()->format($format) . " MODO RECEPCION: correo RESOLUCION: {$this->formatResoluciones($i)} ";
    }

    protected function formatResoluciones(Incidencia $i)
    {
        $texto = 'xxxx';
        $concat = '';
        if (null != $i->getResoluciones())
        {
            foreach ($i->getResoluciones() as $resolucion)
            {
                $concat .= $resolucion->getTexto();
            }
        }
        if('' != $concat){
            $texto = $concat;
        }
        return $texto;
    }

//    public function createMail($texto)
//    {
//        $message = \Swift_Message::newInstance()
//            ->setSubject('Sending TICKET STATES')
//            ->setFrom('raziel.valle@fractaliasoftware.com')
//            ->setTo('raziel.valle@fractaliasoftware.com')
//            ->setBody( 'start-message: ' . $texto . ' ' . 'end-message' );
//
//        return $message;
//    }
//    public function sendMail(Incidencia $incidencia)
//    {
//        $format = 'Y-m-d H:i:s';
//        $insert = $incidencia->getFechaInsercion();
//        $texto = 'Estado del Ticket: ' .$incidencia->getEstado() . '<br /> Fecha (Completa) de Inserción: ' . $insert->format($format);
//        $this->mail_service->send($this->createMail((string)$texto));
//        
//    }
}
