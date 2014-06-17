<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

use Pi2\Fractalia\SmsBundle\Entity\Sms;

/**
 * Description of SMSManager
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class SmsManager
{
    private $datosApi = array();


    public function __construct()
    {
        if (count($this->datosApi) < 1)
        {
            $this->datosApi = $GLOBALS['kernel']->getContainer()->getParameter('pi2_frac_sgsd_soap_server.envio_sms.api');
        }
    }

    public function write()
    {
        return $this->send->escribe();
    }

    public function createSms($destinatario, $idmensajeTexto)
    {
        $format = 'd/m/y H:i:s';
        $em = $GLOBALS['kernel']->getContainer()->get('doctrine')->getManager();
        $now = (new \DateTime('NOW'));

        $new_sms = new Sms();
        $mensajeObj = $em->getRepository('FractaliaSmsBundle:Mensaje')->find($idmensajeTexto);

        $new_sms->setDestinatario($destinatario);
        $new_sms->setRemitente($this->datosApi['remitente']);
        

        if ($mensajeObj->getEstado() == 'CORRECT')
        {
            $estado = 'POR_ENVIAR';
        }
        else
        {
            $estado = 'ERROR_BUILD';
        }

        $new_sms->setMensaje($mensajeObj);
        $new_sms->setEstadoEnvio($estado);

        $new_sms->setFechaCreacion($now);        

        $new_sms->setFechaActualizacion($now);
        
        $em->persist($new_sms);
        $em->flush();
        
        return $this->preparaSmsAGrupo($destinatario, $new_sms->getMensaje()->getTexto());
    }
    
    public function preparaSmsAGrupo($destinatario, $mensajeTexto)
    {

        return array(
            $this->datosApi['apiuser'],
            $this->datosApi['apipass'],
            $destinatario,
            $mensajeTexto,
            $this->datosApi['remitente']
        );
    }
}
