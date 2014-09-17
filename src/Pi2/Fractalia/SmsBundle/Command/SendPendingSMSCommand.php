<?php
namespace Pi2\Fractalia\SmsBundle\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

use Pi2\Fractalia\SmsBundle\Manager\SmsManager;
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
       $params = $GLOBALS['kernel']->getContainer()->getParameter('fractalia_sms.envio_sms.api');
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
                    $mId = $message->getId();
                   
                    
                    self::GetLogger()->info("Sending SMS, id= ".$mId);
                    $output->writeln("Sending SMS, id= ".$mId);
                    if(( $error =  $this->SendSMS($message, $output)) == 0)
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
                    $this->UpdateSentStaus($message, $error );
                }
                
            }
            catch (Exception $exception)
            {
                $output->writeln("<error>".$exception."</error>");
            }
            self::Unlock();
            self::GetLogger()->info($count." SMSs sent,".$fail." failed. unlocking");
            $output->writeln("<info>".$count." SMSs sent,".$fail." failed.  unlocking</info>");
            
        }
        else
        {
         
            $output->writeln("Cron job already executing, if it doesn't finish kill it");
            self::GetLogger()->info("Cron job already executing, if it doesn't finish kill it");
        }
        
    }
    
    
    protected function UpdateSentStaus($mensaje, $error )
    {
         
        if( $error == 0)
        {
            $now = (new \DateTime('NOW'));
            $mensaje->setFechaEnvio($now);
            $mensaje->setEstadoEnvio("ENVIADO");
            $mensaje->setRespuestaApi(0);
        }
        else
        {
            $mensaje->setEstadoEnvio("ERROR");
            if( is_int($error))
            {
                $mensaje->setRespuestaApi(print_r($error,true));
            }
            else
            {
                $mensaje->setLog($error);
            }
        }
        $em = self::GetDoctrine();
        $em->flush();
    }
    
    /**
     * Gets the first posted SMS and removes it from the queue
     * @return the firs sms or null if not found
     */
    protected function GetFirstSMS(OutputInterface $output)
    {
       
        $em = self::GetDoctrine();
         $query = $em->getRepository('FractaliaSmsBundle:Sms')->createQueryBuilder('s')
                ->where("s.estadoEnvio = 'POR_ENVIAR'")
                ->orderBy('s.fechaEnvio', 'ASC')
                ->getQuery();
        $entities = $query->getResult();
        if( count($entities)>0)
        {
            $sms = $entities[0];
            //$output->writeln("ID:".$sms->getMensajeId());
            $mensaje = $sms->getMensaje();//$em->getRepository('FractaliaSmsBundle:Mensaje')->find($sms->getMensajeId());
            
            if( $mensaje != null )
            {
                
                //cambiar estado del envio:
                $sms->setEstadoEnvio("ENVIANDO");
                $em->flush();
                
                $returnValue =  $sms;
                $output->writeln("HAY MENSAJE:\n".$this->PrintSms($returnValue));
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
        if($smsData!=null)
        {
//            $sms = new SmsManager();
            //Instantiate MOVISTAR client
            $params = self::GetParameters();
            $client = new XmlRpcClient($params['url']);
            
                
            //Inyectando el servicio de manejo de sms
            $smsManager =  $this->getApplication()->getKernel()->getContainer()->get('fractalia_sms.sms_manager');
            
//            $parameters = $sms->preparaSmsAGrupo($smsData->getDestinatario(),$smsData->getMensaje()->getTexto());
            $parameters = $smsManager->preparaSmsAGrupo($smsData->getDestinatario(),$smsData->getMensaje()->getTexto());
            self::GetLogger()->debug("sending sms:".$this->PrintSms( $smsData));
            $output->writeln("<info>sending sms:".$this->PrintSms( $smsData)."</info>");
            
            try
            {
                $resp = $client->__call("MensajeriaNegocios_enviarAGrupoContacto", $parameters);
                self::GetLogger()->info("sms send result:", array ('datos' => $resp));
                $output->writeln("<info>sms send result:".print_r($resp,true)."</info>");
                
                if ($resp != 0)
                {   
                    self::GetLogger()->debug("sending sms ERROR:" ,array ('datos' => $resp));
                    $output->writeln("<error>sending sms ERROR:".print_r($resp,true)."</error>");
                    return $resp;
                }
                return 0;
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
    protected function PrintSms($s)
    {
        return $s->getId()." To:".$s->getDestinatario()." Msg:".$this->GetSmsText($s);
    }
    protected function GetSmsText($s)
    {
        $m = $s->getMensaje();
        return $m==null?"":$m->getTexto();
    }
}
