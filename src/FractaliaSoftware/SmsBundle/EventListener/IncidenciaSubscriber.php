<?php

/**
 * Description of IncidenciaSubscriber
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */


namespace FractaliaSoftware\SmsBundle\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use Pi2\Fractalia\Entity\SGSD\Incidencia;

class IncidenciaSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            Events::postPersist,
            Events::postUpdate,
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->imprimeLog($args);
    }
    
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->imprimeLog($args);
    }

    public function imprimeLog(LifecycleEventArgs $args)
    {
        $obj = $args->getObject();
        $entity = $args->getEntity();
        $objManager = $args->getObjectManager();

        // tal vez sólo quieres actuar en alguna entidad "Incidencia"
        if ($entity instanceof Incidencia) {
            return array ( $obj, $objManager );
        }
    }
}
