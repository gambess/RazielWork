<?php

namespace Pi2\Fractalia\SmsBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Pi2\Fractalia\SmsBundle\Manager\MensajeManager;
use Pi2\Fractalia\SmsBundle\Manager\SmsManager;

class BuildSummarySMSCommand extends Command {

    private static $_logger = null;

    /**
     * function to centralize log instantiation
     * @return the logger
     */
    private static function GetLogger() {
        if (self::$_logger == NULL) {
            self::$_logger = $GLOBALS['kernel']->getContainer()->get('logger');
        }
        return self::$_logger;
    }

    private static function GetDoctrine() {
        $doctrine = $GLOBALS['kernel']->getContainer()->get('doctrine');
        return $doctrine->getManager();
    }

    protected function configure() {
        $this
                ->setName('cron:buildsummary')
                ->setDescription('Builds and enqueues sumary SMS, intended to be run every 4 hours.')
        ;
    }

    protected function getService($name) {
        return $this->getApplication()->getKernel()->getContainer()->get($name);
    }

    protected function getParamConf($name) {
        return $this->getApplication()->getKernel()->getContainer()->getParameter($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $em = self::GetDoctrine();
        $servicios = $this->getParamConf('fractalia_sms.envio_sms.servicios_soc');
        $datosResumenes = $this->getParamConf('fractalia_sms.resumenes.resumen');

        $array = $em->getRepository('Pi2\Fractalia\Entity\SGSD\Incidencia')->getResumen($datosResumenes['estados'], $servicios);
        $gruposDestino = $this->getParamConf('fractalia_sms.envio_sms.grupo_destino');
        $msj_manager = $this->getService('fractalia_sms.mensaje_manager');
        if (is_array($array) and count($array) > 0) {
            $messageId = $msj_manager->createMensaje($array, 'RESUMEN');
        }
        if (is_string($array) and $array != null) {

            $messageId = $msj_manager->createMensaje($array, 'NO_PENDIENTES');
        }
        $count = 0;
        $now = (new \DateTime('NOW'));
        if (sizeof($gruposDestino) > 0) {
            foreach ($gruposDestino as $d) {
                $arrayDias = preg_split('/\s*,\s*/', $d['dias']);

                if (in_array($this->getDiaEsp(), $arrayDias) && ($now->format('H:i') >= $d['desde']) && ($now->format('H:i') <= $d['hasta'])) {
                    $sms_manager = $this->getService('fractalia_sms.sms_manager');
                    $sms_manager->createSms($d['destinatario'], $messageId);
                    self::GetLogger()->info("Summary SMS built to " . $d['destinatario'] . " |" . date("Y-m-d H:i:s"));
                    $output->writeln("Summary SMS built to " . $d['destinatario'] . " |" . date("Y-m-d H:i:s"));
                    $count++;
                }
            }
        }
        self::GetLogger()->info("Summary: " . $count . "SMS(s) build and posted " . date("d/m/y H:i:s"));
        $output->writeln("Summary: " . $count . " SMS(s) build and posted " . date("d/m/y H:i:s"));
    }

    //TODO --> enviarlo a clase generica, hay copia en IncidenciasListener
    private function getDiaEsp() {
        $now = (new \DateTime('NOW'));
        switch (strtolower($now->format('D'))) {
            case 'mon': $dia = 'lunes';
                break;
            case 'tue': $dia = 'martes';
                break;
            case 'wed': $dia = 'miercoles';
                break;
            case 'thu': $dia = 'jueves';
                break;
            case 'fri': $dia = 'viernes';
                break;
            case 'sat': $dia = 'sabado';
                break;
            case 'sun': $dia = 'domingo';
                break;
            default:
                $dia = null;
                break;
        }
        return ($dia);
    }

}
