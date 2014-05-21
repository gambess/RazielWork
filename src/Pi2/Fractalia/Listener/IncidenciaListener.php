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
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Pi2\Fractalia\Entity\SGSD\Incidencia;

class IncidenciaListener
{
    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;
    /** Array de momento para pruebas del servicio SOC */
    private $services_soc = array('SEGEST MON', 'SEGEST SOC', 'SEG MERCEDES', 'SOPORTE SEGURIDAD', 'SOC SEGURIDAD');
    private $grupos_envio = array('ESCALADO' => '[24h]', 'SOPORTE' => '[23:00-07:00]');

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Trigger para capturar las inserciones
     *
     * @param Incidencia $incidencia
     * @param LifecycleEventArgs $event
     * @ORM\PostPersist
     */
    public function postPersist(Incidencia $incidencia, LifecycleEventArgs $event)
    {
//        if ("CRITICA" == $incidencia->getPrioridad())
//        {
//            if ($this->filterIncidenciaByServiceSOC($incidencia) instanceof Incidencia)
//            {
                /* try catch for logger */
//                $this->sendSMS($this->prepareSms($incidencia));
//                return $incidencia->getPrioridad();
                $now = new \DateTime;
                $incidencia->setFechaResolucion($now);
//            }
//        }
    }

    /**
     * Trigger para capturar las actualizaciones
     *
     * @param Incidencia $incidencia
     * @param LifecycleEventArgs $event
     * @ORM\PostUpdate 
     */
    public function postUpdate(Incidencia $incidencia, LifecycleEventArgs $event)
    {
//        if ("CRITICA" == $incidencia->getPrioridad())
//        {
//            if ($this->filterIncidenciaByServiceSOC($incidencia))
//            {
                /* try catch for logger */
//                $this->sendSMS($this->prepareSms($incidencia));
//                return $incidencia->getPrioridad();
                $now = new \DateTime;
                $incidencia->setFechaResolucion($now);
//            }
//        }
    }

    public function filterIncidenciaByServiceSOC(Incidencia $incidencia)
    {
        if ($incidencia instanceof Incidencia)
        {

            if (in_array($incidencia->getGrupoOrigen(), $this->services_soc) or in_array($incidencia->getGrupoDestino(), $this->services_soc))
            {
                return true;
            }
        }
    }

    public function prepareSms(Incidencia $incidencia)
    {
        return true;
    }

    public function sendSMS($text)
    {
        return true;
    }

}
