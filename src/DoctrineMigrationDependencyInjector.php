<?php

namespace DoctrineMigrationDependencyInjector;
use Doctrine\Common\EventSubscriber;
use Doctrine\Migrations\Event\MigrationsEventArgs;
use Doctrine\Migrations\Events;
use Doctrine\Migrations\Metadata\MigrationPlan;
use Interop\Container\Containerinterface;

class DoctrineMigrationDependencyInjector implements EventSubscriber
{
    private ContainerInterface $serviceManager;

    public function __construct(ContainerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getSubscribedEvents() : array
    {
        return [
            Events::onMigrationsMigrating,
        ];
    }

    public function onMigrationsMigrating(MigrationsEventArgs $args) : void
    {
        $migrationPlans = $args->getPlan()->getItems();
        /** @var MigrationPlan $migrationPlan */
        foreach ($migrationPlans as $migrationPlan) {
            $migration = $migrationPlan->getMigration();
            if ($migration instanceof ContainerAwareInterface) {
                $migration->setContainer($this->serviceManager);
            }
        }
    }
}
