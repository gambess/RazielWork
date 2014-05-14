<?php

/**
 * Description of IncidenciaSubscriber
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */


namespace FractaliaSoftware\SmsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use Pi2\Fractalia\Entity\SGSD\Incidencia;

class IncidenciaSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
//            'postUpdate',
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // tal vez sólo quieres actuar en alguna entidad "Incidencia"
        if ($entity instanceof Incidencia) {
            echo ':::::: EVENT IN SUBSCRIBER :::::';
        }
    }
}
