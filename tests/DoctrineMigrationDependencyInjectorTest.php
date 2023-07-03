<?php

namespace DoctrineMigrationDependencyInjectorTest;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Event\MigrationsEventArgs;
use Doctrine\Migrations\Events;
use Doctrine\Migrations\Metadata\MigrationPlan;
use Doctrine\Migrations\Metadata\MigrationPlanList;
use Doctrine\Migrations\MigratorConfiguration;
use Doctrine\Migrations\Version\Version;
use DoctrineMigrationDependencyInjector\ContainerAwareInterface;
use DoctrineMigrationDependencyInjector\DoctrineMigrationDependencyInjector;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use Psr\Log\LoggerInterface;

class DoctrineMigrationDependencyInjectorTest extends TestCase
{
    private Prophet $prophet;
    private ObjectProphecy $sm;
    private DoctrineMigrationDependencyInjector $sut;

    protected function prophesize(?string $classOrInterface = null): ObjectProphecy
    {
        return $this->prophet->prophesize($classOrInterface);
    }

    protected function setUp(): void
    {
        $this->prophet = new Prophet();
        $this->sm = $this->prophesize(ServiceManager::class);
        $this->sut = new DoctrineMigrationDependencyInjector(
            $this->sm->reveal()
        );
    }
    public function testSutInstanceofEventSubscriber()
    {
        $this->assertInstanceOf(EventSubscriber::class, $this->sut);
    }
    public function testSutGetSubscribedEventsHasOnMigrationMigrating()
    {
        $this->assertContains(Events::onMigrationsMigrating, $this->sut->getSubscribedEvents());
    }

    public function testSutHasOnMigrationExistsMethod()
    {
        $this->assertTrue(method_exists($this->sut, 'onMigrationsMigrating'));
    }
    public function testSutOnMigrationExistsShouldSetContainerIntoMigrationClass()
    {
        $class = $this->getContainerAwareClass();
        $this->sut->onMigrationsMigrating($this->getMigrationEventArgs($class));
        $this->assertEquals($class->getContainer(), $this->sm->reveal());
    }


    private function getMigrationEventArgs($classWithContainer): MigrationsEventArgs
    {
        $planList = new MigrationPlanList(
            [
                new MigrationPlan(
                    new Version('1'),
                    $this->prophesize(AbstractMigration::class)->reveal(),
                    'up'
                ),
                new MigrationPlan(
                    new Version('2'),
                    $classWithContainer,
                    'up'
                )
            ],
            'up'
        );
        return new MigrationsEventArgs(
            $this->prophesize(Connection::class)->reveal(),
            $planList,
            $this->prophesize(MigratorConfiguration::class)->reveal()
        );

    }
    private function getContainerAwareClass()
    {
        $connection = $this->prophesize(Connection::class);
        $connection->createSchemaManager()->willReturn(
            $this->prophesize(AbstractSchemaManager::class)->reveal()
        );
        $logger = $this->prophesize(LoggerInterface::class);
        return new class($connection->reveal(), $logger->reveal()) extends AbstractMigration implements ContainerAwareInterface {
            private $container;
            public function setContainer(ContainerInterface $container)
            {
                $this->container = $container;
            }
            public function up(Schema $schema): void
            {
            }
            public function getContainer()
            {
                return $this->container;
            }
        };
    }
}