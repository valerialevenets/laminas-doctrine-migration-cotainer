<?php

namespace DoctrineMigrationDependencyInjectorTest;

use DoctrineMigrationDependencyInjector\ContainerAwareInterface;
use PHPUnit\Framework\TestCase;

class ContainerAwareInterfaceTest extends TestCase
{
    public function testSutHasSetContainerMethod()
    {
        $this->assertTrue(method_exists(ContainerAwareInterface::class, 'setContainer'));
    }
}