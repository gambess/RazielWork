<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ListenerResolver
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace Pi2\Fractalia\ListenerResolver;

use Doctrine\ORM\Mapping\DefaultEntityListenerResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ListenerResolver extends DefaultEntityListenerResolver
{
    private $container;
    private $mapping;

    public function __construct($container)
    {
        $this->container = $container;
        $this->mapping = array();
    }

    public function addMapping($className, $service)
    {
        $this->mapping[$className] = $service;
    }

    public function resolve($className)
    {
        if (isset($this->mapping[$className]) && $this->container->has($this->mapping[$className]))
        {
            return $this->container->get($this->mapping[$className]);
        }

        return parent::resolve($className);
    }

//    public function resolve($className)
//    {
//        $id = null;
//        if ($className === 'Pi2\Fractalia\Listener\IncidenciaListener') {
//            $id = 'pi2_fractalia_listener_incidencia';
//        }
//
//        if (is_null($id)) {
//            return new $className();
//        } else {
//            return $this->container->get($id);
//        }
//    }
}
