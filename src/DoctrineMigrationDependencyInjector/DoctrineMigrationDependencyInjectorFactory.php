<?php

namespace DoctrineMigrationDependencyInjector;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DoctrineMigrationDependencyInjectorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new DoctrineMigrationDependencyInjector($container);
    }
}
