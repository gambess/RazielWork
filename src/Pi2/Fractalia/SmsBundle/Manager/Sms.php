<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

use Pi2\Fractalia\SmsBundle\Entity\Smsevento;

/**
 * Description of Sms
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class Sms
{
    private $logger;
    private $datosApi = array();
    
    public function __construct($logger)
    {
        $this->logger = $logger;
        
        if( count($this->datosApi)<1)
        {
            $this->datosApi = $GLOBALS['kernel']->getContainer()->getParameter('pi2_frac_sgsd_soap_server.envio_sms.api');
        }
    }

    public function preparaSmsAGrupo($destinario, $mensajeTexto)
    {

        return array(
            $this->datosApi['apiuser'],
            $this->datosApi['apipass'],
            $destinario,
            $mensajeTexto,
            $this->datosApi['remitente']
        );
    }
    
    public function saveSmsGrupo($destinatario, $idmensajeTexto)
    {
        $format = 'd/m/y H:i:s';
        $em = $GLOBALS['kernel']->getContainer()->get('doctrine')->getManager();
        $now = (new \DateTime('NOW'));

        $new_sms = new Smsevento();
        $mensajeObj = $em->getRepository('FractaliaSmsBundle:Mensaje')->find($idmensajeTexto);
        
        $new_sms->setDestinatario($destinatario);
        $new_sms->setRemitente($this->datosApi['remitente']);
        
        if ($mensajeObj->getEstado() == 'CORRECTO'){
            $estado = 'POR_ENVIAR';
        }else{
            $estado = 'ERROR';
        }
        
        $new_sms->setMensajeTexto($mensajeObj->getTexto());
        $new_sms->setEstado($estado);
        
        $new_sms->setFechaCreacion($now);
        $new_sms->setFechaActualizacion($now->format($format));
        $new_sms->setMensaje($mensajeObj);
        
        $em->persist($new_sms);
        $em->flush();
        
        
//        return array(
//            $this->datosApi['apiuser'],
//            $this->datosApi['apipass'],
//            $destinario,
//            $mensajeTexto,
//            $this->datosApi['remitente']
//        );
    }

    public function preparaSmsANumero()
    {
        
    }

    public function setDatosApi($api)
    {
        $this->datosApi = $api;
    }

}
