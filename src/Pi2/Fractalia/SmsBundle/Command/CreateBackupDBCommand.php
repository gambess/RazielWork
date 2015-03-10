<?php

namespace Pi2\Fractalia\SmsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pi2\Fractalia\SmsBundle\Manager\CleanManager;

/**
 * Clean DB 
 */
class CreateBackupDBCommand extends ContainerAwareCommand
{
    //directory
    private $_dir = "/tmp";
    private $_extension = ".sql.gz";

    /**
     * Configures the command
     */
    protected function configure()
    {
        $this
            ->setName('cron:backupdb')
            ->setDescription("Run mysqldump and put the file in {$this->_dir} dir")
        ;
    }

    /**
     * executes the command
     * @param \Symfony\Component\Console\Input\InputInterface $input input
     * @param \Symfony\Component\Console\Output\OutputInterface $output output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $filename = "";
        $logger = $this->getContainer()->get('monolog.logger.maintain');
        $dateTime = (new \DateTime("NOW"));
        $logger->info("Starting backup. Please wait...");
        $output->writeln("Starting Backup Please wait...");
        $this->setFilename($dateTime, $filename);
        if (true === $this->mysqldump($output, $filename))
        {
            $logger->info("Backup ready and saved in " . $filename);
            $output->writeln("Backup ready and saved in " . $filename);
            $output->writeln("Sending email... ");
            $this->sendReport("Backup realizado y almacenado en:\n\n\t" . $filename);
        }
        else
        {
            $logger->error("Some error ocurred in backup task a email is generated and sended");
            $output->writeln("<error>Backup task is not complete... some fail.</error>");
            $this->sendReport("Ha fallado la generación del backup planificado.\nPor favor notificar al equipo de desarollo");
        }
    }

    /**
     * Run MysqlDump and put the bakup in temporary dir
     * 
     * @param OutputInterface $output
     * @param type $fileName
     * @return boolean
     */
    protected function mysqldump(OutputInterface $output, $fileName)
    {
        $dbName = $this->getContainer()->getParameter('database_name');
        $dbUser = $this->getContainer()->getParameter('database_user');
        $dbPwd = $this->getContainer()->getParameter('database_password');
        $mysqldumpCmd = "mysqldump -u {$dbUser} -p{$dbPwd} {$dbName} | gzip -cq > {$fileName}";
        $execute = passthru($mysqldumpCmd);
        if ($execute === false)
        {
            return false;
        }
        return true;
    }

    /**
     * Set the file name with the datetime and extension with dir included
     *  
     * @param type $dateTime
     * @param string $filename
     * @return boolean
     */
    protected function setFilename($dateTime, &$filename)
    {

        if (is_null($dateTime) or ! ($dateTime instanceof \DateTime))
        {
            $dateTime = (new \DateTime("NOW"));
        }
        $format = "d_m_Y-H_i_s";
        $filename = $this->_dir . "/dump_" . $dateTime->format($format) . $this->_extension;
        return true;
    }

    protected function sendReport($partMessage)
    {
        $mailer = $this->getContainer()->get('swiftmailer.mailer');
        $sender = $this->getContainer()->getParameter('mailer_user');
        $backup_mail = $this->getContainer()->getParameter('pi2_frac_sgsd_soap_server.mail.respaldo');
        $cc_mail = $this->getContainer()->getParameter('pi2_frac_sgsd_soap_server.mail.cc.respaldo'); 
        
        if(is_null($backup_mail)){
            return false;
        }
        
        $head = "Por favor no conteste este correo.\n\n";
        $subject = 'Información SGSD: tareas de respaldo automático';
        $signature = "\n\nServidor Producción:\n\n\thttps://sgsd.iriscene.es\n\nIP del Servidor:\n\n\t178.33.1.88\n\n--\nTarea respaldo automático base de datos SGSD.";
        
        
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($sender)
            ->setTo($backup_mail)
            ->setCc($cc_mail)
            ->setBcc($sender)
            ->setBody($head . $partMessage . $signature)
        ;
        $mailer->send($message);
        return true;

    }

}
