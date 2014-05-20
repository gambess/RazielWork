<?php

/**
 * Description of IncidenciaListener
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace FractaliaSoftware\SmsBundle\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Pi2\Fractalia\Entity\SGSD\Incidencia;

class IncidenciaListener
{
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $obj = $eventArgs->getObject();
        $entity = $eventArgs->getEntity();
        $objManager = $eventArgs->getObjectManager();

        // tal vez sólo quieres actuar en alguna entidad "Incidencia"
        if ($entity instanceof Incidencia) {
            return array ($obj, $entity, $objManager);
        }
    }
    
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $obj = $eventArgs->getObject();
        $entity = $eventArgs->getEntity();
        $objManager = $eventArgs->getObjectManager();

        // tal vez sólo quieres actuar en alguna entidad "Incidencia"
        if ($entity instanceof Incidencia) {
            return array ($obj, $entity, $objManager);
        }
    }
}
