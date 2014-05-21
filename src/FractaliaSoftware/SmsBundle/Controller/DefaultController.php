<?php

namespace FractaliaSoftware\SmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use Doctrine\ORM\Events;
//use Doctrine\Common\EventManager;
//use FractaliaSoftware\SmsBundle\EventListener\IncidenciaSubscriber;
ini_set("memory_limit","2056M");
class DefaultController extends Controller
{

    /**
     * @Route("/test/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        
        $logger = $this->get('logger');
                
        $logger->notice('Petición ', array('desde el ip:' => $this->get('request')->getClientIp()));
                
        $listener = $this->get('pi2_fractalia_listener_incidencia');
//        $resolver = $this->get('pi2_fractalia_listener_resolver');

        $em = $this->getDoctrine()->getManager();
        

        try {
            //Capturo el buffer de salida y lo modifico en caso de error para
            // adaptar la salida a las especificaciones del proyecto
//            ob_start();
                $em->getConfiguration()->getEntityListenerResolver()->register($listener);
                $em->getConfiguration()->setEntityListenerResolver( $this->get('pi2_fractalia_listener_resolver'));
                $entity_manager = var_export($em);
                $entity_resolver = var_export($em->getConfiguration()->getEntityListenerResolver());
                $register_listener = var_export($em->getEventManager()->getListeners());
//            ob_get_flush();
            $logger->notice('Pruebaaaaa', array('EM' => $entity_manager, 'ER' => $entity_resolver, 'Event Listeners Registered' => $register_listener));
        
            exit;
        } catch (\Exception $e) {            
            return $e;
        }
        
        
        
        
//          $subscriber = new IncidenciaSubscriber;
//        $listener = new IncidenciaListener;          
//        $eventManager->addEventListener(array(Events::postPersist, Events::postUpdate), $listener);
//        $eventManager->addEventSubscriber($subscriber);
//        $eventManager->addEventListener(array(Events::postPersist, Events::postUpdate), $listener);
//        $eventManager->addEventSubscriber($subscriber);
//        $eventManager->dispatchEvent($eventName);
//        return array( 'name' => get_class_methods($listener) );
    }
    /**
     * @Route("/log/{name}")
     * @Template()
     */
    public function logAction()
    {
        
    }

}
