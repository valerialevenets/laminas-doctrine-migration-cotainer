<?php

namespace DoctrineMigrationDependencyInjector;

use Doctrine\ORM\EntityManager;
use Laminas\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        $this->addDoctrineMigrationDependencyInjector($event);
    }
    private function addDoctrineMigrationDependencyInjector(MvcEvent $event)
    {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();
        /** @var EntityManager $entityManager */
        $entityManager = $serviceManager->get(EntityManager::class);
        $entityManager->getEventManager()
            ->addEventSubscriber($serviceManager->get(DoctrineMigrationDependencyInjector::class));
    }
}