<?php

namespace DoctrineMigrationDependencyInjectorTest;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use DoctrineMigrationDependencyInjector\DoctrineMigrationDependencyInjector;
use DoctrineMigrationDependencyInjector\Module;
use Laminas\Mvc\Application;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;

class ModuleTest extends TestCase
{
    private Prophet $prophet;
    private ObjectProphecy $dependencyInjector;
    private Module $module;


    protected function setUp(): void
    {
        $this->prophet = new Prophet();
        $this->dependencyInjector = $this->prophesize(DoctrineMigrationDependencyInjector::class);
        $this->module = new Module();
    }

    protected function prophesize(?string $classOrInterface = null): ObjectProphecy
    {
        return $this->prophet->prophesize($classOrInterface);
    }

    public function testGetConfig()
    {
        $this->assertNotEmpty($this->module->getConfig());
    }
    public function testOnBootstrap()
    {
        $mvcEvent = $this->prophesize(MvcEvent::class);
        $application = $this->prophesize(Application::class);
        $serviceManager = $this->prophesize(ServiceManager::class);
        $entityManager = $this->prophesize(EntityManager::class);
        $eventManager = $this->prophesize(EventManager::class);

        $serviceManager->get(DoctrineMigrationDependencyInjector::class)
            ->willReturn($this->dependencyInjector->reveal());
        $serviceManager->get(EntityManager::class)
            ->willReturn($entityManager->reveal());

        $mvcEvent->getApplication()->willReturn($application->reveal());
        $application->getServiceManager()->willReturn($serviceManager->reveal());
        $entityManager->getEventManager()->willReturn($eventManager->reveal());

        $eventManager->addEventSubscriber($this->dependencyInjector->reveal())
            ->shouldBeCalled();

        $this->module->onBootstrap($mvcEvent->reveal());
        $this->prophet->checkPredictions();
        $this->assertTrue(true);
    }
}