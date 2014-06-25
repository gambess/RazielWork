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
 * Clase que se encarga de gestionar la creacion de sms's a partir de una mensaje
 * Implementada como servicio
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class SmsManager
{
    /*
     *  Inyectamos las configuracioness
     */
    private $configuraciones;
    
    
    public function __construct($confs)
    {
        $this->configuraciones = $confs;
    }

    /*
     * Funcion que crea el SMS.
     * Se añade un registro para que el cron envie el mensaje creado
     * @param $destinatario string, nombre del destinatario a enviar
     * @param $idmensajeTexto int, id del mensaje a adjuntar.  
     * Debe ser un destinatario valido de la API utilizada. Esto no se valida
     */
    
    public function createSms($destinatario, $idmensajeTexto, $em=null)
    {
        if($em == null){
                    $em = $GLOBALS['kernel']->getContainer()->get('doctrine')->getManager();

        }
        $now = (new \DateTime('NOW'));

        $new_sms = new Sms();
        $mensajeObj = $em->getRepository('FractaliaSmsBundle:Mensaje')->find($idmensajeTexto);
        //Si no existe el mensaje que?

        $new_sms->setDestinatario($destinatario);
        
        //Unico dato de configuracion
        $new_sms->setRemitente($this->configuraciones->getDatosApi()['remitente']);
        
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
            $this->configuraciones->getDatosApi()['apiuser'],
            $this->configuraciones->getDatosApi()['apipass'],
            $destinatario,
            $mensajeTexto,
            $this->configuraciones->getDatosApi()['remitente']
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
