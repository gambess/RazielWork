<?php

namespace Pi2\Fractalia\SmsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Pi2\Fractalia\SmsBundle\XmlRpcClient\XmlRpcClient;
use Pi2\Fractalia\SmsBundle\Event\SmsEvent;
use Pi2\Fractalia\SmsBundle\TcpClient\TcpClient;

const SMS_OK = "pi2.fractalia.sms.send.ok";
const SMS_KO = "pi2.fractalia.sms.send.error";

define('LOCK_DIR', '/tmp/sms_sender_lock');
define('LOCK_SUFFIX', '.lock');

/**
 * Sends Pending SMSs 
 */
//class SendPendingSMSCommand extends Command
class SendPendingSMSCommand extends ContainerAwareCommand
{
    //for locking
    private static $_pid;

    private static function getDoctrine()
    {
        $doctrine = $GLOBALS['kernel']->getContainer()->get('doctrine');
        return $doctrine->getManager();
    }

    private static function getParameters()
    {
        $params = $GLOBALS['kernel']->getContainer()->getParameter('fractalia_sms.envio_sms.api');
        return $params;
    }

    /**
     * Checks if we are already running
     * 
     * @return boolean true if is already runnging
     */
    private static function isRunning()
    {
        $pids = explode(PHP_EOL, `ps -e | awk '{print $1}'`);
        if (in_array(self::$_pid, $pids))
            return TRUE;
        return FALSE;
    }

    /**
     * Adquires Lock
     * @return boolean TRUE if the lock is adquired
     */
    public static function lock($logger)
    {


        $lock_file = LOCK_DIR . LOCK_SUFFIX;

        if (file_exists($lock_file))
        {
            // Is running?
            self::$_pid = file_get_contents($lock_file);
            if (self::isRunning())
            {
//                $logger->info("==" . self::$_pid . "== Already in progress...");
                return FALSE;
            }
            else
            {
                $logger->info("==" . self::$_pid . "== Previous job died abruptly...");
            }
        }
        self::$_pid = getmypid();
        file_put_contents($lock_file, self::$_pid);
//        $logger->info("==" . self::$_pid . "== Lock acquired, processing the job...");
        return self::$_pid;
    }

    /**
     * Releases the lock
     * @return boolean TRUE
     */
    public static function unlock($logger)
    {


        $lock_file = LOCK_DIR . LOCK_SUFFIX;

        if (file_exists($lock_file))
        {
            unlink($lock_file);
        }
//        $logger->info("==" . self::$_pid . "== Releasing lock...");
        return TRUE;
    }

    /**
     * Configures the command
     */
    protected function configure()
    {
        $this
            ->setName('cron:sendsms')
            ->setDescription('Sends all pending SMS')
        ;
    }

    /**
     * executes the command
     * @param \Symfony\Component\Console\Input\InputInterface $input input
     * @param \Symfony\Component\Console\Output\OutputInterface $output output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = $this->getContainer()->get('monolog.logger.listener');

        //$name = $input->getArgument('name');
        $count = 0;
        $fail = 0;
        $dispatcher = new EventDispatcher();
        if (($pid = self::lock($logger)) !== FALSE)
        {

            try
            {
//                $logger->info("Executing pending sms's");
//                $output->writeln("Executing pending sms's");
                While (($message = $this->getFirstSMS($output)) != null)
                {
                    $mId = $message->getId();

                    $logger->info("Sending SMS, id= " . $mId);
//                    $output->writeln("Sending SMS, id= " . $mId);
                    if (( $respuesta = $this->sendSMS($message, $output)) == 0)
                    {
                        $count++;
//                        $logger->info("SMS Sent, calling event");
//                        $output->writeln("<info>SMS Sent, calling event</info>");
                        $dispatcher->dispatch(SMS_OK, new SmsEvent($respuesta, $mId));
                    }
                    else
                    {
                        $fail++;
//                        $logger->warning("SMS Failed, calling event");
//                        $output->writeln("<error>SMS Failed, calling event</error>");
                        $dispatcher->dispatch(SMS_KO, new SmsEvent($respuesta, $mId));
                    }
                    $this->updateSentStaus($message, $respuesta);
                }
            }
            catch (\Exception $exception)
            {
                $output->writeln("<error>" . $exception->getMessage() . "</error>");
            }
            self::unlock($logger);
            $logger->info($count . " SMSs sent," . $fail . " failed. unlocking");
            $output->writeln("<info>" . $count . " SMSs sent," . $fail . " failed.  unlocking</info>");
        }
        else
        {

            $output->writeln("Cron job already executing, if it doesn't finish kill it");
            $logger->info("Cron job already executing, if it doesn't finish kill it");
        }
    }

    /**
     * Update Sms sended status
     * 
     * @param Sms $sms
     * @param mixed null|int $respuesta
     */
    protected function updateSentStaus($sms, $respuesta = null)
    {
        if (is_null($respuesta))
        {
            $sms->setEstadoEnvio("ENVIANDO");
        }
        elseif ($respuesta == 0)
        {
            $now = (new \DateTime('NOW'));
            $sms->setFechaEnvio($now);
            $sms->setEstadoEnvio("ENVIADO");
            $sms->setRespuestaApi(0);
        }
        elseif (!is_null($respuesta) and $respuesta != 0)
        {
            $sms->setEstadoEnvio("ERROR");
            if (is_int($respuesta))
            {
                $sms->setRespuestaApi(print_r($respuesta, true));
            }
            else
            {
                $sms->setLog($respuesta);
            }
        }
        $em = self::getDoctrine();
        $em->persist($sms);
        $em->flush();
    }

