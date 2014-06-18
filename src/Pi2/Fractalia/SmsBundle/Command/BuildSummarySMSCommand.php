<?php
namespace Pi2\Fractalia\SmsBundle\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



class BuildSummarySMSCommand extends Command
{
    
    private static $_logger = null;
   

    /**
     * function to centralize log instantiation
     * @return the logger
     */
    private static function GetLogger()
    {
        if( self::$_logger== NULL)
        {
            self::$_logger = $GLOBALS['kernel']->getContainer()->get('logger');
        }
        return self::$_logger;
    }
    
    
    
    protected function configure()
    {
        $this
            ->setName('cron:buildsummary')
            ->setDescription('Builds and enqueues sumary SMS, intended to be run every 4 hours.')
            
            
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$name = $input->getArgument('name');
        //Step 1
        //create message 
        //Step 2
        //Save Sms to database with POR_ENVIAR status
        //end.
        
        if( ( $temp = 5) == 5)
        {
            $output->writeln("ok".$temp);
        }
        self::GetLogger()->info("Summary SMS build and posted ".date("Y-m-d H:i:s"));
        $output->writeln("Summary SMS build and posted ".date("Y-m-d H:i:s"));
    }
}


