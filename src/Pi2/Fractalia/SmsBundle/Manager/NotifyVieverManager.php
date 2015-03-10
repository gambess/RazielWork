<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Pi2\Fractalia\SmsBundle\Manager;

use Pi2\Fractalia\Entity\SGSD\Incidencia;

/**
 * Description of NotifyVieverManager
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */
class NotifyVieverManager
{
    private $_em;
    private $_mailer;
    private $_logger;

    /**
     * Constructor setea los servicios adicionales usados
     * 
     * @param EntityManager $em
     * @param SwitfMailer $mailer
     * @param Logger $logger
     */
    public function __construct($em, $mailer, $logger)
    {
        $this->_em = $em;
        $this->_mailer = $mailer;
        $this->_logger = $logger;
    }

    /**
     * Procesa el array con notificaciones
     * 
     * @param array $arrayNotifications
     */
    public function processNotifications($arrayNotifications)
    {
        $message = "";
        if (is_array($arrayNotifications))
        {
            foreach ($arrayNotifications as $servicio => $arrayCategorias)
            {
                if (is_array($arrayCategorias) and count($arrayCategorias) > 0)
                {
                    foreach ($arrayCategorias as $categoria => $arrayTickets)
                    {
                        if (is_array($arrayTickets) and count($arrayTickets) > 0)
                        {
                            foreach ($arrayTickets as $idTicket)
                            {
                                $ticket = $this->findTicket($idTicket);
                                if ($ticket instanceof Incidencia and ( is_null($this->ticketIsMarcked($ticket)) or $this->ticketIsMarcked($ticket) === false))
                                {
                                    $message = "Detalle Visualización:\n\nServicio: " . $servicio . "\nCategoría: " . $categoria . "\nTicket Visualizado: " . $idTicket;
                                    $this->sendMail($message, $servicio);
                                    $this->marckTicketAsViewed($ticket);
                                }
                            }
                        }
                        else
                        {
                            continue;
                        }
                    }
                }
                else
                {
                    continue;
                }
            }
        }
    }

    /**
     * Encargado de enviar el email
     * 
     * @param string $data
     */
    public function sendMail($data, $servicio)
    {
        /**
         * Mailer y cuentas
         */
        $sender = $GLOBALS['kernel']->getContainer()->getParameter('mailer_user');
        $services = $GLOBALS['kernel']->getContainer()->getParameter('sgsd_web_monitor');

        $ticket_mail = $services['servicios'][$servicio]['mails']['destinatario'];
        $cc_mail = $services['servicios'][$servicio]['mails']['cc'];

        if (is_null($ticket_mail))
        {
            $this->_logger->info("No se enviará correo del buzon {$servicio} El destinatario esta vacio ", array('Mensaje' => $data));
            return false;
        }

        /*
         * Encabezado, subject y firma
         */
        $head = "Por favor no conteste este correo.\n\n";
        $subject = 'Información SGSD: Notificaciones Visualizadas en el Web Monitor';
        $signature = "\n\nServidor Producción:\n\n\thttps://sgsd.iriscene.es\n\nIP del Servidor:\n\n\t178.33.1.88\n\n--\nTarea Notificacion automática Visualizacion en Web Monitor.";

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($sender)
            ->setTo($ticket_mail)
            ->setCc($cc_mail)
            ->setBcc($sender)
            ->setBody($head . $data . $signature)
        ;
        $this->_mailer->send($message);
        $this->_logger->info("Se envió un correo: ", array('Mensaje' => $data));
        return true;
    }

    public function marckTicketAsViewed($ticketObj)
    {
        if ($ticketObj instanceof Incidencia)
        {
            $ticketObj->setNotificaVista(true);
            $this->_em->persist($ticketObj);
            $this->_em->flush();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function ticketIsMarcked($ticketObj)
    {
        if ($ticketObj instanceof Incidencia)
        {
            return $ticketObj->getNotificaVista();
        }
        else
        {
            return false;
        }
    }

    public function findTicket($id)
    {
        return $this->_em->getRepository('Pi2\Fractalia\Entity\SGSD\Incidencia')->findOneBy(array('numeroCaso' => $id));
    }

}
