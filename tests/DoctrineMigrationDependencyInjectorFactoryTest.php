<?php

namespace DoctrineMigrationDependencyInjectorTest;
use DoctrineMigrationDependencyInjector\DoctrineMigrationDependencyInjector;
use DoctrineMigrationDependencyInjector\DoctrineMigrationDependencyInjectorFactory;
use Interop\Container\Containerinterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class DoctrineMigrationDependencyInjectorFactoryTest extends TestCase
{
    use ProphecyTrait;
    private ObjectProphecy $container;
    protected function setUp(): void
    {
        $this->container = $this->prophesize(Containerinterface::class);
    }
    public function testInvocation()
    {
        $factory = new DoctrineMigrationDependencyInjectorFactory();
        $this->assertInstanceOf(
            DoctrineMigrationDependencyInjector::class,
            $factory($this->container->reveal(), '')
        );
    }
}