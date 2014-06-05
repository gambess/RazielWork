<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Sms;

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
