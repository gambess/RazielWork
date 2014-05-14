<?php

/**
 * Description of IncidenciaListener
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace FractaliaSoftware\SmsBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Pi2\Fractalia\Entity\SGSD\Incidencia;

class IncidenciaListener
{
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $entityManager = $eventArgs->getEntityManager();

        // tal vez sólo quieres actuar en alguna entidad "Incidencia"
        if ($entity instanceof Incidencia) {
//            return $entity;
            echo ':::::: EVENT IN LISTENER :::::';
        }
    }
}
