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
    private $container;

//    private $formatter;
//    private $another_service;

    public function __construct($logger, $container)
    {
        $this->logger = $logger;
        $this->container = $container;
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
        $prioridades = $this->container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.prioridad');
        if (in_array($inci->getPrioridad(), $prioridades))
        {
            $estados = $this->container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.estado');
            if (in_array($inci->getEstado(), $estados))
            {
                $now = (new \DateTime('NOW'));
                if ($this->filterIncidenciaByService($inci))
                {
                    $datosApi = $this->container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.api');
                    $destinatarios = $this->container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.grupo_destino');

                    if (sizeof($destinatarios) > 0)
                    {
                        foreach ($destinatarios as $d)
                        {

                            $this->logger->info('Nuevo Intento de Notificación a las', array('Fecha y Hora' => $now->format('d/m/y H:i')));


                            $arrayDias = preg_split('/\s*,\s*/', $d['dias']);
                            if (in_array($this->getDiaEsp(), $arrayDias) && ($now->format('H:i') >= $d['desde']) && ($now->format('H:i') <= $d['hasta']))
                            {

                                //Instanciamos Cliente API MOVISTAR
                                $client = new XmlRpcClient($datosApi['url']);
                                $parameters = array(
                                    $datosApi['apiuser'],
                                    $datosApi['apipass'],
                                    $d['destinatario'],
                                    $this->prepareSMS($inci),
                                    $datosApi['remitente']
                                );
                                $this->logger->info('datos del mensaje compuesto', array('Grupo Destino' => $d['destinatario'], 'Cuerpo del SMS' => $this->prepareSMS($inci), 'Remitente' => $datosApi['remitente']));

                                try
                                {
                                    $resp = $client->__call("MensajeriaNegocios_enviarAGrupoContacto", $parameters);
                                    $this->logger->info('Código de Resultado del Envio', array('Codigo de envio:' => $resp));
                                }
                                catch (Exception $e)
                                {
                                    $this->logger->info('Se encontro un error', array('Codigo de envio:' => $e->getTraceAsString()));
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function filterIncidenciaByService(Incidencia $i)
    {
        $soc_service = $this->container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.servicio');

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
        $t = $this->container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.traduccion_tipo_caso');
        $tipo = strtolower($t[$i->getTipoCaso()]) . "/" . strtolower($i->getPrioridad());
        $tsol = $this->container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.tsol_guardia');
        $format = 'd/m/y H:m:s';

        return
            "RESUELTO ID: {$i->getNumeroCaso()} "
            . "CLIENTE: {$this->setClienteMensaje($i)} "
            . "TIPO: {$tipo} "
            . "TECNICO: " . strtolower($i->getTecnicoAsignadoFinal()) . " "
            . "TSOL: {$tsol['nombre']} "
            . "FECHA: " . $i->getFechaApertura()->format($format) . " "
            . "MODO RECEPCION: correo "
            . "RESOLUCION: {$this->formatResoluciones($i)} ";
    }

    protected function formatResoluciones(Incidencia $i)
    {
        $texto = 'No hay datos';
        $concat = '';
        if (null != $i->getResoluciones())
        {
            foreach ($i->getResoluciones() as $resolucion)
            {
                $concat .= $resolucion->getTexto();
            }
        }
        if ('' != $concat)
        {
            $texto = $concat;
        }
        return $texto;
    }

    protected function setClienteMensaje(Incidencia $i)
    {
        $pattern = "^\[(.*?)\]^";
        $clientes = $this->container->getParameter('pi2_frac_sgsd_soap_server.envio_sms.nombres_cortos');
        $matches = array();
        if (preg_match_all($pattern, $i->getTitulo(), $matches, PREG_SET_ORDER) >= 3)
        {
            return strtolower($matches[2][1]);
        }
        else
        {
            foreach ($clientes as $cliente)
            {
                if (strpos(strtolower($i->getTitulo()), strtolower($cliente)) > 0)
                {
                    return strtolower($cliente);
                }
            }
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
