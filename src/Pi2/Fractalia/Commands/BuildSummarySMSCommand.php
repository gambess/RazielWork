<?php
namespace Pi2\Fractalia\Commands;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



class BuildSummarySMSCommand extends Command
{
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
        
        if( ( $temp = 5) == 5)
        {
            $output->writeln("ok".$temp);
        }
        
        $output->writeln("Summary SMS build and posted ".date("Y-m-d H:i:s"));
    }
}


