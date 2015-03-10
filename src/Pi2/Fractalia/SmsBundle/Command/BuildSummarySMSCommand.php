<?php

namespace Pi2\Fractalia\SmsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildSummarySMSCommand extends ContainerAwareCommand
{

    private static function GetDoctrine()
    {
        $doctrine = $GLOBALS['kernel']->getContainer()->get('doctrine');
        return $doctrine->getManager();
    }

    protected function configure()
    {
        $this
            ->setName('cron:buildsummary')
            ->setDescription('Builds and enqueues sumary SMS.')
        ;
    }

    protected function getService($name)
    {
        return $this->getApplication()->getKernel()->getContainer()->get($name);
    }

    protected function getParamConf($name)
    {
        return $this->getApplication()->getKernel()->getContainer()->getParameter($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = $this->getContainer()->get('monolog.logger.listener');

        $em = self::GetDoctrine();
        $servicios = $this->getParamConf('fractalia_sms.envio_sms.servicios_soc');
        $datosResumenes = $this->getParamConf('fractalia_sms.resumenes.resumen');

        $array = $em->getRepository('Pi2\Fractalia\Entity\SGSD\Incidencia')->getResumen($datosResumenes['estados'], $servicios);
        $gruposDestino = $this->getParamConf('fractalia_sms.envio_sms.grupo_destino');
        $msj_manager = $this->getService('fractalia_sms.mensaje_manager');
        if (is_array($array) and count($array) > 0)
        {
            $arrayResumenes = array();
            $messageId = array();
            $i = 0;
            foreach ($array as $resumen)
            {
                array_push($arrayResumenes, $resumen);
                $i++;
                if ($i > 10)
                {
                    array_push($messageId, $msj_manager->createMensaje($arrayResumenes, 'RESUMEN'));
                    $i = 0;
                    $arrayResumenes = array();
                }
            }
            if (count($arrayResumenes) > 0)
            {
                array_push($messageId, $msj_manager->createMensaje($arrayResumenes, 'RESUMEN'));
            }
        }
        if (is_string($array) and $array != null)
        {

            $messageId = $msj_manager->createMensaje($array, 'NO_PENDIENTES');
        }
        $count = 0;
        $now = (new \DateTime('NOW'));
        if (sizeof($gruposDestino) > 0)
        {
            foreach ($gruposDestino as $d)
            {
                $arrayDias = preg_split('/\s*,\s*/', $d['dias']);

                if (in_array($this->getDiaEsp(), $arrayDias) && ($now->format('H:i') >= $d['desde']) && ($now->format('H:i') <= $d['hasta']))
                {
                    $sms_manager = $this->getService('fractalia_sms.sms_manager');
                    if (is_int($messageId))
                    {
                        $sms_manager->createSms($d['destinatario'], $messageId);
                        $logger->info("Summary SMS built to " . $d['destinatario'] . " |" . date("Y-m-d H:i:s"));
                        $output->writeln("Summary SMS built to " . $d['destinatario'] . " |" . date("Y-m-d H:i:s"));
                        $count++;
                    }
                    if (is_array($messageId) and count($messageId) > 0)
                    {

                        foreach ($messageId as $id)
                        {
                            $sms_manager->createSms($d['destinatario'], $id);
                            $logger->info("Summary SMS built to " . $d['destinatario'] . " |" . date("Y-m-d H:i:s"));
                            $output->writeln("Summary SMS built to " . $d['destinatario'] . " |" . date("Y-m-d H:i:s"));
                            $count++;
                        }
                    }
                }
            }
        }
        $logger->info("Summary: " . $count . "SMS(s) build and posted " . date("d/m/y H:i:s"));
        $output->writeln("Summary: " . $count . " SMS(s) build and posted " . date("d/m/y H:i:s"));
    }

    //TODO --> enviarlo a clase generica, hay copia en IncidenciasListener
    private function getDiaEsp()
    {
        $now = (new \DateTime('NOW'));
        switch (strtolower($now->format('D')))
        {
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
