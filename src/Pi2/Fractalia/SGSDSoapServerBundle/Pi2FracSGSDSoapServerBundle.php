<?php

namespace Pi2\Fractalia\SGSDSoapServerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Pi2\Fractalia\SGSDSoapServerBundle\DependencyInjection\Compiler\DoctrineEntityListenerPass;

class Pi2FracSGSDSoapServerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineEntityListenerPass());
    }
}
