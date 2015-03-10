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

    public function createSms($destinatario, $idmensajeTexto, $em = null)
    {
        if ($em == null)
        {
            $em = $GLOBALS['kernel']->getContainer()->get('doctrine')->getManager();
        }
        $now = (new \DateTime('NOW'));

        $new_sms = new Sms();
        $mensajeObj = $em->getRepository('FractaliaSmsBundle:Mensaje')->find($idmensajeTexto);
        //Si no existe el mensaje que?

        $new_sms->setDestinatario($destinatario);

        //Unico dato de configuracion
        $new_sms->setRemitente($this->configuraciones->getDatosApi()['remitente']);

        $estado = $this->traduceEstado($mensajeObj->getEstado());

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

    /**
     * Check if a sms got the text in sms and GROUP and return a response
     * @param string $texto
     * @param string $contactGroup
     * @param EntityManager $em
     */
    public function CheckSms($texto, $contactGroup, $em = null)
    {
        if ($em == null)
        {
            $em = $GLOBALS['kernel']->getContainer()->get('doctrine')->getManager();
        }
        $smsResult = $em->getRepository('FractaliaSmsBundle:Sms')->findOneSmsByTextAndGroup($texto, $contactGroup);
        if (is_array($smsResult) and count($smsResult) > 0)
        {

            if (count($smsResult) == 1)
            {

                if ($smsResult[0]->getEstadoEnvio() == "ASENTIDO")
                {
                    return 2;
                }
                $smsResult[0]->setEstadoEnvio("ASENTIDO");
                $smsResult[0]->setFechaActualizacion(new \DateTime('NOW'));
                $em->persist($smsResult[0]);
                $em->flush();
                return 1;
            }
            elseif (count($smsResult) > 1)
            {
                return 3;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Busca un sms por el texto exacto y se marca como asentido
     * En caso de existir mas de un sms se marca como asentido el ultimo mensaje enviado
     * 
     * @param string $texto
     * @param EntityManager $em
     */
    public function findAndMarckSmsAsentido($texto, $em = null)
    {
        if ($em == null)
        {
            $em = $GLOBALS['kernel']->getContainer()->get('doctrine')->getManager();
        }
        $smsResult = $em->getRepository('FractaliaSmsBundle:Sms')->findOneByMessageText($texto);

        if (is_array($smsResult) and count($smsResult) > 0)
        {
            if (count($smsResult) == 1)
            {
                if ($smsResult[0]->getEstadoEnvio() == "ASENTIDO")
                {
                    return 2;
                }
                $this->marckAsAsentido($smsResult[0], $em);
                return 1;
            }
            elseif (count($smsResult) > 1)
            {
                foreach ($smsResult as $entity)
                {
                    if ($entity->getEstadoEnvio() == "ASENTIDO")
                    {
                        continue;
                    }
                    if ($entity->getEstadoEnvio() == "ENVIADO")
                    {
                        $this->marckAsAsentido($entity, $em);
                        return 1;
                    }
                }
                return 3;
            }
            else
            {
                return 0;
            }
        }
        if (is_array($smsResult) and count($smsResult) == 0)
        {
            $arrayMensajes = $em->getRepository('FractaliaSmsBundle:Sms')->getTextsFromLastSmss(20);
            if (is_array($arrayMensajes) and count($arrayMensajes) > 0)
            {
                $res = $this->compareTexts($texto, $arrayMensajes);
                if ($res > 0)
                {
                    $entity = $arrayMensajes = $em->getRepository('FractaliaSmsBundle:Sms')->findBy(array('id' => $res));
                    if ($entity instanceof Sms)
                    {
                        $this->marckAsAsentido($entity, $em);
                        return 1;
                    }
                    else
                    {
                        return 4;
                    }
                }
                else
                {
                    return 4;
                }
            }
            else
            {
                return 4;
            }
            return 4;
        }
    }

    /**
     * Marca como asentido la entidad y persiste
     * 
     * @param Sms $entity
     * @param EntityManager $em
     */
    protected function marckAsAsentido(Sms $entity, $em)
    {

        $entity->setEstadoEnvio("ASENTIDO");
        $entity->setFechaActualizacion(new \DateTime('NOW'));
        $em->persist($entity);
        $em->flush();
    }

    protected function compareTexts($text, $array)
    {

        foreach ($array as $a)
        {
            $textDb = str_replace(' ', '', $a['texto']);
            $textSe = str_replace(' ', '', $text);
            if (strcmp($textSe, $textDb) === 0)
            {
                return $a['id'];
            }
            else
                return 0;
        }
    }

    function updateSms($id)
    {
        
    }

    function readSms($id)
    {
        
    }

    function deleteSms($id)
    {
        
    }

    function ListSmss()
    {
        
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

    /*
     * Retorna el Texto Formateado para el envio a traves de la nueva API TCP de Movistar
     * Este Formato convierte los numeros separados por coma en string solo sirve para envios a GRUPO
     */

    public function formateaSms($destinatario, $mensaje)
    {
        $destinosString = $this->configuraciones->getNumerosFromGrupo($destinatario);
        if ($destinosString >= -4 and $destinosString <= -1)
        {
            return false;
        }
        if (!is_null($destinosString) and ! empty($destinatario))
        {
            return $destinosString . '|' . $mensaje . '|' . $this->configuraciones->getDatosApi()['remitente'];
        }
    }

    /**
     * Devuelve un array con el ip y el puerto para conectarse
     * 
     * @return array
     */
    public function getIpAndPort()
    {
        return array(
            'ip' => $this->configuraciones->getDatosApi()['ip'],
            'port' => $this->configuraciones->getDatosApi()['port'],
        );
    }

    /**
     * Cuenta si existen sms para un grupo de contacto 
     * 
     * @param string $groupContact
     */
    public function countSmsWithGroupContact($contactGroup)
    {
        $em = $GLOBALS['kernel']->getContainer()->get('doctrine')->getManager();
        $result = $em->getRepository('FractaliaSmsBundle:Sms')->countByGroup($contactGroup);
        return $result;
    }

    /**
     * Prepara el array que consulta por los contactos de un grupo
     * 
     * @param string $grupoContacto
     * @return array
     */
    public function preparaConsultaContactosGrupo($grupoContacto)
    {

        return array(
            $this->configuraciones->getDatosApi()['apiuser'],
            $this->configuraciones->getDatosApi()['apipass'],
            $grupoContacto
        );
    }

    protected function traduceEstado($param)
    {
        switch ($param)
        {
            case "CORRECT":
                return "POR_ENVIAR";
            case "FAIL":
                return "ERROR_BUILD";
        }
    }

}
