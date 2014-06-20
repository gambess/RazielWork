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


    /*
     * Constructor En caso de que no se inyecten los datos de la Api.
     * Se Obtiene de la configuracion directamente
     */
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

    /*
     * Funcion que crea el SMS.
     * Se añade un registro para que el cron envie el mensaje creado
     * @param $destinatario string, nombre del destinatario a enviar
     * @param $idmensajeTexto int, id del mensaje a adjuntar.  
     * Debe ser un destinatario valido de la API utilizada. Esto no se valida
     */
    
    public function createSms($destinatario, $idmensajeTexto)
    {
        $em = $GLOBALS['kernel']->getContainer()->get('doctrine')->getManager();
        $now = (new \DateTime('NOW'));

        $new_sms = new Sms();
        $mensajeObj = $em->getRepository('FractaliaSmsBundle:Mensaje')->find($idmensajeTexto);

        $new_sms->setDestinatario($destinatario);
        $new_sms->setRemitente($this->datosApi['remitente']);
        
        $estado = $this->traduceEstado( $mensajeObj->getEstado() );

        $new_sms->setMensaje($mensajeObj);
        $mensajeObj->setFechaAdjuntadoSms($now);
        $em->persist($mensajeObj);
        $em->flush();

        $new_sms->setEstadoEnvio($estado);
        $new_sms->setFechaCreacion($now);        
        $new_sms->setFechaActualizacion($now);
        
        $em->persist($new_sms);
        $em->flush();
        
//        return $this->preparaSmsAGrupo($destinatario, $new_sms->getMensaje()->getTexto());
    }
    
    function updateSms($id) {
        
    }
    function readSms($id) {
        
    }
    function deleteSms($id) {
        
    }
    function ListSmss() {
        
    }
    /*
     * Retorna el Texto Formateado para el envio a traves de la API de Movistar
     * Este Formato solo sirve para envios a GRUPO
     */
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
    
    protected function traduceEstado($param) {
        switch($param){
            case "CORRECT":
                return "POR_ENVIAR";
            case "FAIL":
                return "ERROR_BUILD";
        }
    }
}
