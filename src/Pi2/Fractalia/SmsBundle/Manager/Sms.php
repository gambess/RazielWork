<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

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

    public function preparaSmsANumero()
    {
        
    }

    public function setDatosApi($api)
    {
        $this->datosApi = $api;
    }

}
