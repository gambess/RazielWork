<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntityListenerResolver
 *
 * @author Raziel Valle Miranda <raziel.valle@fractaliasoftware.com>
 */

namespace Pi2\Fractalia\ListenerResolver;

use Doctrine\ORM\Mapping\DefaultEntityListenerResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityListenerResolver extends DefaultEntityListenerResolver
{
    /** 
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     * 
     */
    private $container;
    
    /** 
     * @var array
     * 
     */
    private $mapping;

    /**
     * Creates a container aware entity resolver.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);

        $this->mapping = array();
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Maps an entity listener to a service.
     *
     * @param string $className The entity listener class.
     * @param string $service   The service ID.
     */
    public function addMapping($className, $service)
    {
        $this->mapping[$className] = $service;
    }

    public function resolve($className)
    {
        if (isset($this->mapping[$className]) && $this->container->has($this->mapping[$className])) {
            return $this->container->get($this->mapping[$className]);
        }

        return parent::resolve($className);
    }
}

