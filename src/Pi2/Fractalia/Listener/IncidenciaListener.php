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
use Pi2\Fractalia\XmlRpcClient\XmlRpcClient;

class IncidenciaListener
{
    private $logger;
    private $message;
    private $estados = array();
    private $prioridades = array();
    private $sgsd_services = array();
    private $datosApi = array();
    private $destinatarios = array();
    private $msj;
    private $sms;

//    private $formatter;
//    private $another_service;

    public function __construct($logger, $message, $sms)
    {
        $this->logger = $logger;
        $this->message = $message;
        $this->sms = $sms;
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
        //recurperar prioridades en fichero configuracion
        if ($this->filterByPrioridades($inci))
        {

            if ($this->filterByEstados($inci))
            {
                $now = (new \DateTime('NOW'));
                if ($this->filterBySgsdService($inci))
                {
                    if (sizeof($this->destinatarios) > 0)
                    {
                        foreach ($this->destinatarios as $d)
                        {

                            $this->logger->info('Nuevo Intento de Notificación a las', array('Fecha y Hora' => $now->format('d/m/y H:i')));


                            $arrayDias = preg_split('/\s*,\s*/', $d['dias']);
                            if (in_array($this->getDiaEsp(), $arrayDias) && ($now->format('H:i') >= $d['desde']) && ($now->format('H:i') <= $d['hasta']))
                            {
                                $this->message->copyIncidencia($inci);
                                $this->msj = $this->message->getTextMessage();
                                //Instanciamos Cliente API MOVISTAR
                                $client = new XmlRpcClient($this->datosApi['url']);
                                $parameters = $this->sms->preparaSmsAGrupo($d['destinatario'], $this->msj);

                                $this->logger->info('datos del mensaje compuesto', array('Grupo Destino' => $d['destinatario'], 'Cuerpo del SMS' => $this->msj, 'Remitente' => $this->datosApi['remitente']));

                                try
                                {
                                    $resp = $client->__call("MensajeriaNegocios_enviarAGrupoContacto", $parameters);
                                    $this->logger->info('Código de Resultado del Envio', array('Codigo de envio:' => $resp));
                                    if ($resp != 0)
                                    {
                                        throw new \Exception('Codigo de Envio Recibio:'.$resp);
                                    }
                                }
                                catch (Exception $e)
                                {
                                    $this->logger->error('ERROR_ENVIO', array('Codigo respuesta' => $e->getMessage()));
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function setPrioridades($prioridades)
    {
        $this->prioridades = $prioridades;
    }

    public function setEstados($estados)
    {
        $this->estados = $estados;
    }

    public function setSgsdServices($sgsd_services)
    {

        $this->sgsd_services = $sgsd_services;
    }

    public function setApi($api)
    {
        $this->datosApi = $api;
    }

    public function setDestinatarios($destinatarios)
    {

        $this->destinatarios = $destinatarios;
    }

    protected function filterByPrioridades(Incidencia $incidencia)
    {

        return in_array($incidencia->getPrioridad(), $this->prioridades);
    }

    protected function filterByEstados(Incidencia $incidencia)
    {
        return in_array($incidencia->getEstado(), $this->estados);
    }

    protected function filterBySgsdService(Incidencia $incidencia)
    {

        if (in_array($incidencia->getGrupoDestino(), $this->sgsd_services) or in_array($incidencia->getGrupoOrigen(), $this->sgsd_services))
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
