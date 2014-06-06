<?php
namespace Pi2\Fractalia\Commands;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Pi2\Fractalia\SmsBundle\Sms\Sms;
use Pi2\Fractalia\XmlRpcClient\XmlRpcClient;

const SMS_OK = "pi2.fractalia.sms.send.ok";
const SMS_KO = "pi2.fractalia.sms.send.error";

define('LOCK_DIR', '/tmp/sms_sender_lock');
define('LOCK_SUFFIX', '.lock');


/**
 * Encapsulates sms operation result for events
 */
class SMSEvent extends Event
{
    private $result;
    private $id;
    public function GetResult()
    {
        return $this->result;
    }
    public function GetId()
    {
        return $this->id;
    }
    function __construct( $result, $id)
    {
        $this->result = $result;
        $this->id = $id;
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
   
   private static function GetDoctrine()
   {
       $doctrine = $GLOBALS['kernel']->getContainer()->get('doctrine');
       return $doctrine->getManager();
   }
   
   
   private static function GetParameters()
   {
       $params = $GLOBALS['kernel']->getContainer()->getParameter('pi2_frac_sgsd_soap_server.envio_sms.api');
       return $params;
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
                While( ($message =  $this->GetFirstSMS($output)) != null)
                {
                    $mId = 0;
                    if( count($message > 3))
                    {
                        $mId = $message[3];
                    }
                    self::GetLogger()->info("Sending SMS, id= ".$mId);
                    $output->writeln("Sending SMS, id= ".$mId);
                    if(( $error =  $this->SendSMS($message, $output)) == TRUE)
                    {
                        $count++;
                        self::GetLogger()->info("SMS Sent, calling event");
                        $output->writeln("<info>SMS Sent, calling event</info>");
                        $dispatcher->dispatch(SMS_OK, new SMSEvent($error,$mId ));
                    }
                    else
                    {
                        $fail++;
                        self::GetLogger()->warning("SMS Failed, calling event");
                        $output->writeln("<error>SMS Failed, calling event</error>");
                        $dispatcher->dispatch(SMS_KO, new SMSEvent($error, $mId));
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
    protected function GetFirstSMS(OutputInterface $output)
    {
       
        $em = self::GetDoctrine();
        //$rep =  $em->getRepository('FractaliaSmsBundle:Sms');
        
       // $query = $em->createQuery('SELECT * FROM Sms s ORDER BY s.fechaEnvio ASC');
        
            
//$articles = $query->getResult(); // array of CmsArticle objects
        
        $entities = $em->getRepository('FractaliaSmsBundle:Sms')->findBy(array(), array('fechaEnvio' => 'ASC'));
        $query = $em->getRepository('FractaliaSmsBundle:Sms')->createQueryBuilder('s')
                ->where("s.estadoEnvio = 'POR_ENVIAR'")
                ->orderBy('s.fechaEnvio', 'ASC')
                ->getQuery();
        $entities = $query->getResult();
        if( count($entities)>0)
        {
            $sms = $entities[0];
            $output->writeln("ID:".$sms->getMensajeId());
            $mensaje = $em->getRepository('FractaliaSmsBundle:Mensaje')->find($sms->getMensajeId());
            
            if( $mensaje != null )
            {
                
                //cambiar estado del envio:
                $sms->setEstadoEnvio("ENVIANDO");
                $em->flush();
                
                $returnValue =  [$sms->getRemitente(), $sms->getDestinatario(),$mensaje->getTexto(), $sms->getId()];
                $output->writeln("HAY MENSAJE:\n".print_r($returnValue));
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
    protected function SendSMS($smsData, $output)
    {
        if(count($smsData)>2)
        {
            $sms = new Sms(self::GetLogger());
            //Instantiate MOVISTAR client
            $params = self::GetParameters();
            $client = new XmlRpcClient($params['url']);
            $parameters = $sms->preparaSmsAGrupo($smsData[1],$smsData[2]);
            self::GetLogger()->debug("sending sms:".print_r($smsData));
            $output->writeln("<info>sending sms:".print_r($smsData)."</info>");
            
            try
            {
                $resp = $client->__call("MensajeriaNegocios_enviarAGrupoContacto", $parameters);
                self::GetLogger()->info("sms send result:".$resp);
                $output->writeln("<info>sms send result:".$resp."</info>");
                
                if ($resp != 0)
                {   
                    self::GetLogger()->debug("sending sms ERROR:".print_r($resp));
                    $output->writeln("<error>sending sms ERROR:".print_r($resp)."</error>");
                    return $resp;
                }
                return TRUE;
            }
            catch (Exception $e)
            {
                self::GetLogger()->debug("sending sms ERROR:".print_r($e));
                $output->writeln("<error>sending sms ERROR:".print_r($e)."</error>");
                return $e;
            }
        }
        return "invalid arguments, sendSMS requires an array of three elements";
    }
}
