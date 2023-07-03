<?php

namespace DoctrineMigrationDependencyInjector;
use Interop\Container\ContainerInterface;

interface ContainerAwareInterface
{
    /**
     * @param ContainerInterface $container
     * @return null
     */
    public function setContainer(ContainerInterface $container);
}