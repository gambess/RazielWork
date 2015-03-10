<?php

namespace Pi2\Fractalia\SmsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Pi2\Fractalia\SmsBundle\Manager\CleanManager;

/**
 * Clean DB 
 */
class CleanNotifySMSCommand extends ContainerAwareCommand
{
    //for count remove records
    private $_counters = array();

    private function getEntityManager()
    {
        $doctrine = $GLOBALS['kernel']->getContainer()->get('doctrine');
        return $doctrine->getManager();
    }

    /**
     * Configures the command
     */
    protected function configure()
    {
        $this
            ->setName('cron:cleandb')
            ->setDescription('Remove all about notifications with 7 days older for all notif. and 30 days older for ferr notif.')
            ->addOption('all', null, InputOption::VALUE_REQUIRED, 'Days older for all notifications', 7)
            ->addOption('ferr', null, InputOption::VALUE_REQUIRED, 'Days older for ferr notifications', 30)
        ;
    }

    /**
     * executes the command
     * @param \Symfony\Component\Console\Input\InputInterface $input input
     * @param \Symfony\Component\Console\Output\OutputInterface $output output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $all =  $input->getOption('all');
        $ferr =  $input->getOption('ferr');
        $format = "d-m-Y H:i";
        $logger = $this->getContainer()->get('monolog.logger.maintain');
        $cleanner = new CleanManager();
        $em = $this->getEntityManager();
        $startTime = (new \DateTime("NOW"));
        $logger->info("Starting DB cleannig at... " . $startTime->format($format) . " Please wait.");
        $output->writeln("Starting DB cleannig at...  " . $startTime->format($format) . " Please wait.");
        $cleanner->cleanDb($all, $ferr, $em, $logger, $this->_counters);
        $finishTime = (new \DateTime("NOW"));
        $logger->info("Finish cleannig DB at " . $finishTime->format($format));
        $output->writeln("Finish cleannig DB at " . $finishTime->format($format));
        $resumen = $this->prepareResumen($this->_counters, $startTime, $finishTime);
        $output->writeln("Sending email... ");
        $this->sendReport("Limpieza ejecutada:\n\nResumen:\n\n" . $resumen);
        $output->writeln("Resumen: \n" . $resumen);

    }

    /**
     * 
     * @param type $arrayResumen
     * @param type $startTime
     * @param type $finishTime
     * @return string
     */
    protected function prepareResumen($arrayResumen, $startTime = null, $finishTime = null)
    {
        $message = "";
        if (!is_null($startTime)and ! is_null($finishTime))
        {
            $diff = $finishTime->diff($startTime);
            $time = $diff->format('%i');
        }
        if (isset($time) and ! is_null($time))
        {
            $message .= "Limpieza realizada en {$time} minutos.\n\n";
        }

        if (is_array($arrayResumen) and count($arrayResumen) > 0)
        {
            foreach ($arrayResumen as $key => $value)
            {
                switch ($key)
                {
                    case "incidenciast":
                        $message .= "Incidencias Totales borradas: " . $value . "\n";
                        break;
                    case "incidencias":
                        $message .= "Incidencias borradas: " . $value . "\n";
                        break;
                    case "incidenciasf":
                        $message .= "Incidencias ferr borradas: " . $value . "\n";
                        break;
                    case "resumenes":
                        $message .= "Resumenes borrados: " . $value . "\n";
                        break;
                    case "mensajesr":
                        $message .= "Mensajes de resumen borrados: " . $value . "\n";
                        break;
                    case "smsr":
                        $message .= "Sms de resumen borrados: " . $value . "\n";
                        break;
                    case "eventos":
                        $message .= "Eventos borrados: " . $value . "\n";
                        break;
                    case "smse":
                        $message .= "Sms de eventos borrados: " . $value . "\n";
                        break;
                    case "mensajese":
                        $message .= "Mensajes de eventos borrados: " . $value . "\n";
                        break;
                    case "acciones":
                        $message .= "Acciones borradas: " . $value . "\n";
                        break;
                    case "descripciones":
                        $message .= "Descripciones borradas: " . $value . "\n";
                        break;
                    case "infoadjuntas":
                        $message .= "Info Adjuntas borradas: " . $value . "\n";
                        break;
                    case "resoluciones":
                        $message .= "Resoluciones borradas: " . $value . "\n";
                        break;
                }
            }
            if ($message != "")
            {
                return $message;
            }
        }
        else
        {
            return "No se ha realizado ninguna acción";
        }
    }

    protected function sendReport($resumen)
    {
        /**
         * Mailer y cuentas
         */
        $mailer = $this->getContainer()->get('swiftmailer.mailer');
        $sender = $this->getContainer()->getParameter('mailer_user');
        $clean_mail = $this->getContainer()->getParameter('pi2_frac_sgsd_soap_server.mail.limpieza');
        $cc_mail = $this->getContainer()->getParameter('pi2_frac_sgsd_soap_server.mail.cc.limpieza');
        
        if(is_null($clean_mail)){
            return false;
        }
        /*
         * Encabezado, subject y firma
         */
        $head = "Por favor no conteste este correo.\n\n";
        $subject = 'Información SGSD: tareas de limpieza automática';
        $signature = "\n\nServidor Producción:\n\n\thttps://sgsd.iriscene.es\n\nIP del Servidor:\n\n\t178.33.1.88\n\n--\nTarea Limpieza automático base de datos SGSD.";
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($sender)
            ->setTo($clean_mail)
            ->setCc($cc_mail)
            ->setBcc($sender)
            ->setBody($head . $resumen . $signature)
        ;
        $mailer->send($message);
        return true;
    }

}
