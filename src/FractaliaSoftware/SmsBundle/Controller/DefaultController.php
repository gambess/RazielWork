<?php

namespace FractaliaSoftware\SmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\Events;
use Doctrine\Common\EventManager;
use FractaliaSoftware\SmsBundle\EventListener\IncidenciaSubscriber;

class DefaultController extends Controller
{

    /**
     * @Route("/test/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        $listener = $this->get('incidencia.listener');
        $subscriber = new IncidenciaSubscriber;

//        $listener = new IncidenciaListener;
        $em = $this->getDoctrine()->getManager();
        $eventManager = new EventManager;

        $eventManager->addEventListener(array(Events::postPersist, Events::postUpdate), $listener);
        $eventManager->addEventSubscriber($subscriber);
//        $eventManager->addEventListener(array(Events::postPersist, Events::postUpdate), $listener);
//        $eventManager->addEventSubscriber($subscriber);
//        $eventManager->dispatchEvent($eventName);
//        return array('name' => );
    }
    /**
     * @Route("/log/{name}")
     * @Template()
     */
    public function logAction()
    {
        
    }

}
