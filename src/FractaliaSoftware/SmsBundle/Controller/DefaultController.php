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
        
        $eventManager = new EventManager;
        
        $eventManager->addEventListener(Events::postPersist, $listener);
        
        $eventManager->addEventSubscriber(new IncidenciaSubscriber);
        return array('name' => $eventManager->getListeners());
    }
}
