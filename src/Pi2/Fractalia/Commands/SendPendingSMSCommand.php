<?php
namespace Pi2\Fractalia\Commands;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

const SMS_OK = "pi2.fractalia.sms.send.ok";
const SMS_KO = "pi2.fractalia.sms.send.error";



/**
 * Encapsulates sms operation result for events
 */
class SMSEvent extends Event
{
    private $result;
    public function GetResult()
    {
        return $this->result;
    }
    function __construct( $result)
    {
        $this->result = $result;
    }
}



/**
 * Sends Pending SMSs 
 */
class SendPendingSMSCommand extends Command
{
    
    
 
   //for locking
   private static $_pid;
   
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
   
   /**
    * Checks if we are already running
    * 
    * @return boolean true if is already runnging
    */
   private static function IsRunning() 
    {
        $pids = explode(PHP_EOL, `ps -e | awk '{print $1}'`);
        if(in_array(self::$_pid, $pids))
                return TRUE;
        return FALSE;
    }

    /**
     * Adquires Lock
     * @return boolean TRUE if the lock is adquired
     */
    public static function Lock() 
    {
        

        $lock_file = LOCK_DIR.LOCK_SUFFIX;

        if(file_exists($lock_file)) {
                // Is running?
                self::$_pid = file_get_contents($lock_file);
                if(self::IsRunning()) {
                    self::GetLogger()->debug("==".self::$_pid."== Already in progress...");

                        return FALSE;
                }
                else {
                        self::GetLogger()->debug("==".self::$_pid."== Previous job died abruptly...");
                }
        }

        self::$_pid = getmypid();
        file_put_contents($lock_file, self::$_pid);

        self::GetLogger()->debug("==".self::$_pid."== Lock acquired, processing the job...");
        return self::$_pid;
    }

    /**
     * Releases the lock
     * @return boolean TRUE
     */
    public static function Unlock() {
            
            
            $lock_file = LOCK_DIR.LOCK_SUFFIX;

            if(file_exists($lock_file))
            {
                    unlink($lock_file);
            }
            self::GetLogger()->debug("==".self::$_pid."== Releasing lock...");
            return TRUE;
    }   
    
    
    
    /**
     * Configures the command
     */
    protected function configure()
    {
        $this
            ->setName('cron:sendsms')
            ->setDescription('Sends all pending SMS, intended to be called each minute')        
            
        ;
    }

    /**
     * executes the command
     * @param \Symfony\Component\Console\Input\InputInterface $input input
     * @param \Symfony\Component\Console\Output\OutputInterface $output output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$name = $input->getArgument('name');
        $count = 0;
        $fail = 0;
        $dispatcher = new EventDispatcher();
        if(($pid = self::Lock()) !== FALSE) 
        {   
            
            try
            {
                self::GetLogger()->info("Executing pending sms's");
                $output->writeln("Executing pending sms's");
                While( ($message =  $this->GetFirstSMS()) != null)
                {
                    self::GetLogger()->info("Sending SMS");
                    $output->writeln("Sending SMS");
                    if(( $error =  $this->SendSMS($message)) == TRUE)
                    {
                        $count++;
                        self::GetLogger()->info("SMS Sent, calling event");
                        $output->writeln("<info>SMS Sent, calling event</info>");
                        $dispatcher->dispatch(SMS_OK, new SMSEvent($error));
                    }
                    else
                    {
                        $fail++;
                        self::GetLogger()->warning("SMS Failed, calling event");
                        $output->writeln("<error>SMS Failed, calling event</error>");
                        $dispatcher->dispatch(SMS_KO, new SMSEvent($error));
                    }
                }
                
            }
            catch (Exception $exception)
            {
                $output->writeln("<error>".$exception."</error>");
            }
            self::GetLogger()->info($count." SMSs sent,".$fail." failed. unlocking");
            $output->writeln("<info>".$count." SMSs sent,".$fail." failed.  unlocking</info>");
            self::Unlock();
        }
        else
        {
         
            $output->writeln("Cron job already executing, if it doesn't finish kill it");
            self::GetLogger()->info("Cron job already executing, if it doesn't finish kill it");
        }
        
    }
    
    /**
     * Gets the first posted SMS and removes it from the queue
     * @return the firs sms or null if not found
     */
    protected function GetFirstSMS()
    {
        sleep(1);
        return null;
    }
    
    /**
     * Sends a SMS
     * @param type $smsData
     * @return TRUE if sent, otherwise error code
     */
    protected function SendSMS($smsData)
    {
        
        return FALSE;
    }
}
