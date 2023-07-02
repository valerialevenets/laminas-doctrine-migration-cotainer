# laminas-doctrine-migration-cotainerinterface
Allows you to inject containerinterface inside your migration class.

To make it work you need to add 'DoctrineMigrationDependencyInjector' in config/modules.config.php 
and implement DoctrineMigrationDependencyInjector\ContainerAwareInterface in your migration.