    /**
     * Gets the first posted SMS and removes it from the queue
     * @return the firs sms or null if not found
     */
    protected function getFirstSMS(OutputInterface $output)
    {

        $em = self::getDoctrine();
        $query = $em->getRepository('FractaliaSmsBundle:Sms')->createQueryBuilder('s')
            ->where("s.estadoEnvio = 'POR_ENVIAR'")
            ->orderBy('s.fechaEnvio', 'ASC')
            ->getQuery();
        $entities = $query->getResult();
        if (count($entities) > 0)
        {

            $sms = $entities[0];
            //$output->writeln("ID:".$sms->getMensajeId());
            $mensaje = $sms->getMensaje(); //$em->getRepository('FractaliaSmsBundle:Mensaje')->find($sms->getMensajeId());

            if ($mensaje != null)
            {
                $returnValue = $sms;
                $output->writeln("HAY MENSAJE:\n" . $this->PrintSms($returnValue));
                return $returnValue;
            }
        }
        //$output->writeln("get: ".  print_r($entities)."\ncount: ".count($entities));
        return null;
    }

    /**
     * Sends a SMS
     * @param type $smsData
     * @return TRUE if sent, otherwise error code
     */
    protected function sendSMS($smsData, $output)
    {
        $message = "";
        $em = self::getDoctrine();
        $logger = $this->getContainer()->get('monolog.logger.listener');
        if ($smsData != null)
        {
            //Instantiate MOVISTAR client
            $params = self::getParameters();
//            
            //COMENTAR CUANDO SE CAMBIE A NUEVO CLIENTE
            $client = new XmlRpcClient($params['url']);

            //DESCOMENTAR CUANDO SE CAMBIE A NUEVO CLIENTE
//            $cliente = new TcpClient();
            //Inyectando el servicio de manejo de sms
            $smsManager = $this->getApplication()->getKernel()->getContainer()->get('fractalia_sms.sms_manager');

            //COMENTAR CUANDO SE CAMBIE A NUEVO CLIENTE
            $parameters = $smsManager->preparaSmsAGrupo($smsData->getDestinatario(), $smsData->getMensaje()->getTexto());

            //DESCOMENTAR CUANDO SE CAMBIE A NUEVO CLIENTE
//            $host = $smsManager->getIpAndPort();
//            $parameters = $smsManager->formateaSms($smsData->getDestinatario(), $smsData->getMensaje()->getTexto());
            if (!$parameters)
            {
                $resp = -998;
                $output->writeln("<error>sms ERROR: The application can't buid the sms" . print_r($resp, true) . "</error>");
                return $resp;
            }
            //DESCOMENTAR CUANDO SE CAMBIE A NUEVO CLIENTE
//            $socket = new TcpClient();
//            $socket->connect($host['ip'], $host['port']);

            $logger->info("sending sms:" . $this->PrintSms($smsData));
            $output->writeln("<info>sending sms:" . $this->PrintSms($smsData) . "</info>");

            try
            {
                //cambiar estado del envio:
                $this->updateSentStaus($smsData, null);

                //COMENTAR CUANDO SE CAMBIE A NUEVO CLIENTE
                $resp = $client->__call("MensajeriaNegocios_enviarAGrupoContacto", $parameters);

//                DESCOMENTAR CUANDO SE CAMBIE A NUEVO CLIENTE
//                $resp = $socket->write($parameters);
//                $socket->close();
                $logger->info("sms send result:", array('datos' => $resp));

                if (($resp >= -93 and $resp < 0) or $resp === false)
                {
                    if ($smsData->getNotificaFallo() == NULL or $smsData->getNotificaFallo() == 0)
                    {
                        if ($resp >= -93 or $resp < 0)
                        {
                            $message = "Respuesta del server:\nCódigo de error: " . $resp . "\n\nMensaje Texto envíado:\n" . $this->GetSmsText($smsData);
                        }
                        elseif ($resp == false)
                        {
                            $logger->info("Resultado:", array('respuesta recibida y codificada [-100]' => $resp));
                            $resp = -999;
                            $message = "Respuesta Interna del servidor:\n" . $resp . "\n\nNo se pudo establecer conexión con la API de mensajería\n\nMensaje Texto envíado:\n" . $this->GetSmsText($smsData);
                        }
                        $output->writeln("Sending email... ");
                        $this->sendReport($message);
                        $smsData->setNotificaFallo(1);
                        $em->persist($smsData);
                        $em->flush();
                        $output->writeln("Email Sended and Sms updated ... ");
                    }
                    else
                    {
                        $logger->info("No se re-enviará el email por que el mensaje esta marcado con una notificacion previa de fallo");
                    }
//                    
                    $output->writeln("<error>sending sms ERROR:" . print_r($resp, true) . "</error>");
                    return $resp;
                }
                $output->writeln("<info>sms send result:" . print_r($resp, true) . "</info>");
                return 0;
            }
            catch (\Exception $e)
            {
                $logger->info("sending sms ERROR:" . $e->getMessage());
                $output->writeln("<error>sending sms ERROR:" . $e->getMessage() . "</error>");
                $message = "Respuesta interna del server:\nCódigo Error: " . $e->getCode() . "\n\nMensaje de Error: " . $e->getMessage() . "\n\nMensaje Texto envíado:\n" . $this->GetSmsText($smsData);
                if ($smsData->getNotificaFallo() == NULL or $smsData->getNotificaFallo() == 0)
                {
                    $output->writeln("Sending email... ");
                    $this->sendReport($message);
                    $smsData->setNotificaFallo(1);
                    if ($smsData->getEstadoEnvio() == "ENVIANDO")
                    {
                        $this->updateSentStaus($smsData, $e->getCode());
                    }
                    $em->persist($smsData);
                    $em->flush();
                    $output->writeln("Email Sended and Sms updated ... ");
                }
                else
                {
                    $logger->info("No se re-enviará el email por que el mensaje esta marcado con una notificacion previa de fallo");
                }
                return $e->getCode();
            }
        }
        return "invalid arguments, sendSMS requires an array of three elements";
    }

