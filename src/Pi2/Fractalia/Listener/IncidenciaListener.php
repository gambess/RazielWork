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
use Pi2\Fractalia\SmsBundle\Manager\SmsManager;

class IncidenciaListener
{
    /*
     * Filtros de los eventos
     */
//    private $_prioridades;
//    private $_estado;
//    private $_grupoOrigenIn;
//    private $_grupoOrigenNot;
//    private $_grupoDestinoIn;
//    private $_grupoDestinoNot;
//    private $_filtroTitulo;


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
//        $arrayEventos = array();
        $arrayTmp = array();
        $arrayFiltros = array();
        $arrayDias = array();

        //capturo el objeto en un insercion o actualizacion
        $inci = $event->getObject();
        $em = $event->getEntityManager();

        $now = (new \DateTime('NOW'));
        //Se inicia el monitoreo de la incidencia si en los campos grupo origen o grupo destino
        //se encuentra algun servicio SOC, obtenido del fichero de configuración
        if ($this->filtrarByServicesSOC($inci))
        {
            $arrayEventos = $this->configuraciones->getEventos();

            //Existen Filtros
            if (count($arrayEventos) > 0)
            {
                foreach ($arrayEventos as $plantillaNombre => $array)
                {
                    if ($plantillaNombre == null or is_array($array) == null or count($array) == 0)
                    {
                        continue;
                    }

                    //load filtros
                    $arrayPrioridades = array_slice($array, 0, 1, true);
                    $arrayEstados = array_slice($array, 1, 1, true);
                    $origen = array_slice($array, 2, 1, true);
                    foreach ($origen as $arr)
                    {
                        $arrayGrupoOrigenIn = array_slice($arr, 0, 1, true);
                        $arrayGrupoOrigenNot = array_slice($arr, 1, 1, true);
                    }
                    $destino = array_slice($array, 3, 1, true);
                    foreach ($destino as $a)
                    {
                        $arrayGrupoDestinoIn = array_slice($a, 0, 1, true);
                        $arrayGrupoDestinoNot = array_slice($a, 1, 1, true);
                    }
                    $arrayFiltroTitulo = array_slice($array, 4, 1, true);
                    //Cargados los filtros en cada Array
//                print_r($arrayPrioridades);
//                print_r($arrayEstados);
//                    print_r($arrayGrupoDestinoIn);
//                    print_r($arrayGrupoDestinoNot);
//                    print_r($arrayGrupoOrigenIn);
//                    print_r($arrayGrupoOrigenNot);
//                    print_r($arrayFiltroTitulo);

                    if (count($arrayPrioridades) > 0 and ( $this->isIn($inci->getPrioridad(), $arrayPrioridades['prioridades'])))
                    {
                        $arrayTmp = array_shift($arrayEstados['estado']);
                        if (count($arrayEstados) > 0 and ( $this->isIn($inci->getEstado(), $arrayTmp)))
                        {
                            if (count($this->configuraciones->getDestinos()) > 0)
                            {

                                $id_mensaje = $this->mensajeManager->createMensaje($inci, $plantillaNombre, $em);
                                $this->crearSmsPorDestinatario($id_mensaje, $this->configuraciones->getDestinos());
//                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    protected function crearSmsPorDestinatario($id_mensaje, $destinatarios)
    {
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

    protected function isIn($txt, $array)
    {
        return in_array($txt, $array);
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