    protected function PrintSms($s)
    {
        return $s->getId() . " To:" . $s->getDestinatario() . " Msg:" . $this->GetSmsText($s);
    }

    protected function GetSmsText($s)
    {
        $m = $s->getMensaje();
        return $m == null ? "" : $m->getTexto();
    }

    protected function sendReport($partMessage)
    {
        $mailer = $this->getContainer()->get('swiftmailer.mailer');
        $sender = $this->getContainer()->getParameter('mailer_user');
        $sms_mail = $this->getContainer()->getParameter('pi2_frac_sgsd_soap_server.mail.sms');
        $cc_mail = $this->getContainer()->getParameter('pi2_frac_sgsd_soap_server.mail.cc.sms');

        if (is_null($sms_mail))
        {
            return false;
        }

        $head = "Por favor no conteste este correo.\n\n";
        $subject = 'Información SGSD: Notificacion automática en error de envío SMS';
        $signature = "\n\nServidor Producción:\n\n\thttps://sgsd.iriscene.es\n\nIP del Servidor:\n\n\t178.33.1.88\n\n--\nTarea notificacion automática en error de envío SMS para SGSD.";


        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($sender)
            ->setTo($sms_mail)
            ->setCc($cc_mail)
            ->setBcc($sender)
            ->setBody($head . $partMessage . $signature)
        ;
        $mailer->send($message);
        return true;
    }

}
